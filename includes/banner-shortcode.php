<?php
/**
 * Banner shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function bannerwoo_shortcode_banner( $atts ) {

	// Attributes
	$a = ( shortcode_atts(
		array(
			'id' => '',
			'alignment' => 'left',
		), $atts )
	);
	
	// get id
	$id = esc_attr($a['id']);
	if ( FALSE === get_post_status( $id ) ) {
		// The post does not exist
		echo '<p class="red">' . __( 'Error. This banner does not exist!', 'bannerwoo' ) . '</p>';
		return;
	}

	// get alignment
	$alignment = esc_attr($a['alignment']);

	// verify expiration
	$current_rot = get_post_meta( $id, '_current_rot', true);
	$build_exp = get_post_meta( $id, '_rot_' . $current_rot . '_expire', true );
	$state='';
	if (strtotime('today') > $build_exp) {
		
		$state = 'expired';
		update_post_meta( $id, '_rot_' . $current_rot . '_state', $state );
	}
	$state = get_post_meta( $id, '_rot_' . $current_rot . '_state', true );
	
	// build the banner
	$options = get_option( 'bannerwoo_settings' );
	$max = get_post_meta( $id, 'bannerwoo_max_rotation', true );
	$id_user = get_post_meta( $id, '_rot_' . $current_rot . '_user', true);
	$id_immagine = get_post_meta( $id, '_rot_' . $current_rot . '_image', true );
	$url_immagine = get_post_meta( $id, '_rot_' . $current_rot . '_url', true );
	$filetype = wp_check_filetype( wp_get_attachment_url( $id_immagine ) );
	$banner_size = get_post_meta( $id, 'bannerwoo_size_select', true );
	$temp_size = explode( ',', $banner_size );
	$banner_size_x = $temp_size[0];
	$banner_size_y = $temp_size[1];
	
	// set the date
	$bannerwoo_unit_time = get_post_meta( $id, 'bannerwoo_unit_time', true );
	$prstart = get_post_meta( $id, '_rot_' . $current_rot . '_order_date', true );
	$data_start = strtotime( $prstart );
 	$data_end = strtotime( 'today UTC' );
 	$date1 = new DateTime(date_i18n( get_option( 'date_format' ), $data_start ));
	$date2 = new DateTime(date_i18n( get_option( 'date_format' ), $data_end ));
	$diff = $date2->diff($date1)->format("%a");
	$oggi = strtotime( $prstart . '+' . $diff . ' ' . $bannerwoo_unit_time )*1000;

	
	ob_start();
	?>

	<div class="banner_alignment_<?php echo $alignment; ?>">
					
		<?php if ( $state == 'active' && ( $filetype['ext'] == 'jpg' || $filetype['ext'] == 'png' || $filetype['ext'] == 'gif' ) ) {

			?><div class="banner_<?php echo str_replace( ',', 'x', $banner_size ); ?>">
				<a href="<?php echo $url_immagine; ?>">
					<?php echo wp_get_attachment_image( $id_immagine, str_replace( ',', 'x', $banner_size ) ); ?>
				</a>
			</div><?php

		} else {

			?><div style="background: <?php echo isset($options['bannerwoo_text_field_0']) ? $options['bannerwoo_text_field_0'] : '#ffffff'; ?>; border-color: <?php echo isset($options['bannerwoo_text_field_0a']) ? $options['bannerwoo_text_field_0a'] : '#cccccc'; ?>;" class="bannerw banner_<?php echo str_replace( ',', 'x', $banner_size ); ?>">
				<?php if ( $state == 'active' ) { ?>
					<span style="color: <?php echo isset($options['bannerwoo_text_field_0b']) ? $options['bannerwoo_text_field_0b'] : '#888888'; ?>">
				<?php } else { ?>
					<?php // set a session ?>
					<?php global $woocommerce; ?>
					<?php unset($woocommerce->session->ban_rot); ?>
					<?php $woocommerce->session->ban_rot = $current_rot; ?>
					<a style="color: <?php echo isset($options['bannerwoo_text_field_0b']) ? $options['bannerwoo_text_field_0b'] : '#888888'; ?>" href="<?php echo get_the_permalink($id); ?>">
				<?php } ?>
					<?php if ( $state == 'active' ) { ?>
						<?php echo $banner_size_x . ' x ' . $banner_size_y; ?>
					<?php } else { ?>
						<?php echo isset($options['bannerwoo_text_field_0c']) ? $options['bannerwoo_text_field_0c'] : 'Buy now!'; ?>
					<?php } ?>
				<?php if ( $state == 'active' ) { ?>
					</span>
				<?php } else { ?>
					</a>
				<?php } ?>
			</div><?php
			
		} ?>
	
	</div>

	<div class="clear_<?php echo $alignment; ?>"></div>


	<?php
	/**
 	* Clean expired
 	*/
 	if ( $state == 'expired' ) {
		update_post_meta( $id, '_rot_' . $current_rot . '_user', '');
		update_post_meta( $id, '_rot_' . $current_rot . '_image', '' );
		update_post_meta( $id, '_rot_' . $current_rot . '_url', '' );
		update_post_meta( $id, '_rot_' . $current_rot . '_expire', '' );
		//update_post_meta( $id, '_rot_' . $current_rot . '_state', '' );
	}

	/**
 	* Increment rotation
 	*/
	$bloop = ++$current_rot;
	if ( $bloop > $max ) {
		$bloop = 1;
	}
	update_post_meta( $id, '_current_rot', $bloop);
		
	return ob_get_clean();

}
add_shortcode( 'banner', 'bannerwoo_shortcode_banner' );



?>
