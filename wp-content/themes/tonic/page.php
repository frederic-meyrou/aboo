<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @since 1.0.0
 */
$bavotasan_theme_options = bavotasan_theme_options();
get_header(); ?>

	<div id="primary" <?php bavotasan_primary_attr(); ?>>

		<?php
		while ( have_posts() ) : the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="entry-title"><?php the_title(); ?></h1>

			    <div class="entry-content">
				    <?php the_content( __( 'Read more &rarr;', 'tonic' ) ); ?>
			    </div><!-- .entry-content -->

			    <?php get_template_part( 'content', 'footer' ); ?>
			</article><!-- #post-<?php the_ID(); ?> -->
			<?php
			comments_template( '', true );
		endwhile; // end of the loop.
		?>

	</div><!-- #primary.c8 -->

<?php get_footer(); ?>