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
    <div class="row">
        <div class="col-10 m-auto">
            <form method="GET">
                <h4>Filtros de búsqueda</h4>

                <select name="asesor" id="" class="custom-select mt-2">
                    <option selected value="0">Todos los asesores</option>
                    @foreach ($asesores as $asesor)
                    <option value="{{ $asesor->id }}" @if(Request::get('asesor')==$asesor->id ) selected @endif>{{ $asesor->name }}</option>
                    @endforeach
                </select>
                <select name="tipo" id="tipo" class="custom-select mt-2">
                    <option value="1">Estadísticas detalladas</option>
                    <option value="2" @if(Request::get('tipo')==2 ) selected @endif>Estadísticas sencillas</option>
                </select>
                <select name="fecha" id="statistics-date" class="custom-select mt-2">
                    <option value="30">Últimos 30 días</option>
                    <option value="15" @if(Request::get('fecha')==15 ) selected @endif>Últimos 15 días</option>
                    <option value="7" @if(Request::get('fecha')==7 ) selected @endif>Última semana</option>
                    <option value="1" @if(Request::get('fecha')==1 ) selected @endif>Ayer</option>
                    <option value="0" @if(Request::get('fecha')==0 ) selected @endif>Hoy</option>
                    <option value="9000" @if(Request::get('fecha') == 9000) selected @endif>Todas las estadísticas</option>
                </select>
                <input type="hidden" name="" value="{{ csrf_token() }}" />
                <button class="btn btn-success mt-2">
                    <i class="fa fa-search"></i>
                    Buscar
                </button>
                <a href="#confirmarEliminado" data-toggle="modal">
                    <button class="btn btn-danger mt-2">
                        <i class="fa fa-trash"></i>
                        Eliminar estadísticas
                    </button>
                </a>
                <!-- <div id="reportrange" class="mt-2"
                style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div> -->
            </form>
        </div>
    </div>
    @if(\Session::has('success'))
    <div class="row">
        <div class="col-10 m-auto">
            <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                {{ \Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif
    <!-- Remove Modal -->
    <div id="confirmarEliminado" class="modal fade remove-option">
        <div class="modal-dialog">
            <div class="modal-content modal-confirm">
                <form action="/opciones/estadisticas/delete" method="POST" id="form-remove">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <div class="modal-header flex-column">
                        <div class="icon-box">
                            <i class="material-icons">&#xE5CD;</i>
                        </div>
                        <h4 class="modal-title w-100">¿Estás seguro?</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <input type="hidden" name="asesor" value="{{ Request::get('asesor') }}">
                    @if(is_array(Request::get('fecha')))
                    @foreach(Request::get('fecha') as $date)
                    <input type="hidden" name="fecha[]" value="{{ $date }}">
                    @endforeach
                    @else
                    <input type="hidden" name="fecha" value="{{ Request::get('fecha') }}">
                    @endif
                    <div class="modal-body text-dark">
                        <p>Se eliminarán las estadísticas filtradas.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Eliminar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(Request::get('tipo') == 2)
    <div class="row mt-2">
        <div class="col-10 m-auto">
            <span>
                Recolectado: ${{ number_format($collected, 0, ',', '.') }}<br>
                Participaciones totales: {{ $participantes }}<br>
                Premios entregados: {{ $ganadores }}<br>
                Participaciones sin premio: {{ $perdedores }}
            </span>
        </div>
    </div>
    @else
    <div class="row mt-5">
        <div class="col-10 m-auto">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="prueba123">
                    <thead class="thead-dark">
                        <tr>
                            <th>Asesor</th>
                            <th>Resultado descripción</th>
                            <th>Resultado victoria</th>
                            <th>Resultado sigue intentando</th>
                            <th>Cantidad de dinero jugado</th>
                            <th>Intentos disponibles</th>
                            <th>Intentos realizados</th>
                            <th>Fecha de juego</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statistics as $key => $statistic)
                        <tr>
                            @if($key == 0 || $statistic->code != $statistics[$key - 1]->code)
                            <td rowspan="{!! $statistics->where('code', $statistic->code)->count() !!}">{{$statistic->asesor->name }}</td>
                            @endif
                            <td style="min-width: 200px;">{{ $statistic->option->description }}</td>
                            <td>{{ $statistic->option->win == 1 ? 'Si' : 'No' }}</td>
                            <td>{{ $statistic->option->keep_trying == 1 ? 'Si' : 'No' }}</td>
                            @if($key == 0 || $statistic->code != $statistics[$key - 1]->code)
                            <td rowspan="{!! $statistics->where('code', $statistic->code)->count() !!}">{{ $statistic->amount->quantity }}</td>
                            <td rowspan="{!! $statistics->where('code', $statistic->code)->count() !!}">{{ $statistic->amount->attempts }}</td>
                            <td rowspan="{!! $statistics->where('code', $statistic->code)->count() !!}">{!! $statistics->where('code', $statistic->code)->count() !!}</td>
                            @endif
                            <td style="min-width: 200px;">{{ $statistic->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if (count($statistics) < 1) <tr>
                        <td colspan="8" class="text-center p-4">
                            No hay estadísticas para las fechas filtradas
                        </td>
                        </tr>
                        @endif
                </table>
            </div>
        </div>
    </div>
    @endif
</body>
<script type="module">
    $('#statistics-date').on('change', async function(e) {
        let filter = $(this).val();
        if (filter != 100) {
            let url = location.origin + '/opciones/estadisticas/filtros';
            let _token = $('#token').val();
            await fetch(url, {
                    method: 'GET',
                    mode: 'cors',
                    headers: {
                        'X-CSRF-TOKEN': _token,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        date: filter
                    })
                })
                .then(response => response.json())
                .then(data => {

                })
                .catch(error => {

                })
        }
    });
</script>
<script type="module">
/* $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        format: 'YYYY-MM-DD',
        language: 'es',
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

}); */
</script>
<script type="module">
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['23 de junio', '24 de junio', '25 de junio', '26 de junio', '27 de junio', '28 de junio'],
            datasets: [{
                label: 'Total participantes',
                data: [12, 19, 13, 15, 12, 23],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }, {
                label: 'Total perdedores',
                data: [3, 4, 6, 8, 9, 13],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }, {
                label: 'Total ganadores',
                data: [1, 2, 3, 5, 2, 8],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@include('layout.footer')