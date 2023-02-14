<?php
/**
 * File for the BulkAI_Post class.
 *
 * @package WordPress
 */

namespace bulk_ai;

/**
 * This class handles the creation of posts.
 */
class Bulk_AI_Content {

	/**
	 * Substitute node data in sections.
	 *
	 * @param array $node_data the node data for the current post being created.
	 * @param array $sections the sections in the template.
	 */
	public function replace_node_data_in_sections( array $node_data, array $sections ): array {

		if ( empty( $node_data ) || empty( $sections ) ) {

			return $sections;

		}

		if ( isset( $node_data['bai_template'] ) ) {

			unset( $node_data['bai_template'] );

		}

		$node_keys = array_keys( $node_data );

		foreach ( $node_keys as $array_key => $node_key ) {

			$node_keys[ $array_key ] = '{' . $node_key . '}';

		}

		foreach ( $sections as &$section ) {

			$section['content'] = str_replace( $node_keys, $node_data, $section['content'] );

		}

		return $sections;

	}


	/**
	 * Substitute node data in content.
	 *
	 * @param array  $node_data the node data for the current post being created.
	 * @param string $content the content of the template.
	 */
	public function replace_node_data_in_content( array $node_data, string $content ): string {

		if ( empty( $node_data ) || empty( $content ) ) {

			return $content;

		}

		if ( isset( $node_data['bai_template'] ) ) {

			unset( $node_data['bai_template'] );

		}

		$node_keys = array_keys( $node_data );

		foreach ( $node_keys as $array_key => $node_key ) {

			$node_keys[ $array_key ] = '{' . $node_key . '}';

		}

		$new_content = str_replace( $node_keys, $node_data, $content );

		return $new_content;

	}

	/**
	 * Substitute the sections in the content.
	 *
	 * @param array  $sections the sections to substitute in the content.
	 * @param string $content the content.
	 */
	public function replace_sections_in_content( array $sections, string $content ): string {

		foreach ( $sections as $section ) {

			$section_name = '{' . $section['name'] . '}';

			$new_content = str_replace( $section_name, $section['content'], $content );

		}

		return $new_content;

	}

}
