<?php

do_atomic( 'before_entry' ); // picturesque_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // picturesque_open_entry ?>

	<?php if ( is_singular() ) { ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', the_title( '<h1 class="entry-title">', '</h1>', false ) ); ?>

<div class="byline">
<?php echo do_shortcode( '[entry-terms taxonomy="portfolio" before="Portfolio: "]' ); ?>

<?php $cptp_item_url = get_post_meta( get_the_ID(), '_portfolio_item_url', true );
if ( !empty( $cptp_item_url ) )
	echo '<a href="' . esc_url( $cptp_item_url ) . '">Project <abbr title="Uniform Resource Locator">URL</abbr></a>';
?>
</div>

		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'picturesque' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-published] [entry-edit-link before="| "]', 'picturesque' ) . '</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } else { ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">

			<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'size' => 'portfolio-large', 'image_scan' => true ) ); ?>

			<?php if ( has_excerpt() ) {
				the_excerpt();
				wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'picturesque' ) . '</span>', 'after' => '</p>' ) );
			} ?>

		</div><!-- .entry-summary -->

		<footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-published] [entry-edit-link before="| "]', 'picturesque' ) . '</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } ?>

	<?php do_atomic( 'close_entry' ); // picturesque_close_entry ?>

</article><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // picturesque_after_entry ?>