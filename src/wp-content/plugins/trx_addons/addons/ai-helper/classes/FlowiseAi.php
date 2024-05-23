<?php
namespace TrxAddons\AiHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to make queries to the OpenAi API
 */
class FlowiseAi extends Singleton {

	/**
	 * The object to log queries to the API
	 *
	 * @access private
	 * 
	 * @var Logger  The object to log queries to the API
	 */
	var $logger = null;
	var $logger_section = 'flowise-ai';

	/**
	 * The object of the API
	 *
	 * @access private
	 * 
	 * @var api  The object of the API
	 */
	var $api = null;

	/**
	 * Plugin constructor.
	 *
	 * @access protected
	 */
	protected function __construct() {
		parent::__construct();
		$this->logger = Logger::instance();
	}

	/**
	 * Return an object of the API
	 * 
	 * @param string $token  API token for the API
	 * 
	 * @return api  The object of the API
	 */
	public function get_api( $host = '', $token = '' ) {
		if ( empty( $this->api ) ) {
			if ( empty( $host ) ) {
				$host = $this->get_host();
			}
			if ( empty( $token ) ) {
				$token = $this->get_token();
			}
			if ( ! empty( $host ) && ! empty( $token ) ) {
				$this->api = new \FlowiseAi\Api\Query( $host, $token );
			}
		}
		return $this->api;
	}

	/**
	 * Return an API token for the API from the plugin options.
	 * This method is a wrapper for the get_token() method to allow to override it in the child classes.
	 * 
	 * @access public
	 * 
	 * @return string  API key for the API
	 */
	public function get_api_key() {
		return $this->get_token();
	}

	/**
	 * Return an API token for the API from the plugin options
	 * 
	 * @access protected
	 * 
	 * @return string  API token for the API
	 */
	protected function get_token() {
		return trx_addons_get_option( 'ai_helper_token_flowise_ai' );
	}

	/**
	 * Return a host URL for the API from the plugin options
	 * 
	 * @access protected
	 * 
	 * @return string  Host URL for the API
	 */
	protected function get_host() {
		return trx_addons_get_option( 'ai_helper_host_flowise_ai' );
	}

	/**
	 * Return a maximum number of tokens in the prompt and response for specified model
	 *
	 * @access static
	 * 
	 * @param string $model  Model name (flow id) for the API
	 * 
	 * @return int  The maximum number of tokens in the prompt and response for specified model
	 */
	static function get_max_tokens( $model ) {
		$max_tokens = 0;
		if ( ! empty( $model ) ) {
			$model = str_replace( 'flowise-ai/', '', $model );
			$models = Lists::get_flowise_ai_chat_models();
			if ( ! empty( $models ) && is_array( $models ) ) {
				foreach ( $models as $k => $v ) {
					if ( $k == $model ) {
						$max_tokens = $v['max_tokens'];
						break;
					}
				}
			}
		}
		return $max_tokens;
	}

	 /**
	 * Send a query to the API
	 *
	 * @access public
	 * 
	 * @param array $args  Query arguments
	 * 
	 * @return array  Response from the API
	 */
	public function query( $args = array(), $params = array() ) {
		$args = array_merge( array(
			'host'  => $this->get_host(),
			'token' => $this->get_token(),
			'prompt' => '',
			'system_prompt' => '',
			'frequency_penalty' => 0,
			'presence_penalty' => 0,
		), $args );

		$args['max_tokens'] = ! empty( $args['max_tokens'] )
								? min( $args['max_tokens'], self::get_max_tokens( $args['model'] ) )
								: self::get_max_tokens( $args['model'] );

		$args['messages'] = array();
		if ( ! empty( $args['prompt'] ) ) {
			$args['messages'][] = array(
									'role' => 'user',
									'content' => $args['prompt']
								);
		}

		$response = false;

		if ( ! empty( $args['token'] ) && ! empty( $args['host'] ) && count( $args['messages'] ) > 0 ) {

			$args = $this->prepare_args( $args );

			if ( $args['max_tokens'] > 0 ) {
				$chat_args = $this->override_args( array(
					'question' => $args['messages'][ count( $args['messages'] ) - 1 ]['content'],
				), $args );

				$api = $this->get_api( $args['host'], $args['token'] );

				$response = $api->query( $chat_args );
				if ( is_string( $response ) ) {
					$response = trim( $response );
					if ( substr( trim( $response ), 0, 1 ) == '{' ) {
						$response = json_decode( $response, true );
						$response = $this->prepare_response( $response, $chat_args );
						$this->logger->log( $response, 'query', $args, $this->logger_section );
					}
				} else {
					$response = false;
				}
			}
		}

		return $response;

	}

	/**
	 * Send a chat messages to the API
	 *
	 * @access public
	 * 
	 * @param array $args  Query arguments
	 * 
	 * @return array  Response from the API
	 */
	public function chat( $args = array(), $params = array() ) {
		$args = array_merge( array(
			'host'  => $this->get_host(),
			'token' => $this->get_token(),
			'model' => '',
			'messages' => array(),
			'system_prompt' => '',
			'frequency_penalty' => 0,
			'presence_penalty' => 0,
		), $args );

		$args['max_tokens'] = ! empty( $args['max_tokens'] )
								? min( $args['max_tokens'], self::get_max_tokens( $args['model'] ) )
								: self::get_max_tokens( $args['model'] );

		$response = false;

		if ( ! empty( $args['token'] ) && ! empty( $args['host'] ) && ! empty( $args['model'] ) && count( $args['messages'] ) > 0 ) {
			$args = $this->prepare_args( $args );
			
			if ( $args['max_tokens'] > 0 ) {
				$chat_args = $this->override_args( array(
					'question' => $args['messages'][ count( $args['messages'] ) - 1 ]['content'],
					'model' => $args['model'],
				), $args );
				
				$api = $this->get_api( $args['host'], $args['token'] );

				$response = $api->query( $chat_args );

				if ( is_string( $response ) ) {
					$response = trim( $response );
					if ( substr( trim( $response ), 0, 1 ) == '{' ) {
						$response = json_decode( $response, true );
						$response = $this->prepare_response( $response, $chat_args );
						$this->logger->log( $response, 'chat', $args, $this->logger_section );
					}
				} else {
					$response = false;
				}
			}
		}

		return $response;

	}

	/**
	 * Convert a response object to the format, compatible with OpenAI API response
	 */
	protected function prepare_response( $response, $args ) {
		if ( ! empty( $response['text'] ) ) {
			$prompt_tokens = $this->count_tokens( $args['question'] );
			$completion_tokens = $this->count_tokens( $response['text'] );
			$response = array(
				'finish_reason' => 'stop',
				'model' => ! empty( $args['model'] ) ? $args['model'] : __( 'FlowiseAI Chatbot', 'trx_addons' ),
				'usage' => array(
							'prompt_tokens' => $prompt_tokens,
							'completion_tokens' => $completion_tokens,
							'total_tokens' => $prompt_tokens + $completion_tokens,
							),
				'choices' => array(
								array(
									'message' => array(
										'content' => $response['text']
									)
								)
							)
			);
		}
		return $response;
	}

	/**
	 * Prepare args for the API: limit the number of tokens
	 *
	 * @access private
	 * 
	 * @param array $args  Query arguments
	 * 
	 * @return array  Prepared query arguments
	 */
	private function prepare_args( $args = array() ) {
		if ( ! empty( $args['messages'] ) && is_array( $args['messages'] ) ) {
			$tokens_total = 0;
			foreach ( $args['messages'] as $k => $message ) {
				// Remove all HTML tags
				//$message['content'] = strip_tags( $message['content'] );
				// Remove duplicate newlines
				$message['content'] = preg_replace( '/[\\r\\n]{2,}/', "\n", $message['content'] );
				// Remove all Gutenberg block comments
				$message['content'] = preg_replace( '/<!--[^>]*-->/', '', $message['content'] );
				// Count tokens
				$tokens_total += $this->count_tokens( $message['content'] );
				// Save the message
				$args['messages'][ $k ]['content'] = $message['content'];
			}
			$args['max_tokens'] = max( 0, $args['max_tokens'] - $tokens_total );
		}
		if ( ! empty( $args['model'] ) ) {
			$args['model'] = str_replace( 'flowise-ai/', '', $args['model'] );
		}
		return $args;
	}

	/**
	 * Add OverrideConfig with chat args
	 *
	 * @access private
	 * 
	 * @param array $args  Query arguments
	 * 
	 * @return array  Query arguments with OverrideConfig
	 */
	private function override_args( $chat_args = array(), $args = array() ) {
		$override = array();
		if ( ! empty( $args['system_prompt'] ) ) {
			$override['systemMessagePrompt'] = $args['system_prompt'];
		}
		if ( ! empty( $args['max_tokens'] ) ) {
			$override['maxTokens'] = $args['max_tokens'];
		}
		if ( ! empty( $args['temperature'] ) ) {
			$override['temperature'] = $args['temperature'];
		}
		if ( ! empty( $args['frequency_penalty'] ) ) {
			$override['frequencyPenalty'] = $args['frequency_penalty'];
		}
		if ( ! empty( $args['presence_penalty'] ) ) {
			$override['presencePenalty'] = $args['presence_penalty'];
		}
		if ( ! empty( $args['override_config'] ) ) {
			$json = json_decode( $args['override_config'], true );
			if ( ! empty( $json ) && is_array( $json ) ) {
				$override = array_merge( $override, $json );
			}
		}
		if ( count( $override ) > 0 ) {
			$chat_args['overrideConfig'] = $override;
		}
		return $chat_args;
	}

	/**
	 * Calculate the number of tokens for the API
	 * 
	 * @access private
	 * 
	 * @param string $text  Text to calculate
	 * 
	 * @return int  Number of tokens for the API
	 */
	private function count_tokens( $text ) {
		$tokens = 0;

		// Way 1: Get number of words and multiply by coefficient		
		// $words = count( explode( ' ', $text ) );
		// $coeff = strpos( $text, '<!-- wp:' ) !== false ? $this->blocks_to_tokens_coeff : $this->words_to_tokens_coeff;
		// $tokens = round( $words * $coeff );

		// Way 2: Get number of tokens via utility function with tokenizer
		// if ( ! function_exists( 'gpt_encode' ) ) {
		// 	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/vendors/gpt3-encoder/gpt3-encoder.php';
		// }
		// $tokens = count( (array) gpt_encode( $text ) );

		// Way 3: Get number of tokens via class tokenizer (same algorithm)
		$tokens = count( (array) \Rahul900day\Gpt3Encoder\Encoder::encode( $text ) );

		return $tokens;
	}

}
