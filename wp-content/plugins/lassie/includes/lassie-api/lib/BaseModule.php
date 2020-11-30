<?php
namespace Lassie;

use Lassie\{ Instance, Helpers };

class BaseModule
{

	// module endpoint, to be redefined for custom endpoints
	//public static $endpoint = '';

	// module endpoint separator, redefined for unusual endpoints
	public static $urlSeparator = '/';

	/**
	 * Magically process method calls.
	 * @param  String method    Method name
	 * @param  Array args       Method arguments (args[1] should be Lassie\Instance).
	 */
	public static function __callStatic($method, $args)
	{
		$lassie = $args[0];

		if (!($lassie instanceof Instance)) {
			throw new \Exception('First argument must be Lassie\Instance object.');
		}

		$method = Helpers::to_snake_case($method);
		$type = Helpers::getMethodType($method);

		// Module.create -> POST module/create
		// Module.createSomething -> POST module/something
		if (strpos($method, '_') !== false) {
			$method = implode('_', array_slice(explode('_', $method), 1));
		}

		$params = count($args) > 1 ? $args[1] : [];

		// determine endpoint, either custom or based on namespace
		$endpoint = isset(static::$endpoint) ? static::$endpoint : (function () {

			$classPath = explode('\\', strtolower(get_called_class()));
			array_shift($classPath);

			return join('/', $classPath);
		})();

		$url = $endpoint . static::$urlSeparator . $method;

		return $lassie->performRequest($type, $url, $params);
	}

}
