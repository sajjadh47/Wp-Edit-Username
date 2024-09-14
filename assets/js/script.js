jQuery( document ).ready( function( $ )
{
	var $container 					= $( ".user-user-login-wrap" );

	var $input  					= $container.find( 'input#user_login' );

	var $update_user_name_tigger 	= $( "#update_user_name_tigger" );

	var $wpeu_new_username 			= $( "#wpeu_new_username" );
	
	var $wp_edit_username_nonce 	= $( "#wp_edit_username_nonce" );
	
	var $wpeu_message 				= $( "#wpeu_message" );

	$container.find( '.description' ).remove();
	
	$input.after( '<button class="button" id="edit_username_modal_btn" data-toggle="modal" data-target="#edit_username_modal">Edit</button>' );

	$( document ).on( 'click', "#edit_username_modal_btn", function( e )
	{	
		e.preventDefault();
		
		$update_user_name_tigger.removeAttr( 'disabled' );
	} );

	$( "#cancel_button" ).click( function( event )
	{	
		$wpeu_new_username.val( '' );
		
		$wpeu_message.empty().hide();
	} );

	$wpeu_new_username.keyup( function( event )
	{	
		if ( $( this ).val === '' )
		{	
			$update_user_name_tigger.prop( 'disabled', 'disabled' );
		}
		else
		{
			$update_user_name_tigger.removeAttr( 'disabled' );
		}
	} );

	$( document ).on( 'click', "#update_user_name_tigger", function( e )
	{
		$wpeu_message.empty().hide();
		
		$( "#edit_username_modal .modal-content" ).addClass( 'updating' );

		var data =
		{	
			action 					: 'wpeu_update_user_name',
			
			wp_edit_username_nonce 	: $wp_edit_username_nonce.val(),
			
			current_username 		: $input.val(),
			
			new_username 			: $wpeu_new_username.val()
		};

		$.post( ajaxurl, data ).done( function( msg )
		{	
			$update_user_name_tigger.prop( 'disabled', 'disabled' );
			
			$( "#edit_username_modal .modal-content" ).removeClass( 'updating' );
			
			if ( msg.alert_message !== undefined )
			{
				$( "#wpeu_message" ).addClass( 'alert-danger' ).html( '<span class="dashicons dashicons-no"></span>' + msg.alert_message ).show();
			}
			else if( msg.success_message !== undefined )
			{
				$input.val( $wpeu_new_username.val() );
			
				$wpeu_new_username.val('');
			
				$wpeu_message.addClass( 'alert-success' ).html( msg.success_message ).show();
			}
		} );
	} );
} );