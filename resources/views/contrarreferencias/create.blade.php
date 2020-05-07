@extends('layouts.app4')

@section('content')
<div class="container-fluid">
	<!--Mensajes de Guardado o Actualización de Contrarreferencia-->
	<?php $message=Session::get('message') ?>

	<script>console.log($message)</script>
	@if($message == 'contrarreferencia')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Contrarreferencia Creada Exitosamente
		</div>
	@elseif($message == 'store')
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente Creado Exitosamente
	@elseif($message == 'paciente')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente no Existe
		</div>
	@elseif($message == 'cie10')
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Diagnóstico de Egreso no Existe
		</div>
	@elseif($message == 'actualizado')
		<div class="alert alert-warning alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Paciente Actualizado Exitosamente
		</div>
	@endif
	<!--Recupera ID de última contrarreferencia-->
	<?php $idContra=Session::get('idContra') ?>

	<!--FIN Mensajes de Guardado o Actualización de Contrarreferencia-->
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Contrarreferencia-->
			<div class="panel panel-default">
                <div class="panel-heading">Ingreso de Contrarreferencia</div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ URL::to('contrarreferencias') }}">
                        {{ csrf_field() }}

						<!--Información del paciente-->
						<div class="row row-border">
							<div class="col-xs-10 col-sm-11 col-md-11">
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
									<input id="paciente" type="text" class="form-control input-sm" name="paciente" value="{{ old('paciente') }}" placeholder="R.U.N. Paciente (Sin puntos ni dígito verificador)" required autofocus>
								</div>
								<div class="col-xs-12 col-sm-1 col-md-1 col-md-offset-5">
									<a class="btn btn-sm btn-primary pull-right" href="{{ URL::to('crear/pacientes/2') }}">Crear Paciente</a>
								</div>
							</div>

							<div class="row row-padding">
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="nombre" class="control-label">Nombre</label>
									<input id="nombre" type="text" class="form-control input-sm" name="nombre" value="{{ old('nombre') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="apPaterno" class="control-label">Apellido Paterno</label>
									<input id="apPaterno" type="text" class="form-control input-sm" name="apPaterno" value="{{ old('apPaterno') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="apMaterno" class="control-label">Apellido Materno</label>
									<input id="apMaterno" type="text" class="form-control input-sm" name="apMaterno" value="{{ old('apMaterno') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-2">
									<label for="fechaNacimiento" class="control-label">Fecha Nac.</label>
									<input id="fechaNacimiento" type="text" class="form-control input-sm" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-1">
									<label for="edad" class="control-label">Edad</label>
									<input id="edad" type="text" class="form-control input-sm" name="edad" value="{{ old('edad') }}" readonly>
								</div>
							</div>
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-6 col-md-4">
									<label for="direccion" class="control-label">Dirección</label>
									<input id="direccion" type="text" class="form-control input-sm" name="direccion" value="{{ old('direccion') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-2">
									<label for="telefono" class="control-label">Teléfono</label>
									<input id="telefono" type="text" class="form-control input-sm" name="telefono" value="{{ old('telefono') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-2">
									<label for="telefono2" class="control-label">Teléfono 2</label>
									<input id="telefono2" type="text" class="form-control input-sm" name="telefono" value="{{ old('telefono2') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4">
									<label for="email" class="control-label">Correo Electrónico</label>
									<input id="email" type="text" class="form-control input-sm" name="email" value="{{ old('email') }}" readonly>
								</div>
							</div>
						</div>
						<!--Elementos ocultos-->
						<input name="idPaciente" id="idPaciente" type="hidden" value="{{ old('idPaciente') }}">
						<!--Información Clínica-->
						<div class="row row-border">
							<div class="col-xs-10 col-sm-11 col-md-11">
								<h5>Información Clínica SIC</h5>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1">
								<h5>
									<a href="#" class="pull-right"  data-toggle="collapse" data-target="#infoClinica">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h5>
							</div>
						</div>
						<br>
						<div id="infoClinica" class="collapse in">
							<div class="row row-padding">
								<div class="col-xs-12 col-sm-6 col-md-4">
									<label for="especialidadSIC" class="control-label">Especialidad</label>
									<input id="especialidadSIC" type="text" class="form-control input-sm" name="especialidadSIC" value="{{ old('especialidadSIC') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-5">
									<label for="sospechaSIC" class="control-label">Sospecha Diagnóstica</label>
									<input id="sospechaSIC" type="text" class="form-control input-sm" name="sospechaSIC" value="{{ old('sospechaSIC') }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="fechaSIC" class="control-label">Fecha de Entrada</label>
									<div class="form-inline">
										<input id="fechaSIC" type="text" class="form-control input-sm" name="fechaSIC" value="{{ old('fechaSIC') }}" readonly>
										<a class="btn btn-default btn-sm pull-right" id="detalles">
											<span class="glyphicon glyphicon-list" aria-hidden="true"></span>
											Detalles
										</a>
									</div>
								</div>
							</div>
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<label for="precisionSIC" class="control-label">Precisión Diagnóstica</label>
									<textarea class="form-control" rows="3"  id="precisionSIC" name="precisionSIC" readonly>{{ old('precisionSIC') }}</textarea>
								</div>
							</div>
						</div>


						<!-- Inicio Tipo Salida -->
						
						<div class="row row-border">
							<div class="col-xs-10 col-sm-11 col-md-11">
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
									<select id="tipoAlta" class="form-control" name="tipoAlta" required>
										<option value="">Seleccione</option>
										@if(old('tipoAlta') == 0)
											<option value="0">Alta Sin Control APS</option>
											<option value="2" selected>Alta con Control APS</option>
											<option value="1">Control en la Especialidad</option>
										@elseif(old('tipoAlta') == 1)
											<option value="0">Alta</option>
											<option value="2">Alta con Control APS</option>
											<option value="1" selected>Control en la Especialidad</option>
										@elseif(old('tipoAlta') == 2)
											<option value="0">Alta</option>
											<option value="2" selected>Alta con Control APS</option>
											<option value="1">Control en la Especialidad</option>
										@else
											<option value="0">Alta</option>
											<option value="2">Alta con Control APS</option>
											<option value="1">Control en la Especialidad</option>
										@endif
									</select>
								</div>
								
								<div class="col-xs-12 col-sm-6 col-md-3" id="divControlAps" hidden>
									<label for="controlAps" class="control-label">Control en</label>
									<select id="controlAps" class="form-control" name="controlAps">
										<option value="">Seleccione</option>
										@if(old('controlAps') == 1)
											<option value="1" selected>Menos de 7 Días</option>
											<option value="2">Menos de 15 Días</option>
											<option value="3">Menos de 30 Días</option>
										@elseif(old('controlAps') == 2)
											<option value="1">Menos de 7 Días</option>
											<option value="2" selected>Menos de 15 Días</option>
											<option value="3">Menos de 30 Días</option>
										@elseif(old('controlAps') == 3)
											<option value="1">Menos de 7 Días</option>
											<option value="2">Menos de 15 Días</option>
											<option value="3" selected>Menos de 30 Días</option>
										@else
											<option value="1">Menos de 7 Días</option>
											<option value="2">Menos de 15 Días</option>
											<option value="3">Menos de 30 Días</option>
										@endif
									</select>
								</div>
							
								<div class="col-xs-12 col-sm-6 col-md-3" id="divControlEspec" hidden>
									<label for="controlEspec" class="control-label">Próximo Control en</label>
									<select id="controlEspec" class="form-control" name="controlEspec">
										<option value="">Seleccione</option>
										@if(old('controlEspec') == 1)
											<option value="1" selected>1 mes</option>
										@else
											<option value="1">1 mes</option>
										@endif
										@for($i = 2; $i<= 12; $i++)
											@if(old('controlEspec') == $i)
												<option value="{{ $i }}" selected>{{ $i }} meses</option>
											@else
												<option value="{{ $i }}">{{ $i }} meses</option>
											@endif
										@endfor
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3" id="divReqExamenes" hidden>
									<label for="reqExamenes" class="control-label">Control con exámenes (gestión hospital)</label>
									<select id="reqExamenes" class="form-control" name="reqExamenes">
										@if( old('reqExamenes') === "1" )
											<option value="">Seleccione</option>
											<option value="1" selected>Si</option>
											<option value="0">No</option>
										@elseif( old('reqExamenes') === "0" )
											<option value="">Seleccione</option>
											<option value="1">Si</option>
											<option value="0" selected>No</option>
										@else
											<option value="" selected>Seleccione</option>
											<option value="1">Si</option>
											<option value="0">No</option>
										@endif
									</select>
								</div>
							</div>
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-12 col-md-12" id="divExamenes" hidden>
									<label for="examenes" class="control-label">Examenes</label>
									<textarea class="form-control" rows="3"  id="examenes" name="examenes" maxlength="150" autofocus>{{ old('examenes') }}</textarea>
								</div>
							</div>
						</div>
						</br>

						<!-- Fin Tipo Salida -->



						<div class="row row-border">
								<a class="btn btn-default" href=" {{ URL::to('../doc/Arsenal2020.pdf')  }} " target="_blank"  >ARSENAL FARMACOLÓGICO PARA ESTABLECIMIENTOS DE ATENCIÓN PRIMARIA</a>
								</br>					
						</br>
						</div>
						</br>



						<!--Información Clinica Hospital-->
						<div class="row row-border">
							<div class="col-xs-10 col-sm-11 col-md-11">
								<h5>Información Clínica Hospital </h5>
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
									<input id="contrarrefiere" type="text" class="form-control" name="contrarrefiere" value="{{ $establecimiento->name }}" readonly>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="origen" class="control-label">Establecimiento Origen APS</label>
									<select id="origen" class="form-control" name="origen" required>
										<option value="">Seleccione</option>
										@foreach($origenAps as $origen)
											@if($origen->origen_id == old('origen') && old('origen') != -1)
												<option value="{{ $origen->origen_id }}" selected>{{ $origen->name }}</option>
											@else
												<option value="{{ $origen->origen_id }}">{{ $origen->name }}</option>
											@endif
										@endforeach

										@if(old('origen') == -1)
											<option value="-1" selected>Otro Establecimiento</option>
										@else
											<option value="-1">Otro Establecimiento</option>
										@endif
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3" id="divOtroEstablecimiento" hidden>
									<label for="otroEstablecimiento" class="control-label">Otros Establecimientos</label>
									<select id="otroEstablecimiento" class="form-control" name="otroEstablecimiento">
										<option value="">Seleccione</option>
										@foreach($establecimientos as $establecimiento)
											@if($establecimiento->id == old('otroEstablecimiento'))
												<option value="{{ $establecimiento->id }}" selected>{{ $establecimiento->name }}</option>
											@else
												<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="primeraConsulta" class="control-label">Primera Consulta</label>
									<select id="primeraConsulta" class="form-control" name="primeraConsulta" required>
										@if( old('primeraConsulta') === "1" )
											<option value="">Seleccione</option>
											<option value="1" selected>Si</option>
											<option value="2">No</option>
										@elseif( old('primeraConsulta') === "2" )
											<option value="">Seleccione</option>
											<option value="1">Si</option>
											<option value="2" selected>No</option>
										@else
											<option value="" selected>Seleccione</option>
											<option value="1">Si</option>
											<option value="2">No</option>
										@endif
									</select>
								</div>
							</div>
							<div class="row row-padding">
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="diagEgreso" class="control-label">Diagnóstico de Egreso</label>
									<input id="diagEgreso" type="text" class="form-control" name="diagEgreso" value="{{ old('diagEgreso') }}" required autofocus>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-9">
									<label for="tratamiento" class="control-label">Tratamiento Realizado</label>
									<textarea class="form-control" rows="3"  id="tratamiento" name="tratamiento" maxlength="3000" required autofocus>{{ old('tratamiento') }}</textarea>
								</div>
							</div>
							<!--Elementos ocultos-->
							<input name="idCie10" id="idCie10" type="hidden" value="{{ old('idCie10') }}">
							<div class="row row-padding">

								<!-- Bloquear En control para Alta sin Control APS -->
								
								<div class="col-xs-12 col-sm-6 col-md-3" id="IndicacionesAps">
									<label for="reqIndicaciones" class="control-label">Indicaciones para APS</label>
									<select id="reqIndicaciones" class="form-control" name="reqIndicaciones" required>
										@if( old('reqIndicaciones') === "1" )
											<option value="">Seleccione</option>
											<option value="1" selected>Si</option>
											<option value="0">No</option>
										@elseif( old('reqIndicaciones') === "0" )
											<option value="">Seleccione</option>
											<option value="1">Si</option>
											<option value="0" selected>No</option>
										@else
											<option value="" selected>Seleccione</option>
											<option value="1">Si</option>
											<option value="0">No</option>
										@endif
									</select>
								</div>
								

								<div class="col-xs-12 col-sm-6 col-md-9" id="divIndicaciones" hidden>
									<label for="indicaciones" class="control-label">Ingrese Indicaciones</label>
									<textarea class="form-control" rows="3"  id="indicaciones" name="indicaciones" maxlength="3000" autofocus>{{ old('indicaciones') }}</textarea>
								</div>
							</div>
							<div class="row row-padding row-border" id="divReqEvaluacion" hidden>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="reqEvaluacion" class="control-label">Requiere evaluación en APS para otra especialidad</label>
									<select id="reqEvaluacion" class="form-control" name="reqEvaluacion">
										@if( old('reqEvaluacion') === "1" )
											<option value="">Seleccione</option>
											<option value="1" selected>Si</option>
											<option value="0">No</option>
										@elseif( old('reqEvaluacion') === "0" )
											<option value="">Seleccione</option>
											<option value="1">Si</option>
											<option value="0" selected>No</option>
										@else
											<option value="" selected>Seleccione</option>
											<option value="1">Si</option>
											<option value="0">No</option>
										@endif
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-9" id="divObservaciones" hidden>
									<label for="observaciones" class="control-label">Indique Observación</label>
									<textarea class="form-control" rows="3"  id="observaciones" name="observaciones" maxlength="150" autofocus>{{ old('observaciones') }}</textarea>
								</div>
							</div>
							</br>
							<div class="row row-padding row-border">
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="esGes" class="control-label">GES</label>
									<select id="esGes" class="form-control" name="esGes" required>
										@if( old('esGes') === "1" )
											<option value="">Seleccione</option>
											<option value="1" selected>Si</option>
											<option value="0">No</option>
										@elseif( old('esGes') === "0" )
											<option value="">Seleccione</option>
											<option value="1">Si</option>
											<option value="0" selected>No</option>
										@else
											<option value="" selected>Seleccione</option>
											<option value="1">Si</option>
											<option value="0">No</option>
										@endif
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3" id="divTipoGes" hidden>
									<label for="tipoGes" class="control-label">Manejo en</label>
									<select id="tipoGes" class="form-control" name="tipoGes">
										<option value="">Seleccione</option>
										@foreach($tipoGES as $tipo)
											@if($tipo->id == old('tipoGes'))
												<option value="{{ $tipo->id }}" selected>{{ $tipo->name }}</option>
											@else
												<option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="protocolo" class="control-label">Pertinencia por Protocolo</label>
									<select id="protocolo" class="form-control" name="protocolo" required>
										<option value="">Seleccione</option>
										@foreach($protocolos as $protocolo)
											@if($protocolo->id == old('protocolo'))
												<option value="{{ $protocolo->id }}" selected>{{ $protocolo->name }}</option>
											@else
												<option value="{{ $protocolo->id }}">{{ $protocolo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<label for="tiempo" class="control-label">Pertinencia por Tiempo</label>
									<select id="tiempo" class="form-control" name="tiempo" required>
										<option value="">Seleccione</option>
										@foreach($protocolos as $protocolo)
											@if($protocolo->id == old('tiempo'))
												<option value="{{ $protocolo->id }}" selected>{{ $protocolo->name }}</option>
											@else
												<option value="{{ $protocolo->id }}">{{ $protocolo->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<!--Información Salida-->
					</br>

						<div class="row row-border">
							<div class="col-xs-12 col-sm-6 col-md-4">
								<input id="firma" type="text" class="form-control" name="firma" value="{{ $user }} - {{ $especialidad->name }}" disabled>
								<p class="text-center">Firma Electrónica Simple</p>
							</div>
						</div>
						<div class="row row-border"></div>
						<br>
						<div class="row">
							<div class="col-xs-1 col-sm-1 col-md-1">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                            </div>
                        </div>
					</form>
				</div>
            </div>
			<!--FIN Panel Formulario Documento-->
        </div>
    </div>
</div>

<!-- OCULTA o MUESTRA ESTABLECIMIENTO ORIGEN -->
<script>
document.getElementById('origen').addEventListener('change', function() {
    if (this.value == -1) {
        document.getElementById('divOtroEstablecimiento').hidden = false;
		document.getElementById("otroEstablecimiento").required = true;
    }
	else {
		document.getElementById('divOtroEstablecimiento').hidden = true;
		document.getElementById("otroEstablecimiento").required = false;
    }
});
</script>

<!-- OCULTA o MUESTRA INDICACIONES -->
<script>
document.getElementById('reqIndicaciones').addEventListener('change', function() {
    if (this.value == 1) {
        document.getElementById('divIndicaciones').hidden = false;
		document.getElementById('divReqEvaluacion').hidden = false;
		document.getElementById("indicaciones").required = true;
		document.getElementById("reqEvaluacion").required = true;
    }
	else {
        document.getElementById('divIndicaciones').hidden = true;
		document.getElementById('divReqEvaluacion').hidden = true;
		document.getElementById("indicaciones").required = false;
		document.getElementById("reqEvaluacion").required = false;
    }
});
</script>

<!-- OCULTA o MUESTRA OBSERVACIONES -->
<script>
document.getElementById('reqEvaluacion').addEventListener('change', function() {
    if (this.value == 1) {
        document.getElementById('divObservaciones').hidden = false;
		document.getElementById("observaciones").required = true;
    }
	else {
        document.getElementById('divObservaciones').hidden = true;
		document.getElementById("observaciones").required = false;
    }
});
</script>

<!-- OCULTA o MUESTRA TIPO GES -->
<script>
document.getElementById('esGes').addEventListener('change', function() {
    if (this.value == 1) {
        document.getElementById('divTipoGes').hidden = false;
		document.getElementById('tipoGes').required = true;
    }
	else {
        document.getElementById('divTipoGes').hidden = true;
		document.getElementById('tipoGes').required = false;
    }
});
</script>

<!-- OCULTA o MUESTRA TIPO ALTA -->
<script>
document.getElementById('tipoAlta').addEventListener('change', function() {
	if (this.value == 2) {
		document.getElementById('divControlAps').hidden = false;
		document.getElementById("controlAps").required = true;

		document.getElementById('divControlEspec').hidden = true;
		document.getElementById("controlEspec").required = false;

		document.getElementById('divReqExamenes').hidden = true;
		document.getElementById("reqExamenes").required = false;

		document.getElementById('divExamenes').hidden = true;
		document.getElementById("examenes").required = false;

		document.getElementById("reqExamenes").value = "";

		document.getElementById('IndicacionesAps').hidden = false;

	}
	else if (this.value == 1) {
		document.getElementById('divControlEspec').hidden = false;
		document.getElementById("controlEspec").required = true;

		document.getElementById('divReqExamenes').hidden = false;
		document.getElementById("reqExamenes").required = true;

		document.getElementById('divControlAps').hidden = true;
		document.getElementById("controlAps").required = false;

		document.getElementById('IndicacionesAps').hidden = false;

	}
	else {
		document.getElementById('divControlAps').hidden = true;
		document.getElementById("controlAps").required = false;

		document.getElementById('divControlEspec').hidden = true;
		document.getElementById("controlEspec").required = false;

		document.getElementById('divReqExamenes').hidden = true;
		document.getElementById("reqExamenes").required = false;

		document.getElementById('divExamenes').hidden = true;
		document.getElementById("examenes").required = false;

		document.getElementById("reqExamenes").value = "";

		document.getElementById('IndicacionesAps').hidden = true;

	}
});

document.getElementById('reqExamenes').addEventListener('change', function() {
	if (this.value == 1) {
		document.getElementById('divExamenes').hidden = false;
		document.getElementById("examenes").required = true;
	}
	else {
		document.getElementById('divExamenes').hidden = true;
		document.getElementById("examenes").required = false;
	}
});
</script>


















<!-- DETERMINA SI MUESTRA O NO LOS DIV OCULTOS SEGUN VALORES "OLD" -->
<script>
window.addEventListener("load",function(){
    //SI ESTABLECIMIENTO ORIGEN ES OTROS ESTABLECIMIENTOS
	if ( document.getElementById('origen').value == -1) {
		document.getElementById('divOtroEstablecimiento').hidden = false;
		document.getElementById("otroEstablecimiento").required = true;
	}
	//SI HAY INDICACIONES PARA APS
	if ( document.getElementById('reqIndicaciones').value == 1) {
        document.getElementById('divIndicaciones').hidden = false;
		document.getElementById('divReqEvaluacion').hidden = false;
		document.getElementById("indicaciones").required = true;
		document.getElementById("reqEvaluacion").required = true;
    }
	//SI REQUIERE EVALUACION
	if ( document.getElementById('reqEvaluacion').value == 1) {
        document.getElementById('divObservaciones').hidden = false;
		document.getElementById("observaciones").required = true;
    }
	//SI ES GES
	if ( document.getElementById('esGes').value == 1) {
        document.getElementById('divTipoGes').hidden = false;
		document.getElementById("tipoGes").required = true;
    }
	//SI ES TIPO SALIDA CON CONTROL APS
	if ( document.getElementById('tipoAlta').value == 2) {
		document.getElementById('divControlAps').hidden = false;
		document.getElementById("controlAps").required = true;
	}
	//SI ES TIPO SALIDA CON CONTROL EN ESPECIALIDAD
	if ( document.getElementById('tipoAlta').value == 1) {
		document.getElementById('divControlEspec').hidden = false;
		document.getElementById("controlEspec").required = true;

		document.getElementById('divReqExamenes').hidden = false;
		document.getElementById("reqExamenes").required = true;
	}
	//SI REQUIERE EXAMENES
	if ( document.getElementById('reqExamenes').value == 1) {
		document.getElementById('divExamenes').hidden = false;
		document.getElementById("examenes").required = true;
	}
	//MUESTRA PDF PARA CONTRARREFERENCIA ANTERIOR
	@if($idContra != null)
		window.open("{{ URL::to('contrarreferencias/pdf/'.$idContra) }}", "_blank");
	@endif
});
</script>

<!-- AUTOCOMPLETA RUT -->
<script>
$("#paciente").autocomplete({

	source: function(request, response) {
		$.ajax({
			url: "{{ route('getPaciente') }}",
			dataType: "json",
			data: {
				term : request.term
			},

			success: function(data) {
				response(data);
			}
		});
	},
	select: function (event, ui) {
        //datos pacientes
		$("#idPaciente").val(ui.item.id);
		$("#nombre").val(ui.item.nombre);
		$("#apPaterno").val(ui.item.apPaterno);
		$("#apMaterno").val(ui.item.apMaterno);
		$("#direccion").val(ui.item.direccion);
		$("#telefono").val(ui.item.telefono);
		$("#telefono2").val(ui.item.telefono2);
		$("#email").val(ui.item.email);
		$("#fechaNacimiento").val(ui.item.fechaNacimiento);
		$("#edad").val(ui.item.edad);
		//datos SIC
		$("#fechaSIC").val(ui.item.fechaSIC);
		$("#sospechaSIC").val(ui.item.sospechaSIC);
		$("#precisionSIC").val(ui.item.precisionSIC);
		$("#especialidadSIC").val(ui.item.especialidadSIC);
    },
	minLength: 2,
});

</script>
<!-- AUTOCOMPLETA DIAGNOSTICO CIE10 -->
<script>
$("#diagEgreso").autocomplete({
	source: function(request, response) {
		$.ajax({
			url: "{{ route('getDiagnostico') }}",
			dataType: "json",
			data: {
				term : request.term
			},

			success: function(data) {
				response(data);
			}
		});
	},
	select: function (event, ui) {
		$("#idCie10").val(ui.item.id);
	},
	minLength: 2,
});

</script>
<!-- URL CON HISTORIA DE LISTAS DE ESPERA -->
<script>
$('#detalles').click(function(){
	if ( document.getElementById('idPaciente').value !== "" ) {
		window.open("{{ URL::to('contrarreferencias/le') }}"+"/"+document.getElementById('idPaciente').value , "_blank");
	}
	return false;
});
</script>

@endsection
