<?php
/**
 * Bannerwoo Admin Option
 */
add_action( 'admin_menu', 'bannerwoo_add_admin_menu' );
add_action( 'admin_init', 'bannerwoo_settings_init' );

function bannerwoo_add_admin_menu(  ) { 

	add_menu_page( 'BannerWoo', 'BannerWoo', 'manage_options', 'bannerwoo', 'bannerwoo_options_page' );

}

function bannerwoo_settings_init(  ) { 

	register_setting( 'pluginPage', 'bannerwoo_settings' );

	add_settings_section(
		'bannerwoo_pluginPage_section', 
		__( 'Banners Placeholders', 'bannerwoo' ), 
		'bannerwoo_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'bannerwoo_text_field_0', 
		__( 'Background color', 'bannerwoo' ), 
		'bannerwoo_text_field_0_render', 
		'pluginPage', 
		'bannerwoo_pluginPage_section' 
	);

	add_settings_field( 
		'bannerwoo_text_field_0a', 
		__( 'Border color', 'bannerwoo' ), 
		'bannerwoo_text_field_0a_render', 
		'pluginPage', 
		'bannerwoo_pluginPage_section' 
	);

	add_settings_field( 
		'bannerwoo_text_field_0b', 
		__( 'Text/Link color', 'bannerwoo' ), 
		'bannerwoo_text_field_0b_render', 
		'pluginPage', 
		'bannerwoo_pluginPage_section' 
	);

	add_settings_field( 
		'bannerwoo_text_field_0c', 
		__( 'Text for empty banner', 'bannerwoo' ), 
		'bannerwoo_text_field_0c_render', 
		'pluginPage', 
		'bannerwoo_pluginPage_section' 
	);

}


function bannerwoo_text_field_0_render(  ) { 

	$options = get_option( 'bannerwoo_settings' );
	?>

	<input data-default-color='#ffffff' class='bannerwoo-color-field' type='text' name='bannerwoo_settings[bannerwoo_text_field_0]' value="<?php echo isset($options['bannerwoo_text_field_0']) ? $options['bannerwoo_text_field_0'] : '#ffffff'; ?>">
	<?php
	
}

function bannerwoo_text_field_0a_render(  ) { 

	$options = get_option( 'bannerwoo_settings' );
	?>

	<input data-default-color='#cccccc' class='bannerwoo-color-field' type='text' name='bannerwoo_settings[bannerwoo_text_field_0a]' value="<?php echo isset($options['bannerwoo_text_field_0a']) ? $options['bannerwoo_text_field_0a'] : '#cccccc'; ?>">
	<?php
	
}

function bannerwoo_text_field_0b_render(  ) { 

	$options = get_option( 'bannerwoo_settings' );
	?>

	<input data-default-color='#888888' class='bannerwoo-color-field' type='text' name='bannerwoo_settings[bannerwoo_text_field_0b]' value="<?php echo isset($options['bannerwoo_text_field_0b']) ? $options['bannerwoo_text_field_0b'] : '#888888'; ?>">
	<?php
	
}

function bannerwoo_text_field_0c_render(  ) { 

	$options = get_option( 'bannerwoo_settings' );
	?>

	<input type='text' name='bannerwoo_settings[bannerwoo_text_field_0c]' value="<?php echo isset($options['bannerwoo_text_field_0c']) ? $options['bannerwoo_text_field_0c'] : __( 'Buy now!', 'bannerwoo' ); ?>">
	<?php
	
}

function bannerwoo_settings_section_callback(  ) { 

	echo __( 'Set the colors and text of the banners placeholders.', 'bannerwoo' );

}


function bannerwoo_options_page(  ) { 

	?>
	<div class="wrap">
		<h2>BannerWoo</h2>
		<div class="updated woocommerce-message">
		
        	<h2><?php _e( 'Get Support and Pro Features?', 'bannerwoo' ); ?></h2>
			<p><?php _e( 'By purchasing the premium version of Bannerwoo plugin, you will take advantage of the advanced features of the product and you will get one year of free updates and support through our platform in 24h.', 'bannerwoo' ); ?></p>
			<p><a class="button-primary" href="http://www.bannerwoo.com/" target="_blank"><?php _e( 'Get Support and Pro Features', 'bannerwoo' ); ?></a></p>
    	
    	</div>
	
		<form action='options.php' method='post'>

			<?php
				settings_fields( 'pluginPage' );
				do_settings_sections( 'pluginPage' );
				submit_button();
			?>
	
		</form>
	</div>
	<?php

}

?>