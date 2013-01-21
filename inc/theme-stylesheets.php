<?php
/**
 * Theme Stylesheets - A script for allowing themes to load additional stylesheets.
 *
 * This script was created to be used in conjunction with the WordPress theme customizer.  It allows 
 * theme developers to set up some various, selectable style options.  The user can then select a 
 * style for each option at which point the new stylesheet is loaded for that option.  
 *
 * One example usage might be to have a "h1 - h6" header font option that allows the user to load a 
 * stylesheet with a specific font via @font-face.  Another example might be to load a stylesheet 
 * based on a fluid- vs. fixed-width design of the theme.  Or, you can have multiple "skins" for 
 * your theme.  Use your imagination.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   ThemeStylesheets
 * @version   0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2013, Justin Tadlock
 * @link      http://justintadlock.com
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Wrapper class to handle the theme stylesheets functionality.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
class Theme_Stylesheets {

	/**
	 * Theme-defined style options.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array
	 */	 
	public $styles = array();

	/**
	 * Value of the stylesheet directory path.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string
	 */
	public $stylesheet_dir = '';

	/**
	 * Value of the template directory path.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string
	 */
	public $template_dir = '';

	/**
	 * Value of the stylesheet directory URI path.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string
	 */
	public $stylesheet_dir_uri = '';

	/**
	 * Value of the template directory URI path.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string
	 */
	public $template_dir_uri = '';

	/**
	 * Sets up the feature.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Get the theme's stylesheet settings for this feature. */
		$supports = get_theme_support( 'theme-stylesheets' );

		/* If no stylesheet options are defined, there's nothing to do. */
		if ( empty( $supports[0] ) || !is_array( $supports ) )
			return;

		/* Set up the $styles property. */
		$this->styles = $supports[0];

		/* Set up the directory and directory URI paths. */
		$this->stylesheet_dir     = trailingslashit( get_stylesheet_directory() );
		$this->template_dir       = trailingslashit( get_template_directory() );
		$this->stylesheet_dir_uri = trailingslashit( get_stylesheet_directory_uri() );
		$this->template_dir_uri   = trailingslashit( get_template_directory_uri() );

		/* Enqueue the stylesheets with a priority of '15'. */
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_styles' ), 15 );

		/* Set up the theme customizer section, settings, and controls. */
		add_action( 'customize_register', array( &$this, 'customize_register' ) );

		/* Ajax callback for the live preview when using the theme customizer. */
		add_action( 'wp_ajax_theme_stylesheets_customize_ajax', array( &$this, 'customize_preview_ajax' ) );
		add_action( 'wp_ajax_nopriv_theme_stylesheets_customize_ajax', array( &$this, 'customize_preview_ajax' ) );
	}

	/**
	 * Enqueues the stylesheets for each style option.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_styles() {

		/* Set up an array for loaded stylesheets so that we don't load multiples of the same file. */
		$loaded = array();

		/* Loop through each of the stylesheet options. */
		foreach ( $this->styles as $name => $args ) {

			/* Get the theme mod setting for the stylesheet. */
			$stylesheet = get_theme_mod( "theme_stylesheet_{$name}", $args['default'] );

			/* Get the full stylesheet with URI path. */
			$style_uri = $this->load_style( $stylesheet );

			/* If we have a stylesheet and it hasnt already been loaded, enqueue it. */
			if ( !empty( $style_uri ) && !in_array( $style_uri, $loaded ) )
				wp_enqueue_style( "theme-style-{$name}", esc_url( $style_uri ), null, '', 'all' );

			/* Add the stylesheet file name to the $loaded array. */
			$loaded[] = $style_uri;
		}
	}

	/**
	 * Sets up the theme customizer section, settings, and controls.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function customize_register( $wp_customize ) {

		/* Add the 'styles' section. */
		$wp_customize->add_section(
			'theme-styles',
			array(
				'title'      => esc_html__( 'Styles', 'theme-styles' ),
				'priority'   => 35,
				'capability' => 'edit_theme_options'
			)
		);

		/* Each control will be given a priority of $priority + 10. */
		$priority = 0;

		/* Loop through each of the style options and add settings and controls. */
		foreach( $this->styles as $name => $args) {

			/* Get the file header if the theme defined one. */
			$file_header = isset( $args['file_header'] ) ? $args['file_header'] : '';

			/* Get the stylesheets from the parent and child theme folders. */
			$choices = array_flip( $this->get_styles( $file_header ) );

			/* If any stylesheets were found, add a setting and control for this style option. */
			if ( !empty( $choices ) ) {

				/* Iterate the priority. */
				$priority = $priority + 10;

				/* Add the stylesheet setting. */
				$wp_customize->add_setting(
					"theme_stylesheet_{$name}",
					array(
						'default'              => get_theme_mod( "theme_stylesheet_{$name}", $args['default'] ),
						'type'                 => 'theme_mod',
						'capability'           => 'edit_theme_options',
						'priority'             => $priority,
						'sanitize_callback'    => array( $this, 'sanitize_file_name' ),
						'sanitize_js_callback' => array( $this, 'sanitize_file_name' ),
						'transport'            => 'postMessage'
					)
				);

				/* Add the stylesheet control. */
				$wp_customize->add_control(
					"theme-stylesheet-{$name}",
					array(
						'label'    => $args['label'],
						'section'  => 'theme-styles',
						'settings' => "theme_stylesheet_{$name}",
						'type'     => 'select',
						'choices'  => $choices
					)
				);
			}
		}

		/* If viewing the customize preview screen, add a script to show a live preview. */
		if ( $wp_customize->is_preview() && !is_admin() )
			add_action( 'wp_footer', array( &$this, 'customize_preview_script' ), 21 );
	}

	/**
	 * Outputs the jQuery neeeded for the live preview on the theme customizer screen.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function customize_preview_script() { 

		/* Create a nonce for the Ajax. */
		$nonce = wp_create_nonce( 'theme_stylesheets_customize_nonce' ); ?>

		<script type="text/javascript">

		<?php foreach( $this->styles as $name => $args ) { ?>

			wp.customize(
				'theme_stylesheet_<?php echo $name; ?>',
				function( value ) {
					value.bind( 
						function( to ) {
							jQuery.post( 
								'<?php echo admin_url( 'admin-ajax.php' ); ?>', 
								{ 
									action:          'theme_stylesheets_customize_ajax',
									_ajax_nonce:     '<?php echo $nonce; ?>',
									theme_stylesheet: to
								},
								function( response ) {
									jQuery( 'link#theme-style-<?php echo $name; ?>-css' ).attr( 'href', response );
								}
							);
						} 
					);
				}
			);

		<?php } ?>
		</script><?php
	}

	/**
	 * Ajax callback function for loading a new stylesheet on the theme customizer screen.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function customize_preview_ajax() {

		/* Check the AJAX nonce to make sure this is a valid request. */
		check_ajax_referer( 'theme_stylesheets_customize_nonce' );

		/* If a stylesheet file name was posted, load the style (sanitized in the load_style() method). */
		if ( isset( $_POST['theme_stylesheet'] ) )
			echo $this->load_style( $_POST['theme_stylesheet'] );

		/* Always die() when handling Ajax. */
		die();
	}

	/**
	 * Finds the given stylesheet name in the proper directory.  Child theme files overwrite parent 
	 * theme files.  If the file is found, it is returned with the full directory URI path.  Minimized 
	 * versions of the stylesheets are supported in the form of "style.min.css" and will be loaded 
	 * over non-minimized versions if SCRIPT_DEBUG is not enabled.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  string $stylesheet
	 * @return string
	 */
	public function load_style( $stylesheet ) {

		/* Sanitize the stylesheet name. */
		$stylesheet = $this->sanitize_file_name( $stylesheet );

		/* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		/* If using a child theme, check for the '{$style}.min.css' file and the '{$style}.css' file. */
		if ( is_child_theme() ) {

			if ( !empty( $suffix ) && file_exists( $this->stylesheet_dir . $suffix . $stylesheet ) )
				$style_uri = $this->stylesheet_dir_uri . $suffix . $stylesheet;

			elseif ( file_exists( $this->stylesheet_dir . $stylesheet ) )
				$style_uri = $this->stylesheet_dir_uri . $stylesheet;
		}

		/* If no style, check for the '{$style}.min.css' file and the '{$style}.css' file in the template directory. */
		if ( empty( $style_uri ) ) {

			if ( !empty( $suffix ) && file_exists( $this->template_dir . $suffix . $stylesheet ) )
				$style_uri = $this->template_dir_uri . $suffix . $stylesheet;

			elseif ( file_exists( $this->template_dir . $stylesheet ) )
				$style_uri = $this->template_dir_uri . $stylesheet;
		}

		/* Return the stylesheet with the URI path. */
		return !empty( $style_uri ) ? $style_uri : '';
	}

	/**
	 * Loads CSS files from both the parent and child theme based on the file header data.  By default, 
	 * 'Theme Style: Example' is what it looks for, but a custom header is allowed.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  string $header
	 * @return array
	 */
	public function get_styles( $header = '' ) {

		$header = !empty( $header ) ? $header : 'Theme Style';

		/* Set up an empty styles array. */
		$styles = array();

		/* Get the theme object. */
		$theme = wp_get_theme();

		/* Get the theme CSS files two levels deep. */
		$files = (array) $theme->get_files( 'css', 2, true );

		/* Loop through each of the CSS files and check if they are styles. */
		foreach ( $files as $file => $path ) {

			/* Get file data based on the given header. */
			$headers = get_file_data( $path, array( $header => $header ) );

			/* Continue loop if the header is empty. */
			if ( empty( $headers[ $header ] ) )
				continue;

			/* Add the CSS filename and template name to the array. */
			$styles[ $file ] = $headers[ $header ];
		}

		/* Return array of styles. */
		return array_flip( $styles );
	}

	/**
	 * Sanitizes an individual stylesheet file name.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  string $file_name
	 * @return string
	 */
	public function sanitize_file_name( $file_name ) {

		add_filter( 'sanitize_file_name_chars', array( &$this, 'sanitize_file_name_chars' ) );

		$file_name = sanitize_file_name( $file_name );

		remove_filter( 'sanitize_file_name_chars', array( &$this, 'sanitize_file_name_chars' ) );

		return $file_name;
	}

	/**
	 * Filter on the WordPress 'sanitize_file_name_chars' hook to allow a '/' in the file name.
	 * This is because we're supporting both the directory and file name.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $not_allowed
	 * @return array
	 */
	public function sanitize_file_name_chars( $not_allowed ) {

		/* Get the key for the '/' character. */
		$key = array_search( '/', $not_allowed );

		/* Remove the '/' character from the not allowed characters. */
		if ( !empty( $key ) )
			unset( $not_allowed[ $key ] );

		/* Return the array of not allowed characters. */
		return $not_allowed;
	}
}

$_theme_stylesheets = new Theme_Stylesheets();

?>