<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @since 1.0.0
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    <i class="icon-quote-left quote"></i>
	    <div class="entry-content">
		    <?php the_content( __( 'Read more &rarr;', 'tonic' ) ); ?>
	    </div><!-- .entry-content -->

	    <?php get_template_part( 'content', 'footer' ); ?>
	</article>