<?php
/**
 * Set up the default theme options
 *
 * @since 1.0.0
 */
function bavotasan_theme_options() {
	//delete_option( 'tonic_theme_options' );
	$default_theme_options = array(
		'width' => '',
		'layout' => '2',
		'primary' => 'c8',
		'tagline' => 'on',
		'display_author' => 'on',
		'display_date' => 'on',
		'display_comment_count' => 'on',
		'display_categories' => 'on',
		'excerpt_content' => 'excerpt',
		'home_widget' =>'on',
		'home_posts' =>'on',
		'jumbo_headline_title' => 'Jumbo Headline!',
		'jumbo_headline_text' => 'Got something important to say? Then make it stand out by using the jumbo headline option and get your visitor\'s attention right away.',
		'jumbo_headline_button_text' => 'Call to Action',
		'jumbo_headline_button_link' => '#',
	);

	return get_option( 'tonic_theme_options', $default_theme_options );
}

class Bavotasan_Customizer {
	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 1000 );
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	/**
	 * Add a 'customize' menu item to the admin bar
	 *
	 * This function is attached to the 'admin_bar_menu' action hook.
	 *
	 * @since 1.0.0
	 */
	public function admin_bar_menu( $wp_admin_bar ) {
	    if ( current_user_can( 'edit_theme_options' ) && is_admin_bar_showing() ) {
	    	$wp_admin_bar->add_node( array( 'parent' => 'bavotasan_toolbar', 'id' => 'customize_theme', 'title' => __( 'Theme Options', 'tonic' ), 'href' => esc_url( admin_url( 'customize.php' ) ) ) );
   			$wp_admin_bar->add_node( array( 'parent' => 'bavotasan_toolbar', 'id' => 'documentation_faqs', 'title' => __( 'Documentation & FAQs', 'tonic' ), 'href' => 'https://themes.bavotasan.com/documentation', 'meta' => array( 'target' => '_blank' ) ) );
	    }
	}

	/**
	 * Adds theme options to the Customizer screen
	 *
	 * This function is attached to the 'customize_register' action hook.
	 *
	 * @param	class $wp_customize
	 *
	 * @since 1.0.0
	 */
	public function customize_register( $wp_customize ) {
		$bavotasan_theme_options = bavotasan_theme_options();

		$wp_customize->add_setting( 'tonic_theme_options[tagline]', array(
			'default' => $bavotasan_theme_options['tagline'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_tagline', array(
			'label' => __( 'Display Tagline', 'tonic' ),
			'section' => 'title_tagline',
			'settings' => 'tonic_theme_options[tagline]',
			'type' => 'checkbox',
		) );

		// Layout section panel
		$wp_customize->add_section( 'bavotasan_layout', array(
			'title' => __( 'Layout', 'tonic' ),
			'priority' => 35,
		) );

		$wp_customize->add_setting( 'tonic_theme_options[width]', array(
			'default' => $bavotasan_theme_options['width'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_width', array(
			'label' => __( 'Site Width', 'tonic' ),
			'section' => 'bavotasan_layout',
			'settings' => 'tonic_theme_options[width]',
			'priority' => 10,
			'type' => 'select',
			'choices' => array(
				'' => '1200px',
				'w960' => __( '960px', 'tonic' ),
			),
		) );

		$wp_customize->add_setting( 'tonic_theme_options[layout]', array(
			'default' => $bavotasan_theme_options['layout'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_site_layout', array(
			'label' => __( 'Site Layout', 'tonic' ),
			'section' => 'bavotasan_layout',
			'settings' => 'tonic_theme_options[layout]',
			'priority' => 15,
			'type' => 'radio',
			'choices' => array(
				'1' => __( 'Left Sidebar', 'tonic' ),
				'2' => __( 'Right Sidebar', 'tonic' ),
				'6' => __( 'No Sidebar', 'tonic' )
			),
		) );

		$choices =  array(
			'c2' => '17%',
			'c3' => '25%',
			'c4' => '34%',
			'c5' => '42%',
			'c6' => '50%',
			'c7' => '58%',
			'c8' => '66%',
			'c9' => '75%',
			'c10' => '83%',
			'c12' => '100%',
		);

		$wp_customize->add_setting( 'tonic_theme_options[primary]', array(
			'default' => $bavotasan_theme_options['primary'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_primary_column', array(
			'label' => __( 'Main Content', 'tonic' ),
			'section' => 'bavotasan_layout',
			'settings' => 'tonic_theme_options[primary]',
			'priority' => 20,
			'type' => 'select',
			'choices' => $choices,
		) );

		$wp_customize->add_setting( 'tonic_theme_options[excerpt_content]', array(
			'default' => $bavotasan_theme_options['excerpt_content'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_excerpt_content', array(
			'label' => __( 'Post Content Display', 'tonic' ),
			'section' => 'bavotasan_layout',
			'settings' => 'tonic_theme_options[excerpt_content]',
			'priority' => 30,
			'type' => 'radio',
			'choices' => array(
				'excerpt' => __( 'Teaser Excerpt', 'tonic' ),
				'content' => __( 'Full Content', 'tonic' ),
			),
		) );

		$wp_customize->add_setting( 'tonic_theme_options[home_widget]', array(
			'default' => $bavotasan_theme_options['home_widget'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_home_widget', array(
			'label' => __( 'Display Home Page Top Widget Area', 'tonic' ),
			'section' => 'bavotasan_layout',
			'settings' => 'tonic_theme_options[home_widget]',
			'priority' => 35,
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'tonic_theme_options[home_posts]', array(
			'default' => $bavotasan_theme_options['home_posts'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_home_posts', array(
			'label' => __( 'Display Home Page Posts &amp; Sidebar', 'tonic' ),
			'section' => 'bavotasan_layout',
			'settings' => 'tonic_theme_options[home_posts]',
			'priority' => 40,
			'type' => 'checkbox',
		) );

		// Jumbotron
		$wp_customize->add_section( 'bavotasan_jumbo', array(
			'title' => __( 'Jumbo Headline', 'tonic' ),
			'priority' => 36,
		) );

		$wp_customize->add_setting( 'tonic_theme_options[jumbo_headline_title]', array(
			'default' => $bavotasan_theme_options['jumbo_headline_title'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_jumbo_headline_title', array(
			'label' => __( 'Title', 'tonic' ),
			'section' => 'bavotasan_jumbo',
			'settings' => 'tonic_theme_options[jumbo_headline_title]',
			'priority' => 26,
			'type' => 'text',
		) );

		$wp_customize->add_setting( 'tonic_theme_options[jumbo_headline_text]', array(
			'default' => $bavotasan_theme_options['jumbo_headline_text'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_jumbo_headline_text', array(
			'label' => __( 'Text', 'tonic' ),
			'section' => 'bavotasan_jumbo',
			'settings' => 'tonic_theme_options[jumbo_headline_text]',
			'priority' => 27,
			'type' => 'text',
		) );

		$wp_customize->add_setting( 'tonic_theme_options[jumbo_headline_button_text]', array(
			'default' => $bavotasan_theme_options['jumbo_headline_button_text'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_jumbo_headline_button_text', array(
			'label' => __( 'Button Text', 'tonic' ),
			'section' => 'bavotasan_jumbo',
			'settings' => 'tonic_theme_options[jumbo_headline_button_text]',
			'priority' => 28,
			'type' => 'text',
		) );

		$wp_customize->add_setting( 'tonic_theme_options[jumbo_headline_button_link]', array(
			'default' => $bavotasan_theme_options['jumbo_headline_button_link'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_jumbo_headline_button_link', array(
			'label' => __( 'Button Link', 'tonic' ),
			'section' => 'bavotasan_jumbo',
			'settings' => 'tonic_theme_options[jumbo_headline_button_link]',
			'priority' => 29,
			'type' => 'text',
		) );

		// Posts panel
		$wp_customize->add_section( 'bavotasan_posts', array(
			'title' => __( 'Posts', 'tonic' ),
			'priority' => 45,
		) );

		$wp_customize->add_setting( 'tonic_theme_options[display_categories]', array(
			'default' => $bavotasan_theme_options['display_categories'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_display_categories', array(
			'label' => __( 'Display Categories', 'tonic' ),
			'section' => 'bavotasan_posts',
			'settings' => 'tonic_theme_options[display_categories]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'tonic_theme_options[display_author]', array(
			'default' => $bavotasan_theme_options['display_author'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_display_author', array(
			'label' => __( 'Display Author', 'tonic' ),
			'section' => 'bavotasan_posts',
			'settings' => 'tonic_theme_options[display_author]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'tonic_theme_options[display_date]', array(
			'default' => $bavotasan_theme_options['display_date'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_display_date', array(
			'label' => __( 'Display Date', 'tonic' ),
			'section' => 'bavotasan_posts',
			'settings' => 'tonic_theme_options[display_date]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'tonic_theme_options[display_comment_count]', array(
			'default' => $bavotasan_theme_options['display_comment_count'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'bavotasan_display_comment_count', array(
			'label' => __( 'Display Comment Count', 'tonic' ),
			'section' => 'bavotasan_posts',
			'settings' => 'tonic_theme_options[display_comment_count]',
			'type' => 'checkbox',
		) );
	}
}
$bavotasan_customizer = new Bavotasan_Customizer;