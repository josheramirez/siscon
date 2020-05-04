@extends('layouts.app4')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
			<!--Panel Formulario Crear Contrarreferencia-->
			<div class="panel panel-default">
                <div class="panel-heading">Reporte Contrarreferencias</div>
                <div class="panel-body">
					{{ csrf_field() }}
					<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('contrarreferencias/resultado') }}">
						<div class="form-group">
							<label for="fecha" class="col-md-4 control-label">Fecha Contrarreferencia</label>
							<div class="col-md-3">
								<input id="desde" type="text" class="form-control" name="desde" placeholder="dd-mm-aaaa">
                            </div>
							<div class="col-md-3">
								<input id="hasta" type="text" class="form-control" name="hasta" placeholder="dd-mm-aaaa">
                            </div>
						</div>

						<div class="form-group">
                            <label for="paciente" class="col-md-4 control-label">RUN /N° Documento Paciente</label>
                            <div class="col-md-6">
								<input id="paciente" type="text" class="form-control" name="paciente" autofocus>
                            </div>
                        </div>
						<!--Elementos ocultos-->
						<input name="idPaciente" id="idPaciente" type="hidden">

						<div class="form-group">
                            <label for="establecimiento" class="col-md-4 control-label">Establecimiento Contrarrefiere</label>
                            <div class="col-md-6">
                                <select id="establecimiento" class="form-control" name="establecimiento">
									<option value="">Seleccione</option>
									@foreach($establecimientos as $establecimiento)
										<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>

						<div class="form-group">
                            <label for="origen" class="col-md-4 control-label">Establecimiento Origen</label>
                            <div class="col-md-6">
                                <select id="origen" class="form-control" name="origen">
									<option value="">Seleccione</option>
									@foreach($establecimientos as $establecimiento)
										<option value="{{ $establecimiento->id }}">{{ $establecimiento->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>

						<div class="form-group">
                            <label for="tipoAlta" class="col-md-4 control-label">Tipo de Salida</label>
                            <div class="col-md-6">
                                <select id="tipoAlta" class="form-control" name="tipoAlta">
									<option value="">Seleccione</option>
									<option value="0">Alta</option>
									<option value="2">Alta con Control APS</option>
									<option value="1">Control en la Especialidad</option>
								</select>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="estado" class="col-md-4 control-label">Estado Contrarreferencia</label>
                            <div class="col-md-6">
                                <select id="estado" class="form-control" name="estado">
									<option value="">Seleccione</option>
									<option value="IC">Ingresada</option>
									<option value="RC">Revisada por SOME</option>
									<option value="AC">Asignada a Médico APS</option>
									<option value="RA">Revisada por Médico APS</option>
									<option value="CC">Cerrada</option>
								</select>
                            </div>
                        </div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Consultar
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
    },
	minLength: 2,
});
</script>
<!-- FECHA DESDE -->
<script>
$('#desde').datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        maxDate: 0,
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
document.getElementById('desde').addEventListener('keyup', function() {
	var v = this.value;
	if (v.match(/^\d{2}$/) !== null) {
		this.value = v + '-';
	} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
		this.value = v + '-';
	}
});
</script>
<!-- FECHA HASTA -->
<script>
$('#hasta').datepicker({
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        maxDate: 0,
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
document.getElementById('hasta').addEventListener('keyup', function() {
	var v = this.value;
	if (v.match(/^\d{2}$/) !== null) {
		this.value = v + '-';
	} else if (v.match(/^\d{2}\-\d{2}$/) !== null) {
		this.value = v + '-';
	}
});
</script>
@endsection
