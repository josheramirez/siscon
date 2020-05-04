<div class="collapse navbar-collapse" id="app-navbar-collapse">		
	<!-- Left Side Of Navbar -->
	<ul class="nav navbar-nav">
		<!-- Authentication Links -->
		@if (!Auth::guest())

			@if( Auth::user()->isRole('Administrador') || Auth::user()->isRole('Pacientes'))
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Administración <span class="caret"></span>
					</a>
					
					<ul class="dropdown-menu" role="menu">
						@if( Auth::user()->isRole('Administrador'))
							<li>
								<a href="{{ URL::to('users') }}"> Usuarios </a>
							</li>
						@endif
						
						@if(Auth::user()->isRole('Pacientes'))
							<li>
								<a href="{{ URL::to('pacientes') }}"> Pacientes </a>
							</li>
						@endif
					</ul>
				</li>
			@endif	
			
			@if( Auth::user()->isRole('Hospital') || Auth::user()->isRole('Some') || Auth::user()->isRole('Aps') )
				<li class="dropdown">	
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Contrarreferencia <span class="caret"></span>
					</a>
					
					<ul class="dropdown-menu" role="menu">
						@if( Auth::user()->isRole('Hospital') )	
							<li>
								<a href="{{ URL::to('contrarreferencias/create') }}" aria-expanded="false"> Registro Contrarreferencia </a>
							</li>
						@endif
						
						@if( Auth::user()->isRole('Some') )	
							<li>
								<a href="{{ URL::to('contrarreferencias/some') }}" aria-expanded="false"> Some </a>
							</li>
						@endif
						
						@if( Auth::user()->isRole('Aps') )	
							<li>
								<a href="{{ URL::to('contrarreferencias/aps') }}" aria-expanded="false"> Aps </a>
							</li>
						@endif
					</ul>
				</li>	
			@endif

		@endif

		<li class="dropdown">	
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Reportes <span class="caret"></span>
			</a>
			
			<ul class="dropdown-menu" role="menu">
				@if( Auth::user()->isRole('Hospital') || Auth::user()->isRole('Some') || Auth::user()->isRole('Aps') )
				<li>
					<a href="{{ URL::to('contrarreferencias/reporte') }}"> Reporte Contrarreferencia </a>
				</li>
				@endif
			</ul>
		</li>

		<li class="dropdown">	
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Manuales <span class="caret"></span>
			</a>
			
			<ul class="dropdown-menu" role="menu">
				@if( Auth::user()->isRole('Hospital') || Auth::user()->isRole('Some') || Auth::user()->isRole('Aps') )
				<li>
					<a href="{{ asset('/manuales/contrarreferencia.pdf') }}" aria-expanded="false" target="_blank"> Manual de Contrarreferencia </a>
				</li>
				@endif
			</ul>
		</li>
	</ul>

	<!-- Right Side Of Navbar -->
	<ul class="nav navbar-nav navbar-right">
		<!-- Authentication Links -->
		@if (!Auth::guest())
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					{{ Auth::user()->name }} <span class="caret"></span>
				</a>

				<ul class="dropdown-menu" role="menu">
					<li>
						<a href="{{ URL::to('users/password/cambiar') }}"> Cambiar Contraseña </a>
					</li>
					
					<li>
						<a href="{{ route('logout') }}"
							onclick="event.preventDefault();
									 document.getElementById('logout-form').submit();">
							Salir
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</li>
				</ul>
			</li>
		@endif
	</ul>
</div>