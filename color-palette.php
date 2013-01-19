<?php

class Color_Palette {

	public $palette = array();

	public $default_colors = array();
	public $labels = array();
	public $wham = array();

	public 	$allowed_properties = array(
		'color',
		'background-color',
		'border-color',
		'border-top-color',
		'border-bottom-color',
		'border-right-color',
		'border-left-color',
		'outline-color'
	);

	public function __construct() {

		$supports = get_theme_support( 'color-palette' );

		$this->palette = $supports[0];

		add_action( 'customize_register', array( &$this, 'customize_register' ) );

		add_filter( 'body_class', array( &$this, 'body_class' ) );

		add_action( 'wp_head', array( &$this, 'custom_colors_callback' ) );
	}

	public function body_class( $classes ) {

		$classes[] = 'custom-colors';

		return $classes;
	}

	public function customize_register( $wp_customize ) {

		$i = 0;

		foreach ( $this->palette as $name => $args ) {

			$wp_customize->add_setting(
				"color_palette_{$name}",
				array(
					'default'              => "#{$args['default']}",
					'type'                 => 'theme_mod',
					'capability'           => 'edit_theme_options',
					'sanitize_callback'    => 'sanitize_hex_color_no_hash',
					'sanitize_js_callback' => 'maybe_hash_hex_color',
					'transport'            => 'postMessage'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					"color-palette-control-{$name}",
					array(
						'label'    => sprintf( __( '%s Color', 'color-palette' ), $this->palette[ $name ]['label'] ),
						'section'  => 'colors',
						'settings' => "color_palette_{$name}"
					)
				)
			);
		}

		/* If viewing the customize preview screen, add a script to show a live preview. */
		if ( $wp_customize->is_preview() && !is_admin() )
			add_action( 'wp_footer', array( &$this, 'customize_preview_script' ), 21 );
	}


	function remove_js_pseudo( $element ) {

		if ( false === strpos( $element, ':' ) )
			return true;

		return false;
	}

	function get_js_hover( $element ) {

		if ( strpos( $element, ':hover' ) )
			return true;

		return false;
	}

	public function custom_colors_callback() {

		$style = '';

		foreach ( $this->palette as $name => $args ) {

			$color = get_theme_mod( "color_palette_{$name}", $args['default'] );


			foreach ( $args['properties'] as $property => $elements )
				$style .= join( ', ', $elements ) . " { {$property}: #{$color}; }";
		}

		/* Output the custom colors style. */
		echo "\n" . '<style type="text/css" id="custom-colors-css">' . trim( $style ) . '</style>' . "\n";
	}

	public function customize_preview_script() { ?>

		<script type="text/javascript">

		<?php foreach ( $this->palette as $name => $args ) { ?>
			wp.customize( 
				'color_palette_<?php echo $name; ?>',
				function( value ) {
					value.bind(
						function( to ) {
						<?php foreach ( $args['properties'] as $property => $elements ) { 

							$elements = array_filter( $elements, array( $this, 'remove_js_pseudo' ) );

							$do_not_overwrite = apply_filters( 'color_palette_js_do_not_overwrite', '', $name, $property );

							$not = !empty( $do_not_overwrite ) ? ".not( '{$do_not_overwrite}' )" : '';

							?>
							
							jQuery( '<?php echo join( ', ', $elements ); ?>' )<?php echo $not; ?>.css( '<?php echo $property; ?>', to );
						<?php } ?>
						}
					);
				}
			);
		<?php } ?> 

		</script><?php 
	}

}

$color_palette = new Color_Palette();






?>