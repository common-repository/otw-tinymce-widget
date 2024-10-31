<?php
/**
 * Init function
 */
if( !function_exists( 'otw_mcsw_widgets_init' ) ){
	
	function otw_mcsw_widgets_init(){
		
		global $otw_components, $wp_filesystem;
		
		if( isset( $otw_components['registered'] ) && isset( $otw_components['registered']['otw_shortcode'] ) ){
			
			$shortcode_components = $otw_components['registered']['otw_shortcode'];
			arsort( $shortcode_components );
			
			if( otw_init_filesystem() ){
				foreach( $shortcode_components as $shortcode ){
					if( $wp_filesystem->is_file( $shortcode['path'].'/widgets/otw_shortcode_widget.class.php' ) ){
						
						include_once( $shortcode['path'].'/widgets/otw_shortcode_widget.class.php' );
						break;
					}
				}
			}
		}
		register_widget( 'OTW_Shortcode_Widget' );
	}
}
/**
 * Init function
 */
if( !function_exists( 'otw_mcsw_init' ) ){
	
	function otw_mcsw_init(){
		
		global $otw_mcsw_plugin_url, $otw_mcsw_plugin_options, $otw_mcsw_shortcode_component, $otw_mcsw_shortcode_object, $otw_mcsw_form_component, $otw_mcsw_validator_component, $otw_mcsw_form_object, $wp_mcsw_cs_items, $otw_mcsw_js_version, $otw_mcsw_css_version, $wp_widget_factory, $wp_filesystem;
		
		if( is_admin() ){
			
		
			add_action('admin_menu', 'otw_mcsw_init_admin_menu' );
			
			add_action('admin_print_styles', 'otw_mcsw_enqueue_admin_styles' );
			
			add_action('admin_enqueue_scripts', 'otw_mcsw_enqueue_admin_scripts');
		}
		otw_mcsw_enqueue_styles();
		
		include_once( plugin_dir_path( __FILE__ ).'otw_mcsw_dialog_info.php' );
		
		//shortcode component
		$otw_mcsw_shortcode_component = otw_load_component( 'otw_shortcode' );
		$otw_mcsw_shortcode_object = otw_get_component( $otw_mcsw_shortcode_component );
		$otw_mcsw_shortcode_object->js_version = $otw_mcsw_js_version;
		$otw_mcsw_shortcode_object->css_version = $otw_mcsw_css_version;
		$otw_mcsw_shortcode_object->editor_button_active_for['page'] = false;
		$otw_mcsw_shortcode_object->editor_button_active_for['post'] = false;
		
		$otw_mcsw_shortcode_object->add_default_external_lib( 'css', 'style', get_stylesheet_directory_uri().'/style.css', 'live_preview', 10 );
		
		if( isset( $otw_mcsw_plugin_options['otw_mcsw_theme_css'] ) && strlen( $otw_mcsw_plugin_options['otw_mcsw_theme_css'] ) ){
			
			if( preg_match( "/^http(s)?\:\/\//", $otw_mcsw_plugin_options['otw_mcsw_theme_css'] ) ){
				$otw_mcsw_shortcode_object->add_default_external_lib( 'css', 'theme_style', $otw_mcsw_plugin_options['otw_mcsw_theme_css'], 'live_preview', 11 );
			}else{
				$otw_mcsw_shortcode_object->add_default_external_lib( 'css', 'theme_style', get_stylesheet_directory_uri().'/'.$otw_mcsw_plugin_options['otw_mcsw_theme_css'], 'live_preview', 11 );
			}
		}
		
		$otw_mcsw_shortcode_object->shortcodes['html_editor'] = array( 'title' => esc_html__('HTML Editor', 'otw_mcsw'),'enabled' => false,'children' => false, 'parent' => false, 'order' => 125,'path' => dirname( __FILE__ ).'/otw_components/otw_shortcode/', 'url' => $otw_mcsw_plugin_url.'include/otw_components/otw_shortcode/', 'dialog_text' => $otw_mcsw_dialog_text   );
		
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_mcsw_shortcode_object.labels.php' );
		$otw_mcsw_shortcode_object->init();
		
		//form component
		$otw_mcsw_form_component = otw_load_component( 'otw_form' );
		$otw_mcsw_form_object = otw_get_component( $otw_mcsw_form_component );
		$otw_mcsw_form_object->js_version = $otw_mcsw_js_version;
		$otw_mcsw_form_object->css_version = $otw_mcsw_css_version;
		
		if( otw_init_filesystem() && $wp_filesystem->is_file( plugin_dir_path( __FILE__ ).'otw_labels/otw_mcsw_form_object.labels.php' ) ){
			include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_mcsw_form_object.labels.php' );
		}
		$otw_mcsw_form_object->init();
		
		//validator component
		$otw_mcsw_validator_component = otw_load_component( 'otw_validator' );
		$otw_mcsw_validator_object = otw_get_component( $otw_mcsw_validator_component );
		$otw_mcsw_validator_object->init();
		
	}
}

/**
 * include needed styles
 */
if( !function_exists( 'otw_mcsw_enqueue_styles' ) ){
	function otw_mcsw_enqueue_styles(){
		global $otw_mcsw_plugin_url, $otw_mcsw_css_version;
	}
}


/**
 * Admin styles
 */
if( !function_exists( 'otw_mcsw_enqueue_admin_styles' ) ){
	
	function otw_mcsw_enqueue_admin_styles(){
		
		global $otw_mcsw_plugin_url, $otw_mcsw_css_version;
		
		wp_enqueue_style( 'otw_mcsw_admin', $otw_mcsw_plugin_url.'/css/otw_mcsw_admin.css', array( 'thickbox' ), $otw_mcsw_css_version );
	}
}

/**
 * Admin scripts
 */
if( !function_exists( 'otw_mcsw_enqueue_admin_scripts' ) ){
	
	function otw_mcsw_enqueue_admin_scripts( $requested_page ){
		
		global $otw_mcsw_plugin_url, $otw_mcsw_js_version;
		
		switch( $requested_page ){
			
			case 'widgets.php':
					wp_enqueue_script("otw_shotcode_widget_admin", $otw_mcsw_plugin_url.'include/otw_components/otw_shortcode/js/otw_shortcode_widget_admin.js'  , array( 'jquery', 'thickbox' ), $otw_mcsw_js_version );
					
					if(function_exists( 'wp_enqueue_media' )){
						wp_enqueue_media();
					}else{
						wp_enqueue_style('thickbox');
						wp_enqueue_script('media-upload');
						wp_enqueue_script('thickbox');
					}
				break;
		}
	}
	
}

/**
 * Init admin menu
 */
if( !function_exists( 'otw_mcsw_init_admin_menu' ) ){
	
	function otw_mcsw_init_admin_menu(){
		
		global $otw_mcsw_plugin_url;
		
		add_menu_page(__('OTW TinyMCE Widget', 'otw_mcsw'), esc_html__('OTW TinyMCE Widget', 'otw_mcsw'), 'manage_options', 'otw-mcsw-settings', 'otw_mcsw_settings', $otw_mcsw_plugin_url.'images/otw-sbm-icon.png');
		add_submenu_page( 'otw-mcsw-settings', esc_html__('Settings', 'otw_mcsw'), esc_html__('Settings', 'otw_mcsw'), 'manage_options', 'otw-mcsw-settings', 'otw_mcsw_settings' );

	}
}

/**
 * Settings page
 */
if( !function_exists( 'otw_mcsw_settings' ) ){
	
	function otw_mcsw_settings(){
		require_once( 'otw_mcsw_settings.php' );
	}
}



/**
 * Keep the admin menu open
 */
if( !function_exists( 'otw_open_mcsw_menu' ) ){
	
	function otw_open_mcsw_menu( $params ){
		
		global $menu;
		
		foreach( $menu as $key => $item ){
			if( $item[2] == 'otw-cm-settings' ){
				$menu[ $key ][4] = $menu[ $key ][4].' wp-has-submenu wp-has-current-submenu wp-menu-open menu-top otw-menu-open';
			}
		}
	}
}


?>