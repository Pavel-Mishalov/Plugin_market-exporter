<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 26.02.17
 * Time: 15:51
 */

include_once ('../../Config_yml_api.php');

$param = Config_yml_api::GetParam();

if( Config_yml_api::OAuth( $param['oauth_token'], $param['oauth_client_id'] ) ){
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		header('Content-type: application/json; charset=UTF-8');
		header('HTTP/1.1 200 OK');

		$post_body  = file_get_contents("php://input");
		$query = json_decode($post_body);

		//Необходимо обработать запрос о смене статуса и вывести информацию в админку

	}
	else{
		header('Content-type: application/json; charset=UTF-8');
		header("HTTP/1.0 400 Bad Request");
		$answer = array(
			"Answer" => "Данные должны передоваться методом POST."
		);
		echo json_encode($answer);
	}
}
else{
	header('Content-type: application/json; charset=UTF-8');
	header("HTTP/1.0 400 Bad Request");
	$answer = array(
		"Ответ" => "Yandex Market, не прошел аундетификацию."
	);
	echo json_encode($answer);
}