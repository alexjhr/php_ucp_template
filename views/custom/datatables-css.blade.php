{{-- Customs Styles: Datatables --}}
@css('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')
@css('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')
@css('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')

<style>
	table.dataTable {
		margin-top: 0 !important;
		margin-bottom: 0 !important;
	}
	.dataTables_info {
		padding-left: 1em;
		padding-bottom: 1em;
	}

	.dataTables_paginate {
		padding-right: 1em;
		padding-top: .25em;
	}
	div.dt-buttons {
		padding-bottom: 1em;
		flex-wrap: nowrap !important;
	}
</style>