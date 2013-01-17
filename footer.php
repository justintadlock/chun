			</div><!-- .wrap -->
		</div><!-- #main -->

		<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

		<?php get_template_part( 'menu', 'subsidiary' ); // Loads the menu-subsidiary.php template. ?>

		<footer id="footer">

			<div class="footer-content">
				<?php hybrid_footer_content(); ?>
			</div><!-- .footer-content -->

		</footer><!-- #footer -->

	</div><!-- #container -->

	<?php wp_footer(); // wp_footer ?>

</body>
</html>