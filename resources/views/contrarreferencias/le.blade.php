@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Histórico Lista de Espera</div>
                <div class="panel-body">
                    {{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<thead>
								  <tr>
									<th>Fecha de Entrada</th>
									<th>Especialidad</th>
									<th>Sospecha Diagnóstica</th>
									<th>Precisión Diagnóstica</th>
									<th>Código</th>
									<th>Estado</th>
								  </tr>
								</thead>
								<tbody>
									@foreach($listaEsperas as $listaEspera)
									<tr>
										<td>{{ $listaEspera->fechaentrada }}</td>
										<td>{{ $listaEspera->especialidad }}</td>
										<td>{{ $listaEspera->cie10 }}</td>
										<td>{{ $listaEspera->precdiag }}</td>
										<td>{{ $listaEspera->rem }}</td>
										<td>
											@if( $listaEspera->estado == 1 )
												Abierto
											@else
												Cerrado
											@endif
										</td>
									</tr>
								  @endforeach
								</tbody>
							</table>
							<!--paginacion-->
							{{ $listaEsperas->links() }}
						</div>
					</div>
					<!-- FIN Lista de Cie10 -->			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection