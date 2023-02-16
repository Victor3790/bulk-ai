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
				'timeout' => 10,
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $access_token,
				),
				'body'    => '{
					"model": "text-davinci-003",
					"prompt": "' . $prompt . '",
					"max_tokens": 50
				  }',
			)
		);

		$body = $response['body'];

		$data = json_decode( $body, true );

		if ( empty( $data ) ) {

			throw new \Exception( 'Bulk AI error: Invalid response.', 2 );

		}

		$text = trim( $data['choices'][0]['text'] );

		return $text;

	}
}
