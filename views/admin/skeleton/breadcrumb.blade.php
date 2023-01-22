<ol class="breadcrumb">
	@foreach ($routes as $route)
		@if($route[1])
			<li class="breadcrumb-item">
				<a href="{{ $route[1] }}">{{ $route[0] }}</a>
			</li>
		@else
			<li class="breadcrumb-item active">{{ $route[0] }}</li>				
		@endif
	@endforeach
	<li class="breadcrumb-item active">{{ $lastRoute[0] }}</li>
</ol>