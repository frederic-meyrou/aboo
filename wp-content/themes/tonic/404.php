<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @since 1.0.0
 */
get_header(); ?>

	<div id="primary" <?php bavotasan_primary_attr(); ?>>

		<article id="post-0" class="post error404 not-found">
			<img src="<?php echo BAVOTASAN_THEME_URL; ?>/library/images/aboo-logo.png" alt="" class="aligncenter" />

	    	<header>
	    	   	<h1 class="entry-title"><?php _e( 'ERREUR 404', 'tonic' ); ?></h1>
	        </header>

	        <div class="entry-content">
	            <p><?php _e( "Désolé, mais la page que vous recherchez ne figure pas sur le site.", 'tonic' ); ?></p>
	        </div>

	    </article>

	</div><!-- #primary.c8 -->

<?php get_footer(); ?>