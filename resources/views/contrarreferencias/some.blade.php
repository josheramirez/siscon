@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Actualización de Contrarreferencia-->
	<?php $message=Session::get('message') ?>
	@if($message == 'actualiza')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Contrarreferencia Actualizada Exitosamente
		</div>
	@endif
	<!--FIN Mensajes de Guardado o Actualización de Contrarreferencia-->
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">SOME - Consulta Contrarreferencias</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row">
						<!-- Formulario de Filtro por Rut -->
						<div class="col-md-3 col-md-offset-3">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('contrarreferencias/some') }}">
								<div class="input-group">
									<input id="searchRut" name="searchRut" type="text" class="form-control input-sm" placeholder="Buscar por Rut">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<!-- Formulario de Filtro por NumDoc -->
						<div class="col-md-3">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('contrarreferencias/some') }}">
								<div class="input-group">
									<input id="searchNumDoc" name="searchNumDoc" type="text" class="form-control input-sm" placeholder="Buscar por Número de Documento (Pasaporte)">
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
						<!-- Formulario de Filtro por Tipo de Salida -->
						<div class="col-md-3">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('contrarreferencias/some') }}">
								<div class="input-group">
									<select id="searchSalida" class="form-control input-sm" name="searchSalida">
										<option value="" selected>Seleccione Tipo de Salida</option>
										<option value="1">Alta</option>
										<option value="2">Alta con Control Aps</option>
										<option value="3">Control en la Especialidad</option>
									</select>
									<span class="input-group-btn ">
										<button class="btn btn-default btn-sm" type="submit">Ir</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					</br>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>RUN / Nro de Documento</th>
									<th>Fecha Solicitud</th>
									<th>Ges</th>
									<th>Pertinencia</th>
									<th>Tipo Salida</th>
									<th>Control En</th>
									<th>Días Faltantes</th>
									<th>Acciones</th>
								  </tr>
								</thead>
								<tbody>
									@foreach($contrarreferencias as $contrarreferencia)
										<tr>
											<td>
												@if ( $contrarreferencia->tipoDoc == 1 )
													{{ $contrarreferencia->rut }} - {{ $contrarreferencia->dv }}
												@else
													{{ $contrarreferencia->numDoc }}
												@endif
											</td>
											<td>{{ $contrarreferencia->fecha }}</td>
											<td>
												@if ( $contrarreferencia->ges_id == null )
													No
												@else
													Si
												@endif
											</td>
											<td>{{ $contrarreferencia->name }}</td>
											<td>
												@if ( $contrarreferencia->tipo_salida == 0 )	
													Alta
												@elseif ( $contrarreferencia->tipo_salida == 2 )
													Alta con Control APS
												@elseif ( $contrarreferencia->tipo_salida == 1 )
													Control en la Especialidad
												@endif
											</td>
											<td>
												@if ( $contrarreferencia->tipo_salida == 2 )
													@if ( $contrarreferencia->control_aps == 1 )
														Menos de 7 Días
													@elseif ( $contrarreferencia->control_aps == 2 )
														Menos de 15 Días
													@elseif ( $contrarreferencia->control_aps == 3 )
														Menos de 30 Días
													@endif
												@elseif ( $contrarreferencia->tipo_salida == 1 )
													@if ( $contrarreferencia->control_especialidad == 1 )
														1 Mes
													@else
														{{ $contrarreferencia->control_especialidad }} Meses
													@endif
												@endif
											</td>
											<td style="text-align:center">
												@if ( $contrarreferencia->tipo_salida == 2 )
													@php
														$now = new DateTime('now');
														$fecha_fin = new DateTime($contrarreferencia->created_at);
														$class="label label-success";
														if ($contrarreferencia->control_aps == 1) {
															$fecha_fin = $fecha_fin->modify('+7 day');
														}
														if ($contrarreferencia->control_aps == 2) {
															$fecha_fin = $fecha_fin->modify('+15 day');
														}
														if ($contrarreferencia->control_aps == 3) {
															$fecha_fin = $fecha_fin->modify('+30 day');
														}

														$resultado = $now->diff( $fecha_fin );
														$valor = $resultado->format('%r%a');

														if ($valor < 0 )
														{
															$class="label label-danger";
														}else if ($valor == 0 )
														{
															$class="label label-warning";
														}
													@endphp
													<span class="<?php echo $class; ?>"> {{ $valor }} días </span>

												@elseif ( $contrarreferencia->tipo_salida == 1 )
													@php
														$now = new DateTime('now');
														$fecha_fin = new DateTime($contrarreferencia->created_at);
														$meses = $contrarreferencia->control_especialidad;
														$class="label label-success";

														$fecha_fin = $fecha_fin->modify('+'.$meses.' months');
														$resultado = date_diff($now, $fecha_fin );
														$valor = $resultado->format('%r%a');

														if ($valor < 0 )
														{
															$class="label label-danger";
														}else if ($valor == 0 )
														{
															$class="label label-warning";
														}
													@endphp

													<span class="<?php echo $class; ?>"> {{ $valor }} días </span>
												@endif

											</td>
											<td>
												@if ( $contrarreferencia->tipo_salida != 0 )
													<a href="{{ URL::to('contrarreferencias/' . $contrarreferencia->id . '/agendaSome') }}">Agendar/Egresar</a>
												@endif
											</td>
										</tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $contrarreferencias->links() }}
						</div>
					</div>
					<!-- FIN Lista de Cie10 -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
