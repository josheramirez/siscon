@extends('layouts.app4')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Contrarreferencia-->
			<div class="panel panel-default">
                <div class="panel-heading">Detalle Contrarreferencia</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row row-border row-padding">
						<div class="col-xs-1 col-sm-1 col-md-1">
							<a href="{{ URL::to('contrarreferencias/pdf/'.$contrarreferencia->id) }}" class="btn btn-primary" target="_blank">Imprimir</a>
						</div>
					</div>
					<!--Información del paciente-->
					<div class="row row-border">
						<div class="col-xs-10 col-sm-10 col-md-11">
							<h5>Información Paciente</h5>
						</div>
						<div class="col-xs-1 col-sm-1 col-md-1">
							<h5>
								<a href="#" class="pull-right"  data-toggle="collapse" data-target="#infoPaciente">
									<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h5>
						</div>
					</div>
					<br>
					<div id="infoPaciente" class="collapse in">
						<div class="row row-padding row-border">
							<div class="col-xs-12 col-sm-6 col-md-6">
							@if( $paciente->tipoDoc == 1 )
								<label for="paciente" class="control-label">R.U.N.</label>
								<input id="paciente" type="text" class="form-control input-sm" value="{{ $paciente->rut }}-{{ $paciente->dv }}" readonly>
							@else
								<label for="paciente" class="control-label">Número de Documento</label>
								<input id="paciente" type="text" class="form-control input-sm" value="{{ $paciente->numDoc }}" readonly>
							@endif
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="nombre" class="control-label">Nombre</label>
								<input id="nombre" type="text" class="form-control input-sm" value="{{ $paciente->nombre }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="apPaterno" class="control-label">Apellido Paterno</label>
								<input id="apPaterno" type="text" class="form-control input-sm" value="{{ $paciente->apPaterno }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="apMaterno" class="control-label">Apellido Materno</label>
								<input id="apMaterno" type="text" class="form-control input-sm" value="{{ $paciente->apMaterno }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
								<label for="fechaNacimiento" class="control-label">Fecha Nac.</label>
								<input id="fechaNacimiento" type="text" class="form-control input-sm" value="{{ $fechaNacimiento }}" readonly>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<label for="edad" class="control-label">Edad</label>
								<input id="edad" type="text" class="form-control input-sm" value="{{ $edad }}" readonly>
							</div>
						</div>
						<div class="row row-padding row-border">
							<div class="col-md-4">
								<label for="direccion" class="control-label">Dirección</label>
								<input id="direccion" type="text" class="form-control input-sm" value="{{ $paciente->direccion }} {{ $paciente->numero }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
								<label for="telefono" class="control-label">Teléfono</label>
								<input id="telefono" type="text" class="form-control input-sm" value="{{ $paciente->telefono }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-2">
								<label for="telefono2" class="control-label">Teléfono 2</label>
								<input id="telefono2" type="text" class="form-control input-sm" value="{{ $paciente->telefono2 }}" readonly>
							</div>
							<div class="col-md-4">
								<label for="email" class="control-label">Correo Electrónico</label>
								<input id="email" type="text" class="form-control input-sm" value="{{ $paciente->email }}" readonly>
							</div>
						</div>
					</div>
					<!--Información Clinica-->
					<div class="row row-border">
						<div class="col-xs-10 col-sm-10 col-md-11">
							<h5>Información Clínica</h5>
						</div>
						<div class="col-xs-1 col-sm-1 col-md-1">
							<h5>
								<a href="#" class="pull-right"  data-toggle="collapse" data-target="#infoHospital">
									<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h5>
						</div>
					</div>
					</br>
					<div id="infoHospital" class="collapse in">
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="contrarrefiere" class="control-label">Establecimiento Contrarreferencia</label>
								<input id="contrarrefiere" type="text" class="form-control input-sm" name="contrarrefiere" value="{{ $estContra->name }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="origen" class="control-label">Establecimiento Origen APS</label>
								<input id="origen" type="text" class="form-control input-sm input-sm" value="{{ $estOrigen->name }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="primeraConsulta" class="control-label">Primera Consulta</label>
								@if( $contrarreferencia->primera_consulta == 1 )
									<input id="primeraConsulta" type="text" class="form-control input-sm" value="Si" readonly>
								@elseif( $contrarreferencia->primera_consulta == 2 )
									<input id="primeraConsulta" type="text" class="form-control input-sm" value="No" readonly>
								@endif
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="diagEgreso" class="control-label">Diagnóstico de Egreso</label>
								<input id="diagEgreso" type="text" class="form-control input-sm" name="diagEgreso" value="{{ $cie10->codigo }} - {{ $cie10->name }}" readonly>
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<label for="tratamiento" class="control-label">Tratamiento Realizado</label>
								<textarea class="form-control input-sm" rows="3"  id="tratamiento" name="tratamiento" readonly>{{ $contrarreferencia->tratamiento }}</textarea>
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<label for="indicaciones" class="control-label">Indicaciones</label>
								<textarea class="form-control input-sm" rows="3"  id="indicaciones" name="indicaciones" readonly>{{ $contrarreferencia->indicaciones_aps }}</textarea>
							</div>
						</div>
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<label for="observaciones" class="control-label">Observación</label>
								<textarea class="form-control input-sm" rows="3"  id="observaciones" name="observaciones" readonly>{{ $contrarreferencia->observaciones }}</textarea>
							</div>
						</div>
						<div class="row row-padding row-border">
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="esGes" class="control-label">GES</label>
								@if( $contrarreferencia->ges_id != null )
									<input id="esGes" type="text" class="form-control input-sm" value="Si" readonly>
								@else
									<input id="esGes" type="text" class="form-control input-sm" value="No" readonly>
								@endif
							</div>
							@if( $contrarreferencia->ges_id != null )
								<div class="col-xs-12 col-sm-6 col-md-3" id="divTipoGes">
									<label for="tipoGes" class="control-label">Manejo en</label>
									<input id="tipoGes" type="text" class="form-control input-sm" value="{{ $tipoGes }}" readonly>
								</div>
							@endif
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="protocolo" class="control-label">Pertinencia por Protocolo</label>
								<input id="protocolo" type="text" class="form-control input-sm" value="{{ $protocolo->name }}" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="tiempo" class="control-label">Pertinencia por Tiempo</label>
								<input id="tiempo" type="text" class="form-control input-sm" value="{{ $tiempo->name }}" readonly>
							</div>
						</div>
					</div>
					<!--Información Salida-->
					<div class="row row-border">
						<div class="col-xs-10 col-sm-10 col-md-11">
							<h5>Salida</h5>
						</div>
						<div class="col-xs-1 col-sm-1 col-md-1">
							<h5>
								<a href="#" class="pull-right"  data-toggle="collapse" data-target="#infoSalida">
									<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h5>
						</div>
					</div>
					</br>
					<div id="infoSalida" class="collapse in">
						<div class="row row-padding">
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="tipoAlta" class="control-label">Tipo Salida</label>
								@if( $contrarreferencia->tipo_salida == 0 )
									<input id="tipoAlta" type="text" class="form-control input-sm" value="Alta" readonly>
								@elseif( $contrarreferencia->tipo_salida == 2 )
									<input id="tipoAlta" type="text" class="form-control input-sm" value="Alta con Control APS" readonly>
								@elseif( $contrarreferencia->tipo_salida == 1 )
									<input id="tipoAlta" type="text" class="form-control input-sm" value="Control en la Especialidad" readonly>
								@endif
							</div>
							@if( $contrarreferencia->tipo_salida == 2 )
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="controlAps" class="control-label">Control en</label>
								@if( $contrarreferencia->control_aps == 1 )
									<input id="controlAps" type="text" class="form-control input-sm" value="Menos de 7 Días" readonly>
								@elseif( $contrarreferencia->control_aps == 2 )
									<input id="controlAps" type="text" class="form-control input-sm" value="Menos de 15 Días" readonly>
								@elseif( $contrarreferencia->control_aps == 3 )
									<input id="controlAps" type="text" class="form-control input-sm" value="Menos de 30 Días" readonly>
								@endif
							</div>
							@elseif( $contrarreferencia->tipo_salida == 2 )
							<div class="col-xs-12 col-sm-6 col-md-3">
								<label for="controlEspec" class="control-label">Próximo Control en</label>
								@if( $contrarreferencia->control_especialidad == 1 )
									<input id="controlEspec" type="text" class="form-control input-sm" value="{{ $contrarreferencia->control_especialidad }} Mes" readonly>
								@else
									<input id="controlEspec" type="text" class="form-control input-sm" value="{{ $contrarreferencia->control_especialidad }} Meses" readonly>
								@endif
							</div>
							@endif
						</div>
						<div class="row row-padding row-border">
							@if( $contrarreferencia->tipo_salida == 1 )
							<div class="col-xs-12 col-sm-12 col-md-12">
								<label for="examenes" class="control-label">Examenes</label>
								<textarea class="form-control" rows="3"  id="examenes" name="examenes" readonly>{{ $contrarreferencia->detalle_examenes }}</textarea>
							</div>
							@endif
						</div>
					</div>
					<!--Actualizar Información-->
					<div class="row row-border">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<h5>Actualizar</h5>
						</div>
					</div>
					</br>
					<div class="row row-padding">
						<div class="col-xs-1 col-sm-1 col-md-1">
							<a class="btn btn-sm btn-primary" href="{{ URL::to('contrarreferencias/'. $contrarreferencia->id. '/cerrar') }}">Cierre de Solicitud</a>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
