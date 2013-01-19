<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    Picturesque
 * @subpackage Functions
 * @version    0.1.0
 * @since      0.1.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/themes/picturesque
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'picturesque_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
function picturesque_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	add_theme_support( 'hybrid-core-scripts', array( 'comment-reply' ) );
	add_theme_support( 'hybrid-core-styles', array( '25px', 'gallery', 'parent', 'style' ) );

	/* Add theme support for framework extensions. */
	add_theme_support( 
		'theme-layouts', 
		array( '1c', '2c-l', '2c-r' ),
		array( 'default' => '2c-l' )
	);

	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );

	add_theme_support( 
		'color-palette',
		array(
			'primary' => array(
				'default'    => 'cb5700',
				'label'      => __( 'Primary', 'chun' ),
				'properties' => array( 
					'color' => array(
						'a', 
						'pre', 
						'code', 
						'.breadcrumb-trail a', 
						'.format-link .entry-title a .meta-nav', 
						'#respond label .required', 
						'#footer a:hover'
					),
					'background-color' => array(
						'#site-title a',
						'li.comment .comment-reply-link',
					),
					'border-top-color' => array( 
						'body' 
					),
					'border-bottom-color' => array( 
						'.breaadcrumb-trail a:hover',
						'.sticky.hentry', 
						'.loop-meta', 
						'.page-template-portfolio .hentry.page'
					),
				)
			),
			'secondary' => array(
				'default'    => '050505',
				'label'      => __( 'Secondary', 'chun' ),
				'properties' => array( 
					'color' => array( 
						'.entry-title', 
						'.entry-title a',
						'.loop-title', 
						'#site-description', 
						'#menu-portfolio li.current-cat a', 
						'#menu-portfolio li.current-menu-item a', 
						'.page-numbers.current'
					),
					'background-color' => array(
						'.breadcrumb-trail',
						'li.comment .comment-reply-link:hover',
						'#footer'
					),
					'border-top-color' => array(
						'.hentry',
						'.loop-meta',
						'.attachment-meta',
						'#comments-template',
						'.page-template-portfolio .hentry.page'
					),
					'border-bottom-color' => array(
						'body' 
					),
				)
			),
			'menu_primary_1' => array(
				'default'    => '00393e',
				'label'      => __( 'Primary Menu 1st', 'chun' ),
				'properties' => array( 
					'color' => array( 
						'#menu-primary li a' 
					),
					'background-color' => array(
						'#menu-primary li li a:hover',
						'#menu-primary li li:hover > a'
					),
				)
			),
			'menu_primary_2' => array(
				'default'    => '00666f',
				'label'      => __( 'Primary Menu 2nd', 'chun' ),
				'properties' => array( 
					'color' => array( 
						'#menu-primary li a:hover',
						'#menu-primary li:hover > a',
						'#menu-primary li.current-menu-item > a',
					),
					'background-color' => array(
						'#menu-primary li li a',
					),
					'border-bottom-color' => array(
						'#menu-primary-items ul li:first-child > a::after'
					),
					'border-right-color' => array(
						'#menu-primary-items ul ul li:first-child a::after'
					),
				)
			),
		)
	);

	require_once( trailingslashit( get_template_directory() ) . 'color-palette.php' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 
		'post-formats', 
		array( 'aside', 'chat', 'image', 'gallery', 'quote' ) 
	);

	/* Add support for WordPress custom background. */
	add_theme_support( 
		'custom-background',
		array(
			//'default-color' => '050505',
			//'default-image' => trailingslashit( get_template_directory_uri() ) . 'images/bg.png',
			'wp-head-callback' => 'picturesque_custom_background_callback'
		)
	);

	/* Add support for WordPress custom header image. */
	add_theme_support(
		'custom-header',
		array(
			'wp-head-callback' => '__return_false',
			'admin-head-callback' => '__return_false',
			'header-text' => false,
			'default-image' => 'remove-header',
			'width' => 1050,
			'height' => 200
		)
	);

	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'picturesque_embed_defaults' );

	/* Set content width. */
	hybrid_set_content_width( 650 );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'picturesque_disable_sidebars' );
	add_action( 'template_redirect', 'picturesque_one_column' );

	/* Add classes to the comments pagination. */
	add_filter( 'previous_comments_link_attributes', 'picturesque_previous_comments_link_attributes' );
	add_filter( 'next_comments_link_attributes', 'picturesque_next_comments_link_attributes' );

	/* Add infinity symbol to aside posts. */
	add_filter( 'the_content', 'picturesque_post_format_tools_aside_infinity', 9 ); // run before wpautop

	add_action( 'init', 'chun_register_image_sizes' );
	add_action( 'init', 'chun_register_menus', 11 );

	add_filter( 'embed_handler_html', 'chun_embed_html' );
	add_filter( 'embed_oembed_html',  'chun_embed_html' );

	/* Testing out some early Hybrid Core 1.6 proposed HTML changes. */
	add_filter( "{$prefix}_sidebar_defaults", 'chun_sidebar_defaults' );
	add_filter( 'cleaner_gallery_defaults',   'chun_gallery_defaults' );
}

add_filter( 'hybrid_context', 'agag' );

function agag( $context ) {

	if ( is_front_page() )
		$context[] = 'aaafff';

	return $context;
}

add_filter( 'color_palette_js_do_not_overwrite', 'chun_save_my_colors', 10, 3 );

function chun_save_my_colors( $element, $name, $property ) {

	if ( 'color' === $property && 'primary' === $name )
		$element = '#site-title a, .menu a, .entry-title a';
	elseif ( 'color' === $property && 'menu_primary_1' === $name )
		$element = '#menu-primary li .sub-menu li a, #menu-primary li.current-menu-item li a, #menu-primary li li.current-menu-item > a';

	return $element;
}



function chun_register_menus() {

	if ( post_type_exists( 'portfolio_item' ) )
		register_nav_menu( 'portfolio', esc_html__( 'Portfolio', 'chun' ) );
}



function chun_register_image_sizes() {

	/* Size: 'post-thumbnail' */
	set_post_thumbnail_size( 160, 120, true );

	/* For the CPT: Portfolio plugin. */
	if ( post_type_exists( 'portfolio_item' ) )
		add_image_size( 'portfolio-large', 650, 488, true );
}


function chun_embed_html( $html ) {

	if ( in_the_loop() && has_post_format( 'video' ) && preg_match( '/(<embed|object|iframe)/', $html ) )
		$html = '<div class="embed-wrap">' . $html . '</div>';

	return $html;
}

/* === HYBRID CORE 1.6 CHANGES. === 
 *
 * The following changes are slated for Hybrid Core version 1.6 to make it easier for 
 * theme developers to build awesome HTML5 themes.  If you overwrite these via a hook, 
 * keep in mind that you might need to change your code in the next major theme update.
 */

	function chun_sidebar_defaults( $defaults ) {

		$defaults = array(
			'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		);

		return $defaults;
	}

	function chun_gallery_defaults( $defaults ) {

		$defaults['size']       = 'post-thumbnail'; // Not for Hybrid Core 1.6.
		$defaults['itemtag']    = 'figure';
		$defaults['icontag']    = 'div';
		$defaults['captiontag'] = 'figcaption';

		return $defaults;
	}

	/**
	 * Adds an infinity character "&#8734;" to the end of the post content on 'aside' posts.
	 *
	 * @since 0.1.1
	 * @access public
	 * @param string $content The post content.
	 * @return string $content
	 */
	function picturesque_post_format_tools_aside_infinity( $content ) {

		if ( has_post_format( 'aside' ) && !is_singular() )
			$content .= ' <a class="permalink" href="' . get_permalink() . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '">&#8734;</a>';

		return $content;
	}

/* End Hybrid Core 1.6 section. */

function chun_get_portfolio_item_link() {

	$url = get_post_meta( get_the_ID(), '_portfolio_item_url', true );

	if ( !empty( $url ) )
		return '<a href="' . esc_url( $url ) . '">' . __( 'Project <abbr title="Uniform Resource Locator">URL</abbr>', 'chun' ) . '</a>';
}





/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since 0.1.0
 */
function picturesque_one_column() {

	if ( !is_active_sidebar( 'primary' ) && !is_active_sidebar( 'secondary' ) )
		add_filter( 'get_theme_layout', 'picturesque_theme_layout_one_column' );

	elseif ( is_attachment() && wp_attachment_is_image() && 'default' == get_post_layout( get_queried_object_id() ) )
		add_filter( 'get_theme_layout', 'picturesque_theme_layout_one_column' );

	elseif ( is_post_type_archive( 'portfolio_item' ) || is_tax( 'portfolio' ) )
		add_filter( 'get_theme_layout', 'picturesque_theme_layout_one_column' );
}

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since 0.1.0
 * @param string $layout The layout of the current page.
 * @return string
 */
function picturesque_theme_layout_one_column( $layout ) {
	return 'layout-1c';
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since 0.1.0
 * @param array $sidebars_widgets A multidimensional array of sidebars and widgets.
 * @return array $sidebars_widgets
 */
function picturesque_disable_sidebars( $sidebars_widgets ) {

	if ( current_theme_supports( 'theme-layouts' ) && !is_admin() ) {

		if ( 'layout-1c' == theme_layouts_get_layout() )
			$sidebars_widgets['primary'] = false;
	}

	return $sidebars_widgets;
}

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since 0.1.0
 */
function picturesque_embed_defaults( $args ) {

	$args['width'] = hybrid_get_content_width();

	if ( current_theme_supports( 'theme-layouts' ) ) {

		$layout = theme_layouts_get_layout();

		if ( 'layout-1c' == $layout )
			$args['width'] = 1000;
	}

	return $args;
}

/**
 * Adds 'class="prev" to the previous comments link.
 *
 * @since 0.1.0
 * @param string $attributes The previous comments link attributes.
 * @return string
 */
function picturesque_previous_comments_link_attributes( $attributes ) {
	return $attributes . ' class="prev"';
}

/**
 * Adds 'class="next" to the next comments link.
 *
 * @since 0.1.0
 * @param string $attributes The next comments link attributes.
 * @return string
 */
function picturesque_next_comments_link_attributes( $attributes ) {
	return $attributes . ' class="next"';
}



/**
 * Returns a set of image attachment links based on size.
 *
 * @since 0.1.0
 * @return string Links to various image sizes for the image attachment.
 */
function picturesque_get_image_size_links() {

	/* If not viewing an image attachment page, return. */
	if ( !wp_attachment_is_image( get_the_ID() ) )
		return;

	/* Set up an empty array for the links. */
	$links = array();

	/* Get the intermediate image sizes and add the full size to the array. */
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';

	/* Loop through each of the image sizes. */
	foreach ( $sizes as $size ) {

		/* Get the image source, width, height, and whether it's intermediate. */
		$image = wp_get_attachment_image_src( get_the_ID(), $size );

		/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
		if ( !empty( $image ) && ( true === $image[3] || 'full' == $size ) )
			$links[] = "<a class='image-size-link' href='" . esc_url( $image[0] ) . "'>{$image[1]} &times; {$image[2]}</a>";
	}

	/* Join the links in a string and return. */
	return join( ' <span class="sep">/</span> ', $links );
}

/**
 * This is a fix for when a user sets a custom background color with no custom background image.  What 
 * happens is the theme's background image hides the user-selected background color.  If a user selects a 
 * background image, we'll just use the WordPress custom background callback.
 *
 * @since 0.1.0
 * @link http://core.trac.wordpress.org/ticket/16919
 */
function picturesque_custom_background_callback() {

	/* Get the background image. */
	$image = get_background_image();

	/* If there's an image, just call the normal WordPress callback. We won't do anything here. */
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}

	/* Get the background color. */
	$color = get_background_color();

	/* If no background color, return. */
	if ( empty( $color ) )
		return;

	/* Use 'background' instead of 'background-color'. */
	$style = "background: #{$color};";

?>
<style type="text/css">body.custom-background { <?php echo trim( $style ); ?> }</style>
<?php

}

/**
 * Displays an attachment image's metadata and exif data while viewing a singular attachment page.
 *
 * Note: This function will most likely be restructured completely in the future.  The eventual plan is to 
 * separate each of the elements into an attachment API that can be used across multiple themes.  Keep 
 * this in mind if you plan on using the current filter hooks in this function.
 *
 * @since 0.1.0
 */
function picturesque_image_info() {

	/* Set up some default variables and get the image metadata. */
	$meta = wp_get_attachment_metadata( get_the_ID() );
	$items = array();
	$list = '';

	/* Add the width/height to the $items array. */
	$items['dimensions'] = sprintf( __( '<span class="prep">Dimensions:</span> %s', 'picturesque' ), '<span class="image-data"><a href="' . esc_url( wp_get_attachment_url() ) . '">' . sprintf( __( '%1$s &#215; %2$s pixels', 'picturesque' ), $meta['width'], $meta['height'] ) . '</a></span>' );

	/* If a timestamp exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = sprintf( __( '<span class="prep">Date:</span> %s', 'picturesque' ), '<span class="image-data">' . date( get_option( 'date_format' ), $meta['image_meta']['created_timestamp'] ) . '</span>' );

	/* If a camera exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = sprintf( __( '<span class="prep">Camera:</span> %s', 'picturesque' ), '<span class="image-data">' . $meta['image_meta']['camera'] . '</span>' );

	/* If an aperture exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = sprintf( __( '<span class="prep">Aperture:</span> %s', 'picturesque' ), '<span class="image-data">' . sprintf( __( 'f/%s', 'picturesque' ), $meta['image_meta']['aperture'] ) . '</span>' );

	/* If a focal length is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = sprintf( __( '<span class="prep">Focal Length:</span> %s', 'picturesque' ), '<span class="image-data">' . sprintf( __( '%s mm', 'picturesque' ), $meta['image_meta']['focal_length'] ) . '</span>' );

	/* If an ISO is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['iso'] ) )
		$items['iso'] = sprintf( __( '<span class="prep">ISO:</span> %s', 'picturesque' ), '<span class="image-data">' . $meta['image_meta']['iso'] . '</span>' );

	/* If a shutter speed is given, format the float into a fraction and add it to the $items array. */
	if ( !empty( $meta['image_meta']['shutter_speed'] ) ) {

		if ( ( 1 / $meta['image_meta']['shutter_speed'] ) > 1 ) {
			$shutter_speed = '1/';

			if ( number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1 ) ==  number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0 ) )
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0, '.', '' );
			else
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1, '.', '' );
		} else {
			$shutter_speed = $meta['image_meta']['shutter_speed'];
		}

		$items['shutter_speed'] = sprintf( __( '<span class="prep">Shutter Speed:</span> %s', 'picturesque' ), '<span class="image-data">' . sprintf( __( '%s sec', 'picturesque' ), $shutter_speed ) . '</span>' );
	}

	/* Allow devs to overwrite the array of items. */
	$items = apply_atomic( 'image_info_items', $items );

	/* Loop through the items, wrapping each in an <li> element. */
	foreach ( $items as $item )
		$list .= "<li>{$item}</li>";

	/* Format the HTML output of the function. */
	$output = '<div class="image-info"><h3>' . __( 'Image Info', 'picturesque' ) . '</h3><ul>' . $list . '</ul></div>';

	/* Display the image info and allow devs to overwrite the final output. */
	echo apply_atomic( 'image_info', $output );
}


?>