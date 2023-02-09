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
class Bulk_AI_Post {

	/**
	 * Create a post based on a template.
	 *
	 * @param array  $article_data Current article data.
	 * @param object $import The import object.
	 * @param object $post_to_update Post object for the post that's being updated.
	 * @param array  $current_xml_node Parsed data for the current import record.
	 */
	public function get_content( $article_data, $import, $post_to_update, $current_xml_node ): array {

		$query = new \WP_Query(
			array(
				'post_type' => 'bulk-ai-template',
				'name'      => $current_xml_node['bai_template'],
			)
		);

		if ( ! $query->have_posts() ) {

			return $article_data;

		}

		$template_data = $query->get_posts()[0]->to_array();

		$template_content      = $template_data['post_content'];
		$raw_template_sections = get_post_meta( $template_data['ID'], 'sections' );
		$template_sections     = json_decode( $raw_template_sections[0], true );

		$content = $this->substitute_sections_in_content( $template_sections, $template_content );

		$article_data['post_content'] = $content;
		return $article_data;

	}

	/**
	 * Substitute the sections in the content.
	 *
	 * @param array  $sections the sections to substitute in the content.
	 * @param string $content the content.
	 */
	private function substitute_sections_in_content( array $sections, string $content ): string {

		foreach ( $sections as $section ) {

			$section_name = '{' . $section['name'] . '}';

			$new_content = str_replace( $section_name, $section['content'], $content );

		}

		return $new_content;

	}

}
