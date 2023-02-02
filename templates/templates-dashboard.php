<?php
/**
 * This is the main dashboard, it has the list of templates
 *
 * @package WordPress
 */

?>
<div class="wrap">
<h1 class="wp-heading-inline">Templates</h1>
<a href="<?php echo esc_url( admin_url( 'admin.php?page=bulk-ai-page&view=template-form' ) ); ?>" class="page-title-action">Add New</a>
<?php
	$list_table->prepare_items();
	$list_table->display();
?>
</div>
