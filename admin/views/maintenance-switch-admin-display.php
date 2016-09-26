<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.fugu.fr
 * @since      1.3.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/admin/views
 */
 
/**
 * Generated by the WordPress Option Page generator
 * at http://jeremyhixon.com/wp-tools/option-page/
 */

class Maintenance_Switch_Admin_Display {
	
	/**
	 * The settings from database
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      array    $maintenance_switch_settings    the array of settings from db option.
	 */
	private $maintenance_switch_settings;
	
	/**
	 * The plugin controller object
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      Maintenance_Switch    $plugin    the instance of plugin controller.
	 */	
	private $plugin;
	
	/**
	 * Define the core functionality of the plugin admin view.
	 *
	 * @since    1.3.0
	 */
	public function __construct( &$plugin ) {
		
		$this->plugin = $plugin;
		
		$this->plugin->admin_action_request();
		
		$this->maintenance_switch_settings = $plugin->get_the_settings();
		
		add_action( 'admin_init', array( $this, 'maintenance_switch_page_init' ) );
	}
	
	/**
	 * Display the admin settings view.
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function maintenance_switch_create_admin_page() { 
		$plugin_settings_url = admin_url( 'options-general.php?page=' . MS_SLUG );
		?>

		<div id="ms-form" class="wrap">
			
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<form id="settings-form" method="POST" action="options.php">
				<?php
					settings_fields( 'maintenance_switch' );
					$this->do_settings_sections_tabs( 'maintenance-switch' );
				?>				
				<p class="submit">
					<?php submit_button( __('Save Settings', MS_SLUG), 'primary', 'submit', false ); ?>
					<a id="page-preview" class="button-secondary"><?php _e( 'Preview page', MS_SLUG) ?></a>
				</p>
			</form>
			
			<h2><?php _e( 'Default settings', MS_SLUG ); ?></h2>
			
			<form id="restore-settings-form" action="<?php echo $plugin_settings_url; ?>" method="POST" class="inline-form">
				<input type="hidden" name="action" value="restore_settings" />
				<?php submit_button( __('Restore all settings', MS_SLUG), 'secondary', 'submit', false, array( 'data-msg' => __( 'Are you sure you want to retore all the default settings?', MS_SLUG ) ) ); ?>
			</form>
			
			<form id="restore-html-form" action="<?php echo $plugin_settings_url; ?>" method="POST" class="inline-form">
				<input type="hidden" name="action" value="restore_html" />
				<?php submit_button( __('Restore page HTML', MS_SLUG), 'secondary', 'submit', false, array( 'data-msg' => __( 'Are you sure you want to retore the default HTML code?', MS_SLUG ) ) ); ?>
			</form>
			
			<?php if ( ! $this->plugin->theme_file_exists() ) : ?>
			<form id="create-theme-file" action="<?php echo $plugin_settings_url; ?>" method="POST" class="inline-form">
				<input type="hidden" name="action" value="create_theme_file" />
				<?php submit_button( __('Create file in the theme', MS_SLUG), 'secondary', 'submit', false, array( 'data-msg' => __( 'Are you sure you want to create the file in your theme?', MS_SLUG ) ) ); ?>
			</form>
			<?php else : ?>
			<form id="delete-theme-file" action="<?php echo $plugin_settings_url; ?>" method="POST" class="inline-form">
				<input type="hidden" name="action" value="delete_theme_file" />
				<?php submit_button( __('Delete file in the theme', MS_SLUG), 'secondary', 'submit', false, array( 'data-msg' => __( 'Are you sure you want to delete the file in your theme?', MS_SLUG ) ) ); ?>
			</form>
			<?php endif; ?>
			
			<form id="preview-form" data-default-action="<?php echo plugins_url( 'preview.php', dirname(dirname(__FILE__)) ); ?>" method="POST" target="ms-preview"></form>
			
		</div>
	<?php }
		
	/**
	 * Register all sections and fields
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function maintenance_switch_page_init() {
		
		register_setting(
			'maintenance_switch', // option_group
			'maintenance_switch_settings', // option_name
			array( $this, 'maintenance_switch_sanitize' ) // sanitize_display
		);

		add_settings_section(
			'maintenance_switch_display_section', // id
			__( 'Display', MS_SLUG ), // title
			array( $this, 'maintenance_switch_display_section_info' ), // callback
			'maintenance-switch' // page
		);
		
		add_settings_field(
			'ms_page_html', // id
			__( 'Maintenance page HTML:', MS_SLUG ), // title
			array( $this, 'ms_page_html_display' ), // callback
			'maintenance-switch', // page
			'maintenance_switch_display_section' // section
		);
		
		add_settings_field(
			'ms_use_theme', // id
			__( 'Use theme file:', MS_SLUG ), // title
			array( $this, 'ms_use_theme_display' ), // callback
			'maintenance-switch', // page
			'maintenance_switch_display_section' // section
		);

		add_settings_section(
			'maintenance_switch_permissions_section', // id
			__( 'Permissions', MS_SLUG ), // title
			array( $this, 'maintenance_switch_permissions_section_info' ), // callback
			'maintenance-switch' // page
		);

		add_settings_field(
			'ms_switch_roles', // id
			__( 'Switch ability:', MS_SLUG ), // title
			array( $this, 'ms_switch_roles_display' ), // callback
			'maintenance-switch', // page
			'maintenance_switch_permissions_section' // section
		);
		
		add_settings_field(
			'ms_allowed_roles', // id
			__( 'Bypass ability:', MS_SLUG ), // title
			array( $this, 'ms_allowed_roles_display' ), // callback
			'maintenance-switch', // page
			'maintenance_switch_permissions_section' // section
		);
		
		add_settings_field(
			'ms_allowed_ips', // id
			'', // title
			array( $this, 'ms_allowed_ips_display' ), // callback
			'maintenance-switch', // page
			'maintenance_switch_permissions_section' // section
		);

		add_settings_section(
			'maintenance_switch_core_section', // id
			__( 'Behavior', MS_SLUG ), // title
			array( $this, 'maintenance_switch_core_section_info' ), // callback
			'maintenance-switch' // page
		);

		add_settings_field(
			'ms_error_503', // id
			__( 'Code 503:', MS_SLUG ), // title
			array( $this, 'ms_error_503_display' ), // callback
			'maintenance-switch', // page
			'maintenance_switch_core_section' // section
		);

	}

	/**
	 * Sanitize the form submit values
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function maintenance_switch_sanitize($input) {
		$sanitary_values = array();
		
		if ( isset( $input['ms_switch_roles'] ) ) {
			$sanitary_values['ms_switch_roles'] = $input['ms_switch_roles'];
		}
		
		if ( isset( $input['ms_allowed_roles'] ) ) {
			$sanitary_values['ms_allowed_roles'] = $input['ms_allowed_roles'];
		}
			
		if ( isset( $input['ms_allowed_ips'] ) ) {
			$sanitary_values['ms_allowed_ips'] = sanitize_text_field( str_replace( ' ', '', $input['ms_allowed_ips'] ) );
		}

		if ( isset( $input['ms_error_503'] ) ) {
			$sanitary_values['ms_error_503'] = (int) $input['ms_error_503'];
		}
		
		if ( isset( $input['ms_page_html'] ) ) {
			$sanitary_values['ms_page_html'] = esc_textarea( $input['ms_page_html'] );
		}
		
		if ( isset( $input['ms_use_theme'] ) ) {
			$sanitary_values['ms_use_theme'] = (int) $input['ms_use_theme'];
		}

		return $sanitary_values;
	}

	/**
	 * Behavior section field infos
	 * WP Settings API
	 *
	 * @since    1.3.8
	 */
	public function maintenance_switch_core_section_info() {
		// printf( '<p class="description">%s</p>', __( 'Ajust the behavior of the plugin.', MS_SLUG ) );
	}

	/**
	 * Permissions section field infos
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function maintenance_switch_permissions_section_info() {
		// printf( '<p class="description">%s</p>', __( 'Ajust the access and switch permissions.', MS_SLUG ) );
	}
	
	/**
	 * Display section field infos
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function maintenance_switch_display_section_info() {
		// printf( '<p class="description">%s</p>', __( 'Ajust the appearance of the maintenance page', MS_SLUG ) );
	}
	
	/**
	 * Display ms_error_503 field
	 * WP Settings API
	 *
	 * @since    1.3.8
	 */
	public function ms_error_503_display() {		
		printf(
			'<p class="inline-checkbox"><input id="ms_error_503" name="maintenance_switch_settings[ms_error_503]" type="checkbox" value="1" %s></p>',
			( isset( $this->maintenance_switch_settings['ms_error_503'] ) && $this->maintenance_switch_settings['ms_error_503'] == 1 ) ? 'checked' : ''
		);
	  	printf( '<p class="description inline-description">%s</p>', __( 'The maintenance page returns the error code 503 "Service unavailable" (recommanded).', MS_SLUG ) );
	}
	
	/**
	 * Display ms_switch_roles field
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function ms_switch_roles_display() {
		global $wp_roles;
		foreach ($wp_roles->get_names() as $role_value => $role_name) {
			printf(
				'<p class="inline-checkbox"><input id="ms_switch_roles" name="maintenance_switch_settings[ms_switch_roles][]" type="checkbox" value="' . $role_value . '" %s>'.$role_name.'</p>',
				( isset( $this->maintenance_switch_settings['ms_switch_roles'] ) && in_array( $role_value, (array) $this->maintenance_switch_settings['ms_switch_roles'] ) ) ? 'checked' : ''
			);
	  	}
	  	printf( '<p class="description">%s</p>', __( 'The user roles can access the maintenance button in the adminbar and so switch the maintenance mode.', MS_SLUG ) );
	}
	
	/**
	 * Display ms_allowed_roles field
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function ms_allowed_roles_display() {
		global $wp_roles;
		foreach ($wp_roles->get_names() as $role_value => $role_name) {
			printf(
				'<p class="inline-checkbox"><input id="ms_allowed_roles" name="maintenance_switch_settings[ms_allowed_roles][]" type="checkbox" value="' . $role_value . '" %s>'.$role_name.'</p>',
				( isset( $this->maintenance_switch_settings['ms_allowed_roles'] ) && in_array( $role_value, (array) $this->maintenance_switch_settings['ms_allowed_roles'] ) ) ? 'checked' : ''
			);
	  	}
	  	printf( '<p class="description">%s</p>', __( 'The user roles can bypass the maintenance mode and see the site like online.', MS_SLUG ) );
	}
	
	/**
	 * Display ms_allowed_ips field
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function ms_allowed_ips_display() {
		printf(
			'<input id="ms_allowed_ips" name="maintenance_switch_settings[ms_allowed_ips]" size="60" value="%s"><button id="addmyip" class="button-secondary" data-ip="%s">%s</button>',
			isset( $this->maintenance_switch_settings['ms_allowed_ips'] ) ? $this->maintenance_switch_settings['ms_allowed_ips'] : '',
			$this->plugin->get_user_ip(),
			__( 'Add my IP', MS_SLUG )
		);
		printf( '<p class="description">%s</p>', __( 'The IP list can bypass the maintenance mode and see the site like online, comma separated.', MS_SLUG ) );
	}

	/**
	 * Display ms_page_html field
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function ms_page_html_display() {
		$theme_file_exists = $this->plugin->theme_file_exists();
		printf(
			'<textarea id="ms_page_html" class="large-text" cols="70" rows="20" name="maintenance_switch_settings[ms_page_html]" %s>%s</textarea>',
			( isset( $this->maintenance_switch_settings['ms_use_theme'] ) && $this->maintenance_switch_settings['ms_use_theme'] == 1 && $theme_file_exists ) ? 'readonly' : '',
			isset( $this->maintenance_switch_settings['ms_page_html'] ) ? $this->maintenance_switch_settings['ms_page_html'] : ''
		);
		printf( '<p class="description">%s</p>', __( 'The entire HTML code of the maintenance page.', MS_SLUG ) );
	}
	
	/**
	 * Display ms_use_theme field
	 * WP Settings API
	 *
	 * @since    1.3.0
	 */
	public function ms_use_theme_display() {
		$theme_file_url = $this->plugin->get_theme_file_url();
		$theme_file_exists = $this->plugin->theme_file_exists();
		
		printf(
			'<p class="inline-checkbox"><input id="ms_use_theme" name="maintenance_switch_settings[ms_use_theme]" type="checkbox" value="1" %s %s></p>',
			( isset( $this->maintenance_switch_settings['ms_use_theme'] ) && $this->maintenance_switch_settings['ms_use_theme'] == 1 && $theme_file_exists ) ? 'checked' : '',
			$theme_file_exists ? '' : 'disabled'
		);
	  	printf( '<p class="description inline-description">%s</p>', __( 'Use a file in your theme to display maintenance page instead of the HTML field above.', MS_SLUG ) );
	  	print( '<p class="infos messages">' );
		printf( '<input id="ms_preview_theme_file" type="hidden" name="ms_preview_theme_file" value="%s">', $theme_file_url );
		printf( '<div class="message message-%s"><p><strong>%s</strong>: %s</p></div></p>',
			$theme_file_exists ? 'success' : 'error',
			$this->plugin->get_current_theme()->Name,
			MS_THEME_FILENAME . ' ' . ( $theme_file_exists ? __( 'exists', MS_SLUG ) : __( 'is missing', MS_SLUG ) )
	  	);
	}


	/**
	 * Replace the call to 'do_settings_sections()' with a call to this function
	 *
	 * @since    1.3.8
	 */
	public function do_settings_sections_tabs($page){

	    global $wp_settings_sections, $wp_settings_fields;

	    if(!isset($wp_settings_sections[$page])) :
	        return;
	    endif;

	    echo '<div id="settings-tabs">';
	    echo '<ul class="nav-tab-wrapper">';

	    foreach((array)$wp_settings_sections[$page] as $section) :

	        if(!isset($section['title']))
	            continue;

	        printf('<li class="nav-tab"><a href="#%1$s">%2$s</a></li>',
	            $section['id'],     /** %1$s - The ID of the tab */
	            $section['title']   /** %2$s - The Title of the section */
	        );

	    endforeach;

	    echo '</ul>';

	    foreach((array)$wp_settings_sections[$page] as $section) :

	        printf('<div id="%1$s">',
	            $section['id']      /** %1$s - The ID of the tab */
	        );

	        if(!isset($section['title']))
	            continue;

	        if($section['callback'])
	            call_user_func($section['callback'], $section);

	        if(!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]))
	            continue;

	        echo '<table class="form-table">';
	        do_settings_fields($page, $section['id']);
	        echo '</table>';

	        echo '</div>';

	    endforeach;

	    echo '</div>';

	}
}

/* 
 * Retrieve this value with:
 * $maintenance_switch_settings = get_option( 'maintenance_switch_settings' ); // Array of All Options
 * $ms_switch_roles = $maintenance_switch_settings['ms_switch_roles']; // Capacité à switcher
 */
