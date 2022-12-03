@use(App\Sidebar)

{{-- Sidebar --}}
<div class="sidebar">
	{{-- Sidebar user (optional) --}}
	<div class="user-panel mt-3 pb-3 mb-3 d-flex">
		<div class="image">
			<img src="/public/img/profile.png" class="rounded-circle elevation-2" alt="User Image" style="opacity:0.8;">
		</div>
		<div class="info">
			<a href="#" class="d-block">{{ $user->username }}</a>
		</div>
	</div>
	<div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-between">
		<div class="info">
			<a href="/select-service" class="d-block">Servicio: {{ $user->service->name }}</a>
		</div>
	</div>
	{{-- Sidebar Menu --}}
	<nav class="mt-2">
		<ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
			{{-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library --}}
			
			@foreach ($sidebar as $item)
				@if (!is_array($item))
					<li class="nav-header"><b>{{ $item }}</b></li>
				@else
					@if(is_array($item[2]))
						@php
							$isChildren = false;
							foreach($item[2] as $route) {
								$isChildren = Sidebar::actualRoute($route, $lastRoute[1]);

								if($isChildren) break;
							}
						@endphp
						<li class="nav-item {{ $isChildren ? 'menu-open' : '' }}">
							<a href="#" class="nav-link {{ $isChildren ? 'active' : '' }}">
								<i class="{{ $item[1] }} nav-icon"></i>
								<p>
									{{ $item[0] }}
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@foreach ($item[2] as $subitem)								
									<li class="nav-item">
										<a href="{{ $subitem[1] }}" class="nav-link {{ Sidebar::actualRoute($subitem, $lastRoute[1]) ? 'active' : '' }}">
											<i class="far fa-circle nav-icon"></i>
											<p>{{ $subitem[0] }}</p>
										</a>
									</li>
								@endforeach
							</ul>
						</li>
					@else
						<li class="nav-item">
							<a href="{{ $item[2] }}" class="nav-link {{ $lastRoute[1] == $item[2] ? 'active' : '' }}">
								<i class="{{ $item[1] }} nav-icon"></i>
								<p>{{ $item[0] }}</p>
							</a>
						</li>
					@endif
				@endif
			@endforeach
		</ul>
	</nav>
	{{-- /.sidebar-menu --}}
</div>
{{-- /.sidebar --}}