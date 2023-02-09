<?php
/**
 * Class for Bulk AI
 *
 * @package WordPress
 */

namespace bulk_ai;

require_once namespace\PATH . 'includes/class-wp-list-table.php';

/**
 * This class handles the format of the templates table.
 */
class Bulk_AI_List_Table extends WP_List_Table {

	/**
	 * Call the parent constructor and assign the headers
	 */
	public function __construct() {
		parent::__construct();
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);
	}

	/**
	 * Return the column names.
	 */
	public function get_columns(): array {
		return array( 'post_name' => 'Template name' );
	}

	/**
	 * Get the templates.
	 */
	public function prepare_items(): void {

		$args = array(
			'post_type' => 'bulk-ai-template',
		);

		//phpcs:ignore
		if ( isset( $_GET['paged'] ) && is_numeric( $_GET['paged'] ) ) {

			//phpcs:ignore
			$args['paged'] = $_GET['paged'];

		}

		$query          = new \WP_Query( $args );
		$template_posts = $query->get_posts();
		$templates      = array();

		foreach ( $template_posts as $post ) {

			$templates[] = $post->to_array();

		}

		$this->items = $templates;

		$post_count = wp_count_posts( 'bulk-ai-template' );

		$this->set_pagination_args(
			array(
				'total_items' => $post_count->private,
				'per_page'    => get_option( 'posts_per_page' ),
			)
		);
	}

	/**
	 * Echo each template in a row.
	 *
	 * @param array  $item the current item.
	 * @param string $column_name the column name.
	 */
	protected function column_default( $item, $column_name ): void {

		$edit_template_form_url = wp_nonce_url(
			admin_url( 'admin.php?page=bulk-ai-page&view=edit-template-form&template-id=' . $item['ID'] ),
			'bulk-ai-show-edit-template-form' . $item['ID'],
			'bulk-ai-nonce'
		);

		echo '<strong><a href="' . esc_url( $edit_template_form_url ) . '">' .
			esc_html( $item[ $column_name ] ) .
			'</a></strong>';

	}

}
