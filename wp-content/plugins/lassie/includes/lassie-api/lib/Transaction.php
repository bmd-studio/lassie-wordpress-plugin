<?php

namespace Lassie;

use Lassie\BaseModule;

class Transaction extends BaseModule
{

	/**
	 * Perform the GET transaction on the root endpoint.
	 * @param Object lassie     Lassie\Instance object
	 * @param Array  opts       Additional arguments for the request
	 */
	public static function getTransaction($lassie, $opts)
	{
		return $lassie->performRequest('GET', 'transaction', $opts);
	}

	/**
	 * Perform the POST transaction on the root endpoint.
	 * @param Object lassie     Lassie\Instance object
	 * @param Array  opts       Additional arguments for the request
	 */
	public static function postTransaction($lassie, $opts)
	{
		return $lassie->performRequest('POST', 'transaction', $opts);
	}

}
