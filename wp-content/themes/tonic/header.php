<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 * and the left sidebar conditional
 *
 * @since 1.0.0
 */
$bavotasan_theme_options = bavotasan_theme_options();
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class( 'basic' ); ?>>

	<div id="page">

		<div id="header-wrap" class="grid wfull">
			<header id="header" class="grid <?php echo $bavotasan_theme_options['width']; ?> row" role="banner">
				<div class="c12">
					<div id="mobile-menu" class="clearfix">
						<a href="#" class="left-menu fl"><i class="icon-reorder"></i></a>
						<a href="#" class="fr"><i class="icon-search"></i></a>
					</div>
					<div id="drop-down-search"><?php get_search_form(); ?></div>
				</div>
				<?php
				$text_color = get_header_textcolor();
				$header_class = ( 'blank' == $text_color ) ? ' class="remove"' : ' class="left-header fl"';
				?>
				<div<?php echo $header_class; ?>>
					<h1 id="site-title"><a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php if ( $bavotasan_theme_options['tagline'] ) { ?><h2 id="site-description"><?php bloginfo( 'description' ); ?></h2><?php } ?>
				</div>

				<div class="right-header fr">
					<nav id="site-navigation" class="navbar navbar-inverse" role="navigation">
						<h3 class="assistive-text"><?php _e( 'Main menu', 'tonic' ); ?></h3>
						<a class="assistive-text" href="#primary" title="<?php esc_attr_e( 'Skip to content', 'tonic' ); ?>"><?php _e( 'Skip to content', 'tonic' ); ?></a>
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'navbar-inner', 'menu_class' => 'nav', 'fallback_cb' => 'bavotasan_default_menu', 'depth' => 2 ) ); ?>
					</nav><!-- #site-navigation -->
				</div>
			</header><!-- #header .row -->

			<?php
			if ( is_front_page() )
				bavotasan_jumbotron();
			?>

		</div>

		<div id="main" class="grid <?php echo $bavotasan_theme_options['width']; ?> row">
			<div id="left-nav"></div>

			<?php
			bavotasan_home_page_default_widgets();