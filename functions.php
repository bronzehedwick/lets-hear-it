<?php
/**
 * Let's Hear It functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Lets_Hear_It
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'lets_hear_it_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function lets_hear_it_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Let's Hear It, use a find and replace
		 * to change 'lets-hear-it' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'lets-hear-it', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'main-menu' => esc_html__( 'Primary', 'lets-hear-it' ),
				'footer-menu' => esc_html__( 'Footer', 'lets-hear-it' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'lets_hear_it_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'lets_hear_it_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function lets_hear_it_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'lets_hear_it_content_width', 640 );
}
add_action( 'after_setup_theme', 'lets_hear_it_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function lets_hear_it_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'lets-hear-it' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'lets-hear-it' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title recent-episodes__main-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name' => esc_html__( 'Homepage content', 'lets-hear-it' ),
			'id' => 'homepage-content',
			'description' => esc_html__( 'Add widgets here.', 'lets-hear-it' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s recent-episodes">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title recent-episodes__main-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'lets_hear_it_widgets_init' );

Class LHI_Recent_Episodes extends SeriouslySimplePodcasting\Widgets\Recent_Episodes {
	function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_recent_episodes', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Episodes', 'seriously-simple-podcasting' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}

		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		$query_args = ssp_episodes( $number, '', true, 'widget' );

		$qry = new WP_Query( apply_filters( 'ssp_widget_recent_episodes_args', $query_args ) );

		if ($qry->have_posts()) :
?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<?php while ( $qry->have_posts() ) : $qry->the_post(); ?>
        <div class="recent-episodes__item">
            <?php
            $series = get_the_terms( get_the_ID(), 'series' )[0];
            $series_id = $series->term_id;
            $series_image = get_option( "ss_podcasting_data_image_{$series_id}", false );
            if ( $series_image ) {
                $series_image_attachment_id = attachment_url_to_postid( $series_image );
                $img = wp_get_attachment_image_src( $series_image_attachment_id, 'thumbnail' );
            }
            ?>
        <?php if ( $series_image ) : ?>
          <img class="recent-episodes__image" src="<?php echo $img[0]; ?>" width="<?php echo $img[1]; ?>" height="<?php echo $img[2]; ?>">
        <?php endif; ?>
        <h3 class="recent-episodes__title">
          <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
        </h3>
        <p class="recent-episodes__subtitle">
          <a href="<?php echo $series->taxonomy . '/' . $series->slug; ?>"><?php echo $series->name; ?></a>
        </p>
			<?php if ( $show_date ) : ?>
				<p class="recent-episodes__date">Released <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time></p>
			<?php endif; ?>
			</div>
		<?php endwhile; ?>
		<?php echo $args['after_widget']; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_recent_episodes', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}
}

function lhi_recent_episodes_widget_register() {
	unregister_widget( 'LHI_Recent_Episodes' );
	register_widget( 'LHI_Recent_Episodes' );
}
add_action( 'widgets_init', 'lhi_recent_episodes_widget_register' );

/**
 * Enqueue scripts and styles.
 */
function lets_hear_it_scripts() {
	wp_enqueue_style( 'lets-hear-it-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'lets-hear-it-style', 'rtl', 'replace' );

	wp_enqueue_script( 'lets-hear-it-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'lets_hear_it_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function lets_hear_it_two_factor_providers( $providers ) {
	return array(
	  'Two_Factor_Totp'         => TWO_FACTOR_DIR . 'providers/class-two-factor-totp.php',
	  'Two_Factor_FIDO_U2F'     => TWO_FACTOR_DIR . 'providers/class-two-factor-fido-u2f.php',
	  'Two_Factor_Backup_Codes' => TWO_FACTOR_DIR . 'providers/class-two-factor-backup-codes.php',
	);
}
add_filter( 'two_factor_providers', 'lets_hear_it_two_factor_providers' );
