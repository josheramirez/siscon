@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Reporte Contrarreferencias</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
							<form class="form-horizontal" role="form" method="GET" action="{{ URL::to('contrarreferencias/excel') }}">
								<input type="hidden" name="idPaciente" id="idPaciente" value="{{$idPaciente}}">
								<input type="hidden" name="establecimiento" id="establecimiento" value="{{$establecimiento}}">
								<input type="hidden" name="origen" id="origen" value="{{$origen}}">
								<input type="hidden" name="tipoAlta" id="tipoAlta" value="{{$tipoAlta}}">
								<input type="hidden" name="estado" id="estado" value="{{$estado}}">
								<input type="hidden" name="desde" id="desde" value="{{$desde}}">
								<input type="hidden" name="hasta" id="hasta" value="{{$hasta}}">
								<button class="btn btn-sm btn-primary" type="submit">Exportar a Excel</button>
								<span class="label label-default">(Hasta 1000 registros)</span>
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
									<th>Establecimiento Contrarreferencia</th>
									<th>Establecimiento Origen</th>
									<th>Médico</th>
									<th>Especialidad</th>
									<th>Fecha Ingreso</th>
									<th>Ges</th>
									<th>Pertinencia</th>
									<th>Tipo Salida</th>
									<th>Estado</th>
									<th>Revisar</th>
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
											<td>{{ $contrarreferencia->contrarref }}</td>
											<td>{{ $contrarreferencia->origen }}</td>
											@if( $contrarreferencia->nombre_medico != null)
												<td>{{ $contrarreferencia->nombre_medico }}</td>
											@else
												<td>{{ $contrarreferencia->nombre_usuario }}</td>
											@endif	
											<td>{{ $contrarreferencia->especialidad }}</td>
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
											@if ( $contrarreferencia->estado == 'IC' )
												Ingresada
											@elseif ( $contrarreferencia->estado == 'RC' )
												Revisada por SOME
											@elseif ( $contrarreferencia->estado == 'AC' )
												Asignada a Médico APS
											@elseif ( $contrarreferencia->estado == 'RA' )
												Revisada por Médico APS
											@elseif ( $contrarreferencia->estado == 'CC' )
												Cerrada
											@endif
											</td>
											<td><a href="{{ URL::to('contrarreferencias/' . $contrarreferencia->id . '/detalle') }}">Detalle</a></td>
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
