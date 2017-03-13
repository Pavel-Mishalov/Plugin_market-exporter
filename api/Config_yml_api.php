<?php

/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 26.02.17
 * Time: 14:22
 */


class Config_yml_api{

	/**
	 * @param $token
	 * @param $clientId
	 *
	 * @return bool
	 */
    
	public static function OAuth( $token, $clientId ){
        if ($token == get_option('market_exporter_api')['auth_token'] && $clientId == get_option('market_exporter_api')['oauth_client_id']) {
            return true;
        } else {
            return false;
        }
	}
	
	public static function GetParam(){
		return $_REQUEST;
	}
}

