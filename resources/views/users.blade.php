@include('layout.header')
<style>
    .remove-option .modal-confirm {
        max-width: 400px;
    }

    .remove-option .modal-confirm .modal-content {
        padding: 20px;
        border-radius: 5px;
        border: none;
        text-align: center;
        font-size: 14px;
    }

    .remove-option .modal-confirm .modal-header {
        border-bottom: none;
        position: relative;
    }

    .remove-option .modal-confirm h4 {
        text-align: center;
        font-size: 26px;
    }

    .remove-option .modal-confirm .close {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .remove-option .modal-confirm .modal-body,
    .remove-option .modal-confirm .modal-footer a {
        color: #999;
        text-align: center;
        padding: 0;
    }

    .remove-option .modal-confirm .modal-footer {
        border: none;
        text-align: center;
        border-radius: 5px;
        font-size: 13px;
        padding: 10px 15px 25px;
    }

    .remove-option .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        z-index: 9;
        text-align: center;
        border: 3px solid #f15e5e;
    }

    .remove-option .modal-confirm .icon-box i {
        color: #f15e5e;
        font-size: 46px;
        display: inline-block;
        margin-top: 13px;
    }

    .remove-option .modal-confirm .btn,
    .modal-confirm .btn:active {
        color: #fff;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.4s;
        line-height: normal;
        min-width: 120px;
        border: none;
        min-height: 40px;
        border-radius: 3px;
        margin: 0 5px;
    }

    .remove-option .modal-confirm .btn-secondary {
        background: #c1c1c1;
    }

    .remove-option .modal-confirm .btn-secondary:hover,
    .remove-option .modal-confirm .btn-secondary:focus {
        background: #a8a8a8;
    }

    .remove-option .trigger-btn {
        display: inline-block;
        margin: 100px auto;
    }
</style>

<body class="body-in">
    <div class="container mt-5">
        <div class="row justify-content-end w-90">
            <a href="#addEditUser" class="btn btn-success" data-toggle="modal">
                <i class="fa fa-add"></i>
            </a>
        </div>
        <div class="row mt-4 w-90">
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                {{ $errors->first() }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if(\Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                {{ \Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th colspan="4" class="bg-dark text-white">
                                <h3>Asesores registrados</h3>
                            </th>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $datas)
                        <tr>
                            <td>{{ $datas['name'] }}</td>
                            <td>{{ $datas['user'] }}</td>
                            <td>
                                @if ($datas['state'] == 1)
                                <div class="badge badge-success">Activo</div>
                                @else
                                <div class="badge badge-danger">Inactivo</div>
                                @endif
                            </td>
                            <td>
                                <form action="/opciones/usuarios/{{ $datas['id'] }}" method="post">
                                    <div class="d-flex">
                                        <a href="#addEditUser" title="Editar" data-id="{{ $datas->id }}" data-name="{{ $datas->name }}" data-user="{{ $datas->user }}" class="btn btn-info edit-option" data-toggle="modal">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#removeUser" data-toggle="modal" data-id="{{ $datas->id }}" class="btn btn-danger button-remove ml-2" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="id" value="{{ $datas['id'] }}">
                                        @if ($datas['state'] == 1)
                                        <input type="hidden" name="state" value="0">
                                        <button class="btn btn-warning ml-2" title="Inactivar" type="submit">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                        @else
                                        <input type="hidden" name="state" value="1">
                                        <button class="btn btn-warning ml-2" title="Activar" type="submit">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        @endif
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach   
                        @if (count($data) < 1)
                            <tr>
                                <td colspan="4" class="text-center p-4">
                                    No hay asesores registrados
                                </td>
                            </tr>
                        @endif                    
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content flex-row">
                {!! $data->links("pagination::bootstrap-4") !!}
            </div>
        </div>
    </div>
    <!-- Remove Modal -->
    <div id="removeUser" class="modal fade remove-option">
        <div class="modal-dialog">
            <div class="modal-content modal-confirm">
                <form action="/opciones/ruleta/delete" method="POST" id="form-remove">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <div class="modal-header flex-column">
                        <div class="icon-box">
                            <i class="material-icons">&#xE5CD;</i>
                        </div>
                        <h4 class="modal-title w-100">¿Estás seguro?</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body text-dark">
                        <p>Se eliminarán las estadísticas relacionadas.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Eliminar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="addEditUser" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('opciones/ruleta/store') }}" enctype="multipart/form-data" id="form-option">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="POST" id="form-user">
                    <input type="hidden" name="id" id="option_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar asesor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" id="user_name" required>
                        </div>
                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" class="form-control" name="user" id="user_user" required>
                        </div>
                        <div class="form-group position-relative">
                            <label>Contraseña</label><br />
                            <input type="password" class="form-control" name="password" id="user_password" required>
                            <span toggle="#user_password" class="fa fa-fw fa-eye field-icon toggle-password position-absolute"
                            style="right: 10px;top: 43px;"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-success" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <canvas id="myChart" width="400" height="400"></canvas> -->
</body>
<script type="module">
    $(document).ready(function() {
        $('#addEditUser').on('show.bs.modal', function(e) {
            let modal = $(this);
            let id = $(e.relatedTarget).attr("data-id");
            if (id) {
                $('.modal-title').text('Editar asesor');
                $('#form-user').attr('value', 'PUT');
                $('#form-option').attr('action', `/opciones/usuarios/${id}`);
                let name = $(e.relatedTarget).attr("data-name");
                let user = $(e.relatedTarget).attr("data-user");

                modal.find("#option_id").val(id);
                $("#user_name").val(name);
                $("#user_user").val(user);
                $('#user_password').val('');
            } else {
                $('.modal-title').text('Agregar asesor');
                $('#form-user').attr('value', 'POST');
                $('#form-option').attr('action', '/opciones/usuarios/');
                $("#user_name").val('');
                $("#user_user").val('');
                $('#user_password').val('');
            }
        });

        $('#removeUser').on('show.bs.modal', function(e) {
            let id = $(e.relatedTarget).attr('data-id');
            $('#form-remove').attr('action', `/opciones/usuarios/${id}`);
        })
    })
</script>
@include('layout.footer')