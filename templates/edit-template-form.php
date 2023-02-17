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
<div class="container">
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<div class="input-item">
			<label class="input-item__label" for="template-name">Template name</label><br>
			<input class="input-item__text-input" type="text" name="template-name" value="<?php echo wp_kses( $template_data['post_title'], 'post' ); ?>" id="template-name"><br>
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
			<?php foreach ( $template_data['sections'] as $section ) : ?>

				<tr>
					<td>
						<input class="input-item__text-input" type="text" name="section-name[]" value="<?php echo wp_kses( $section['name'], 'post' ); ?>">
					</td>
					<td>
						<textarea class="input-item__textarea" name="section-content[]"><?php echo wp_kses( $section['content'], 'post' ); ?></textarea>
					</td>
					<td>
						<div class="button input-item__button input-item__button--red delete-button">Delete</div>
					</td>
				</tr>

			<?php endforeach; ?>

			</tbody>
		</table>
		<div class="table-footer">
			<div id="add-item-button" class="button input-item__button input-item__button--green">Add section</div>
		</div>
		<label for="ai_model">Open AI model.</label><br>
		<select name="ai-model" id="ai-model">
			<?php
			if ( empty( $template_data['model'] ) ) {
				$template_data['model'] = array( '0' );
			}
			?>
			<option value="0">Select a model</option>

			<option value="1" 
				<?php
				if ( '1' === $template_data['model'][0] ) {
					echo 'selected';
				}
				?>
			>
				text-davinci-003
			</option>

			<option value="2" 
				<?php
				if ( '2' === $template_data['model'][0] ) {
					echo 'selected';
				}
				?>
			>
				text-curie-001
			</option>
			<option value="3" 
				<?php
				if ( '3' === $template_data['model'][0] ) {
					echo 'selected';
				}
				?>
			>
				text-babbage-001
			</option>
			<option value="4" 
				<?php
				if ( '4' === $template_data['model'][0] ) {
					echo 'selected';
				}
				?>
			>
				text-ada-001
			</option>

		</select>
		<?php wp_editor( $template_data['post_content'], 'content', array( 'media_buttons' => false ) ); ?>
		<input type="hidden" name="action" value="bulk_ai_update_template">
		<input type="hidden" name="template-id" value="<?php echo wp_kses( $template_data['ID'], 'post' ); ?>">
		<?php wp_nonce_field( 'bulkai-update-template-' . $template_data['ID'], 'bulkai-update-template-nonce' ); ?>
		<?php submit_button(); ?>
	</form>
	<form id="bulk-ai-delete-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="bulk_ai_delete_template">
		<input type="hidden" name="template-id" value="<?php echo wp_kses( $template_data['ID'], 'post' ); ?>">
		<?php wp_nonce_field( 'bulkai-delete-template-' . $template_data['ID'], 'bulkai-delete-template-nonce' ); ?>
		<?php submit_button( 'Delete template', 'delete-template-button button-primary' ); ?>
	</form>
</div>
