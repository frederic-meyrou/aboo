( function( $ ) {
	// Responsive videos
	var all_videos = $( '.entry-content' ).find( 'iframe[src*="player.vimeo.com"], iframe[src*="youtube.com"], iframe[src*="dailymotion.com"],iframe[src*="kickstarter.com"][src*="video.html"], object, embed' ),
    	f_height;

	all_videos.each( function() {
		var video = $(this),
			aspect_ratio = video.attr( 'height' ) / video.attr( 'width' );

		video
			.removeAttr( 'height' )
			.removeAttr( 'width' );

		if ( ! video.parents( 'object' ).length )
			video.wrap( '<div class="responsive-video-wrapper" style="padding-top: ' + ( aspect_ratio * 100 ) + '%" />' );
	} );

	// Mobile menu
	$( '#header' ).on( 'click', '#mobile-menu a', function() {
		if ( $(this).hasClass( 'left-menu' ) )
			$( 'body' ).toggleClass( 'left-menu-open' );
		else
			$( '#drop-down-search' ).slideToggle( 'fast' );
	} );

	$('html').click(function() {
		$( '.sub-menu-parent' ).removeClass( 'open' );
	} );

	$( '#site-navigation' ).on( 'click', '.sub-menu-parent > a', function(e) {
		e.preventDefault();
		e.stopPropagation();
		$( '.sub-menu-parent' ).not( $(this).parents() ).removeClass( 'open' );
		$(this).parent().toggleClass( 'open' );
	} );

	var id = ( $( 'body' ).hasClass( 'left-sidebar' ) ) ? $( '#secondary' ) : $( '#left-nav' );
	Harvey.attach( 'screen and (max-width:768px)', {
      	setup: function() {
      		id.addClass( 'offcanvas' );
	      	$( '#site-navigation' ).prependTo( id ).show();
      	},
      	on: function() {
      		id.addClass( 'offcanvas' );
	      	$( '#site-navigation' ).prependTo( id );
			$( '.widget_search' ).hide();
      	},
      	off: function() {
      		id.removeClass( 'offcanvas' );
      		$( 'body' ).removeClass( 'left-menu-open' );
	      	$( '#secondary, .widget_search' ).show();
			$( '#site-navigation' ).appendTo( '#header .right-header' );
			$( '#drop-down-search' ).hide();
      	}
    } );

	// Footer height
	$(window)
		.resize( function() {
			footer_height();
		} )
		.load( function() {
			footer_height();
		} );

	function footer_height() {
		f_height = $( '#footer-content' ).height() + 20;
		$( '#footer' ).css({ height: f_height + 'px' });
		$( '#page' ).css({ marginBottom: -f_height + 'px' });
		$( '#main' ).css({ paddingBottom: f_height  + 'px' });
	}

	// Image anchor
	$( 'a:has(img)' ).addClass('image-anchor');

	$( 'a[href="#"]' ).click( function(e) {
		e.preventDefault();
	});
} )( jQuery );