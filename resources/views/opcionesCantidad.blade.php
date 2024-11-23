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
            <a href="#addEditAmountOption" class="btn btn-success" data-toggle="modal">
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
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-dark text-white">
                                <h3>Cantidades ruleta</h3>
                            </th>
                        </tr>
                        <tr>
                            <th>Cantidad</th>
                            <th>Intentos disponibles</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $datas)
                        <tr>
                            <td>${{ $datas['quantity'] }}</td>
                            <td>{{ $datas['attempts'] }}</td>
                            <td>
                                @if ($datas['state'] == 1)
                                <div class="badge badge-success">Activo</div>
                                @else
                                <div class="badge badge-danger">Inactivo</div>
                                @endif
                            </td>
                            <td>
                                <form action="/opciones/cantidad/{{ $datas['id'] }}" method="post">
                                    <span class="d-flex">
                                        <a href="#addEditAmountOption" data-id="{{ $datas->id }}" data-attempts="{{ $datas->attempts }}" data-quantity="{{ $datas->quantity }}" class="btn btn-info edit-option" data-toggle="modal">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#removeAmountOption" data-toggle="modal" data-id="{{ $datas->id }}" class="btn btn-danger button-remove ml-2">
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
                                    </span>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex flex-row">
                    {!! $data->links("pagination::bootstrap-4") !!}
                </div>
            </div>
        </div>
    </div>
    <!-- Remove Modal -->
    <div id="removeAmountOption" class="modal fade remove-option">
        <div class="modal-dialog">
            <div class="modal-content modal-confirm">
                <form action="/opciones/cantidad" method="POST" id="form-remove">
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
    <div id="addEditAmountOption" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('opciones/cantidad') }}" enctype="multipart/form-data" id="form-option">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="POST" id="form-amount">
                    <input type="hidden" name="id" id="option_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar Opción</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Intentos</label>
                            <input type="number" class="form-control" name="attempts" id="attempts" min="1" required>
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
</body>
<script type="module">
    $(document).ready(function() {
        $('#addEditAmountOption').on('show.bs.modal', function(e) {
            let modal = $(this);
            let id = $(e.relatedTarget).attr("data-id");
            if (id) {
                $('.modal-title').text('Editar Opción');
                $('#form-option').attr('action', `/opciones/cantidad/${id}`);
                $('#form-amount').attr('value', 'PUT');
                let quantity = $(e.relatedTarget).attr("data-quantity");
                let attempts = $(e.relatedTarget).attr("data-attempts");

                modal.find("#quantity").val(quantity);
                modal.find("#option_id").val(id);
                modal.find('#attempts').val(attempts);
            } else {
                $('#form-amount').attr('value', 'POST');
                $('.modal-title').text('Agregar Opción');
                modal.find("#quantity").val('');
                modal.find("#option_id").val('');
                modal.find('#attempts').val('');
            }
        });


        $('#removeAmountOption').on('show.bs.modal', function(e) {
            let id = $(e.relatedTarget).attr('data-id');
            $('#form-remove').attr('action', `/opciones/cantidad/${id}`);
        })
    })
</script>

@include('layout.footer')