<?php
namespace App\Libraries;

class CurlPieces
{

	/**
	 * Realiza solicitud http tipo _GET
	 * @param url (String), url a donde se realiza la solicitud
	 * @param params (array), parámetros requeridos para la solicitud
	 * @return output (array), resultado de la solicitud
	 * @return info (array), información de la ejecución HTTP
	 */
	public static function httpGet($url, $params=null, $header = array())
	{
		$url = $url;
		if($params){
			$url = $url. "?" . http_build_query($params);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($header){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$output = curl_exec($ch);
		if ($output === false) {
			$error = curl_error($ch);
			die($error);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" => $output, "info" => $info);
	}

	/**
	 * Realiza llamada http tipo _POST
	 * @param url (String), url a donde se realiza la solicitud
	 * @param params (array), parámetros requeridos para la solicitud
	 * @param header (array), Headers requeridos para la solicitud
	 * @return output (array), resultado de la solicitud
	 * @return info (array), información de la ejecución HTTP
	 */
	public static function httpPost($url, $params, $header = array())
	{
    // echo "<pre>";
    // var_dump($url, $params, $header);
    // echo "</pre>"; die;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$output = curl_exec($ch);
		if ($output === false) {
			$error = curl_error($ch);
			die($error);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" => $output, "info" => $info);
	}

	/**
	 * Realiza solicitud http tipo _PATCH
	 * @param url (String), url a donde se realiza la solicitud
	 * @param params (array), parámetros requeridos para la solicitud
	 * @param header (array), Headers requeridos para la solicitud
	 * @return output (array), resultado de la solicitud
	 * @return info (array), información de la ejecución HTTP
	 */
	public static function httpPatch($url, $params, $header = array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$output = curl_exec($ch);
		if ($output === false) {
			$error = curl_error($ch);
			die($error);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" => $output, "info" => $info);
	}

	/**
	 * Realiza solicitud http tipo _PATCH
	 * @param url (String), url a donde se realiza la solicitud
	 * @param params (array), parámetros requeridos para la solicitud
	 * @param header (array), Headers requeridos para la solicitud
	 * @return output (array), resultado de la solicitud
	 * @return info (array), información de la ejecución HTTP
	 */
	public static function httpPut($url, $params, $header = array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$output = curl_exec($ch);
		if ($output === false) {
			$error = curl_error($ch);
			die($error);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" => $output, "info" => $info);
	}

}
