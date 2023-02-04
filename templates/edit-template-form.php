<?php
/**
 * This is the form to create new templates.
 *
 * @package WordPress
 */

?>
<h1>Edit template</h1>
<?php
	//phpcs:ignore 
	if ( isset( $_GET['result-code'] ) && '0' === $_GET['result-code'] ) : 
	?>
	<h4>There has been an error</h4>
	<?php
	//phpcs:ignore
	elseif( isset( $_GET['result-code'] ) && '1' === $_GET['result-code'] ) : 
		?>
	<h4>The new template was created successfully</h4>
		<?php
	//phpcs:ignore
	elseif( isset( $_GET['result-code'] ) && '2' === $_GET['result-code'] ) : 
		?>
	<h4>The new template was updated successfully</h4>
<?php endif; ?>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<label for="template-name">Template name</label><br>
	<input type="text" name="template-name" id="template-name" value="<?php echo wp_kses( $template_data['post_title'], 'post' ); ?>"><br>
	<label for="template-content">Content</label><br>
	<textarea name="template-content" id="template-content" cols="20" rows="5"><?php echo wp_kses( $template_data['post_content'], 'post' ); ?></textarea><br>
	<input type="hidden" name="template-id" value="<?php echo wp_kses( $template_data['ID'], 'post' ); ?>">
	<input type="hidden" name="action" value="bulk_ai_update_template">
	<?php wp_nonce_field( 'bulkai-update-template', 'bulkai-update-template-nonce' ); ?>
	<?php submit_button(); ?>
</form>
