<?php
$format = get_post_format();
$bavotasan_theme_options = bavotasan_theme_options();
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    <?php get_template_part( 'content', 'header' ); ?>

	    <div class="entry-content">
		    <?php
			if ( 'excerpt' == $bavotasan_theme_options['excerpt_content'] && empty( $format ) && ( ! is_single() || is_search() || is_archive() ) ) {
				if( has_post_thumbnail() ) {
					echo '<a href="' . get_permalink() . '" class="image-anchor">';
					the_post_thumbnail( 'thumbnail', array( 'class' => 'alignleft img-polaroid' ) );
					echo '</a>';
				}
				the_excerpt();
			} else {
				the_content( __( 'Read more &rarr;', 'tonic' ) );
			}
			?>
	    </div><!-- .entry-content -->

	    <?php get_template_part( 'content', 'footer' ); ?>
	</article><!-- #post-<?php the_ID(); ?> -->