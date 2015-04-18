/**
 * Bannerwoo admin js functions.
 */

( function( $ ) {

		// enable colorpicker
		$('.bannerwoo-color-field').wpColorPicker();
	
		// banner_type show/hide field in woocommerce general product
		if ( $('#product-type').val() == 'banner_type' ) {
			$('.show_bannerwoo').show();
			$('#_tipo').val('bannerwoo');
			if ( $('#bannerwoo_max_rotation').val() == '') {
				$('#bannerwoo_max_rotation').val('1');
			}
		} else {
			$('.show_bannerwoo').hide();
			$('#_tipo').val('no_banner');
		}
		$('#product-type').on('change', function() {
			if ( this.value == 'banner_type' ) {
				$('.show_bannerwoo').show();
				$('#_tipo').val('bannerwoo');
				if ( $('#bannerwoo_max_rotation').val() == '') {
					$('#bannerwoo_max_rotation').val('1');
				}
			} else {
				$('.show_bannerwoo').hide();
				$('#_tipo').val('no_banner');
			}
		});
		
} )( jQuery );