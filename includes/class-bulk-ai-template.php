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
		$template_id         = $this->create( $template );

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
	 * Create a new template
	 *
	 * @param array $template_data contains the name and content for the new template.
	 */
	private function create( array $template_data ): int {

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
