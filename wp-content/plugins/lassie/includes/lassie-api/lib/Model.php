<?php

namespace Lassie;

use Lassie\{ BaseModule, Helpers };

class Model extends BaseModule
{	
	
	/**
	 * Magically process method calls.
	 * @param  String method    Method name
	 * @param  Array args       Method arguments.
	 */
	public static function __callStatic($method, $args)
	{
		$lassie = $args[0];
		$params = count($args) > 1 ? $args[1] : [];

		if (!($lassie instanceof Instance)) {
			throw new \Exception('First argument must be Lassie\Instance object.');
		}

		$method = Helpers::to_snake_case($method);
		$type = Helpers::getMethodType($method);

		$classPath = explode('\\', get_called_class());
		$params = array_merge([
			'model_name' => Helpers::to_snake_case(end($classPath)),
			'method_name' => $method,
		], $params);

		return $lassie->performRequest($type, 'model', $params);
	}

	/**
	 * Autoloader for the Lassie\Model namespace. As model classes exist within
	 * the imaginary Lassie\Model namespace, these classes must be generated on
	 * the fly.
	 * 
	 * @param String class		Class to be loaded (inc. namespace).
	 */
	public static function autoloader($class)
	{
		$prefix = 'Lassie\Model';
		if (($pos = strpos($class, $prefix)) === false) return;

		$model = substr($class, strlen($prefix) + 1);

		// I'm sorry but I think this is a legit use of eval
		$classGenerator = <<<GEN
			namespace Lassie\Model;
			use Lassie\Model;
			class $model extends Model { }
GEN;

		eval($classGenerator);	
	}

}

spl_autoload_register(__NAMESPACE__ . '\Model::autoloader');
