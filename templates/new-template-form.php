<?php
/**
 * This is the form to create new templates.
 *
 * @package WordPress
 */

?>
<h1>New template</h1>
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
<?php endif; ?>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<label for="template-name">Template name</label><br>
	<input type="text" name="template-name" id="template-name"><br>
	<label for="template-content">Content</label><br>
	<textarea name="template-content" id="template-content" cols="20" rows="5"></textarea><br>
	<input type="hidden" name="action" value="bulk_ai_create_template">
	<?php wp_nonce_field( 'bulkai-create-template', 'bulkai-create-template-nonce' ); ?>
	<?php submit_button(); ?>
</form>
