<?php

add_action( 'admin_menu', 'wpeu_add_dashboard_page' );

add_action( 'admin_init', 'wpeu_register_settings' );

// ---------------------------------------------------------
// Add Plugin Settings page to wp dashboard
// ---------------------------------------------------------

function wpeu_add_dashboard_page()
{
	add_menu_page( "Edit Username", "Edit Username", "manage_options" , "wp-edit-username", "wp_edit_username_view", "dashicons-edit" );
}

function wp_edit_username_view()
{
	$icon = "url(" . plugins_url( "icon/Users-Edit-User-icon.png", __FILE__ ) . ")"; ?>

	<div class="wrap">
	    <h2 style="background-image: <?php echo $icon; ?>;background-repeat:  no-repeat;background-position: left 12px;background-size: 25px;padding-left: 30px;">
	    	Edit Username Settings
		</h2>
	    <form action="options.php" method="post"><?php
			
			settings_fields( 'wpeu_settings_group' );

			do_settings_sections( 'wpeu_settings_section' );

			submit_button('Save Changes'); ?>

	    </form>
	</div>
 <?php }

// ---------------------------------------------------------
// Register Plugin Options Via Settins API
// ---------------------------------------------------------
function wpeu_register_settings()
{
	// add_settings_section( $id, $title, $callback, $page )
	add_settings_section(
		'wpeu_main_settings_section',
		'Main Settings',
		'wpeu_main_settings_description',
		'wpeu_settings_section'
	);

	// add_settings_field( $id, $title, $callback, $page, $section, $args )
	add_settings_field(
		'wpeu_send_email_field_setting',
		'Send Email After Username Change?',
		'wpeu_send_email_field_setting',
		'wpeu_settings_section',
		'wpeu_main_settings_section'
	);
	
	// add_settings_field( $id, $title, $callback, $page, $section, $args )
	add_settings_field(
		'wpeu_email_receiver_field',
		'Send Emails To..?',
		'wpeu_email_receiver_field_setting',
		'wpeu_settings_section',
		'wpeu_main_settings_section'
	);
	
	// add_settings_field( $id, $title, $callback, $page, $section, $args )
	add_settings_field(
		'wpeu_email_subject_field',
		'Email Subject..?',
		'wpeu_email_subject_field_setting',
		'wpeu_settings_section',
		'wpeu_main_settings_section'
	);

	// add_settings_field( $id, $title, $callback, $page, $section, $args )
	add_settings_field(
		'wpeu_email_body_field',
		'Email Body Text..?',
		'wpeu_email_body_field_setting',
		'wpeu_settings_section',
		'wpeu_main_settings_section'
	);

	// register_setting( $option_group, $option_name, $sanitize_callback )
	register_setting( 'wpeu_settings_group', 'wpeu_register_settings_fields', 'wpeu_main_settings_validate' );
}

function wpeu_main_settings_description() {}

// ---------------------------------------------------------
// Validate User Input
// ---------------------------------------------------------
function wpeu_main_settings_validate( $arr_input )
{
	$options = get_option( 'wpeu_register_settings_fields' );
	
	$options['wpeu_send_email_field'] = trim( $arr_input['wpeu_send_email_field'] );
	
	$options['wpeu_email_receiver_field'] = trim( $arr_input['wpeu_email_receiver_field'] );
	
	$options['wpeu_email_subject_field'] = trim( $arr_input['wpeu_email_subject_field'] );
	
	$options['wpeu_email_body_field'] = trim( $arr_input['wpeu_email_body_field'] );
	
	return $options;
}

function wpeu_send_email_field_setting()
{
	$options = get_option( 'wpeu_register_settings_fields' ); ?>

    <input type="checkbox" name="wpeu_register_settings_fields[wpeu_send_email_field]" <?php checked( 'on', $options['wpeu_send_email_field'],true); ?> />
<?php }

function wpeu_email_receiver_field_setting()
{
	$options = get_option( 'wpeu_register_settings_fields' ); ?>

    <input type="radio" name="wpeu_register_settings_fields[wpeu_email_receiver_field]" value="admin_only" <?php checked( 'admin_only' == $options['wpeu_email_receiver_field'] ); ?> /> Admin Only
    
    <input type="radio" name="wpeu_register_settings_fields[wpeu_email_receiver_field]" value="admin_user" <?php checked( 'admin_user' == $options['wpeu_email_receiver_field'] ); ?> /> Admin & User
<?php }

function wpeu_email_subject_field_setting()
{
	$options = get_option( 'wpeu_register_settings_fields' ); ?>

    <input type="text" class="widefat" name="wpeu_register_settings_fields[wpeu_email_subject_field]" value="<?php if ( isset( $options['wpeu_email_subject_field'] ) ){ echo $options['wpeu_email_subject_field']; } else { echo 'subject'; } ?>" />
<?php }

function wpeu_email_body_field_setting()
{
	$options = get_option( 'wpeu_register_settings_fields' ); ?>

    <textarea class="widefat" style="min-height: 150px;" name="wpeu_register_settings_fields[wpeu_email_body_field]" ><?php if ( isset( $options['wpeu_email_body_field'] ) ){ echo $options['wpeu_email_body_field']; } else { echo 'Your Username has been changed'; } ?></textarea>
<?php }
