<?php
/**
 * My banners
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('woocommerce_before_my_account', 'my_bannerwoo');
function my_bannerwoo() {
	$customer_banners = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
		'meta_key'    => '_customer_user',
		'meta_value'  => get_current_user_id(),
		'post_type'   => wc_get_order_types( 'view-orders' ),
		'post_status' => 'completed',
		
		
	) ) );

	if ( $customer_banners ) : 

		echo '<h2>' .__('My Banners','bannerwoo'). '</h2>';
		?>
		<table class="shop_table shop_table_responsive my_account_orders">
			<tr>
 				<th><?php _e( 'Banner', 'bannerwoo' ); ?></th>
				<th><?php _e( 'Size', 'bannerwoo' ); ?></th>
				<th><?php _e( 'Expires', 'bannerwoo' ); ?></th>
				<th><?php _e( 'Status', 'bannerwoo' ); ?></th>
				<th></th>
			</tr>
		<?php
		
		foreach ( $customer_banners as $customer_banner ) {
			$order_id = $customer_banner->ID;
			$order = wc_get_order( $order_id );
			
			if ( sizeof( $order->get_items() ) > 0 ) {

				foreach( $order->get_items() as $item_id => $item ) {

				$prid = $item['product_id'];

				if (get_post_meta( $prid, '_tipo', true ) == 'bannerwoo') {

					$pritems = $item['qty'];
					$rot = $item['this_banner_rotation'];
					$prstart = $order->order_date;
					update_post_meta( $prid, '_rot_' . $rot . '_order_date', $prstart );
					
					// get banner size
					$banner_size = get_post_meta( $prid, 'bannerwoo_size_select', true );
					$temp_size = explode( ',', $banner_size );
					$banner_size_x = $temp_size[0];
					$banner_size_y = $temp_size[1];

					// get status
					$bannerwoo_unit_time = get_post_meta( $prid, 'bannerwoo_unit_time', true );
					$build_exp = strtotime( $prstart . '+' . $pritems . ' ' . $bannerwoo_unit_time );
					update_post_meta( $prid, '_rot_' . $rot . '_expire', $build_exp );
					$state = get_post_meta( $prid, '_rot_' . $rot . '_state', true );
					if ( $state != 'active' ) {
						$state = 'no active';
					}
					if (strtotime('today UTC') > $build_exp) {
						$state = 'expired';
					}
					
					// Set status
					update_post_meta( $prid, '_rot_' . $rot . '_state', $state );
				
					?>
					<tr>
						<td><?php echo get_the_title( $prid ); ?></td>
						<td><?php echo $banner_size_x . ' x ' . $banner_size_y; ?></td>
						<td><?php echo date_i18n( get_option( 'date_format' ), $build_exp ); ?></td>
						<td><?php
							if ( $state == 'expired' ) {
								?><span class="red"><?php _e( 'expired', 'bannerwoo' ); ?></span><?php
							} elseif ( $state == 'no active' ) {
								?><span class="orange"><?php _e( 'no active', 'bannerwoo' ); ?></span><?php
							} else {
								?><span class="green"><?php _e( 'active', 'bannerwoo' ); ?></span><?php
							}

						?></td>

						<td class="right"><?php
							if ( $state == 'expired' ) {
								?><span></span><?php
							} elseif ( $state == 'no active' ) {
								?><a href="<?php echo add_query_arg( 'banner_type', 'yes', $order->get_view_order_url() ); ?>" class="button" ><?php _e( 'Activate Now', 'bannerwoo' ); ?></a><?php
							} else {
								?><a href="<?php echo add_query_arg( 'banner_type', 'yes', $order->get_view_order_url() ); ?>" class="button" ><?php _e( 'Edit', 'bannerwoo' ); ?></a><?php
							}
						?></td>
					</tr>
					<?php
					
				}

				}

			}
			
		}

		?></table><?php

	endif;
}


?>