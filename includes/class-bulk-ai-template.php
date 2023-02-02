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
	 */
	public function create(): int {

		$template_id = wp_insert_post(
			array(
				'post_title'   => 'Title',
				'post-content' => 'content',
				'post_status'  => 'publish',
				'post_type'    => 'bulk-ai-template',
			)
		);

		if ( is_wp_error( $template_id ) ) {
			return 0;
		}

		return $template_id;

	}

}
