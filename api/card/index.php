<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 26.02.17
 * Time: 15:48
 */

include_once ('../Config_yml_api.php');

$param = Config_yml_api::GetParam();

if( Config_yml_api::OAuth( $param['oauth_token'], $param['oauth_client_id'] ) ){
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		header('Content-type: application/json; charset=UTF-8');
		header('HTTP/1.1 200 OK');

		$post_body  = file_get_contents("php://input");
		$query = json_decode($post_body);

		$deliveryOptions = array();

		if($query->cart->delivery->address->city == 'M'){
			$pickup = array(
				"price"=> 0,
				"serviceName" => "Пункт выдачи заказов",
				"type" => "PICKUP",
				"dates" => array(
					"fromDate" => date("d-m-Y", strtotime("+1 day")),
					"reservedUntil" => date("d-m-Y", strtotime("+3 day")),
					"toDate" => date("d-m-Y", strtotime("+7 day"))
				),
				'outlets' => array(
					array(
						"id" => 562048
					),
					array(
						"id" => 498219
					),
					array(
						"id" => 498220
					),
					array(
						"id" => 498223
					),
					array(
						"id" => 498224
					),
					array(
						"id" => 498226
					),
					array(
						"id" => 498227
					)
				)
			);
			array_push($deliveryOptions, $pickup);
		}

		$items = array();

		foreach( $query->cart->delivery->items as $item) {
			$itemOptions = array(
				"count" => $item->count,
				"delivery" => false,
				"feedId" => $item->feedId,
				"offerId" => $item->offerId,
				"price" => wc_get_product( $item->offerId )->sale_price
			);
			array_push($items, $itemOptions);
		}

		$answer = array(
			'cart' => array(
				'deliveryOptions' => $deliveryOptions,
				"items" => $items,
				"paymentMethods" => array(
					"CARD_ON_DELIVERY",
					"CASH_ON_DELIVERY"
				)
			)
		);
		echo json_encode($answer);
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