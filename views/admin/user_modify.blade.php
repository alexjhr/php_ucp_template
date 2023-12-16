@use(\App\RolesDetails)
@use(\Tamtamchik\SimpleFlash\Flash)
@extends('admin/skeleton/index')

@section('mainContent')
	<div class="container-fluid">
		@if(!$edit_user)
			{{-- Alerts --}}
			{!! Flash::display() !!}
		@else
			{{-- Edit User --}}
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">Editar usuario</h3>
				</div>
				{{-- /.card-header --}}
				{{-- form start --}}
				<form class="form-horizontal" method="POST" action="#">
					<div class="card-body">
						{{-- Alerts --}}
						{!! Flash::display() !!}

						<div class="form-group row">
							<label for="email" class="col-sm-2 col-form-label">Correo electrónico</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="email" name="email" value="{{ $edit_user->email }}" placeholder="Ingresa un correo electrónico">
							</div>
						</div>
						<div class="form-group row">
							<label for="username" class="col-sm-2 col-form-label">Nombre de usuario</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="username" name="username" value="{{ $edit_user->username }}" placeholder="Ingresa una nombre de usuario">
							</div>
						</div>
						<div class="form-group row">
							<label for="role" class="col-sm-2 col-form-label">Rol</label>
							<div class="col-sm-10">
								<select class="custom-select" name="role" id="role">
									@foreach(RolesDetails::availableRoles() as $role)
										@php
											$roleValue = RolesDetails::getRoleByName($role);
											$selected = $GLOBALS['auth']->admin()->doesUserHaveRole($edit_user->id, $roleValue);
										@endphp

										<option value="{{ $role }}" {{ $selected ? 'selected' : '' }}>{{ RolesDetails::info[ $role][0] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<hr>
						<div class="form-group row">
							<label for="password" class="col-sm-2 col-form-label">Nueva contraseña</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="password" name="password" placeholder="Ingresa una contraseña">
							</div>
						</div>
						<div class="d-flex justify-content-end">
							<button type="submit" class="btn btn-sm btn-primary">Editar usuario</button>
						</div>
					</div>
					{{-- /.card-body --}}
				</form>
			</div>
			{{-- /.card --}}
		@endif
	</div>
	{{-- /.container-fluid --}}
@endsection

@section('additionalStyles')
	@css('plugins/select2/css/select2.min.css')
	@css('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')
@endsection

@section('additionalScripts')
	@js('plugins/select2/js/select2.full.min.js')
	
	<script>
		$(function () {
			//Initialize Select2 Elements
			$('.select2bs4').select2({
				theme: 'bootstrap4'
			})
		});
  	</script>
@endsection