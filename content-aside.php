<?php
/**
 * Content Template
 *
 * Template used to show post content when a more specific template cannot be found.
 *
 * @package Picturesque
 * @subpackage Template
 * @since 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link http://themehybrid.com/themes/picturesque
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

do_atomic( 'before_entry' ); // picturesque_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // picturesque_open_entry ?>

	<?php if ( is_singular( get_post_type() ) ) { ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', the_title( '<h1 class="entry-title">', '</h1>', false ) ); ?>
			<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'Aside published by [entry-author] on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'picturesque' ) . '</div>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'picturesque' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="Tagged "]', 'picturesque' ) . '</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } else { ?>

		<div class="entry-content">
			<?php the_content( __( 'Read more &rarr;', 'unique' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'unique' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

	<?php } ?>

	<?php do_atomic( 'close_entry' ); // picturesque_close_entry ?>

</article><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // picturesque_after_entry ?>