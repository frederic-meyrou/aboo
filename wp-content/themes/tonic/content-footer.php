<?php
/**
 * The template for displaying article footers
 *
 * @since 1.0.6
 */
 ?>
	<footer class="entry">
	    <?php
	    if ( is_single() ) wp_link_pages( array( 'before' => '<p id="pages">' . __( 'Pages:', 'tonic' ) ) );
	    edit_post_link( __( '(edit)', 'tonic' ), '<p class="edit-link">', '</p>' );
		if ( is_single() ) the_tags( '<p class="tags"><span>' . __( 'Tags:', 'tonic' ) . '</span>', ' ', '</p>' );
	    ?>
	</footer><!-- .entry -->