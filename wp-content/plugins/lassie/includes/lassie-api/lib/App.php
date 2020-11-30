<?php

namespace Lassie;

use Lassie\{ BaseModule, Instance };

class App extends BaseModule
{

	/**
	 * Get Lassie App API key. Implements api/v2/lassie/app/kye
	 * @param  Array  [ 'api_host', 'auth_token' ] request params
	 */
	public static function getKey($args)
	{
		$url = $args['api_host'] . '/lassie/app/key';
		$data = ['auth_token' => $args['auth_token']];

		return Instance::_performRequest('GET', $url, $data, true);
	}

}
