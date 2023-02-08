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
<div class="container">
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<div class="input-item">
			<label class="input-item__label" for="template-name">Template name</label><br>
			<input class="input-item__text-input" type="text" name="template-name" id="template-name"><br>
		</div>
		<table class="section-table">
			<thead>
				<tr>
					<th class="section-table__name">
						Section
					</th>
					<th class="section-table__content">
						Prompt
					</th>
					<th class="section-table__action"></th>
				</tr>
			</thead>
			<tbody id="table-body">
				<tr>
					<td>
						<input class="input-item__text-input" type="text" name="section-name[]">
					</td>
					<td>
						<textarea class="input-item__textarea" name="section-content[]"></textarea>
					</td>
					<td>
						<div class="button input-item__button input-item__button--red delete-button">Delete</div>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="table-footer">
			<div id="add-item-button" class="button input-item__button input-item__button--green">Add section</div>
		</div>
		<?php wp_editor( '', 'content', array( 'media_buttons' => false ) ); ?>
		<input type="hidden" name="action" value="bulk_ai_create_template">
		<?php wp_nonce_field( 'bulkai-create-template', 'bulkai-create-template-nonce' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
