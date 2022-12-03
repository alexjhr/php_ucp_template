<div id="template" class="row mt-2 border py-2">
	<div class="col-auto">
		<span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
	</div>
	<div class="col d-flex flex-column">
		<p class="mb-0">
			<span class="lead" data-dz-name></span>
			(<span data-dz-size></span>)
		</p>
		<strong class="error text-danger" data-dz-errormessage></strong>
		<div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
			<div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
		</div>
	</div>
	<div class="col-auto d-flex align-items-center">
		<div class="btn-group">
			<button class="btn btn-primary start">
				<i class="fas fa-upload"></i>
				<span>Iniciar</span>
			</button>
			<button data-dz-remove class="btn btn-danger cancel">
				<i class="fas fa-times-circle"></i>
				<span>Cancelar</span>
			</button>
		</div>
		<button class="btn btn-info copy" style="display: none;">
			<i class="fas fa-clipboard"></i>
			<span>Copiar enlace</span>
		</button>
	</div>
</div>