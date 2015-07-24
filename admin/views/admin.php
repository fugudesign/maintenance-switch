<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Maintenance_Switch
 * @author    Fugu <info@fugu.fr>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2015 Fugu
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
	<!-- Ajoute 2 champs cachés pour savoir comment rediriger l'utilisateur -->
	<? wp_nonce_field('update-options'); ?>
	
	<table class="form-table">


	<tr><!-- Option: Roles exception -->
	<th scope="row">
		<label for="ms_allowed_roles"><?php _e( 'Exception for roles:', 'maintenance-switch' ); ?></label>
	</th>
	<td>
		<?php
			global $wp_roles;
			if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
			$roles = $wp_roles->get_names();
			$roles_option_value = (array) get_option( 'ms_allowed_roles' );
			foreach ($roles as $role_value => $role_name) {
				$checked = in_array( $role_value, $roles_option_value ) ? 'checked' : '';
				echo '<p class="inline-checkbox"><input name="ms_allowed_roles[]" type="checkbox" value="' . $role_value . '" ' . $checked . '>'.$role_name.'</p>';
		  	}
		?>
		<p class="description"><?php _e( 'The user roles can bypass the maintenance mode and see the site like online.', 'maintenance-switch' ); ?></p>
	</td>
	</tr>
	
	<tr><!-- Option: HTML Code -->
	<th scope="row">
		<label for="ms_maintenance_page_html"><?php _e( 'Maintenance page HTML:', 'maintenance-switch' ); ?></label>
	</th>
	<td>
		<textarea class="" id="ms_maintenance_page_html" name="ms_maintenance_page_html" cols="70" rows="30"><?php echo get_option( 'ms_maintenance_page_html' ) ?></textarea>
		<p class="description"><?php _e( 'The entire HTML code of the maintenance page.', 'bruther-pack' ); ?></p>
	</td>
	</tr>

	
	</table>
	
	<!-- Mise à jour des valeurs -->
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="ms_maintenance_page_html,ms_allowed_roles" />
	
	<!-- Bouton de sauvegarde -->
	<p class="submit">
		<input class="button-primary" type="submit" value="<?php _e('Save Changes'); ?>" />
	</p>
	</form>

</div>
