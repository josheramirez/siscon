<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\Contrarreferencia;
use siscont\ListaEspera;
use siscont\Paciente;
use siscont\Establecimiento;
use siscont\Especialidad;
use siscont\TipoGes;
use siscont\Protocolo;
use siscont\Cie10;
use siscont\MapaDerivacion;
use siscont\MovimientoContra;
use siscont\Servicio;
use siscont\User;
use siscont\LogLep;

use DB;
use Illuminate\Support\Facades\Auth;

use DateTime;
use Excel;
use PDF;

/**
 * Clase Controlador Contrarreferencia
 * Rol: Por Funcion
 */
class ContrarreferenciasController extends Controller
{
    /*******************************************************************************************/
	/*                                 MEDICO ESPECIALIDAD                                     */
	/*******************************************************************************************/
	/**
     * Función para la Creacion de Contrarreferencia.
	 * Vista: contrarreferencias.create
	 * Rol: hospital
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			//determina establecimiento de usuario conectado
			$establecimiento  = Establecimiento::find(session('establecimiento'));

			$especialidad     = Especialidad::find(session('especialidad'));
			$user             = Auth::user()->name;
			$tipoGES          = TipoGes::where('active',1)->orderBy('name')->get();
			$protocolos       = Protocolo::where('active',1)->orderBy('name')->get();
			$establecimientos = Establecimiento::where('active',1)->orderBy('name')->get();

			//determina establecimiento de origen
			$origenAps        =  DB::table('mapa_derivacions')
								->join('establecimientos', 'mapa_derivacions.origen_id','=','establecimientos.id')
								->where([['mapa_derivacions.contraref_id',session('establecimiento')],
										 ['mapa_derivacions.especialidad_id',session('especialidad')],
										 ['mapa_derivacions.active',1]])
								->select('mapa_derivacions.origen_id as origen_id',
									     'establecimientos.name as name')
								->orderBy('name')
								->get();

			return view('contrarreferencias.create',compact('establecimiento','establecimientos','especialidad','user','tipoGES','protocolos','origenAps'));
		}
		else {
			return view('auth/login');
		}
    }

	/**
	 * Función que Muestra el Historial de Lista de Espera del Paciente.
	 * Vista: contrarreferencias.le
	 * Rol: hospital
	 *
	 * @param int $id ID del Paciente
	 * @return \Illuminate\Http\Response
	 */
	public function listaEspera($id) 
	{
		if (Auth::check()) {
			$listaEsperas = DB::table('lista_esperas')
							->join('especialidads', 'lista_esperas.especialidads_ingreso_id','=','especialidads.id')
							->join('cie10s', 'lista_esperas.cie10s_id','=','cie10s.id')
							->where('pacientes_id',$id)
							->select('lista_esperas.fechaingreso as fechaentrada',
									 'lista_esperas.active as estado',
									 'especialidads.name as especialidad',
									 'lista_esperas.precdiag as precdiag',
									 'especialidads.rem as rem',
									 'cie10s.name as cie10')
							->orderBy('fechaentrada', 'DESC')->paginate(10);

			return view('contrarreferencias.le',compact('listaEsperas'));
		}
		else {
			return view('auth/login');
		}
	}

    /**
     * Función que Almacena Registro de Contrarreferencia
	 * Rol: hospital 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
			//valida si paciente existe
			$validator = validator::make($request->all(), [
				'idPaciente' => 'required',
			]);
			if ($validator->fails()) {
				return redirect('contrarreferencias/create')
							->with('message','paciente')
							->withInput();
			}

			//valida si diagnostico cie10 existe
			$validator2 = validator::make($request->all(), [
				'idCie10' => 'required',
			]);
			if ($validator2->fails()) {
				return redirect('contrarreferencias/create')
							->with('message','cie10')
							->withInput();
			}

			$contrarreferencia = new Contrarreferencia();

			$contrarreferencia->paciente_id      	 = $request->input('idPaciente');
			$contrarreferencia->estContra_id     	 = session('establecimiento');
			if( $request->input('origen') == -1 ) {
				$contrarreferencia->estOrigen_id     = $request->input('otroEstablecimiento');
			}
			else {
				$contrarreferencia->estOrigen_id     = $request->input('origen');
			}
			$contrarreferencia->primera_consulta  	 = $request->input('primeraConsulta');
			$contrarreferencia->diagEgreso_id    	 = $request->input('idCie10');
			$contrarreferencia->tratamiento      	 = $request->input('tratamiento');
			$contrarreferencia->indicaciones_aps 	 = $request->input('indicaciones');
			$contrarreferencia->observaciones    	 = $request->input('observaciones');
			$contrarreferencia->ges_id           	 = $request->input('tipoGes');
			$contrarreferencia->protocolo_id     	 = $request->input('protocolo');
			$contrarreferencia->tiempo_id            = $request->input('tiempo');
			$contrarreferencia->tipo_salida          = $request->input('tipoAlta');
			$contrarreferencia->control_aps          = $request->input('controlAps');
			$contrarreferencia->control_especialidad = $request->input('controlEspec');
			$contrarreferencia->detalle_examenes     = $request->input('examenes');
			$contrarreferencia->especialidad_id      = session('especialidad');
			$contrarreferencia->user_id              = Auth::user()->id;
			$contrarreferencia->nombre_usuario       = Auth::user()->name;

			$contrarreferencia->save();

			//guarda movimiento
			if ( $contrarreferencia->id != null ) {
				$movimiento = new MovimientoContra();

				$movimiento->contrarref_id = $contrarreferencia->id;
				$movimiento->estado = 'IC';
				$movimiento->user_id = Auth::user()->id;
				$movimiento->active = 1;

				$movimiento->save();

				//Guarda Log de transacción
				$Log = new logLep;
	        	$Log->name = 'Contrarreferencia';
	        	$Log->tabla_id = $contrarreferencia->id;
	        	$Log->estado = 'IC - Ingresada';
	        	$Log->establecimientos_id = session('establecimiento');
	        	$Log->user_id = Auth::user()->id;
	        	$Log->save();
				//Fin Log de transacción
			}

			return redirect('/contrarreferencias/create')->with('idContra',$contrarreferencia->id)->with('message','contrarreferencia');
		}
		else {
			return view('auth/login');
		}
    }

	/**
     * Función que Muestra el Documento en formato PDF de Contrarreferencia Creada.
	 * Vista: contrarreferencias.pdf
	 * Rol: None
     *
     * @param int $id Id de Contrarreferencia
	 * @return \Illuminate\Http\Response
     */
	public function pdf($id) 
	{
		if (Auth::check()) {
			$contrarreferencia = Contrarreferencia::find($id);

			//determina datos del paciente
			$paciente = Paciente::find($contrarreferencia->paciente_id);
			//formato fecha de nacimiento
			$fechaNacimiento = new DateTime($paciente->fechaNacimiento);
			$fechaNacimiento = $fechaNacimiento->format('d-m-Y');
			//calcula edad
			$date  = date('Y-m-d');//la fecha del computador
			$diff  = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
			$edad = floor($diff / (365*60*60*24));

			//establecimiento contraref_id
			$estContra = Establecimiento::find($contrarreferencia->estContra_id);

			//servicio de salud contraref_id
			$servicio = Servicio::find($estContra->servicio_id);

			//especialidad
			$especialidad = Especialidad::find($contrarreferencia->especialidad_id);

			//establecimiento contraref_id
			$estOrigen = Establecimiento::find($contrarreferencia->estOrigen_id);

			//servicio de salud contraref_id
			$servicio2 = Servicio::find($estOrigen->servicio_id);

			//diagnostico
			$cie10 = Cie10::find($contrarreferencia->diagEgreso_id);

			//protocolo
			$protocolo = Protocolo::find($contrarreferencia->protocolo_id);

			//tiempo
			$tiempo = Protocolo::find($contrarreferencia->tiempo_id);

			//control

			if ( $contrarreferencia->tipo_salida == 0 ) {  // Tipo Salida 0 = Alta
				$control = "";
			}
			elseif ( $contrarreferencia->tipo_salida == 2 ) { // Tipo Salida 2 = Alta con control APS
				if( $contrarreferencia->control_aps == 1 ){
					$control = "Menos de 7 Días";
				}
				elseif( $contrarreferencia->control_aps == 2 ) {
					$control = "Menos de 15 Días";
				}
				elseif( $contrarreferencia->control_aps == 3 ){
					$control = "Menos de 30 Días";
				}
			}
			elseif ( $contrarreferencia->tipo_salida == 1 ) { // Tipo Salida 2 = Control en la especialidad
				if( $contrarreferencia->control_especialidad == 1 ) {
					$control = $contrarreferencia->control_especialidad." Mes";
				}
				else {
					$control = $contrarreferencia->control_especialidad." Meses";
				}
			}

			//usuario
			$user = User::find($contrarreferencia->user_id);

			//fecha
			$fecha = new DateTime($contrarreferencia->created_at);
			$fecha = $fecha->format('d-m-Y');

			//Medico
			$nombre_medico =  $contrarreferencia->nombre_medico;
			$rut_medico	=	$contrarreferencia->rut_medico;
			$dv_medico	=	$contrarreferencia->dv_medico;

			//genera PDF
			$pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
			       ->loadView('contrarreferencias.pdf',
					           compact('contrarreferencia','paciente','fechaNacimiento','edad','estContra','servicio','especialidad','estOrigen','servicio2','cie10','protocolo','tiempo','control','user','fecha','nombre_medico','rut_medico','dv_medico'));
			return $pdf->stream();//$pdf->download('archivo.pdf');
		}
		else {
			return view('auth/login');
		}
	}
	/*******************************************************************************************/
	/*                                         SOME                                            */
	/*******************************************************************************************/
	/**
     * Función que Muestra la lista de Contrarreferencia asignadas al establecimiento, en el perfil SOME.
	 * Vista: contrarreferencias.some
	 * Rol: some
     *
     * @return \Illuminate\Http\Response
     */
	public function some(Request $request) 
	{
		if (Auth::check()) {
			$contrarreferencias = DB::table('contrarreferencias')
								->join('pacientes', 'pacientes.id','=','contrarreferencias.paciente_id')
								->join('establecimientos as origen', 'origen.id','=','contrarreferencias.estOrigen_id')
								->join('establecimientos as contrarref', 'contrarref.id','=','contrarreferencias.estContra_id')
								->join('protocolos', 'protocolos.id','=','contrarreferencias.protocolo_id')
								->join('movimiento_contras', 'movimiento_contras.contrarref_id','=','contrarreferencias.id')
								->select('pacientes.tipoDoc as tipoDoc',
								         'pacientes.rut as rut',
										 'pacientes.dv as dv',
										 'pacientes.numDoc',
										 'origen.name as origen',
										 'contrarref.name as contrarref',
										 'contrarreferencias.id as id',
										 'contrarreferencias.nombre_usuario as nombre_usuario',
										 'contrarreferencias.ges_id as ges_id',
										 'contrarreferencias.tipo_salida as tipo_salida',
										 'contrarreferencias.control_aps as control_aps',
										 'contrarreferencias.control_especialidad as control_especialidad',
										 'protocolos.name as name',
										 'contrarreferencias.created_at')
								->selectRaw('DATE_FORMAT(contrarreferencias.created_at, "%d-%m-%Y") as fecha')
								->where([['estOrigen_id',session('establecimiento')],
										 ['movimiento_contras.active','1'],
										])
								->whereIn('movimiento_contras.estado',['IC','RC'])
								->orderBy('contrarreferencias.created_at', 'ASC');

			if( $request->get('searchRut') != null ) {
				$explode = explode("-",$request->get('searchRut'));
        		$rut = $explode[0];
				$contrarreferencias = $contrarreferencias->where('pacientes.rut','LIKE','%'.$rut.'%');
			}

			if( $request->get('searchNumDoc') != null ) {
				$contrarreferencias = $contrarreferencias->where('pacientes.numDoc','LIKE','%'.$request->get('searchNumDoc').'%');
			}

			if( $request->get('searchSalida') != null ) {
				$contrarreferencias = $contrarreferencias->where('contrarreferencias.tipo_salida','LIKE','%'.$request->get('searchSalida').'%');
			}


			$contrarreferencias = $contrarreferencias->paginate(10)
			                                         ->appends('searchRut',$request->get('searchRut'))
													 ->appends('searchNumDoc',$request->get('searchNumDoc'))
													 ->appends('searchSalida',$request->get('searchSalida'));

			return view('contrarreferencias.some',compact('contrarreferencias'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Muestra resumen de Contrarreferencia para perfil SOME
	 * Vista: contrarreferencias.agendaSome
	 * Rol: some
	 *
	 * @param int $id  ID de la Contrarreferencia
     * @return \Illuminate\Http\Response
	 */
	public function agendaSome($id) 
	{
		if (Auth::check()) {
			
			$contrarreferencia = Contrarreferencia::where('id',$id)
								->select('id','protocolo_id','paciente_id','estOrigen_id','ges_id','tipo_salida','control_aps')
								->selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") as fecha')
								->first();

			//determina si contrarreferencia corresponde al establecimiento del usuario
			if ( $contrarreferencia->estOrigen_id == session('establecimiento') ) {

				$protocolo = Protocolo::find($contrarreferencia->protocolo_id);
				$paciente = Paciente::find($contrarreferencia->paciente_id);

				$mov = MovimientoContra::where([['contrarref_id',$id],['active',1]])->first();

				if ( $mov->estado == 'IC' ) { //Si está en estado ingresado
					//cambia active del movimiento a false (0)
					$movid = $mov->id;
					$movimiento = MovimientoContra::find($movid);
					$movimiento->active = 0;

					$movimiento->save();

					//crea un nuevo movimiento en estado RC
					$movimientoNew = new MovimientoContra();
					$movimientoNew->contrarref_id = $id;
					$movimientoNew->estado        = 'RC';
					$movimientoNew->user_id       = Auth::user()->id;
					$movimientoNew->active        = 1;

					$movimientoNew->save();

					//Guarda Log de transacción
					$Log = new logLep;
		        	$Log->name = 'Contrarreferencia';
		        	$Log->tabla_id = $id;
		        	$Log->estado = 'RC - Revisada por SOME';
		        	$Log->establecimientos_id = session('establecimiento');
		        	$Log->user_id = Auth::user()->id;
		        	$Log->save();
					//Fin Log de transacción
				}

				return view('contrarreferencias.agendaSome',compact('contrarreferencia','protocolo','paciente'));
			}
			else {
				return view('home');
			}

		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función qeu Actualiza Contrarreferencia para perfil SOME. Envia a Medico APS
	 * Rol: some
	 *
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
	public function actualizaSome(Request $request) 
	{
		if (Auth::check()) {

			//recupera id de contrarreferencia
			$id = $request->id;

			$contrarreferencia = Contrarreferencia::find( $id );

			//determina si contrarreferencia corresponde al establecimiento del usuario
			if ( $contrarreferencia->estOrigen_id == session('establecimiento') ) {

				//guarda datos de agendamiento o egreso
				$contrarreferencia->causal_egreso = $request->egreso;

				if ( $request->fecha != null ) {
					$fecha = DateTime::createFromFormat('d-m-Y', $request->fecha);
					$contrarreferencia->fecha_agendamiento = $fecha;
				}

				$contrarreferencia->save();

				//guarda hora de revision de documento y cambia de estado
				$mov = MovimientoContra::where([['contrarref_id',$id],['active',1]])->first();

				if ( $mov->estado == 'RC' ) { //Si está en estado ingresado
					//cambia active del movimiento a false (0)
					$movid = $mov->id;
					$movimiento = MovimientoContra::find($movid);
					$movimiento->active = 0;

					$movimiento->save();

					//crea un nuevo movimiento en estado RC
					$movimientoNew = new MovimientoContra();
					$movimientoNew->contrarref_id = $id;
					if ( $contrarreferencia->causal_egreso == 1 ){
						$movimientoNew->estado        = 'AC';
					}
					else {
						$movimientoNew->estado        = 'CC';
					}
					$movimientoNew->user_id       = Auth::user()->id;
					$movimientoNew->active        = 1;

					$movimientoNew->save();

					//Guarda Log de transacción
					$Log = new logLep;
		        	$Log->name = 'Contrarreferencia';
		        	$Log->tabla_id = $id;
		        	$Log->estado = 'AC - Asignada a Médico APS';
		        	$Log->establecimientos_id = session('establecimiento');
		        	$Log->user_id = Auth::user()->id;
		        	$Log->save();
					//Fin Log de transacción
		        }

				return redirect('/contrarreferencias/some')->with('message','actualiza');
			}
			else {
				return view('home');
			}

		}
		else {
			return view('auth/login');
		}
	}

	/*******************************************************************************************/
	/*                                         APS                                             */
	/*******************************************************************************************/
	/**
     * Función que Muestra la lista de Contrarreferencia asignadas al establecimiento, en el perfil APS.
	 * Vista: contrarreferencias.aps
	 * Rol: aps
     *
     * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
     */
	public function aps(Request $request) 
	{
		if (Auth::check()) {
			$contrarreferencias = DB::table('contrarreferencias')
								->join('pacientes', 'pacientes.id','=','contrarreferencias.paciente_id')
								->join('establecimientos as origen', 'origen.id','=','contrarreferencias.estOrigen_id')
								->join('establecimientos as contrarref', 'contrarref.id','=','contrarreferencias.estContra_id')
								->join('protocolos', 'protocolos.id','=','contrarreferencias.protocolo_id')
								->join('movimiento_contras', 'movimiento_contras.contrarref_id','=','contrarreferencias.id')
								->select('pacientes.tipoDoc as tipoDoc',
								         'pacientes.rut as rut',
										 'pacientes.dv as dv',
										 'pacientes.numDoc',
										 'origen.name as origen',
										 'contrarref.name as contrarref',
										 'contrarreferencias.id as id',
										 'contrarreferencias.nombre_usuario as nombre_usuario',
										 'contrarreferencias.ges_id as ges_id',
										 'contrarreferencias.tipo_salida as tipo_salida',
										 'contrarreferencias.control_aps as control_aps',
										 'contrarreferencias.control_especialidad as control_especialidad',
										 'protocolos.name as name',
										 'contrarreferencias.created_at')
								->selectRaw('DATE_FORMAT(contrarreferencias.created_at, "%d-%m-%Y") as fecha')
								->selectRaw('DATE_FORMAT(contrarreferencias.fecha_agendamiento, "%d-%m-%Y") as fecha_agendamiento')
								->where([['estOrigen_id',session('establecimiento')],
										 ['movimiento_contras.active','1']])
								->whereIn('movimiento_contras.estado',['AC','RA']);

			if( $request->get('searchRut') != null ) {
				$explode = explode("-",$request->get('searchRut'));
        		$rut = $explode[0];
				$contrarreferencias = $contrarreferencias->where('pacientes.rut','LIKE','%'.$rut.'%');
			}

			if( $request->get('searchNumDoc') != null ) {
				$contrarreferencias = $contrarreferencias->where('pacientes.numDoc','LIKE','%'.$request->get('searchNumDoc').'%');
			}

			if( $request->get('searchSalida') != null ) {
				$contrarreferencias = $contrarreferencias->where('contrarreferencias.tipo_salida','LIKE','%'.$request->get('searchSalida').'%');
			}


			$contrarreferencias = $contrarreferencias->paginate(10)
			                                         ->appends('searchRut',$request->get('searchRut'))
													 ->appends('searchNumDoc',$request->get('searchNumDoc'))
													 ->appends('searchSalida',$request->get('searchSalida'));

			return view('contrarreferencias.aps',compact('contrarreferencias'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Muestra resumen de Contrarreferencia para perfil APS
	 * Vista: contrarreferencias.agendaSome
	 * Rol: some
	 *
	 * @param int $id  ID de la Contrarreferencia
     * @return \Illuminate\Http\Response
	 */
	public function detalleAps($id) 
	{
		if (Auth::check()) {
			$contrarreferencia = Contrarreferencia::find($id);

			//determina si contrarreferencia corresponde al establecimiento del usuario
			if ( $contrarreferencia->estOrigen_id == session('establecimiento') ) {

				$paciente = Paciente::find($contrarreferencia->paciente_id);
				//formato fecha de nacimiento
				$fechaNacimiento = new DateTime($paciente->fechaNacimiento);
				$fechaNacimiento = $fechaNacimiento->format('d-m-Y');
				//calcula edad
				$date  = date('Y-m-d');//la fecha del computador
				$diff  = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
				$edad = floor($diff / (365*60*60*24));

				$estContra = Establecimiento::find($contrarreferencia->estContra_id);
				$estOrigen = Establecimiento::find($contrarreferencia->estOrigen_id);

				$cie10 = Cie10::find($contrarreferencia->diagEgreso_id);

				if ( $contrarreferencia->ges_id != null) {
					$tipoGesObject = TipoGes::find($contrarreferencia->ges_id);
					$tipoGes = $tipoGesObject->name;
				}
				else {
					$tipoGes = "";
				}

				$protocolo = Protocolo::find($contrarreferencia->protocolo_id);
				$tiempo = Protocolo::find($contrarreferencia->tiempo_id);

				//guarda hora de revision de documento y cambia de estado
				$mov = MovimientoContra::where([['contrarref_id',$id],['active',1]])->first();

				if ( $mov->estado == 'AC' ) { //Si está en estado ingresado
					//cambia active del movimiento a false (0)
					$movid = $mov->id;
					$movimiento = MovimientoContra::find($movid);
					$movimiento->active = 0;

					$movimiento->save();

					//crea un nuevo movimiento en estado RA
					$movimientoNew = new MovimientoContra();
					$movimientoNew->contrarref_id = $id;
					$movimientoNew->estado        = 'RA';
					$movimientoNew->user_id       = Auth::user()->id;
					$movimientoNew->active        = 1;

					$movimientoNew->save();

					//Guarda Log de transacción
					$Log = new logLep;
		        	$Log->name = 'Contrarreferencia';
		        	$Log->tabla_id = $id;
		        	$Log->estado = 'RA - Revisada por Médico APS';
		        	$Log->establecimientos_id = session('establecimiento');
		        	$Log->user_id = Auth::user()->id;
		        	$Log->save();
					//Fin Log de transacción
				}

				return view('contrarreferencias.detalleAps',compact('contrarreferencia','paciente','fechaNacimiento','edad','estContra','estOrigen','cie10','tipoGes','protocolo','tiempo'));
			}
			else {
				return view('home');
			}

		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Cierra Contrarreferencia para perfil APS.
	 * Rol: some
	 *
	 * @param int $id  ID de la Contrarreferencia
     * @return \Illuminate\Http\Response
	 */
	public function cierraAps($id) 
	{
		if (Auth::check()) {
			$contrarreferencia = Contrarreferencia::find($id);

			//determina si contrarreferencia corresponde al establecimiento del usuario
			if ( $contrarreferencia->estOrigen_id == session('establecimiento') ) {

				//guarda hora de revision de documento y cambia de estado
				$mov = MovimientoContra::where([['contrarref_id',$id],['active',1]])->first();

				if ( $mov->estado == 'RA' ) { //Si está en estado ingresado
					//cambia active del movimiento a false (0)
					$movid = $mov->id;
					$movimiento = MovimientoContra::find($movid);
					$movimiento->active = 0;

					$movimiento->save();

					//crea un nuevo movimiento en estado RC
					$movimientoNew = new MovimientoContra();
					$movimientoNew->contrarref_id = $id;
					$movimientoNew->estado        = 'CC';
					$movimientoNew->user_id       = Auth::user()->id;
					$movimientoNew->active        = 1;

					$movimientoNew->save();

					//Guarda Log de transacción
					$Log = new logLep;
		        	$Log->name = 'Contrarreferencia';
		        	$Log->tabla_id = $id;
		        	$Log->estado = 'CC - Cerrada';
		        	$Log->establecimientos_id = session('establecimiento');
		        	$Log->user_id = Auth::user()->id;
		        	$Log->save();
					//Fin Log de transacción
				}

				return redirect('/contrarreferencias/aps')->with('message','cierra');
			}
			else {
				return view('home');
			}

		}
		else {
			return view('auth/login');
		}
	}
	/*******************************************************************************************/
	/*                                       REPORTES                                          */
	/*******************************************************************************************/
	/**
	 * Función que llama a formulario de filtros de reporte
	 * Vista: contrarreferencias.reporte
	 * Rol: None
     *
	 * @return lista de contrarreferencias que coinciden con la busqueda
	 */
	public function reporte()
	{
		if (Auth::check()) {
			$establecimientos = Establecimiento::where('active',1)->orderBy('name')->get();
			return view('contrarreferencias.reporte',compact('establecimientos'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Resultado reporte según filtros definidos por el usuario
	 * Vista: contrarreferencias.resultado
	 * Rol: None
	 *
     * @param  \Illuminate\Http\Request  $request
	 * @return list lista de contrarreferencias que coinciden con la busqueda
	 */
	public function resultado(Request $request)
	{
		if (Auth::check()) {
			$contrarreferencias = DB::table('contrarreferencias')
								->join('pacientes', 'pacientes.id','=','contrarreferencias.paciente_id')
								->join('especialidads', 'especialidads.id','=','contrarreferencias.especialidad_id')
								->join('establecimientos as origen', 'origen.id','=','contrarreferencias.estOrigen_id')
								->join('establecimientos as contrarref', 'contrarref.id','=','contrarreferencias.estContra_id')
								->join('protocolos', 'protocolos.id','=','contrarreferencias.protocolo_id')
								->join('movimiento_contras', 'movimiento_contras.contrarref_id','=','contrarreferencias.id')
								->leftjoin('movimiento_contras as cierre', function ($join) {
            						$join->on('cierre.contrarref_id', '=', 'contrarreferencias.id')
                 						 ->where('cierre.estado', '=', 'CC');
        						})
								->orderBy('contrarreferencias.id', 'DESC')
								->select('pacientes.tipoDoc as tipoDoc',
								         'pacientes.rut as rut',
										 'pacientes.dv as dv',
										 'pacientes.numDoc',
										 'origen.name as origen',
										 'contrarref.name as contrarref',
										 'especialidads.name as especialidad',
										 'contrarreferencias.id as id',
										 'contrarreferencias.nombre_usuario as nombre_usuario',
										 'contrarreferencias.ges_id as ges_id',
										 'contrarreferencias.tipo_salida as tipo_salida',
										 'contrarreferencias.causal_egreso as causal_egreso',
										 'movimiento_contras.estado as estado',
										 'contrarreferencias.nombre_medico as nombre_medico',
										 'protocolos.name as name')
								->selectRaw('DATE_FORMAT(contrarreferencias.created_at, "%d-%m-%Y") as fecha')
								->selectRaw('DATE_FORMAT(contrarreferencias.fecha_agendamiento, "%d-%m-%Y") as fecha_agendamiento')
								->selectRaw('DATE_FORMAT(cierre.created_at, "%d-%m-%Y") as fecha_cerrada')
								->where('movimiento_contras.active','1');

			if( $request->get('idPaciente') != null ) {
				$contrarreferencias = $contrarreferencias->where('paciente_id',$request->get('idPaciente'));
			}

			if( $request->get('establecimiento') != null ) {
				$contrarreferencias = $contrarreferencias->where('estContra_id',$request->get('establecimiento'));
			}

			if( $request->get('origen') != null ) {
				$contrarreferencias = $contrarreferencias->where('estOrigen_id',$request->get('origen'));
			}

			if( $request->get('tipoAlta') != null ) {
				$contrarreferencias = $contrarreferencias->where('tipo_salida',$request->get('tipoAlta'));
			}

			if( $request->get('estado') != null ) {
				$contrarreferencias = $contrarreferencias->where('movimiento_contras.estado',$request->get('estado'));
			}

			if( $request->get('desde') != null ) {
				//formatea fechas
				$fecha = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde')." 00:00:00");
				$contrarreferencias = $contrarreferencias->where('contrarreferencias.created_at','>=',$fecha);
			}

			if( $request->get('hasta') != null ) {
				//formatea fechas
				$fecha = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('hasta')." 23:59:59");
				$contrarreferencias = $contrarreferencias->where('contrarreferencias.created_at','<=',$fecha);
			}

			$contrarreferencias = $contrarreferencias->paginate(10)
			->appends('idPaciente',$request->get('idPaciente'))
			->appends('establecimiento',$request->get('establecimiento'))
			->appends('origen',$request->get('origen'))
			->appends('tipoAlta',$request->get('tipoAlta'))
			->appends('estado',$request->get('estado'))
			->appends('desde',$request->get('desde'))
			->appends('hasta',$request->get('hasta'));


			//parametros de consulta
			$idPaciente      = $request->get('idPaciente');
			$establecimiento = $request->get('establecimiento');
			$origen          = $request->get('origen');
			$tipoAlta        = $request->get('tipoAlta');
			$estado          = $request->get('estado');
			$desde           = $request->get('desde');
			$hasta           = $request->get('hasta');

			return view('contrarreferencias.resultado',compact('contrarreferencias','idPaciente','establecimiento','origen','tipoAlta','estado','desde','hasta'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Muestra pantalla con Detalle de Contrarreferencia para perfil el Reporte
	 *
	 * @param int $id ID de la Contrarreferencia
	 * @return \Illuminate\Http\Response
	 */
	public function detalle($id) 
	{
		if (Auth::check()) {
			$contrarreferencia = Contrarreferencia::find($id);

			$paciente = Paciente::find($contrarreferencia->paciente_id);
			//formato fecha de nacimiento
			$fechaNacimiento = new DateTime($paciente->fechaNacimiento);
			$fechaNacimiento = $fechaNacimiento->format('d-m-Y');
			//calcula edad
			$date  = date('Y-m-d');//la fecha del computador
			$diff  = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
			$edad = floor($diff / (365*60*60*24));

			$estContra = Establecimiento::find($contrarreferencia->estContra_id);
			$estOrigen = Establecimiento::find($contrarreferencia->estOrigen_id);

			$cie10 = Cie10::find($contrarreferencia->diagEgreso_id);

			if ( $contrarreferencia->ges_id != null) {
				$tipoGesObject = TipoGes::find($contrarreferencia->ges_id);
				$tipoGes = $tipoGesObject->name;
			}
			else {
				$tipoGes = "";
			}

			$protocolo = Protocolo::find($contrarreferencia->protocolo_id);
			$tiempo = Protocolo::find($contrarreferencia->tiempo_id);

			return view('contrarreferencias.detalle',compact('contrarreferencia','paciente','fechaNacimiento','edad','estContra','estOrigen','cie10','tipoGes','protocolo','tiempo'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Genera archivo excel con el mismo resultado de busqueda en resultado
	 * Rol: None
     *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function excel(Request $request)
	{
		if (Auth::check()) {
			$contrarreferencias = DB::table('contrarreferencias')
								->join('pacientes', 'pacientes.id','=','contrarreferencias.paciente_id')
								->join('establecimientos as origen', 'origen.id','=','contrarreferencias.estOrigen_id')
								->join('establecimientos as contrarref', 'contrarref.id','=','contrarreferencias.estContra_id')
								->join('protocolos', 'protocolos.id','=','contrarreferencias.protocolo_id')
								->join('especialidads', 'especialidads.id','=','contrarreferencias.especialidad_id')
								->join('cie10s', 'cie10s.id','=','contrarreferencias.diagEgreso_id')
								->leftjoin('tipo_ges', 'tipo_ges.id','=','contrarreferencias.ges_id')
								->join('movimiento_contras', 'movimiento_contras.contrarref_id','=','contrarreferencias.id')
								->join('users', 'users.id','=','contrarreferencias.user_id')
								->leftjoin('movimiento_contras as cierre', function ($join) {
            						$join->on('cierre.contrarref_id', '=', 'contrarreferencias.id')
                 						 ->where('cierre.estado', '=', 'CC');
        						})
								->orderBy('contrarreferencias.id', 'DESC')
								->select('pacientes.tipoDoc as tipoDoc',
								         'pacientes.rut as rut',
										 'pacientes.dv as dv',
										 'pacientes.numDoc',
										 'origen.name as origen',
										 'contrarref.name as contrarref',
										 'especialidads.name as especialidad',
										 'cie10s.name as diagnostico_cie10',
										 'contrarreferencias.indicaciones_aps',
										 'contrarreferencias.id as id',
										 'contrarreferencias.nombre_usuario as nombre_usuario',
										 'users.email as rut_usuario',
										 'contrarreferencias.ges_id as ges_id',
										 'tipo_ges.name as manejo_ges',
										 'protocolos.name as pertinencia_protocolo',
										 'contrarreferencias.tiempo_id',
										 'contrarreferencias.primera_consulta',
										 'contrarreferencias.tipo_salida',
										 'contrarreferencias.control_especialidad',
										 'contrarreferencias.control_aps',
										 'contrarreferencias.causal_egreso as causal_egreso',
										 'movimiento_contras.estado as estado')
								->selectRaw('DATE_FORMAT(contrarreferencias.created_at, "%d-%m-%Y") as fecha')
								->selectRaw('DATE_FORMAT(contrarreferencias.fecha_agendamiento, "%d-%m-%Y") as fecha_agendamiento')
								->selectRaw('DATE_FORMAT(cierre.created_at, "%d-%m-%Y") as fecha_cerrada')
								->where('movimiento_contras.active','1');

			if( $request->get('idPaciente') != null ) {
				$contrarreferencias = $contrarreferencias->where('paciente_id',$request->get('idPaciente'));
			}

			if( $request->get('establecimiento') != null ) {
				$contrarreferencias = $contrarreferencias->where('estContra_id',$request->get('establecimiento'));
			}

			if( $request->get('origen') != null ) {
				$contrarreferencias = $contrarreferencias->where('estOrigen_id',$request->get('origen'));
			}

			if( $request->get('tipoAlta') != null ) {
				$contrarreferencias = $contrarreferencias->where('tipo_salida',$request->get('tipoAlta'));
			}

			if( $request->get('estado') != null ) {
				$contrarreferencias = $contrarreferencias->where('movimiento_contras.estado',$request->get('estado'));
			}

			if( $request->get('desde') != null ) {
				//formatea fechas
				$fecha = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('desde')." 00:00:00");

				$contrarreferencias = $contrarreferencias->where('contrarreferencias.created_at','>=',$fecha);
			}

			if( $request->get('hasta') != null ) {
				//formatea fechas
				$fecha = DateTime::createFromFormat('d-m-Y H:i:s', $request->get('hasta')." 23:59:59");

				$contrarreferencias = $contrarreferencias->where('contrarreferencias.created_at','<=',$fecha);
			}
			$contrarreferencias = $contrarreferencias->paginate(1000);

			//crea array con información de consulta
			$contrarreferenciasArray[] = ['RUN / Nro de Documento','Establecimiento Contrarreferencia','Establecimiento Origen','Médico','Rut Médico ', 'Especialidad','Diagnóstico CIE-10','Indicaciones APS','Ges', 'Manejo GES', 'Pertinencia Protocolo', 'Pertinencia por tiempo', 'Primer Consulta','Tipo Salida', 'Próximo control Especialidad (Meses)','Próximo control APS (Días)','Fecha Contrarreferencia','Fecha Agendamiento','Causal Egreso', 'Fecha Causal Egreso','Fecha Cerrada por Médico APS','Estado'];

			foreach ($contrarreferencias as $contrarreferencia) {
				//determina tipo de documento
				$doc = '';
				if ( $contrarreferencia->tipoDoc == 1 ) {
					$doc = $contrarreferencia->rut.'-'.$contrarreferencia->dv;
				}
				else {
					$doc = $contrarreferencia->numDoc;
				}
				//determina tipo ges
				if ( $contrarreferencia->ges_id == null ) {
					$ges = 'No';
				}
				else {
					$ges = 'Si';
				}
				//determina tipo de alta
				$tipo_salida = '';
				if ( $contrarreferencia->tipo_salida == 0 ){
					$tipo_salida = 'Alta';
				}
				elseif ( $contrarreferencia->tipo_salida == 2 ){
					$tipo_salida = 'Alta con Control APS';
				}
				elseif ( $contrarreferencia->tipo_salida == 1 ){
					$tipo_salida = 'Control en la Especialidad';
				}
				//determina estado
				$estado = '';
				if ( $contrarreferencia->estado == 'IC' ){
					$estado = 'Ingresada';
				}
				elseif ( $contrarreferencia->estado == 'RC' ){
					$estado = 'Revisada por SOME';
				}
				elseif ( $contrarreferencia->estado == 'AC' ){
					$estado = 'Asignada a Médico APS';
				}
				elseif ( $contrarreferencia->estado == 'RA' ){
					$estado = 'Revisada por Médico APS';
				}
				elseif ( $contrarreferencia->estado == 'CC' ){
					$estado = 'Cerrada';
				}
				//causal egreso
				$causal = '';
				if ( $contrarreferencia->causal_egreso == 2 ) {
					$causal = 'No Ubicable';
				}
				elseif ( $contrarreferencia->causal_egreso == 3 ) {
					$causal = 'Rechaza Atención';
				}
				elseif ( $contrarreferencia->causal_egreso == 4 ) {
					$causal = 'Domicilio No Disponible';
				}


				// Indicaciones APS
				if ( $contrarreferencia->indicaciones_aps != NULL ) {
					$indicaciones_aps = 'Si';
				} else {
					$indicaciones_aps = 'No';
				}
				//Pertenencia por tiempo
				$pertenencia_tiempo = '';
				if ( $contrarreferencia->tiempo_id == 1 ){
					$pertenencia_tiempo = 'Sí, de acuerdo a protocolo';
				}
				elseif ( $contrarreferencia->tiempo_id == 2 ){
					$pertenencia_tiempo = 'Sí, por juicio de experto';
				}
				elseif ( $contrarreferencia->tiempo_id == 3 ){
					$pertenencia_tiempo = 'No pertinente';
				}
				//Primera Consulta
				if ( $contrarreferencia->primera_consulta == 1 ){
					$primera_consulta = 'Si';
				} elseif ( $contrarreferencia->primera_consulta == 2) {
					$primera_consulta = 'No';
				}
				// Control APS
				$control_aps = '';
				if ($contrarreferencia->control_aps == 1) {
					$control_aps = '7 días';
				}
				if ($contrarreferencia->control_aps == 2) {
					$control_aps = '15 días';
				}
				if ($contrarreferencia->control_aps == 3) {
					$control_aps = '30 días';
				}

				// Si tiene Cierre causal (2,3,4) grabar fecha_causal_cerrada,
				$fecha_cerrada = '';
				$fecha_causal_cerrada = '';
				if ($contrarreferencia->causal_egreso == 1) {
					$fecha_cerrada = $contrarreferencia->fecha_cerrada;
				} elseif ($contrarreferencia->causal_egreso == 2 || $contrarreferencia->causal_egreso == 3 || $contrarreferencia->causal_egreso == 4 ) {
					$fecha_causal_cerrada = $contrarreferencia->fecha_cerrada;
				}


				$contrarreferenciasArray[] = [ $doc,
											   $contrarreferencia->contrarref,
											   $contrarreferencia->origen,
											   $contrarreferencia->nombre_usuario,
											   $contrarreferencia->rut_usuario,
											   $contrarreferencia->especialidad,
											   $contrarreferencia->diagnostico_cie10,
											   $indicaciones_aps,
											   $ges,
											   $contrarreferencia->manejo_ges,
											   $contrarreferencia->pertinencia_protocolo,
											   $pertenencia_tiempo,
											   $primera_consulta,
											   $tipo_salida,
											   $contrarreferencia->control_especialidad,
											   $control_aps,
											   $contrarreferencia->fecha,
											   $contrarreferencia->fecha_agendamiento,
											   $causal,
											   $fecha_causal_cerrada,
											   $fecha_cerrada,
											   $estado];
			}

			//Genera archivo Excel
			Excel::create('Archivo', function($excel) use($contrarreferenciasArray) {
 				$excel->sheet('Contrarreferencia', function($sheet) use($contrarreferenciasArray) {
					$sheet->fromArray($contrarreferenciasArray,null,'A1',true,false);
	 			});
			})->export('xls');
		}
		else {
			return view('auth/login');
		}
	}

	/*******************************************************************************************/
	/*                                       AUTOCOMPLETAR                                     */
	/*******************************************************************************************/
	/**
	 * Función que autocompleta con campo de pacientes con RUT
	 *
     * @param  \Illuminate\Http\Request  $request
	 * @return list lista de pacientes que coinciden con la busqueda
	 */
	public function autoComplete(Request $request)
	{

		$explode = explode("-",$request->get('term',''));
        $query = $explode[0];
        // $query = $request->get('term','');

        $pacientes=Paciente::where([['rut','LIKE','%'.$query.'%'],['active','1']])
							->orWhere([['numDoc','LIKE','%'.$query.'%'],['active','1']])
							->orderBy('nombre')->get();

        $data=array();
        foreach ($pacientes as $paciente) {
				//formato fecha de nacimiento
				$fechaNacimiento = new DateTime($paciente->fechaNacimiento);
				$fechaNacimiento = $fechaNacimiento->format('d-m-Y');

				//calcula edad
				$date  = date('Y-m-d');//la fecha del computador
				$diff  = abs(strtotime($date) - strtotime($paciente->fechaNacimiento));
				$edad = floor($diff / (365*60*60*24));

				//extrae datos de lista de espera
				$listaEspera = ListaEspera::where([
										['pacientes_id',$paciente->id],
										['especialidads_ingreso_id',session('especialidad')]
										])->orderBy('fechaingreso', 'DESC')->first();

				if ( $listaEspera <> null) {
					//formato fecha de entrada
					$fechaIngreso = new DateTime($listaEspera->fechaingreso);
					$fechaIngreso = $fechaIngreso->format('d-m-Y');

					//diagnostico
					$cie10 = Cie10::find($listaEspera->cie10s_id);

					//especialidad
					$especialidad = Especialidad::find($listaEspera->especialidads_ingreso_id);

					$data[]=array('value'     =>$paciente->numDoc.$paciente->rut.$paciente->dv." ".$paciente->nombre." ".$paciente->apPaterno." ".$paciente->apMaterno,
								  'id'        =>$paciente->id,
								  'nombre'    =>$paciente->nombre,
								  'apPaterno' =>$paciente->apPaterno,
								  'apMaterno' =>$paciente->apMaterno,
								  'direccion' =>$paciente->direccion." ".$paciente->numero,
								  'telefono'  =>$paciente->telefono,
								  'telefono2' =>$paciente->telefono2,
								  'email'     =>$paciente->email,
								  'fechaNacimiento' =>$fechaNacimiento,
								  'edad'      =>$edad,
								  /*-------datos de lista de espera---------*/
								  'fechaSIC' => $fechaIngreso,
								  'sospechaSIC' => $cie10->name,
								  'precisionSIC' => $listaEspera->precdiag,
								  'especialidadSIC' => $especialidad->name,);
				}
				else {
					$data[]=array('value'     =>$paciente->numDoc.$paciente->rut.$paciente->dv." ".$paciente->nombre." ".$paciente->apPaterno." ".$paciente->apMaterno,
								  'id'        =>$paciente->id,
								  'nombre'    =>$paciente->nombre,
								  'apPaterno' =>$paciente->apPaterno,
								  'apMaterno' =>$paciente->apMaterno,
								  'direccion' =>$paciente->direccion." ".$paciente->numero,
								  'telefono'  =>$paciente->telefono,
								  'telefono2' =>$paciente->telefono2,
								  'email'     =>$paciente->email,
								  'fechaNacimiento' =>$fechaNacimiento,
								  'edad'      =>$edad,);
				}
        }
        if(count($data)) {
             return $data;
        }
		else {
				return ['value'=>'Paciente no encontrado'];
		}
    }

	/**
	 * Función que autocompleta con campo de diagnosticos CIE-10
	 *
     * @param  \Illuminate\Http\Request  $request
	 * @return list lista de pacientes que coinciden con la busqueda
	 */
	public function autoComplete2(Request $request)
	{
        $query = $request->get('term','');

		$cie10s=Cie10::where([[DB::raw('LOWER(codigo)'),'LIKE','%'.strtolower($query).'%'],['active','1']])
                    ->orWhere([[DB::raw('LOWER(name)'),'LIKE','%'.strtolower($query).'%'],['active','1']])
                    ->orderBy('name')
                    ->paginate(20);

        $data=array();
        foreach ($cie10s as $cie10) {

				$data[]=array('value' =>$cie10->codigo."-".$cie10->name,
							  'id'    =>$cie10->id,);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'Diagnóstico no encontrado'];
    }
}
