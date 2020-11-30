<?php

namespace Lassie;

class Helpers
{

	/**
	 * Convert camelCase strings to snake_case
	 * @param String string   camelCase string
	 */
	public static function to_snake_case($string)
	{
		return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
	}

	/**
	 * Determine HTTP method type based on method name (e.g., get_groups -> GET)
	 * @param String string   Method name in snake_case
	 */
	public static function getMethodType($method)
	{
		$keyword = explode('_', $method)[0];

		$getPrefixes = ['get', 'is', 'has'];
		$postPrefixes = ['create', 'switch', 'deactivate', 'activate', 'reset',
						 'insert', 'revoke', 'start', 'end', 'remove', 'update',
						 'delete', 'save', 'end', 'open', 'rename',  'accept',
						 'decline', 'cancel', 'register', 'login', 'increase',
						 'decrease', 'set', 'handle', 'reset', 'generate', 'select',
						 'import', 'commit', 'upsert', 'handle', 'execute', 'transfer',
						 'prohibit', 'grant', 'unlink', 'connect', 'pay', 'invite',
						 'disconnect', 'convert', 'resolve', 'check', 'upgrade'];

		switch ($keyword) {
			case in_array($keyword, $postPrefixes):
				return 'POST';
				break;
			case in_array($keyword, $getPrefixes):
				return 'GET';
				break;
			default:
				return 'GET';
				break;
		}
	}

}
