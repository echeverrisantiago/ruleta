@include('layout.header')
<link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
<body class="img js-fullheight">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <h1 class="mb-4 text-center text-white">ArteCris20</h1>
                        <form method="POST" action="{{ url('/auth/login') }}" class="signin-form">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <input type="text" class="form-control" name="user" placeholder="Usuario" required>
                            </div>
                            <div class="form-group">
                                <input id="password-field" type="password" name="password" class="form-control" placeholder="Contraseña" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3">Ingresar</button>
                            </div>
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
@include('layout.footer')