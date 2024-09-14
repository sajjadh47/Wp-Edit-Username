<?php
/*
Plugin Name: WP Edit Username
Plugin URI : https://wordpress.org/plugins/wp-edit-username/
Description: Change Wordpress User's Username From Edit User Admin Panel.
Version: 1.0.7
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

if ( ! class_exists( 'WP_Edit_Username' ) )
{
	class WP_Edit_Username
	{
		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 * @var      array     $options    	   The plugin options.
		 */
		protected $plugin_name;
		
		protected $options;

		public function __construct()
		{
			$this->plugin_name 	= 'wp-edit-username';

			$options 			= get_option( 'wpeu_register_settings_fields', false );

			if ( is_array( $options ) )
			{
				$this->options 	= array_map( 'trim', array_map( 'strip_tags', $options ) );
			}

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			add_action( 'wp_ajax_wpeu_update_user_name', array( $this, 'update_user_name' ) );
		}

		/**
		 * Add plugin setttings page into the dashboard
		 *
		 * @since    1.0.6
		 */
		public function admin_menu()
		{
			add_menu_page( __( 'Edit Username', 'wp-edit-username' ), __( 'Edit Username', 'wp-edit-username' ), 'manage_options' , 'wp-edit-username', array( $this, 'edit_username_view' ), 'dashicons-edit' );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since    1.0.6
		 */
		public function admin_init()
		{
			global $pagenow;

			// check if current page is edit user page
			if( in_array( $pagenow, array( 'profile.php', 'user-edit.php' ) ) )
			{
				if( current_user_can( 'edit_users' ) )
				{
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

					add_action( 'admin_footer', array( $this, 'show_edit_modal' ) );
				}
			}

			$this->register_settings();
		}

		/**
		 * Render the plugin settings page form
		 *
		 * @since    1.0.6
		 */
		public function edit_username_view()
		{
			$icon = "url(" . plugins_url( "assets/images/icon.png", __FILE__ ) . ")"; ?>

			<div class="wrap">
				<h2 style="background-image: <?php echo $icon; ?>;background-repeat:  no-repeat;background-position: left 12px;background-size: 25px;padding-left: 30px;">
					<?php _e( 'Edit Username Settings', 'wp-edit-username' ); ?>
				</h2>
				<form action="options.php" method="post"><?php
					
					settings_fields( 'wpeu_settings_group' );

					do_settings_sections( 'wpeu_settings_section' );

					submit_button( __( 'Save Changes', 'wp-edit-username' ) ); ?>

				</form>
			</div><?php
		}

		/**
		 * Register Plugin Options Via Settings API
		 *
		 * @since    1.0.6
		 */
		public function register_settings()
		{
			// add_settings_section( $id, $title, $callback, $page )
			add_settings_section(
				'wpeu_main_settings_section',
				__( 'Notifications Settings', 'wp-edit-username' ),
				'__return_empty_string',
				'wpeu_settings_section'
			);

			// add_settings_field( $id, $title, $callback, $page, $section, $args )
			add_settings_field(
				'wpeu_send_email_field',
				__( 'Send Email', 'wp-edit-username' ),
				array( $this, 'send_email_field_setting' ),
				'wpeu_settings_section',
				'wpeu_main_settings_section'
			);
			
			// add_settings_field( $id, $title, $callback, $page, $section, $args )
			add_settings_field(
				'wpeu_email_receiver_field',
				__( 'Send Emails To', 'wp-edit-username' ),
				array( $this, 'email_receiver_field_setting' ),
				'wpeu_settings_section',
				'wpeu_main_settings_section'
			);
			
			// add_settings_field( $id, $title, $callback, $page, $section, $args )
			add_settings_field(
				'wpeu_email_subject_field',
				__( 'Email Subject', 'wp-edit-username' ),
				array( $this, 'email_subject_field_setting' ),
				'wpeu_settings_section',
				'wpeu_main_settings_section'
			);

			// add_settings_field( $id, $title, $callback, $page, $section, $args )
			add_settings_field(
				'wpeu_email_body_field',
				__( 'Email Body Text', 'wp-edit-username' ),
				array( $this, 'email_body_field_setting' ),
				'wpeu_settings_section',
				'wpeu_main_settings_section'
			);

			// register_setting( $option_group, $option_name, $sanitize_callback )
			register_setting( 'wpeu_settings_group', 'wpeu_register_settings_fields', array( $this, 'validate_settings_input' ) );
		}

		/**
		 * Validate User Input
		 *
		 * @since    1.0.6
		 */
		public function validate_settings_input( $arr_input )
		{
			$options 								=  get_option( 'wpeu_register_settings_fields' );
			
			$options['wpeu_send_email_field'] 		= sanitize_text_field( $arr_input['wpeu_send_email_field'] );
			
			$options['wpeu_email_receiver_field'] 	= sanitize_text_field( $arr_input['wpeu_email_receiver_field'] );
			
			$options['wpeu_email_subject_field'] 	= sanitize_text_field( $arr_input['wpeu_email_subject_field'] );
			
			$options['wpeu_email_body_field'] 		= sanitize_textarea_field( $arr_input['wpeu_email_body_field'] );
			
			return $options;
		}

		public function send_email_field_setting()
		{
			?>
				<input type="checkbox" name="wpeu_register_settings_fields[wpeu_send_email_field]" <?php if( isset( $this->options['wpeu_send_email_field'] ) ) checked( 'on', $this->options['wpeu_send_email_field'], true ); ?> />
			<?php
		}

		public function email_receiver_field_setting()
		{
			?>
				<input type="radio" name="wpeu_register_settings_fields[wpeu_email_receiver_field]" value="admin_only" <?php if( isset( $this->options['wpeu_email_receiver_field'] ) ) checked( 'admin_only' == $this->options['wpeu_email_receiver_field'] ); ?> /> <?php _e( 'Admins Only', 'wp-edit-username' ); ?>

				<input type="radio" name="wpeu_register_settings_fields[wpeu_email_receiver_field]" value="user_only" <?php if( isset( $this->options['wpeu_email_receiver_field'] ) ) checked( 'user_only' == $this->options['wpeu_email_receiver_field'] ); ?> /> <?php _e( 'User Only', 'wp-edit-username' ); ?>
			
				<input type="radio" name="wpeu_register_settings_fields[wpeu_email_receiver_field]" value="admin_user" <?php if( isset( $this->options['wpeu_email_receiver_field'] ) ) checked( 'admin_user' == $this->options['wpeu_email_receiver_field'] ); ?> /> <?php _e( 'Admins & User', 'wp-edit-username' ); ?>
			<?php
		}

		public function email_subject_field_setting()
		{
			?><input type="text" class="widefat" name="wpeu_register_settings_fields[wpeu_email_subject_field]" value="<?php if ( isset( $this->options['wpeu_email_subject_field'] ) ){ echo esc_attr( $this->options['wpeu_email_subject_field'] ); } else { _e( 'Username Changed!', 'wp-edit-username' ); } ?>" /><?php
		}

		public function email_body_field_setting()
		{
			?>
				<textarea class="widefat" style="min-height: 150px;" name="wpeu_register_settings_fields[wpeu_email_body_field]" ><?php if ( isset( $this->options['wpeu_email_body_field'] ) ){ echo esc_textarea( $this->options['wpeu_email_body_field'] ); } else { _e( 'Your Username has been changed', 'wp-edit-username' ); } ?></textarea>

				<p><?php _e( 'Available shortcodes are', 'wp-edit-username' ); ?> : <code>{{first_name}}</code> <code>{{last_name}}</code> <code>{{display_name}}</code> <code>{{full_name}}</code> <code>{{old_username}}</code> <code>{{new_username}}</code></p>
			<?php
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles()
		{
			wp_enqueue_style( $this->plugin_name, WPEU_PLUGIN_URL . 'assets/css/style.css', array(), false );
			
			wp_enqueue_style( $this->plugin_name . "bootstrap_css", WPEU_PLUGIN_URL . 'assets/css/bootstrap.css', array(), '', false );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts()
		{
			wp_enqueue_script( $this->plugin_name . "bootstrap_popper", WPEU_PLUGIN_URL . 'assets/js/popper.min.js', array( 'jquery' ), '', true );
			
			wp_enqueue_script( $this->plugin_name . "bootstrap_js", WPEU_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'jquery', $this->plugin_name . "bootstrap_popper"), '', true );
			
			wp_enqueue_script( $this->plugin_name, WPEU_PLUGIN_URL . 'assets/js/script.js', array( 'jquery', $this->plugin_name . "bootstrap_js" ), '', true );
		}

		public function show_edit_modal()
		{
			?>
				<!-- Modal -->
				<div class="modal fade" id="edit_username_modal" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title" id="exampleModalLabel"><?php echo __( 'Enter New Username', 'wp-edit-username' ); ?></h2>
							</div>
							<div class="modal-body">
								<div class="alert" role="alert" id="wpeu_message"></div>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">@</span>
									</div>
									<input type="text" class="form-control" id="wpeu_new_username" placeholder="<?php echo __( 'New Username', 'wp-edit-username' ); ?>" aria-label="Username" aria-describedby="basic-addon1">
									<?php $nonce = wp_create_nonce( 'wp_edit_username_action' ); ?>
									<input type="hidden" name="wp_edit_username_nonce" id="wp_edit_username_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" id="cancel_button" class="btn btn-secondary" data-dismiss="modal"><?php echo __( 'Cancel', 'wp-edit-username' ); ?></button>
								<button type="button" class="btn btn-info" id="update_user_name_tigger"><?php echo __( 'Update Username', 'wp-edit-username' ); ?></button>
							</div>
						</div>
					</div>
				</div>

				<style>.updating::before{content: "";position: absolute;top: 0;left: 0;right: 0;bottom: 0;background-color: rgba(187, 187, 187, 0.5);width: 100%;height: 100%;z-index: 2;background-image: url("<?php echo WPEU_PLUGIN_URL . '/assets/images/loading.gif' ?>");background-repeat: no-repeat;background-position: center;}</style><?php
			}

		/**
		 * Register the ajax callback.
		 *
		 * @since    1.0.0
		 */
		public function update_user_name()
		{
			$response 			= array();

			$to 				= array();
			
			if( ! current_user_can( 'edit_users' ) ) die();

			if ( ! isset( $_POST['new_username'] ) && ! isset( $_POST['current_username'] ) )
			{
				$response['alert_message'] = __( 'Invalid fields!', 'wp-edit-username' );
					
				wp_send_json( $response ); die();
			}

			if ( isset( $_POST['wp_edit_username_nonce'] ) && wp_verify_nonce( $_POST['wp_edit_username_nonce'], 'wp_edit_username_action' ) )
			{
				$new_username 		= sanitize_user( $_POST['new_username'] );
				
				$current_username 	= sanitize_user( $_POST['current_username'] );

				// ---------------------------------------------------------
				// check if new_username is empty
				// ---------------------------------------------------------
				if( empty( $new_username ) || empty( $current_username ) )
				{
					$response['alert_message'] = __( 'Username Can Not be Empty!', 'wp-edit-username' );
					
					wp_send_json( $response ); die();
				}

				// ---------------------------------------------------------
				// Check if username consists invalid illegal character
				// ---------------------------------------------------------
				if( ! validate_username( $new_username ) )
				{
					$response['alert_message'] = __( 'Username can not have illegal characters', 'wp-edit-username' );
					
					wp_send_json( $response ); die();
				}

				// ---------------------------------------------------------
				// Filters the list of blacklisted usernames.
				//
				// @https://developer.wordpress.org/reference/hooks/illegal_user_logins/
				// ---------------------------------------------------------
				$illegal_user_logins = array_map( 'strtolower', (array) apply_filters( 'illegal_user_logins', array() ) );

				if ( in_array( $new_username, $illegal_user_logins ) )
				{
					$response['alert_message'] =  __( 'Sorry, that username is not allowed.', 'wp-edit-username' );
					
					wp_send_json( $response ); die();
				}

				// ---------------------------------------------------------
				// If $new_username already registered
				// ---------------------------------------------------------
				if( username_exists( $new_username ) )
				{
					$response['alert_message'] =  __( 'Sorry, that username already exists and not available.', 'wp-edit-username' );
					
					wp_send_json( $response ); die();
				}

				global $wpdb;

				// ---------------------------------------------------------
				// Change / Update Username With old one
				// ---------------------------------------------------------
				$query  = $wpdb->prepare( "UPDATE $wpdb->users SET user_login = %s WHERE user_login = %s", $new_username, $current_username );
				
				$result = $wpdb->query( $query );

				if ( $result > 0 )
				{
					$response['success_message'] =  sprintf( 'Username Updated from <code>%s</code> to <code>%s</code>.', $current_username, $new_username );

					if ( isset( $this->options['wpeu_send_email_field'] ) && $this->options['wpeu_send_email_field'] == 'on' )
					{
						$user_email = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE user_login = %s", $new_username ) );

						if ( isset( $this->options['wpeu_email_receiver_field'] ) )
						{						
							if ( $this->options['wpeu_email_receiver_field'] == 'user_only' )
							{    
								$to 		= array( sanitize_email( $user_email ) );
							}
							elseif ( $this->options['wpeu_email_receiver_field'] == 'admin_only' )
							{    
								$to 		= array();
								
								$admins 	= get_users( 'role=Administrator' );
								
								foreach ( $admins as $admin )
								{
									$to[] 	= sanitize_email( $admin->user_email );
								}
							}
							elseif ( $this->options['wpeu_email_receiver_field'] == 'admin_user' )
							{
								$to 		= array( sanitize_email( $user_email ) );
								
								$admins 	= get_users( 'role=Administrator' );
								
								foreach ( $admins as $admin )
								{
									$to[] 	= sanitize_email( $admin->user_email );
								}
							}
						}

						$subject = apply_filters( 'wp_username_changed_email_subject', $subject = $this->options['wpeu_email_subject_field'], $old_username, $new_username );

						$body 	 = apply_filters( 'wp_username_changed_email_body', $body = $this->options['wpeu_email_body_field'], $old_username, $new_username );

						$user 	 = get_user_by( 'login', $new_username );
				
						if( $user )
						{
							$body = str_replace( [ '{{first_name}}', '{{last_name}}', '{{display_name}}', '{{full_name}}', '{{old_username}}', '{{new_username}}' ], [ $user->first_name, $user->last_name, $user->display_name, $user->first_name . ' ' . $user->last_name, $current_username, $new_username ], $body );
						}

						$headers = array( 'Content-Type: text/html; charset=UTF-8' );

						foreach ( $to as $email )
						{
							wp_mail( $email, $subject, $body, $headers );
						}
					}
				}

				// ---------------------------------------------------------
				// Change / Update nicename if == username
				// ---------------------------------------------------------
				$query = $wpdb->prepare( "UPDATE $wpdb->users SET user_nicename = %s WHERE user_login = %s AND user_nicename = %s", $new_username, $new_username, $current_username );
				
				$wpdb->query( $query );

				// ---------------------------------------------------------
				// Change / Update display name if == username
				// ---------------------------------------------------------
				$query  = $wpdb->prepare( "UPDATE $wpdb->users SET display_name = %s WHERE user_login = %s AND display_name = %s", $new_username, $new_username, $current_username );
				
				$wpdb->query( $query );

				// ---------------------------------------------------------
				// Update Username on Multisite
				// ---------------------------------------------------------
				if( is_multisite() )
				{
					$super_admins = (array) get_site_option( 'site_admins', array( 'admin' ) );
					
					$array_key = array_search( $current_username, $super_admins );

					if( $array_key )
					{
						$super_admins[ $array_key ] = $new_username;
					}
					
					update_site_option( 'site_admins' , $super_admins );
				}
			}
			else
			{
				$response['alert_message'] =  __( 'Nonce verification failed.', 'wp-edit-username' );
			}

			wp_send_json( $response ); die();
		}
	}
}

$WP_Edit_Username = new WP_Edit_Username();
