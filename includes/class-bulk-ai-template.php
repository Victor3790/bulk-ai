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
	 * Create a new template.
	 */
	public function create_template(): void {

		if ( empty( $_POST['bulkai-create-template-nonce'] ) ) {

			$new_template_form_url = $this->get_new_template_form_url();
			$redirect_url          = add_query_arg( 'result-code', '0', $new_template_form_url );

			wp_safe_redirect( $redirect_url );
			exit;

		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['bulkai-create-template-nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'bulkai-create-template' ) ) {

			$new_template_form_url = $this->get_new_template_form_url();
			$redirect_url          = add_query_arg( 'result-code', '0', $new_template_form_url );

			wp_safe_redirect( $redirect_url );
			exit;

		}

		if ( empty( $_POST['template-name'] ) || empty( $_POST['template-content'] ) ) {

			$new_template_form_url = $this->get_new_template_form_url();
			$redirect_url          = add_query_arg( 'result-code', '0', $new_template_form_url );

			wp_safe_redirect( $redirect_url );
			exit;

		}

		$template['name']    = sanitize_text_field( wp_unslash( $_POST['template-name'] ) );
		$template['content'] = sanitize_text_field( wp_unslash( $_POST['template-content'] ) );
		$template_id         = $this->insert( $template );

		if ( 0 === $template_id ) {

			$new_template_form_url = $this->get_new_template_form_url();
			$redirect_url          = add_query_arg( 'result-code', '0', $new_template_form_url );

			wp_safe_redirect( $redirect_url );
			exit;

		}

		$edit_url = add_query_arg(
			array(
				'page'          => 'bulk-ai-page',
				'view'          => 'edit-template-form',
				'template-id'   => $template_id,
				'result-code'   => '1',
				'bulk-ai-nonce' => wp_create_nonce( 'bulk-ai-show-edit-template-form' . $template_id ),
			),
			admin_url( 'admin.php' )
		);

		wp_safe_redirect( $edit_url );
		exit;

	}

	/**
	 * Update a template.
	 */
	public function update_template(): void {

		if ( empty( $_POST['bulkai-update-template-nonce'] ) ) {

			$this->redirect_to_main();

		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['bulkai-update-template-nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'bulkai-update-template' ) ) {

			$this->redirect_to_main();

		}

		if ( empty( $_POST['template-id'] ) ) {

			$this->redirect_to_main();

		}

		$template_id = sanitize_text_field( wp_unslash( $_POST['template-id'] ) );

		if ( empty( $_POST['template-name'] ) || empty( $_POST['template-content'] ) ) {

			$edit_template_form_url = $this->get_edit_template_form_url( $template_id );
			$redirect_url           = add_query_arg( 'result-code', '0', $edit_template_form_url );

			wp_safe_redirect( $redirect_url );
			exit;

		}

		$template['name']    = sanitize_text_field( wp_unslash( $_POST['template-name'] ) );
		$template['content'] = sanitize_text_field( wp_unslash( $_POST['template-content'] ) );
		$template['id']      = $template_id;
		$updated_template_id = $this->insert( $template );

		if ( 0 === $updated_template_id ) {

			$this->redirect_to_main();

		}

		$edit_url     = $this->get_edit_template_form_url( $updated_template_id );
		$redirect_url = add_query_arg( 'result-code', '2', $edit_url );

		wp_safe_redirect( $redirect_url );
		exit;

	}

	/**
	 * Generate a new template form url
	 */
	private function get_new_template_form_url(): string {

		$url = add_query_arg(
			array(
				'page'          => 'bulk-ai-page',
				'view'          => 'new-template-form',
				'bulk-ai-nonce' => wp_create_nonce( 'bulk-ai-show-new-template-form' ),
			),
			admin_url( 'admin.php' )
		);

		return $url;

	}

	/**
	 * Generate an edit template form url
	 *
	 * @param int $template_id The template id to modify.
	 */
	private function get_edit_template_form_url( int $template_id ): string {

		$url = add_query_arg(
			array(
				'page'          => 'bulk-ai-page',
				'view'          => 'edit-template-form',
				'template-id'   => $template_id,
				'bulk-ai-nonce' => wp_create_nonce( 'bulk-ai-show-edit-template-form' . $template_id ),
			),
			admin_url( 'admin.php' )
		);

		return $url;

	}

	/**
	 * Create or updates a template
	 *
	 * @param array $template_data contains the data for the template.
	 */
	private function insert( array $template_data ): int {

		$args = array(
			'post_title'   => $template_data['name'],
			'post_content' => $template_data['content'],
			'post_status'  => 'private',
			'post_type'    => 'bulk-ai-template',
		);

		if ( ! empty( $template_data['id'] ) ) {

			$args['ID'] = $template_data['id'];

		}

		$template_id = wp_insert_post( $args );

		if ( is_wp_error( $template_id ) ) {

			return 0;
		}

		return $template_id;

	}

	/**
	 * Redirect to templates list
	 */
	private function redirect_to_main(): void {

		$main_url     = admin_url( 'admin.php?page=bulk-ai-page' );
		$redirect_url = add_query_arg( 'result-code', '0', $main_url );

		wp_safe_redirect( $redirect_url );
		exit;

	}

}
