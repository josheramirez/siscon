@extends('layouts.app4')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
			<!--Panel Formulario Crear Documento-->
			<div class="panel panel-default">
                <div class="panel-heading">Agendar Contrarreferencia</div>
                <div class="panel-body">
					<!-- Datos del Documento -->
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>RUN / Nro de Documento</th>
									<th>Fecha Solicitud</th>
									<th>Ges</th>
									<th>Pertinencia</th>
									<th>Tipo Salida</th>
									<th>Control En</th>
								  </tr>
								</thead>
								<tbody>
								  <tr>
									<td>
										@if ( $paciente->tipoDoc == 1 )
											{{ $paciente->rut }} - {{ $paciente->dv }}
										@else
											{{ $paciente->numDoc }}
										@endif
									</td>
									<td>
										{{ $contrarreferencia->fecha }}
									</td>
									<td>
										@if ( $contrarreferencia->ges_id == null )
											No
										@else
											Si
										@endif
									</td>
									<td>
										{{ $protocolo->name }}
									</td>
									<td>
										@if ( $contrarreferencia->tipo_salida == 0 )
											Alta
                    @elseif ( $contrarreferencia->tipo_salida == 1 )
  											Control en la Especialidad
										@elseif ( $contrarreferencia->tipo_salida == 2 )
											Alta con Control APS
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
								  </tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<form role="form" method="POST" action="{{ URL::to('contrarreferencias/actualizar') }}">
								{{ csrf_field() }}
								<div class="row">
									<!--Campo Causal de Egreso-->
									<div class="form-group col-md-6">
										<label for="egreso" class="control-label">Agendar / Causal de Egreso</label>
										<select id="egreso" class="form-control" name="egreso" required autofocus>
											<option value="">Seleccione</option>
											<option value="1">Agendar</option>
											<option value="2">No Ubicable</option>
											<option value="3">Rechaza Atención</option>
											<option value="4">Domicilio No Disponible</option>
										</select>
									</div>
									<!--Campo Fecha-->
									<div class="form-group col-md-3" id="divFecha" hidden>
										<label for="fecha" class="control-label">Fecha Agendamiento</label>
										<input type="text" class="form-control" name="fecha" id="fecha" value="" maxlength="10" placeholder="dd-mm-yyyy">
									</div>
								</div>
								<!--datos ocultos-->
								<input type="hidden" name="id" id="id" value="{{$contrarreferencia->id}}">
								<br>
								<div class="row">
									<div class="form-group col-md-6">
										<button type="submit" class="btn btn-primary">
											Agendar / Egresar
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
            </div>
			<!--FIN Panel Formulario Documento-->
        </div>
    </div>
</div>
<!-- OCULTA o MUESTRA FECHA -->
<script>
document.getElementById('egreso').addEventListener('change', function() {
	if (this.value == 1) {
		document.getElementById('divFecha').hidden = false;
		document.getElementById("fecha").required = true;
	}
	else {
		document.getElementById('divFecha').hidden = true;
		document.getElementById("fecha").required = false;
		document.getElementById("fecha").value = null;
	}
});
</script>
<!--SCRIPT DE CALENDARIO-->
<script>
$('#fecha').datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        minDate: 0,
		dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames:
            ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort:
            ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		//focus
		onSelect: function ()
		{
			this.focus();
		}
});

//funcion que pone mascara de fecha
document.getElementById('fecha').addEventListener('keyup', function() {
	var v = this.value;
	if (v.match(/^\d{2}$/) !== null) {
		this.value = v + '-';
	} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
		this.value = v + '-';
	}
});
</script>
@endsection
