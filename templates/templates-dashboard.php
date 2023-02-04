<?php
/**
 * This is the main dashboard, it has the list of templates
 *
 * @package WordPress
 */

$new_template_form_url = wp_nonce_url(
	admin_url( 'admin.php?page=bulk-ai-page&view=new-template-form' ),
	'bulk-ai-show-new-template-form',
	'bulk-ai-nonce'
);
?>
<div class="wrap">
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
<h1 class="wp-heading-inline">Templates</h1>
<a href="<?php echo esc_url( $new_template_form_url ); ?>" class="page-title-action">Add New</a>
<?php
	$list_table->prepare_items();
	$list_table->display();
?>
</div>
