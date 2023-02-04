<?php
/**
 * File for the BulkAI_Template class.
 *
 * @package WordPress
 */

namespace bulk_ai;

/**
 * This class handles the Bulk AI template post type.
 */
class Bulk_AI_Template {

	/**
	 * Create a new template
	 *
	 * @param array $template_data contains the name and content for the new template.
	 */
	public function create( array $template_data ): int {

		$template_id = wp_insert_post(
			array(
				'post_title'   => $template_data['name'],
				'post_content' => $template_data['content'],
				'post_status'  => 'private',
				'post_type'    => 'bulk-ai-template',
			)
		);

		if ( is_wp_error( $template_id ) ) {

			return 0;
		}

		return $template_id;

	}

}
