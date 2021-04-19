<?php
namespace App\Libraries;

class ClienteApi
{
  protected $host;
  public function __construct()
	{
    $this->host= "http://localhost:59519/api";
  }

	public function send($service, $params)
	{
		$response = $this->ApiMethod($service, $params);
		if(isset($response["info"])) {
			$code = $response["info"]["http_code"];
			if (!in_array($code, array("200","201","202", "400", "401"))) {
				die("Ocurrió un error inesperado. HTTP_CODE: " .$code ." SERVICIO: ".$service);
			}
		}
		$body = json_decode($response["output"], true);
		return $body;
	}


	private function ApiMethod($method, $params = array(), $body = array(), $head = array())
	{
		$url = "";
		switch ($method) {
			
			case 'list_marca':
				$url = $this->host."/vehiculo/ListMarcas";
				$fichero = CurlPieces::httpGet($url, null, null);
				break;
			case 'list_modelos':
				$url = $this->host. "/vehiculo/ListModelos/".$params['id_marca'];
				$fichero = CurlPieces::httpGet($url, null, null);
				break;
			case 'GetVehiculo':
				$url = $this->host. "/vehiculo/GetVehiculo/".$params['patente'];
				$fichero = CurlPieces::httpGet($url, null, null);
				break;
			case 'GetDetalleVehiculo':
				$url = $this->host. "/vehiculo/GetDetalleVehiculo/".$params['id_vehiculo'];
				$fichero = CurlPieces::httpGet($url, null, null);
				break;
			case 'GetAllVehiculo':
				$url = $this->host. "/vehiculo/GetAllVehiculo/";
				$fichero = CurlPieces::httpGet($url, null, null);
				break;
			case 'insert_vehiculo':
				$header = [
					"Content-Type: application/json",
					"X-Content-Type-Options:nosniff",
					"Accept:application/json",
					"Cache-Control:no-cache"
				];
				$url = $this->host. "/vehiculo/InsertVehiculo";
				$fichero = CurlPieces::httpPost($url, json_encode($params, JSON_NUMERIC_CHECK), $header);	
				// $ficheroaux = $fichero;
				// $fichero['output'] = ['id_vehiculo'=> $ficheroaux['output']];
				// echo "<pre>"; var_dump($fichero); echo "</pre>"; die;
				break;
			case 'insert_detalle':
				$header = [
					"Content-Type: application/json",
					"X-Content-Type-Options:nosniff",
					"Accept:application/json",
					"Cache-Control:no-cache"
				];
				$url = $this->host . "/vehiculo/InsertDetalle";
				$fichero = CurlPieces::httpPost($url, json_encode($params, JSON_NUMERIC_CHECK), $header);
				break;
			default:
				die("method no definido");
				break;
		}
		return $fichero;
	}
}