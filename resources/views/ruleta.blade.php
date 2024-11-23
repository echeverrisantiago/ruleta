@include('layout.header')
<link rel="stylesheet" href="{{ asset('css/roulette.css') }}">
<script type="text/javascript" src="{{ asset('js/Winwheel.js') }}"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
<body>
    <img src="{{ asset('storage/ruleta/confetti.gif') }}" alt="confetti" class="confetti d-none">
    {{ $errors->any() }}
    <div class="js-container container" style="top:0px !important;"></div>
    <div align="center">
        <img src="{{ asset('storage/ruleta/logo2.png') }}" alt="logo" height="60" class="mt-3 mb-3 mb-xs-1">
        <div id="roulette" class="d-flex justify-content-center">
            <div class="roulette-style" ontouchmove="startSpin();">
                <img src="{{ asset('storage/ruleta/ruleta.png') }}" alt="ruleta.png" width="548" />
            </div>
            <canvas id="canvas" width="1000" height="1000">
                <p style="color: white" align="center">Lo sentimos, necesitamos actualizar tu navegador o utilizar otro.</p>
            </canvas>
        </div>
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
        <button id="spin_button" class="btn btn-primary spin_button text-dark disabled font-weight-bold" onClick="startSpin();">
            Girar
        </button><br />
        <h5 class="text-white font-weight-bold">Intentos disponibles: <span id="attempts_missing">0</span></h5>
        <div class="d-flex justify-content-center">
            @foreach($data['amount'] as $amount)
            <div class="btn btn-success ml-2 amount-option text-dark font-weight-bold" data-id="{{ $amount['id'] }}" data-attempts="{{ $amount['attempts'] }}">
                <i class="fa-solid fa-money-bill-1-wave"></i>
                <span>{{ $amount['quantity'] }}</span>
            </div>
            @endforeach
        </div>
        <div class="text-danger logout {{ Auth::user()->Rol->code == 'admin' ? 'd-none' : ''; }}">
            <a class="text-danger" href="{{ url('logout') }}"><i class="fa fa-sign-out"></i></a>
        </div>
        <div class="menu-admin {{ Auth::user()->Rol->code == 'admin' ? '' : 'd-none'; }}" id="menu-admin">
            <img alt="_menu" src="{{ asset('storage/ruleta/cog_configuration.png') }}">
        </div>
        <div id="confirmMenu" class="modal fade remove-option">
        <div class="modal-dialog modal-roulette">
        <div class="modal-content">
            <div class="modal-header">
                <h5>¿Volver al menú?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a href="/opciones/usuarios" class="btn btn-success btn-ok text-white">Volver</a>
            </div>
        </div>
    </div>
        </div>
    </div>
    </div>
    <div id="resultRoulette" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="main_result_text text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <span id="result_text" class="result_text"></span>
                </div>
                <div class="main_result_image">
                    <img src="" alt="" id="result_image" class="result_image">
                </div>
                <div class="main_lost_result d-flex m-auto">
                    <span id="span_lost_result" class="span_lost_result"></span>
                </div>
            </div>
        </div>
    </div>
<script>
    var powerSpin = 1;
    var timerValue = 0
    var interval;
    document.addEventListener("DOMContentLoaded", () => {
        $('#menu-admin img').click(() => {
            $('#confirmMenu').modal('show');
        });

        const girar = document.getElementById('spin_button');

        const mousePress = () => {
            interval = setInterval(() => {
                timerValue++
                console.log(timerValue);
                if (timerValue > 2) {
                    powerSpin = 2;
                } else {
                    powerSpin = 1;
                }
            }, 1000);
            timerValue = 0;
        }

        const mouseRelease = () => {
            clearInterval(interval);
            timerValue = 0;            
        }

        girar.addEventListener("mousedown", mousePress);
        girar.addEventListener("mouseup", mouseRelease);
        girar.addEventListener("touchstart", mousePress);
        girar.addEventListener("touchend", () => {
            mouseRelease();
            startSpin();
        });
    });
    
    var code = '';
    var amount_id = '';
    var attempts = '';
    var spinButton = document.getElementById('spin_button');
    var data = {!!$data['rouletteOptions'] !!};
    var angle = {!!$data['angle'] !!};
    let segments = [];
    data.forEach((element, i) => {
        console.log(element);
        segments.push({
            'id': element.id,
            'textFontWeight': 800,
            'win': element.win,
            'lost_result': element.lost_result,
            'keep_trying': element.keep_trying,
            'image': element.background_image,
            'text': element.roulette_description.toUpperCase(),
            'textFontSize': 50,
            'description': element.description,
            'fillStyle': i % 2 == 0 ? '#dcb851' : '#ed4040',
            'strokeStyle': '#fff',
            'strokeWidth': 1,
            'textFillStyle': i % 2 == 0 ? '#111' :  '#fff',
        });
    });

    var count = 360 / segments.length;
    let theWheel = new Winwheel({
        'numSegments': segments.length,
        'outerRadius': 500,
        'drawMode': 'text',
        'drawText': true,
        'textFontFamily': 'Arial',
        'textFillStyle': 'white',            
        'textMargin': 30,
        'segments': segments,
        'animation': {
            'type': 'spinToStop',
            'duration': 5,
            'spins': segments.length * 2,
            'callbackFinished': alertPrize,
            'stopAngle': (count * angle) - this.getPosition(3, (count - 3)),
            'callbackSound': () => this.reproducirAudio('tick.mp3'),
        }
    });

    let amounts = document.getElementsByClassName('amount-option');

    for (let i = 0; i < amounts.length; i++) {
        amounts[i].addEventListener('click', (e) => {
            if (!amounts[i].classList.contains('disabled')) {
                if (!amounts[i].classList.contains('active')) {
                    code = "2-" + new Date().valueOf() + "-" + Math.floor(Math.random() * (9999 - 1111 + 1) + 1111);
                }
                attempts = amounts[i].dataset.attempts;
                $('.active').removeClass('active');
                amounts[i].classList.add('active');
                amount_id = parseInt(amounts[i].dataset.id);
                document.getElementById('spin_button').classList.remove('disabled');
                document.getElementById('attempts_missing').textContent = amounts[i].dataset.attempts;
            }
        });
    }

    let firstImg = new Image();

    firstImg.src = '/storage/opcionesRuleta/1655522323.jpg';
    let wheelPower = 0;
    let wheelSpinning = false;

    function getPosition(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    // -------------------------------------------------------
    // Function to handle the onClick on the power buttons.
    // -------------------------------------------------------
    function powerSelected(powerLevel) {
        if (wheelSpinning == false) {
            wheelPower = powerLevel;
        }
    }

    // -------------------------------------------------------
    // Click handler for spin button.
    // -------------------------------------------------------
    function startSpin() {
        if (amount_id != '') {
            $('.amount-option').addClass('disabled');
            spinButton.classList.add('disabled');
            powerSelected(powerSpin);
            powerSpin = 1;
            if (wheelSpinning == false) {
                if (wheelPower == 1) {
                    theWheel.animation.spins = 3;
                } else if (wheelPower == 2) {
                    theWheel.animation.spins = 6;
                }

                theWheel.startAnimation();
                wheelSpinning = true;
            }
        }
    }

    // -------------------------------------------------------
    // Function for reset button.
    // -------------------------------------------------------
    function resetWheel() {       
        theWheel.stopAnimation(false);
        theWheel.rotationAngle = 0;
        theWheel.draw();
        wheelSpinning = false;
    }

    // -------------------------------------------------------
    // Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters
    // note the indicated segment is passed in as a parmeter as 99% of the time you will want to know this to inform the user of their prize.
    // -------------------------------------------------------
    async function alertPrize(resultado) {
        if (resultado.image) {
            $('#main_lost_result').hide();
            $('.main_result_image').show();
            $('#result_image').attr('src', document.location.origin + '/' + resultado.image);
        } else {
            $('#main_lost_result').show();
            $('.main_result_image').hide();
            $('#result_image').attr('src', '');
        }

        if (resultado.lost_result) {
            $('.span_lost_result').text(resultado.lost_result);
        } else {
            $('.span_lost_result').text('');
        }

        if (resultado.win == 1) {
            reproducirAudio('victory.mp3');
            setTimeout(() => {
                $('.confetti').removeClass('d-none');
            }, 1000);
        } else {
            if (resultado.keep_trying == 1) {
                reproducirAudio('keep_trying.wav');
            } else {
                reproducirAudio('fail.mp3');
            }
        }

        $('#result_text').text(resultado.description);
        setTimeout(() => {
            $('#resultRoulette').modal('show');
        }, 1000);

        setTimeout(() => {
            if (resultado.keep_trying != 0) {
                spinButton.classList.remove('disabled');
            } else {
                if ($('#attempts_missing').text() == 0) {
                    spinButton.classList.add('disabled');
                } else {
                    spinButton.classList.remove('disabled');
                }
            }
            resetWheel();
        }, 3500);

        let url = location.origin + '/ruleta/guardarResultado';
        let _token = $('#token').val();    
        
        setTimeout(() => {
            $('.confetti').addClass('d-none');
        }, 10000);

        await fetch(url, {
                method: 'POST',
                mode: 'cors',
                headers: {
                    'X-CSRF-TOKEN': _token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    money_amount_id: amount_id,
                    roulette_options_id: resultado.id,
                    code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                if (resultado.keep_trying == 0) {
                    let attempts_missing = $('#attempts_missing').text();
                    $('#attempts_missing').text(parseInt(attempts_missing) - 1);
                    if ($('#attempts_missing').text() == 0) {
                        amount_id = '';
                        spinButton.classList.add('disabled');
                        $('.amount-option').removeClass('disabled');
                        $('.active').removeClass('active');
                    }
                }                  

                let stopAngle = (count * data.data.angle) - getPosition(0.5, (count - 1.5));
                theWheel.animation.stopAngle = stopAngle;
            })
            .catch(error => {
                console.log(error);
                if (resultado.keep_trying == 0) {
                    amount_id = '';
                }
                
            });              
        }

    function reproducirAudio(audio) {
        audio = new Audio('/storage/audios/' + audio);
        audio.currentTime = 0;
        audio.play();
    }
</script>
</body>
@include('layout.footer')