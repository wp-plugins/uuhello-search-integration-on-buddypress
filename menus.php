<?php


add_action('admin_menu', 'bp_usi_menu');

function bp_usi_menu() {
	
/*  
	ADDING WORDPRESS ADMINISTRATOR MENUS
	=====================================================================================================

	Required Reading: Adding Administration Menus - http://codex.wordpress.org/Adding_Administration_Menus
	
	If you decided to add a Top-level Menu, your own private/seperate menu.  Similar to the left sidebar 
	Menus which are displayed when you login as an Administrator. Examples: "Post", "Page", "Settings", etc. 
	you must use the WordPress functions: "add_menu_page" and "add_submenu_page".  Both are described 
	in detail by the WordPress Codex article in the link I provided above.  
		
	NOTE: Two functions are used to add menus to your plugin: "add_menu_page" and "add_submenu_page".
		  Configuring their parameters can be a bit confusing, so I will explain the difficult ones in detail.

		  add_menu_page (page_title, menu_title, user_level, file, [function], [icon_url]);
		  add_submenu_page(parent, page_title, menu_title, user_level, file, [function]); 
		  
		  
	-----------------------------------------------------------------------------------------------------------------------
	<page_title>	text that will go into the HTML page title for the page when the menu is active. <page_title> will be 
					inserted between the HTML code <title></title>.  
	-----------------------------------------------------------------------------------------------------------------------
	<menu_title>	the on-screen name for the menu.  The <menu_title> will be text displayed for the menu that users will
					see on the Sidebar. 
	-----------------------------------------------------------------------------------------------------------------------
	<user_level>	Below you will find descriptions on each USER LEVEL/ROLE. (administrator, editor, author, contributor, subscriber).
					Use the names provided in single quotes to assign different levels of access.  If you assign a menu a 
					level of 'author', then contributor and subscriber will not see the menu when they are logged on.  On
					the other hand, administrator and editor will have access to these menus. 
	-----------------------------------------------------------------------------------------------------------------------
	<file>			the name/identifier that is added to the end of the menu's URL.  For example: /admin.php?page=<file>.
					Every menu requires a uniqe url 'page' identifier.  When you enter the value for <file> you are letting
					WordPress know what words will be added at the end of the 'admin.php?page='.  Make sure you don't use 
					predefined WordPress names.  The best way to avoid this is by adding a prefix to your <file> name.  
					bp_usi-home, or bp_usi-options, etc.
	-----------------------------------------------------------------------------------------------------------------------
	[function]		The php function that displays the page content for the menu page. This is an optional parameter,
					if you leave it blank, WordPress will search for a <file> name that links to another menu.
	-----------------------------------------------------------------------------------------------------------------------
	[icon_url]		This only works in WordPress 2.7 or higher. It's places a custom icon in the menu for you. I have added
					several WordPress icons inside the 'images' folder of this plugin.  You can change the icon, by renaming
					the '.png' image name specified in the $icon_url variable below.  
	-----------------------------------------------------------------------------------------------------------------------
	<parent>		filename of the core WordPress admin file that supplies the top-level menu in which you want to insert 
					your submenu, or your plugin file if this submenu is going into a custom top-level menu.
		 		  		 		   
		  	 		I used the constant defined in bp_usi.php, "BP_USI_NAME" to assign the the <parent> to every menu
		  	 		we created for this plugin. 
	
	
	
	USER LEVEL / ROLES
	====================================================================================================
	I have included a list of pre-defined constants used by WordPress User Levels in case you want
	different access prevlidges for each menu.  
	
	administrator - Somebody who has access to all the administration features 
	editor - Somebody who can publish posts, manage posts as well as manage other people's posts, etc. 
	author - Somebody who can publish and manage their own posts 
	contributor - Somebody who can write and manage their posts but not publish posts 
	subscriber - Somebody who can read comments/comment/receive news letters, etc.
	
	More information is available in the WordPress Codex: http://codex.wordpress.org/Roles_and_Capabilities
	
	ICONS
	=====================================================================================================
	We have included some icons that can be used for your plugin in the images folder inside the bp_usi
	diretory.  These icons can be used in the Top-level Menu created below by assiging them to [icon_url].
	
*/


 	$icon_url = plugins_url('images/plugin-menu-icon16.png', __FILE__);
	 
 	## add_menu_page (page_title, menu_title, user_level, file, [function], [icon_url]);  //  adds a new top-level menu:
	add_menu_page('', 'uuHello Search', 'administrator', BP_USI_NAME, 'bp_usi_submenu_welcome', $icon_url) ;
	
	
  	## add_submenu_page (parent, page_title, menu_title, user_level, file, [function]);   // adds a new submenu to the custom top-level menu    
	add_submenu_page(BP_USI_NAME, 'uuHello Search General', 'General', 'administrator', BP_USI_NAME, 'bp_usi_submenu_welcome');    
	add_submenu_page(BP_USI_NAME, 'uuHello Search Options', 'Options', 'administrator', 'bp_usi-options', 'bp_usi_submenu_options');
	
}


function bp_usi_submenu_welcome() {
	
  $icon_url = plugins_url('images/plugin-menu-icon32.png', __FILE__);

  echo '<div class="wrap">';
  echo '<div id="icon-bp_usi" class="icon32"><img src="'. $icon_url . '"></div><h2>uuHello Search General</h2>';
  
  echo '<p>Welcome to sublevel page 1 or the Welcome page.  You can place HTML code, include/require a PHP file, or call a function function from this area.</p>';
  
  echo ' <p>You are viewing the Welcome page for  <a href="http://wordpress.org/extend/plugins/simple-wordpress-framework/">Simple 
  		 WordPress Framework Plugin</a>.  Now that you have installed the application please take the time to read through all the
  		 documentaion found in the php files.</p>
  		   
  		 <p>Remember we are still Beta testing this Framework, and would appreciate any feedback you can offer, wether it be 
  		 code modification, spelling errors, grammer, or anything you feel is important, let us know by going to the 
  		 <a href="http://www.bp_usi.com/contact">contact</a> page on our website.';
  echo '</div>';
 
}

function bp_usi_submenu_options() {
  	
  bp_usi_options_page();  ## Calls a function located in the options.php file. You will find different

}  

?>