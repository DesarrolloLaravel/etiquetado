@extends('auth.auth')

@section('htmlheader_title')
    Log in
@endsection

@section('content')
<style>
    .capatcha {
        text-align: center;
    }

    .g-recaptcha {
        display: inline-block;
    }
</style>
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/home') }}"><b>Acuafood</b><br>Sistema de Producci&oacute;n</a>
        </div><!-- /.login-logo -->

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Ops!</strong> Ha ocurrido un error.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('logout'))
        <div class="alert alert-success">
            <strong>{{ session('logout') }}</strong>
        </div>
    @endif

    <div class="login-box-body">
    <p class="login-box-msg">Ingresa tus datos para iniciar sesi&oacute;n</p>
    <form action="{{ url('/') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Usuario" name="usuario"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Contrase&ntilde;a" name="contrasena"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group capatcha">
            {!! Recaptcha::render() !!}
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Iniciar sesi&oacute;n</button>
            </div><!-- /.col -->
        </div>
    </form>

</div><!-- /.login-box-body -->

</div><!-- /.login-box -->

    @include('auth.scripts')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

@endsection
