<?php
/**
 * Banner type template
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
do_action( 'woocommerce_before_main_content' );

	if (get_query_var( 'banner_type' ) == 'yes' ) :

	?><div class="woocommerce"><?php

	$options = get_option( 'bannerwoo_settings' );
	$order = wc_get_order( get_query_var( 'view-order' ) );

	if ( sizeof( $order->get_items() ) > 0 ) {

		foreach( $order->get_items() as $item_id => $item ) {

			$prid = $item['product_id'];

			if (get_post_meta( $prid, '_tipo', true ) == 'bannerwoo') {

				$rot = $item['this_banner_rotation'];
				$state = get_post_meta( $prid, '_rot_' . $rot . '_state', true );

				if ( $state != 'active' ) {
					?><div class="woocommerce-message"><?php _e( 'Your banner is active now!', 'bannerwoo' ); ?></div><?php
					// Set status active
					$state = 'active';
					
				}
				update_post_meta( $prid, '_rot_' . $rot . '_state', $state );

				// get banner size
				$banner_size = get_post_meta( $prid, 'bannerwoo_size_select', true );
				$temp_size = explode( ',', $banner_size );
				$banner_size_x = $temp_size[0];
				$banner_size_y = $temp_size[1];

				?><h2><?php _e( 'Banner Details', 'bannerwoo' ); ?></h2><?php

				// handle image banner
				if ( $state == 'active' ) {

					// get current banner image
					$tasto = '';
					$image_id =  get_post_meta( $prid, '_rot_' . $rot . '_image', true );
		
					$filetype = wp_check_filetype( wp_get_attachment_url( $image_id ) );
		
					if ( $image_id && ( $filetype['ext'] == 'jpg' || $filetype['ext'] == 'png' || $filetype['ext'] == 'gif' ) ) {

						?><div class="banner_<?php echo str_replace( ',', 'x', $banner_size ); ?>"><?php
						echo wp_get_attachment_image( $image_id, str_replace( ',', 'x', $banner_size ) );
						?></div><?php
						$tasto = __( 'Update Banner', 'bannerwoo' );

					} else {

						?><div style="background: <?php echo isset($options['bannerwoo_text_field_0']) ? $options['bannerwoo_text_field_0'] : '#ffffff'; ?>; border-color: <?php echo isset($options['bannerwoo_text_field_0a']) ? $options['bannerwoo_text_field_0a'] : '#cccccc'; ?>;" class="bannerw banner_<?php echo str_replace( ',', 'x', $banner_size ); ?>">
						<span style="color: <?php echo isset($options['bannerwoo_text_field_0b']) ? $options['bannerwoo_text_field_0b'] : '#888888'; ?>"><?php echo $banner_size_x . ' x ' . $banner_size_y; ?></span>
						</div><?php
						$tasto = __( 'Upload Banner', 'bannerwoo' );

					}
		
					// handle upload
					if ( ! function_exists( 'wp_handle_upload' ) ) {
    					require_once( ABSPATH . 'wp-admin/includes/file.php' );
					}

					// Check that the nonce is valid, and the user can edit this post.
					if ( 
						isset( $_POST['my_image_upload_nonce'] ) 
						&& wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' )
					) {
						// The nonce was valid and the user has the capabilities, it is safe to continue.
						// These files need to be included as dependencies when on the front end.
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
						// Let WordPress handle the upload.
						// Remember, 'my_image_upload' is the name of our file input in our form above.
						$attachment_id = media_handle_upload( 'my_image_upload', 0 );
						$destination = esc_url( $_POST['destination_url'] );
	
						if ( is_wp_error( $attachment_id ) ) {

							// There was an error uploading the image.
							echo '<p class="red">' . __( 'Upload error', 'bannerwoo' ) . '</p>';

						} elseif ( ! $destination ) {

							// Destination Url field is empty.
							echo '<p class="red">' . __( 'Destination Url field is empty', 'bannerwoo' ) . '</p>';

						} else {

							update_post_meta( $prid, '_rot_' . $rot . '_url', $destination );
							update_post_meta( $prid, '_rot_' . $rot . '_image', esc_attr( $attachment_id ) );
							wp_safe_redirect( add_query_arg( 'banner_type', 'yes', $order->get_view_order_url() ) );
							exit();

						}

					}

					?>

					<form class="login" id="featured_upload" method="post" action="#" enctype="multipart/form-data">
						<p class="form-row form-row-wide">
							<label for="destination_url"><?php _e( 'Destination Url', 'bannerwoo' ); ?></label>
							<input class="input-text" type="text" name="destination_url" id="destination_url" value="<?php echo get_post_meta( $prid, '_rot_' . $rot . '_url', true); ?>" />
						</p>
						<p class="form-row form-row-wide">
							<label for="destination_url"><?php _e( 'Select File', 'bannerwoo' ); ?></label>
							<input class="input-text" type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
						</p>

						<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
						<p class="form-row">
							<input class="button" id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="<?php echo $tasto; ?>" />
						</p>
					</form>

					

					<?php

				}

			}
		
		}	
	}

	?></div><?php
	endif;

do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
?>