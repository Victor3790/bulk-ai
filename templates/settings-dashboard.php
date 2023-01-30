<?php
/**
 * This file defines the settings view.
 *
 * @package WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {

	die();

}

?>

<form action="options.php" method="post">
	<?php
		settings_fields( 'bulk-ai-settings-group' );
		do_settings_sections( 'bulk-ai-settings-page' );
		submit_button();
	?>
</form>
