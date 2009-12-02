<?php
/*
Plugin Name: uuHello Search Integration on BuddyPress
Plugin URI: http://www.uuhello.com
Description: uuHello Search Integration on BuddyPress, include the Travel, Shopping, Live, Business.
Version: Beta 0.0.1
Author: Mark Ma
Author URI: http://www.uuhello.com
*/

/*  Copyright 2009  Mark  (email : mark.ma.sg@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/  


// Sets up Plugin configuration and routing based on names of Plugin folder and files.

# define Plugin constants
define( 'BP_USI_VERSION', "0.0.1");				#  Plugin Database Version: Change this value every time you make changes to your Plugin. 
define( 'BP_USI_PURGE_DATA', '1' );				#  When Plugin is deactivated, if 'true', all Tables, and Options will be removed.

define( 'WP_ADMIN_PATH', ABSPATH . '/wp-admin/');  // If you have a better answer to this Constant, feel free to send me an e-mail.  

define( 'BP_USI_FILE', basename(__FILE__) );
define( 'BP_USI_NAME', basename(__FILE__, ".php") );
define( 'BP_USI_PATH', str_replace( '\\', '/', trailingslashit(dirname(__FILE__)) ) );
if ( !defined( 'BP_USI_SLUG' ) )
	define ( 'BP_USI_SLUG', 'uuhello-search' );
 

if ( file_exists( BP_USI_PATH . '/languages/' . get_locale() . '.mo' ) )
	load_textdomain( 'bp-usi', BP_USI_PATH . '/languages/' . get_locale() . '.mo' );

require_once( BP_USI_PATH . '/functions.php' );
require_once( BP_USI_PATH . '/activation.php' );
require_once( BP_USI_PATH . '/deactivate.php' );
require_once( BP_USI_PATH . '/options.php' );
require_once( BP_USI_PATH . '/menus.php' );

define( 'BP_USI_URL', plugins_url('', __FILE__) );  // NOTE: It is recommended that every time you reference a url, that you specify the plugins_url('xxx.xxx',__FILE__), WP_PLUGIN_URL, WP_CONTENT_URL, WP_ADMIN_URL view the video by Will Norris.


register_activation_hook(__FILE__,'bp_usi_activate');  // WordPress Hook that executes the installation

register_deactivation_hook( __FILE__, 'bp_usi_deactivate' ); // WordPress Hook that handles deactivation of the Plugin.

add_action('plugins_loaded', 'bp_usi_activate' );   // check for updates from previous versions.


	
/**
 * bp_usi_setup_globals()
 *
 * Sets up global variables for your component.
 */
function bp_usi_setup_globals() {
	global $bp, $wpdb;

	$bp->usi->image_base = BP_USI_URL . '/images';
	$bp->usi->format_activity_function = 'bp_usi_format_activity';
	$bp->usi->format_notification_function = 'bp_usi_format_notifications';
	$bp->usi->slug = BP_USI_SLUG;
	$bp->usi->slugs = array ( 
		'business' => 'business', 
		'live' => 'live', 
		'shopping' => 'shopping', 
		'travel' => 'travel' 
	);
	$bp->usi->id = BP_USI_SLUG;

	$bp->version_numbers->usi = BP_USI_VERSION;

	/* Register this in the active components array */
	$bp->active_components[$bp->usi->slug] = $bp->usi->id;
	
}
add_action( 'plugins_loaded', 'bp_usi_setup_globals', 5 );	
add_action( 'admin_menu', 'bp_usi_setup_globals', 1 );


/**
 * bp_usi_setup_nav()
 *
 * Sets up the navigation items for the component. This adds the top level nav
 * item and all the sub level nav items to the navigation array. This is then
 * rendered in the template.
 */
function bp_usi_setup_nav() {
	global $bp;

	/*
	if (function_exists('bp_core_new_nav_item')) {
            bp_core_new_nav_item(array(
                'name'=> __( 'Search'),
                'slug'=> $bp->usi->slug,
                'screen_function'=>'bp_usi_screen_one',
                'default_subnav_slug'=>'usi'
            ));
	} else {	
	    // Add 'usi' to the main navigation 
	    bp_core_add_nav_item( 
		__( 'Search'), // The display name 
		$bp->usi->slug // The slug 
	    );

	    // Set a specific sub nav item as the default when the top level item is clicked 
	    bp_core_add_nav_default( 
		$bp->usi->slug, // The slug of the parent nav item 
		'bp_usi_screen_one', // The function to run when clicked 
		'usi-one' // The slug of the sub nav item to make default 
	    );
	}
	*/

	/* Add 'Search' to the main navigation */
	//bp_core_new_nav_item( array( 'name' => __('Search', 'buddypress'), 'slug' => $bp->usi->slug, 'position' => 1, 'show_for_displayed_user' => true, 'screen_function' => 'bp_usi_screen_index', 'default_subnav_slug' => 'business', 'item_css_id' => $bp->usi->id ) );
	
	bp_core_new_nav_item( array( 'name' => __('Business Search', 'buddypress'), 'slug' => $bp->usi->slugs['business'], 'position' => 1, 'show_for_displayed_user' => true, 'screen_function' => 'bp_usi_screen_index', 'item_css_id' => $bp->usi->id ) );
	bp_core_new_nav_item( array( 'name' => __('Travel Search', 'buddypress'), 'slug' => $bp->usi->slugs['travel'], 'position' => 1, 'show_for_displayed_user' => true, 'screen_function' => 'bp_usi_screen_index', 'item_css_id' => $bp->usi->id ) );
	bp_core_new_nav_item( array( 'name' => __('Live Search', 'buddypress'), 'slug' => $bp->usi->slugs['live'], 'position' => 1, 'show_for_displayed_user' => true, 'screen_function' => 'bp_usi_screen_index', 'item_css_id' => $bp->usi->id ) );
	bp_core_new_nav_item( array( 'name' => __('Shopping Search', 'buddypress'), 'slug' => $bp->usi->slugs['shopping'], 'position' => 1, 'show_for_displayed_user' => true, 'screen_function' => 'bp_usi_screen_index', 'item_css_id' => $bp->usi->id ) );
	$usi_link = $bp->loggedin_user->domain . $bp->usi->slug . '/';
		
	/* Add the subnav items to the profile */
	//bp_core_new_subnav_item( array( 'name' => __( 'Business', 'buddypress' ), 'slug' => 'business', 'parent_url' => $usi_link, 'parent_slug' => $bp->usi->slug, 'screen_function' => 'bp_usi_screen_index', 'position' => 10, 'user_has_access' => bp_is_home() ) );
	//bp_core_new_subnav_item( array( 'name' => __( 'Travel', 'buddypress' ), 'slug' => 'travel', 'parent_url' => $usi_link, 'parent_slug' => $bp->usi->slug, 'screen_function' => 'bp_usi_screen_index', 'position' => 20, 'user_has_access' => bp_is_home() ) );
	//bp_core_new_subnav_item( array( 'name' => __( 'Live', 'buddypress' ), 'slug' => 'live', 'parent_url' => $usi_link, 'parent_slug' => $bp->usi->slug, 'screen_function' => 'bp_usi_screen_index', 'position' => 30, 'user_has_access' => bp_is_home() ) );
	//bp_core_new_subnav_item( array( 'name' => __( 'Shopping', 'buddypress' ), 'slug' => 'shopping', 'parent_url' => $usi_link, 'parent_slug' => $bp->usi->slug, 'screen_function' => 'bp_usi_screen_index', 'position' => 40, 'user_has_access' => bp_is_home() ) );

	/* Only execute the following code if we are actually viewing this component (e.g. http://usi.org/usi) */
	if ( $bp->current_component == $bp->usi->slug ) {
		if ( bp_is_home() ) {
			/* If the user is viewing their own profile area set the title to "My usi" */
			$bp->bp_options_title = __( 'Search', 'bp-usi' );
		} else {
			/* If the user is viewing someone elses profile area, set the title to "[user fullname]" */
			$bp->bp_options_avatar = bp_core_get_avatar( $bp->displayed_user->id, 1 );
			$bp->bp_options_title = $bp->displayed_user->fullname;
		}
	}
}
add_action( 'wp', 'bp_usi_setup_nav', 2 );
add_action( 'admin_menu', 'bp_usi_setup_nav', 2 );




/**
 * bp_usi_screen_index()
 *
 * Sets up and displays the screen output for the sub nav item "usi/screen-index"
 */
function bp_usi_screen_index() {
	global $bp;
	
	wp_enqueue_style( 'bp-usi-index', BP_USI_URL . '/css/style.css' );
	
	/* Add a do action here, so your component can be extended by others. */
	do_action( 'bp_usi_screen_index' );	
	bp_core_load_template( apply_filters( 'bp_usi_template_screen_index', 'usi/screen-index' ) );
	
	/* ---- OR ----- */
	 
	 #add_action( 'bp_template_content_header', 'bp_usi_screen_index_header' );
	 add_action( 'bp_template_title', 'bp_usi_screen_index_title' );
	 add_action( 'bp_template_content', 'bp_usi_screen_index_content' );
		
	/* Finally load the plugin template file. */
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'plugin-template' ) );
}

/***
 * The second argument of each of the above add_action() calls is a function that will
 * display the corresponding information. The functions are presented below:
 */

function bp_usi_screen_index_header() {
	#_e( 'usi', 'bp-usi' );
}

function bp_usi_screen_index_title() {
	#_e( 'usi', 'bp-usi' );
}

function bp_usi_screen_index_content() {
	global $bp, $current_user, $user_ID;
	global $user_level;

	get_currentuserinfo();
	echo "<iframe id='ifm-remote' frameborder='0' scrolling='no' src='http://usi.uuhello.cn/channel/" . $bp->current_component . "/?lang=en-US#/wp-content/plugins/bp-usi/usi/index.php'  width='100%' height='600' frameborder='0' scrolling='no'></iframe>";
}



/**
 * bp_usi_load_buddypress()
 *
 * When we activate the component, we must make sure BuddyPress is loaded first (if active)
 * If it's not active, then the plugin should not be activated.
 */
function bp_usi_load_buddypress() {
	if ( function_exists( 'bp_core_setup_globals' ) )
		return true;
	
	/* Get the list of active sitewide plugins */
	$active_sitewide_plugins = maybe_unserialize( get_site_option( 'active_sitewide_plugins' ) );
	if ( isset( $active_sidewide_plugins['buddypress/bp-loader.php'] ) && !function_exists( 'bp_core_setup_globals' ) ) {
		require_once( WP_PLUGIN_DIR . '/buddypress/bp-loader.php' );
		return true;
	}
	
	/* If we get to here, BuddyPress is not active, so we need to deactive the plugin and redirect. */
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( file_exists( ABSPATH . 'wp-admin/includes/mu.php' ) )
		require_once( ABSPATH . 'wp-admin/includes/mu.php' );

	deactivate_plugins( basename(__FILE__), true );
	if ( function_exists( 'deactivate_sitewide_plugin') )
		deactivate_sitewide_plugin( basename(__FILE__), true );
		
	wp_redirect( get_blog_option( BP_ROOT_BLOG, 'home' ) . '/wp-admin/plugins.php' );
}
add_action( 'plugins_loaded', 'bp_usi_load_buddypress', 11 );




?>