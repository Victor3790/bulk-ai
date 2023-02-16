<?php
/**
 * File for the Open AI API connection.
 *
 * @package WordPress
 */

namespace bulk_ai;

/**
 * This class handles connection with Open AI.
 */
class Open_AI_Api_Connection {
	/**
	 * Get the completion based on a prompt.
	 *
	 * @param  string $prompt the prompt to send to Open AI.
	 * @throws \Exception If The Open AI API KEY was not set.
	 */
	public function get_completion( string $prompt ): string {

		$wp_http_curl = new \WP_Http_Curl();

		$access_token = get_option( 'bulk-ai-api-token' );

		if ( ! $access_token ) {

			throw new \Exception( 'Bulk AI error: No API Key set.', 1 );

		}

		$response = $wp_http_curl->request(
			'https://api.openai.com/v1/completions',
			array(
				'method'  => 'POST',
				'timeout' => 60,
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $access_token,
				),
				'body'    => '{
					"model": "text-curie-001",
					"prompt": "' . $prompt . '",
					"max_tokens": 1048
				  }',
			)
		);

		if ( is_wp_error( $response ) ) {

			throw new \Exception( 'Bulk AI error: Invalid response. Wp message: ' . $response->get_error_message(), 2 );

		}

		$body = $response['body'];

		$data = json_decode( $body, true );

		if ( empty( $data ) ) {

			throw new \Exception( 'Bulk AI error: Invalid response.', 2 );

		}

		if ( true === WP_DEBUG ) {

			//phpcs:ignore
			error_log( print_r( 'Open AI usage data:', true ) );
			//phpcs:ignore
			error_log( print_r( 'Prompt tokens: ' . $data['usage']['prompt_tokens'], true ) );
			//phpcs:ignore
			error_log( print_r( 'Completion tokens: ' . $data['usage']['completion_tokens'], true ) );
			//phpcs:ignore
			error_log( print_r( 'Total tokens: ' . $data['usage']['total_tokens'], true ) );

		}

		$text = trim( $data['choices'][0]['text'] );

		return $text;

	}
}
