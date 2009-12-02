<?php

/**
 * options.php functions
 * 
 * These functions are used in the options.php file, they were moved to this location
 * because changes are not required to these routines, and it illiminates clutter.
 *
 */

function ssp_get_options() {
	
	global $ssp_options_array, $ssp_options_array_excluded;
	
	foreach ($ssp_options_array as $option => $default_value) {
		if (in_array( $option, array($ssp_options_array_excluded) ) ) continue;  // ignore getting options listed in this array.
		$ssp_options[$option] = get_option( $option );
	}
	
	return $ssp_options;
}


function bp_usi_activate_options() {
	global $ssp_options_array;
	
	foreach ($ssp_options_array as $option => $default_value) {
	  add_option( $option, $default_value );
	}
}


function bp_usi_deactivate_options()
{
	global $ssp_options_array;
	
	foreach ($ssp_options_array as $option => $default_value) {
	  delete_option( $option, $default_value );
	}
}


# register plugin settings
add_action('admin_init', 'ssp_admin_init');
function ssp_admin_init() {
	global $ssp_options_array, $ssp_options_array_excluded;
	if (function_exists('register_setting'))
		$function = 'register_setting';
	else
		$function = 'add_option_update_handler';
	foreach ($ssp_options_array as $option => $default_value) {
		if (in_array( $option, array($ssp_options_array_excluded) ) ) continue;  // ignore getting options listed in this array.
		call_user_func($function, 'bp_usi', $option);
	}
	// Wrapper for settings_fields function which doesn't exist in wordpress MU 2.6.5
	if (!function_exists('settings_fields')) {
		function settings_fields($option_group) {
			echo "<input type='hidden' name='option_page' value='$option_group' />";
			echo '<input type="hidden" name="action" value="update" />';
			wp_nonce_field("$option_group-options");
		}
	}
}


function bp_usi_options_page() {

	bp_usi_options_html(); 	// display the options page.

}


add_action('admin_head', 'bp_usi_load_options_css');

function bp_usi_load_options_css()
{
	if ( strpos($_SERVER['REQUEST_URI'], 'bp_usi-options' ) !== false ) { # load css for options page
		echo '<link rel="stylesheet" href="'.plugins_url('css/options.css', __FILE__).'" type="text/css" media="screen" />'."\n";
		echo '<link rel="stylesheet" href="'.admin_url().'/css/farbtastic.css" type="text/css" media="screen" />'."\n";
	}
}


add_action( 'init', 'bp_usi_options_load_js' ); # Loads JavaScript and CSS files

function bp_usi_options_load_js()
{
	if ( strpos($_SERVER['REQUEST_URI'], 'bp_usi-options' ) !== false ) { # load js for options page

		## -- Fabrastic Color Picker - Start  ##	
		## ------------------------------------------------------------------------------------------
		## Fabrastic is a circular color selector.  It uses two JavaScript routines that are located in the
		## 'bp_usi/widgets' directory: 1) rgbcolor.js and 2) farbtastic.  It also uses HTML code which I
		## provided below under <!--  
		## Website/Reference: ( http://acko.net/blog/farbtastic-color-picker-released )

		wp_enqueue_script( 'bp_usi_farbtastic', plugins_url('widgets/bp_usi_farbtastic.js', __FILE__), array( 'jquery', 'farbtastic', 'rgbcolor' ) ); // this is very important
		wp_enqueue_script( 'rgbcolor', plugins_url('widgets/rgbcolor.js', __FILE__)   );

		## Do not remove the 'ssp_insert_colorpicker' action or function unless you don't want to use farbastic.

	    add_action('admin_footer', 'ssp_insert_colorpicker');
		function ssp_insert_colorpicker()
		{
			echo "\n";
			echo '<div id="ssp_farbtastic" style="display:none"> </div>'."\n";
			echo "\n";
		}	
		## -- Fabrastic Color Picker - End  ##

	}

}



?>