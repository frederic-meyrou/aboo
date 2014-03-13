<?php
/**
Theme Name: Tonic
Description: Thème enfant pour Tonic
Author: FME
Template: tonic
**/
 
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main, .grid and #page div elements.
 *
 * @since 1.0.0
 */
$bavotasan_theme_options = bavotasan_theme_options();

		/* Do not display sidebar on home page if 'home_posts' turned
			off */
		if ( $bavotasan_theme_options['home_posts'] )
			get_sidebar();
		?>
	</div> <!-- #main.row -->
</div> <!-- #page.grid -->

<footer id="footer" role="contentinfo">
	<div id="footer-content" class="grid <?php echo $bavotasan_theme_options['width']; ?>">
		<div class="row">
			<p class="copyright c12">
				<span class="fl"><?php echo '<a href="' . home_url() . '"><img class="footer-img" src="http://localhost/aboo/wp-content/themes/tonic/library/images/aboo.png"></a>'; ?></span> 
				<span class="fl footer-align"><?php printf( __( 'Copyright &copy; %s %s. Tous droits Réservés.', 'tonic' ), date( 'Y' ), ' <a href="' . home_url() . '">' .'</a>' ); ?></span>
				<span class="credit-link fr"><i class="icon-leaf"></i><?php echo '<a href="' . home_url() . '/wp-login.php">Login</a>'; ?></span>
			</p><!-- .c12 -->
		</div><!-- .row -->
	</div><!-- #footer-content.grid -->
</footer><!-- #footer -->

<?php wp_footer(); ?>
</body>
</html>