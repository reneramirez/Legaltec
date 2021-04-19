<?php

namespace App\Controllers;
use \CodeIgniter\Controller;
use \App\Libraries\ClienteApi;
use CodeIgniter\Database\MySQLi\Result;

class Vehiculo extends BaseController
{
	public $clienteApi;
	public function __construct()
	{
		$this->clienteApi = new ClienteApi();
	}

	public function index()
	{
		echo view('header');
		echo view('vehiculo/buscar');
		echo view('footer');
	}

	public function ingresoMulta(){

		$marcas = $this->clienteApi->send('list_marca', null);
		$msj = ["text" => "", "class" => "d-none"];
		if($_POST){
			$vehiculo = [
										"id_modelo" =>$_POST['slcModelo'],
										"patente" =>$_POST['inpPatente'],
									];
			if($_POST['hdnIdVehiculo']){
				$vehiculo["id_vehiculo"] =$_POST['hdnIdVehiculo'];
			}
			$result = $this->clienteApi->send('insert_vehiculo', $vehiculo);
			if($result)
			{
				$id_vehiculo = $result["id_vehiculo"];
				$detalle = 	[
					[
												"id_vehiculo_detalle"=>$_POST['hdnIdPermiso']?(int)$_POST['hdnIdPermiso'] : 0,
												"id_vehiculo"=> $id_vehiculo,
												"id_tipo_servicio"=>1,
												"id_estado_pago"=>1,
												"monto"=>(int)$_POST['inpPermiso']
											],
											[
												"id_vehiculo_detalle"=>$_POST['hdnIdInteres']?(int)$_POST['hdnIdInteres'] : 0,
												"id_vehiculo"=> $id_vehiculo,
												"id_tipo_servicio"=>2,
												"id_estado_pago"=>1,
												"monto"=>(int)$_POST['inpInteresReajuste']
											],
											[
												"id_vehiculo_detalle"=>$_POST['hdnIdMulta']?(int)$_POST['hdnIdMulta'] : 0,
												"id_vehiculo"=> $id_vehiculo,
												"id_tipo_servicio"=>3,
												"id_estado_pago"=>1,
												"monto"=>(int)$_POST['inpMultas']
											],
										];
										$result = $this->clienteApi->send('insert_detalle', $detalle);
				$msj = ["text" => "Datos guardados con &eacute;xito.", "class" => "alert-success"];
				if(!$result){
					$msj = ["text" => "Error al guardar registro de detalle.", "class" => "alert-danger"];
				}
			}else{
				$msj=["text"=>"Error al guardar registro.", "class"=> "alert-danger"];
			}
		}
		$data = ["marcas" => $marcas, "msj"=> $msj];
		echo view('header');
		echo view('vehiculo/ingreso_multa', $data);
		echo view('footer');
	}

	public function pagos(){

		$msj = ["text" => "", "class" => "d-none"];
		$data=["msj" => $msj];

		if ($_POST) {
			$detalle = $this->clienteApi->send('GetDetalleVehiculo', ["id_vehiculo" => $_POST['hdnIdVehiculo']]);
			foreach ($detalle as $key => $value) {
				if(in_array($value['id_vehiculo_detalle'], $_POST['id_vehiculo_detalle'])){
					$value['id_estado_pago'] = 2;
				}
				unset($value['glosa_tipo_servicio']);
				unset($value['glosa_estado_pago']);
				$detalle[$key] = $value;
			}
			$result = $this->clienteApi->send('insert_detalle', $detalle);
			$msj = ["text" => "Datos guardados con &eacute;xito.", "class" => "alert-success"];
			if (!$result) {
				$msj = ["text" => "Error al guardar registro de detalle.", "class" => "alert-danger"];
			}
			$data = ["msj" => $msj];
		}

		echo view('header');
		echo view('vehiculo/pagos', $data);
		echo view('footer');
	}

	public function loadModelos(){
		if($_POST){
			$id_marca = $_POST['marca'];
			$modelos = $this->clienteApi->send('list_modelos', ["id_marca"=>$id_marca]);
			echo json_encode($modelos);
			die;
		}
	}
	public function getDataVehiculo(){
		if($_POST){
			$patente = $_POST['patente'];
			$vehiculo = $this->clienteApi->send('GetVehiculo', ["patente"=> $patente]);
			$id_vehiculo = $vehiculo['id_vehiculo'];
			$detalle = $this->clienteApi->send('GetDetalleVehiculo', ["id_vehiculo"=> $id_vehiculo]);
			$data = ["vehiculo"=>$vehiculo, "detalle"=>$detalle];
			echo json_encode($data);
			die;
		}
	}

	
}
