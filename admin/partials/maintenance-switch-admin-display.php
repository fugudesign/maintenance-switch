<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/admin/partials
 */
 
global $wp_roles;
if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
$roles = $wp_roles->get_names();
 
?>

<div id="ms-form" class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
	<!-- Ajoute 2 champs cachés pour savoir comment rediriger l'utilisateur -->
	<?php wp_nonce_field('update-options'); ?>
	
	<table class="form-table">


	<tr><!-- Option: Button access roles -->
	<th scope="row">
		<label for="ms_switch_roles"><?php _e( 'Switch ability:', MS_SLUG ); ?></label>
	</th>
	<td>
		<?php
			$roles_option_value = (array) get_option( 'ms_switch_roles' );
			foreach ($roles as $role_value => $role_name) {
				$checked = in_array( $role_value, $roles_option_value ) ? 'checked' : '';
				echo '<p class="inline-checkbox"><input name="ms_switch_roles[]" type="checkbox" value="' . $role_value . '" ' . $checked . '>'.$role_name.'</p>';
		  	}
		?>
		<p class="description"><?php _e( 'The user roles can access the maintenance button in the adminbar and so switch the maintenance mode.', MS_SLUG ); ?></p>
	</td>
	</tr>
	
	<tr><!-- Option: Roles exception -->
	<th scope="row">
		<label for="ms_allowed_roles"><?php _e( 'Bypass ability:', MS_SLUG ); ?></label>
	</th>
	<td>
		<?php
			$roles_option_value = (array) get_option( 'ms_allowed_roles' );
			foreach ($roles as $role_value => $role_name) {
				$checked = in_array( $role_value, $roles_option_value ) ? 'checked' : '';
				echo '<p class="inline-checkbox"><input name="ms_allowed_roles[]" type="checkbox" value="' . $role_value . '" ' . $checked . '>'.$role_name.'</p>';
		  	}
		?>
		<p class="description"><?php _e( 'The user roles can bypass the maintenance mode and see the site like online.', MS_SLUG ); ?></p>
		<br>
		<!-- Option: IP exception -->
		<input class="" id="ms_allowed_ips" name="ms_allowed_ips" size="60" value="<?php echo get_option( 'ms_allowed_ips' ) ?>">
		<button id="addmyip" class="button-primary" data-ip="<?php echo $plugin->get_user_ip(); ?>"><?php _e( 'Add my IP', MS_SLUG) ?></button>
		<p class="description"><?php _e( 'Authorized IPs, comma separated.', MS_SLUG ); ?></p>
	</td>
	</tr>
	
	<tr><!-- Option: HTML Code -->
	<th scope="row">
		<label for="ms_page_html"><?php _e( 'Maintenance page HTML:', MS_SLUG ); ?></label>
	</th>
	<td>
		<textarea class="" id="ms_page_html" name="ms_page_html" cols="70" rows="20"><?php echo get_option( 'ms_page_html' ) ?></textarea>
		<p class="description"><?php _e( 'The entire HTML code of the maintenance page.', MS_SLUG ); ?></p>
	</td>
	</tr>
	
	<tr><!-- Option: Use theme file -->
	<th scope="row">
		<label for="ms_use_theme"><?php _e( 'Use theme file:', MS_SLUG ); ?></label>
	</th>
	<td>
		<?php
			$current_theme = wp_get_theme();
			$theme_file = $current_theme->get_stylesheet_directory() . '/' . MS_THEME_FILENAME;
			$file_exists = file_exists( $theme_file );
		?>
		<p class="inline-checkbox"><input name="ms_use_theme" type="checkbox" value="1" <?php echo ( $file_exists && get_option( 'ms_use_theme' ) ) ? 'checked' : ''; ?> <?php echo $file_exists ? '' : 'disabled'; ?>></p>
		<p class="description inline-description <?php echo $file_exists ? '' : 'disabled'; ?>"><?php _e( 'Use a file in your theme to display maintenance page instead of the HTML field above.', MS_SLUG ); ?></p>
		<p class="infos">
		<span class="<?php echo $file_exists ? 'present' : 'missing'; ?>"> <?php echo $file_exists ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>'; ?> <strong><?php echo $current_theme->Name; ?></strong>: <?php echo MS_THEME_FILENAME; ?> <?php echo $file_exists ? __( 'exists', MS_SLUG ) : __( 'is missing', MS_SLUG ); ?></span><br>
		</p>
	</td>
	</tr>

	
	</table>
	
	<!-- Mise à jour des valeurs -->
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="ms_switch_roles,ms_allowed_roles,ms_allowed_ips,ms_page_html,ms_use_theme" />
	
	<!-- Bouton de sauvegarde -->
	<p class="submit">
		<input class="button-primary" type="submit" value="<?php _e('Save Changes'); ?>" />
	</p>
	</form>
	
</div>

