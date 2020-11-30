<?php
namespace Lassie;

class Instance
{
	
	/**
	 * Lassie API Instance constructor.
	 * @param String apiHost   API host, e.g. http://lassie.example.cloud/api
	 * @param String apiKey    API key
	 * @param String apiSecret API secret
	 * @param Bool Log         Should requests be logged to console
	 */
	public function __construct($apiHost, $apiKey, $apiSecret, $log = false)
	{
		$this->apiHost = $apiHost;
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->log = $log;
	}
	
	/**
	 * Checks whether the instance API keys are valid.
	 * @return  Boolean  API keys are valid
	 */
	public function validate()
	{
		$status = true;
		try {
			$request = $this->performRequest('GET', 'get_validity');
		} catch (Exception $err) {
			return false;
		}
		if (!$request->status) $status = false;
		return $status;
	}

	/**
	 * Build and perform a request.
	 * @param  String   method   HTTP request method
	 * @param  String   endpoint API endpoint (appended to API_HOST)
	 * @param  Array    params   Any parameters to be passed with the request
	 */
	public function performRequest($method, $url, $params = [])
	{
		$endpoint = $this->apiHost . '/' . $url;
		$data = array_merge($params, $this->generateHashArgs());

		return self::_performRequest($method, $endpoint, $data, $this->log);
	}

	/**
	 * Perform a request.
	 * @param  String   method   HTTP request method
	 * @param  String   url      Request URL
	 * @param  Array    data     Any parameters to be passed with the request
	 */
	public static function _performRequest($method, $url, $data, $log = false)
	{
		if ($log) {
			var_dump($method, $url, $data);
		}
		
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => $method,
		]);

		switch ($method) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				curl_setopt_array($curl, [
					CURLOPT_POSTFIELDS => $data,
					CURLOPT_URL => $url
				]);
				break;
			case 'GET':
			default:
				$serialized = http_build_query($data);
				curl_setopt($curl, CURLOPT_URL, $url . '?' . $serialized);
				break;
		}

		$response = curl_exec($curl);
		if (!$response) {
			throw new \Exception(curl_error($curl));
		}
		curl_close($curl);

		$parsed = json_decode($response);
		if ($parsed === null && json_last_error() !== JSON_ERROR_NONE) {
			throw new \Exception('Could not parse Lassie response: '. $response);
		}
		return $parsed;
	}

	/**
	 * Generates request parameters required for Lassie API authentication.
	 * @return Array [ api_key, api_hash, api_hash_content ]
	 */
	public function generateHashArgs()
	{
		$hashContent = md5(random_bytes(16));
		$hash = hash_hmac('sha256', $this->apiKey . ':' . $hashContent, $this->apiSecret);
	
		return [
			"api_key" => $this->apiKey,
			"api_hash_content" => $hashContent,
			"api_hash" => base64_encode($hash)
		];
	}

}
