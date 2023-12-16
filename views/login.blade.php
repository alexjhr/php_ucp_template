@use(\Tamtamchik\SimpleFlash\Flash)

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{!! $_ENV['SITE_NAME'] !!}</title>

	{{-- Google Font: Source Sans Pro --}}
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	{{-- Font Awesome --}}
	@css('plugins/fontawesome-free/css/all.min.css')
	{{-- icheck bootstrap --}}
	@css('plugins/icheck-bootstrap/icheck-bootstrap.min.css')
	{{-- Theme style --}}
	@css('css/adminlte.min.css')
</head>
<body class="hold-transition login-page">
<div class="login-box">
	{{-- /.login-logo --}}
	<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<span class="h1">{!! $_ENV['SITE_NAME_HTML'] !!}</span>
		</div>
		<div class="card-body">
			<p class="login-box-msg">Iniciar sesión en el sistema</p>

			{!! Flash::display() !!}

			<form action="#" method="POST">
				<div class="input-group mb-3">
					<input type="email" name="email" class="form-control" placeholder="Correo electronico" value="{{ @$_GET['email'] }}">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password" class="form-control" placeholder="Contraseña">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-block">Acceder</button>
			</form>
		</div>
		{{-- /.card-body --}}
	</div>
	{{-- /.card --}}
</div>
{{-- /.login-box --}}

{{-- jQuery --}}
@js('plugins/jquery/jquery.min.js')
{{-- Bootstrap 4 --}}
@js('plugins/bootstrap/js/bootstrap.bundle.min.js')
{{-- AdminLTE App --}}
@js('js/adminlte.min.js')
</body>
</html>
