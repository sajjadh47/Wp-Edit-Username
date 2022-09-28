<?php
/*
Plugin Name: WP Edit Username
Plugin URI : https://wordpress.org/plugins/wp-edit-username/
Description: Change Wordpress User's Username From Edit User Admin Panel.
Version: 1.0.3
Author: Sajjad Hossain Sagor
Author URI: https://profiles.wordpress.org/sajjad67
Text Domain: wp-edit-username

License: GPL2
This WordPress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This free software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ---------------------------------------------------------
// Define Plugin Folders Path
// ---------------------------------------------------------
define( "WPEU_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );
define( "WPEU_PLUGIN_URL", plugin_dir_url( __FILE__ ) );

// ---------------------------------------------------------
// Check if current_user_can() functions exists in this scope
// ---------------------------------------------------------
if( ! function_exists( 'wp_get_current_user' ) )
{	
	include( ABSPATH . "wp-includes/pluggable.php" );
}

require_once WPEU_PLUGIN_PATH . 'admin/ajax-handler.php';

require_once WPEU_PLUGIN_PATH . 'includes/settings.php';

global $pagenow;

// check if current page is add/edit user page
if( in_array( $pagenow, array( 'profile.php', 'user-edit.php' ) ) )
{
	if( current_user_can( 'edit_users' ) )
	{
	 	require_once WPEU_PLUGIN_PATH . 'includes/class-edit-username.php';
	 	
	 	require_once WPEU_PLUGIN_PATH . 'admin/modal.php';

 		$WP_Edit_Username = new WP_Edit_Username();
 	}
}
