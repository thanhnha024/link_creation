<?php

namespace FlowiseAi\Api;

use Exception;

class Query {
	private string $api_host = "";
	private string $api_key = "";
	private int $timeout = 0;

	public function __construct( $api_host, $api_key )	{
		$this->api_host = untrailingslashit( $api_host );
		$this->api_key = $api_key;
	}

	/**
	 * Return an URL to the API
	 * 
	 * @param string $chatId  The chat ID
	 * 
	 * @return string  The URL to the API
	 */
	public function apiUrl( $chatId ) {
		return "{$this->api_host}/api/v1/prediction/{$chatId}";
	}

	private function checkArgs( $args ) {
		unset( $args['model'] );
		return apply_filters( 'trx_addons_filter_ai_helper_check_args', $args, 'flowise-ai' );
	}

	/**
	 * Generate an answer for a text prompt
	 * 
	 * @param array $opts  The options for the request
	 * 
	 * @return bool|string  The response from the API
	 */
	public function query( $opts ) {
		// Get the API URL
		$url = $this->apiUrl( $opts['model'] );
		// Send the request
		return $this->sendRequest( $url, 'POST', $this->checkArgs( $opts ) );
	}

	/**
	* @param  string  $url
	* @param  string  $method
	* @param  array   $opts
	* @return bool|string
	*/
	private function sendRequest( string $url, string $method, array $opts = array() ) {
		if ( empty( $this->api_key ) ) {
			throw new Exception( 'API key is missing' );
		}
		// Get a key
		$key = $this->api_key;
		if ( ! empty( $opts['key'] ) ) {
			$key = $opts['key'];
			unset( $opts['key'] );
		}
		// Get a content type
		$type = 'application/json';
		if ( ! empty( $opts['type'] ) ) {
			$type = $opts['type'];
			unset( $opts['type'] );
		}
		$curl_info = array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => $this->timeout,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_CUSTOMREQUEST  => $method,
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: {$type}",
				'Accept: application/json',
				"Authorization: Bearer {$key}",
			),
		);

		if ( $method === 'POST' ) {
			$curl_info[CURLOPT_POSTFIELDS] = $type == 'application/json'
												? json_encode( $opts )
												: $opts;
		}

		$curl = curl_init();
		curl_setopt_array($curl, $curl_info);
		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

}
