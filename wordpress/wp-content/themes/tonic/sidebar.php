<?php
/**
 * The first/left sidebar widgetized area.
 *
 * If no active widgets in sidebar, alert with default login
 * widget will appear.
 *
 * @since 1.0.0
 */

/* Conditional check to see if post/page template is full width
   or if no sidebars was selected in layout options */
$bavotasan_theme_options = bavotasan_theme_options();
$layout = $bavotasan_theme_options['layout'];
if ( 6 != $layout ) {
	?>
	<div id="secondary" <?php bavotasan_sidebar_class(); ?> role="complementary">
		<?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>

		<?php if ( current_user_can( 'edit_theme_options' ) ) { ?>
			<div class="alert"><?php printf( __( 'Add your own widgets by going to the %sWidgets admin page%s.', 'tonic' ), '<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">', '</a>' ); ?></div>
		<?php } ?>

		<aside id="meta" class="widget">
			<h3 class="widget-title"><?php _e( 'Meta', 'tonic' ); ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</aside>
		<?php endif; ?>
	</div><!-- #secondary.widget-area -->
	<?php
}