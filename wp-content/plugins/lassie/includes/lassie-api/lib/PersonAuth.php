<?php

namespace Lassie;

use Lassie\{ Instance, BaseModule };

class PersonAuth extends BaseModule
{

	public static $endpoint = 'auth/login';

	/**
	 * Performs the initial Person Auth request to generate a person-specific
	 * API key and initializes an Instance object.
	 * @param Object  lassie     Lassie\Instance object 
	 * @param Array  args        [ 'username', 'password' ]
	 */
	public static function getPerson($lassie, $args)
	{
		if (!($lassie instanceof Instance)) {
			throw new \Exception('First argument must be Lassie\Instance object.');
		}

		try {
			$response = $lassie->performRequest('POST', static::$endpoint, [
				'username' => $args['username'],
				'password' => $args['password']
			]);
		} catch (Exception $err) {
			if ($lassie->log) var_dump($err);
			return $err;
		}

		$instance = new Instance(
			$lassie->apiHost,
			$response->api_key,
			$response->api_secret,
			$lassie->log
		);
		return $instance;
	}

}
