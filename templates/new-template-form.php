<?php
/**
 * This is the form to create new templates.
 *
 * @package WordPress
 */

?>
<h1>New template</h1>
<form action="" method="post">
	<label for="test">test</label><br>
	<textarea name="test" id="" cols="30" rows="10"></textarea><br>
	<?php wp_nonce_field( 'bulkai-save-template', 'bulkai-save-template-nonce' ); ?>
	<input name="save-template" type="submit" value="Guardar">
</form>
