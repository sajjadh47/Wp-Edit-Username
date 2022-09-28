<?php

add_action( "admin_footer", "wpeu_add_edit_modal" );

function wpeu_add_edit_modal()
{
	?>
		<!-- Modal -->
		<div class="modal fade" id="edit_username_modal" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h2 class="modal-title" id="exampleModalLabel"><?php echo __( 'Enter New Username' ); ?></h2>
					</div>
					<div class="modal-body">
						<div class="alert" role="alert" id="wpeu_message"></div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">@</span>
							</div>
							<input type="text" class="form-control" id="wpeu_new_username" placeholder="<?php echo __( 'New Username' ); ?>" aria-label="Username" aria-describedby="basic-addon1">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="cancel_button" class="btn btn-secondary" data-dismiss="modal"><?php echo __( 'Cancel' ); ?></button>
						<button type="button" class="btn btn-info" id="update_user_name_tigger"><?php echo __( 'Update Username' ); ?></button>
					</div>
			 	</div>
			</div>
		</div>

		<style>.updating::before{content: "";position: absolute;top: 0;left: 0;right: 0;bottom: 0;background-color: rgba(187, 187, 187, 0.5);width: 100%;height: 100%;z-index: 2;background-image: url("<?php echo WPEU_PLUGIN_URL . '/admin/img/loading.gif' ?>");background-repeat: no-repeat;background-position: center;}</style>
<?php }
