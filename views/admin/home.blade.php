@extends('admin/skeleton/index')

@section('mainContent')
<div class="row">
	<div class="col-12 col-sm-6">
		<div class="info-box mb-3">
			<span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Usuarios</span>
				<span class="info-box-number">{{ $countUsers }}</span>
			</div>
			{{-- /.info-box-content --}}
		</div>
		{{-- /.info-box --}}
	</div>
	{{-- /.col --}}
	<div class="col-12 col-sm-6">
		<div class="info-box mb-3">
			<span class="info-box-icon bg-danger elevation-1"><i class="fas fa-crown"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Administradores</span>
				<span class="info-box-number">{{ $countAdmins }}</span>
			</div>
			{{-- /.info-box-content --}}
		</div>
		{{-- /.info-box --}}
	</div>
	{{-- /.col --}}
</div>
{{-- /.row --}}

@endsection