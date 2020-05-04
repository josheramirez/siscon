<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Estilos -->
		<style>
		.container {
			position: relative;
		}
		.head {
			position: relative;
			height: 100px;
			width: 100%;
		}
		.image {
			position: absolute;
			left: 0px;
			width: 100px;
		}
		.image>img {
			height: 100px;
			width: auto;
		}
		.title {
			margin-left: 100px;
			font-family: Arial, Helvetica, sans-serif;
			text-align: center;
			padding-top: 30px;
		}
		.body {
			position: relative;
			margin-top: 5px;
			width: 100%;
		}
		.titulo {
			position: relative;
			background-color: #f3f3f3;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: bold;
			font-size: 12px;
			padding: 5px;
		}
		.detalle {
			position: relative;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			padding: 5px;
		}
		.left {
			position: absolute;
			margin-left: 0px;
		}
		.right {
			position: relative;
			margin-left: 350px;
		}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="head">
				<div class="image">
					<img src="http://10.8.64.41/sic/image/SSMOC.jpg">
				</div>
				<div class="title">
					<h4>DOCUMENTO DE CONTRARREFERENCIA AMBULATORIA</h4>
				</div>
			</div>
			</br>
			<div class="body">
				<div class="titulo">
					ESTABLECIMIENTO QUE CONTRARREFIERE
				</div>
				<div class="detalle">
					<b>Servicio de Salud:</b> {{ $servicio->name }}
				</div>
				<div class="detalle">
					<div class="left">
						<b>Establecimiento:</b> {{ $estContra->name }}
					</div>
					<div class="right">
						<b>Especialidad:</b> {{ $especialidad->name }}
					</div>
				</div>
				<div class="titulo">
					PACIENTE
				</div>
				<div class="detalle">
					<div class="left">
						@if( $paciente->tipoDoc == 1 )
							<b>R.U.N.:</b> {{ $paciente->rut }}-{{ $paciente->dv }}
						@else
							<b>Número de Documento:</b> {{ $paciente->numDoc }}
						@endif
					</div>
					<div class="right">
						<b>Edad:</b> {{ $edad }}
					</div>
				</div>
				<div class="detalle">
					<b>Nombre:</b> {{ $paciente->nombre }} {{ $paciente->apPaterno }} {{ $paciente->apMaterno }}
				</div>
				<div class="titulo">
					SE DERIVA PARA SER ATENDIDO EN
				</div>
				<div class="detalle">
					<b>Servicio de Salud:</b> {{ $servicio2->name }}
				</div>
				<div class="detalle">
					<b>Establecimiento:</b> {{ $estOrigen->name }}
				</div>
				<div class="detalle">
					<b>Diagnostico:</b> {{ $cie10->name }}
				</div>
				<div class="detalle">
					<b>Tratamiento:</b> {{ $contrarreferencia->tratamiento }}
				</div>
				<div class="detalle">
					<b>Indicaciones APS:</b> {{ $contrarreferencia->indicaciones_aps }}
				</div>
				<div class="detalle">
					<b>Indicaciones evaluación en APS para otra especialidad:</b>{{ $contrarreferencia->observaciones }}
				</div>
				<div class="detalle">
					<div class="left">
						<b>Pertinencia por Protocolo:</b> {{ $protocolo->name }}
					</div>
					<div class="right">
						<b>Pertinencia por Tiempo:</b> {{ $tiempo->name }}
					</div>
				</div>
				<div class="detalle">
					<div class="left">
						<b>Primera Consulta:</b>
						@if( $contrarreferencia->primera_consulta == 1 )
							Si
						@elseif( $contrarreferencia->primera_consulta == 2 )
							No
						@endif
					</div>
					<div class="right">
						<b>GES:</b>
						@if( $contrarreferencia->ges_id != null )
							Si
						@else
							No
						@endif
					</div>
				</div>
				<div class="detalle">
					<div class="left">
						<b>Tipo Alta:</b>
						@if( $contrarreferencia->tipo_salida == 0 )
							Alta
						@endif
						@if( $contrarreferencia->tipo_salida == 2 )
							Alta con Control APS
						@endif
						@if( $contrarreferencia->tipo_salida == 1)
							Control en la Especialidad
						@endif
					</div>
					<div class="right">
						<b>Próximo Control:</b> {{ $control }}
					</div>
				</div>
				<div class="titulo">
					PROFESIONAL QUE CONTRARREFIERE
				</div>
				@if( $nombre_medico != null)		
					<div class="detalle">
						<div class="left">
							<b>R.U.N.:</b> {{ $rut_medico.'-'.$dv_medico }}
						</div>
						<div class="right">
							<b>Especialidad:</b> {{ $especialidad->name }}
						</div>
					</div>
					<div class="detalle">
						<div class="left">
							<b>Nombre:</b>{{ $nombre_medico }}
						</div>
						<div class="right">
							<b>Fecha:</b>{{ $fecha }}
						</div>
					</div>
				@else
					<div class="detalle">
						<div class="left">
							<b>R.U.N.:</b> {{ $user->email }}
						</div>
						<div class="right">
							<b>Especialidad:</b> {{ $especialidad->name }}
						</div>
					</div>
					<div class="detalle">
						<div class="left">
							<b>Nombre:</b>{{ $contrarreferencia->nombre_usuario }}
						</div>
						<div class="right">
							<b>Fecha:</b>{{ $fecha }}
						</div>
					</div>
				@endif	
			</div>
		</div>
	</body>
</html>
