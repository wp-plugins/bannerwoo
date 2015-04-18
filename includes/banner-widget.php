<?php
/**
 * Banner widget
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class bannerwoo_widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'bannerwoo_widget', __('Banner', 'bannerwoo'), array( 'description' => __( 'Insert your banner in the sidebars', 'bannerwoo' ), ) );
	}

	// Widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$bid=$instance['bid'];
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// Display the output
		echo do_shortcode('[banner id="' . $bid . '" alignment="center"]' );
		
		echo $args['after_widget'];
	}
		
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'ADV', 'bannerwoo' );
		}
		if ( isset( $instance[ 'bid' ] ) ) {
			$bid = $instance[ 'bid' ];
		} else {
			$bid = '';
		}

		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'bannerwoo' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'bid' ); ?>"><?php _e( 'Banner ID', 'bannerwoo' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'bid' ); ?>" name="<?php echo $this->get_field_name( 'bid' ); ?>" type="text" value="<?php echo esc_attr( $bid ); ?>" />
		</p>
		<?php 
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['bid'] = ( ! empty( $new_instance['bid'] ) ) ? strip_tags( $new_instance['bid'] ) : '';
		return $instance;
	}

}

// Register and load the widget
function bannerwoo_load_widget() {
	register_widget( 'bannerwoo_widget' );
}
add_action( 'widgets_init', 'bannerwoo_load_widget' );

?>