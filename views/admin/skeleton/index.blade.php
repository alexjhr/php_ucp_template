<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $_ENV['SITE_NAME'] }}</title>

	{{-- Google Font: Source Sans Pro --}}
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	{{-- Font Awesome --}}
	@css('plugins/fontawesome-free/css/all.min.css')
	{{-- Theme style --}}
	@css('css/adminlte.min.css')
	{{-- Additional Styles --}}
	@yield('additionalStyles')

	{{-- Extract last route --}}
	@php $lastRoute = array_pop($routes); @endphp
</head>
<body class="hold-transition sidebar-mini layout-fixed">
{{-- Site wrapper --}}
<div class="wrapper">
	{{-- Navbar --}}
	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		{{-- Left navbar links --}}
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
			</li>
			<li class="nav-item d-none d-sm-inline-block">
				<a href="/" class="nav-link">Inicio</a>
			</li>
		</ul>

		{{-- Right navbar links --}}
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" data-widget="fullscreen" href="#" role="button">
					<i class="fas fa-expand-arrows-alt"></i>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/logout">
					<i class="fas fa-sign-out-alt"></i>
				</a>
			</li>
		</ul>
	</nav>
	{{-- /.navbar --}}

	{{-- Main Sidebar Container --}}
	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		{{-- Brand Logo --}}
		<a href="/" class="brand-link text-center">
			<span class="brand-text font-weight-light">{!! $_ENV['SITE_NAME_HTML'] !!}</span>
		</a>

		@include('admin/skeleton/sidebar')
	</aside>

	{{-- Content Wrapper. Contains page content --}}
	<div class="content-wrapper">
		{{-- Content Header (Page header) --}}
		<section class="content-header">
			<div class="container-fluid">
				<div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
					<h1>{{ $lastRoute[0] }}</h1>

					@include('admin/skeleton/breadcrumb')
				</div>
			</div>{{-- /.container-fluid --}}
		</section>

		{{-- Main content --}}
		<section class="content">
			@yield('mainContent')
		</section>
		{{-- /.content --}}
	</div>
	{{-- /.content-wrapper --}}

	<footer class="main-footer">
		<div class="float-right d-none d-sm-block">
			<b>Version</b> {{ $_ENV['SITE_VERSION'] }}
		</div>
		<strong>{{ $_ENV['SITE_NAME'] }}</strong>.
	</footer>
</div>
{{-- ./wrapper --}}

{{-- jQuery --}}
@js('plugins/jquery/jquery.min.js')
{{-- Bootstrap 4 --}}
@js('plugins/bootstrap/js/bootstrap.bundle.min.js')
{{-- AdminLTE App --}}
@js('js/adminlte.min.js')
{{-- Additional Scripts --}}
@yield('additionalScripts')

</body>
</html>
