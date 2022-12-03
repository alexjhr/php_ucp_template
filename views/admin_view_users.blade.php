@use(\App\RolesDetails)
@use(\App\Helpers\DateFormat)
@use(App\Model\User)
@extends('skeleton/index')

@section('mainContent')
	<div class="container-fluid">
		{{-- Alerts --}}
		{!! $GLOBALS['flash']->display() !!}

		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title border-0">Lista de todos los usuarios registrados en el sistema</h3>
			</div>
			{{-- /.card-header --}}

			<div class="card-body table-responsive p-0">
				<table id="adminUsers" class="table border-bottom table-hover">
					<thead>
						<tr>
							<th>Usuario</th>
							<th>Correo electronico</th>
							<th>Rol</th>
							<th>Último inicio de sesión</th>
							<th>Fecha de registro</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						@foreach (User::all() as $item)
							<tr id="itemUser{{ $item->id }}">
								<td>{{ $item->username }}</td>
								<td>{{ $item->email }}</td>
								<td>
									@foreach ($item->getRoles() as $role)
										<span class="badge bg-{{ RolesDetails::info[ $role ][1] }}">{{ RolesDetails::info[ $role ][0] }}</span>
									@endforeach
								</td>
								<td>{{ $item->last_login ? DateFormat::humanTiming($item->last_login) : 'Nunca' }}</td>
								<td>{{ date('Y-m-d H:i:s', $item->created_at) }}</td>
								<td>
									<a href="/admin/edit-user/{{ $item->id }}" role="button" class="btn btn-sm btn-primary">
										<i class="fas fa-edit"></i>
									</a>
									<a href="/admin/inspect-user/{{ $item->id }}" role="button" class="btn btn-sm btn-success">
										<i class="fas fa-eye"></i>
									</a>
									<button type="button" class="btn btn-sm btn-danger" data-bs-viewUserId="{{ $item->id }}" data-bs-viewUser="delete">
										<i class="fas fa-trash"></i>
									</button>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			{{-- /.card-body --}}
		</div>
		{{-- /.card --}}
	</div>
	{{-- /.container-fluid --}}
@endsection

@section('additionalStyles')
	{{-- SweetAlert2 --}}
	@css('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')

	{{-- Datatables --}}
	@include('custom/datatables-css')
@endsection

@section('additionalScripts')
	{{-- SweetAlert2 --}}
	@js('plugins/sweetalert2/sweetalert2.min.js')

	{{-- DataTables & Plugins --}}
	@js('plugins/datatables/jquery.dataTables.min.js')
	@js('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')
	@js('plugins/datatables-responsive/js/dataTables.responsive.min.js')
	@js('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')
	@js('plugins/datatables-buttons/js/dataTables.buttons.min.js')
	@js('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')

	<script>
		$('#adminUsers').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": false,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
			}
		});

		$('[data-bs-viewUser="delete"]').on('click', function() {
			let itemId = $(this).attr('data-bs-viewUserId');

			Swal.fire({
				icon: 'question',
				title: '¿Estás seguro de eliminar el usuario?',
				showCancelButton: true,
				confirmButtonText: 'Eliminar',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.isConfirmed) {
					$.post('/admin/delete-user', { id: itemId }, function(data) {

						$('#itemUser' + itemId).remove();
						Swal.fire('Eliminado!', '', 'success')
					}).catch(() => Swal.fire('No se pudo eliminar el usuario', '', 'error'));
				}
			})
		});
	</script>
@endsection