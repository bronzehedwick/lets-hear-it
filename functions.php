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

function lhi_the_content( $content ) {
	global $post;
	if ( !is_front_page() && $post->post_name == 'series' ) {
		$series_items = get_terms(
			array('series'),
			array(
				'hide_empty' => true,
				'orderby' => 'name',
				'order' => 'ASC',
			)
		);
		$content = '<div class="recent-episodes"><br>';
		foreach ($series_items as $series) {
			$series_id = $series->term_id;
			$series_image = get_option( "ss_podcasting_data_image_{$series_id}", false );
			if ( empty( $series_image ) ) {
				continue;
			}
			$series_image_attachment_id = attachment_url_to_postid( $series_image );
			$img_small = wp_get_attachment_image_src( $series_image_attachment_id, 'thumbnail' );
			$img_medium = wp_get_attachment_image_src( $series_image_attachment_id, 'medium' );
			$content .= '<div class="recent-episodes__item">';
			$content .= '<a class="link-no-hover" href="' . get_permalink( $series->term_id ) . '">';
			$content .= '<img class="recent-episodes__image" loading="lazy" alt="" srcset="' . $img_medium[0] . ' ' . $img_medium[1] . 'w, ' . $img_small[0] . ' ' . $img_small[1] . 'w" sizes="150px" src="' . $img_small[0] . '">';
			$content .= '</a>';
			$content .= '<h2 class="recent-episodes__title">';
			$content .= '<a href="' . get_permalink( $series->term_id ) . '">';
			$content .= $series->name;
			$content .= '</a>';
			$content .= '</h2>';
			$content .= '</div>';
		}
		$content .= '</div>';
	}
	return $content;
}
add_filter( 'the_content', 'lhi_the_content', 100 );

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
			<?php $series = get_the_terms( get_the_ID(), 'series' )[0];
			$series_id = $series->term_id;
			$series_image = get_option( "ss_podcasting_data_image_{$series_id}", false );
			if ( $series_image ) {
				$series_image_attachment_id = attachment_url_to_postid( $series_image );
				$img_small = wp_get_attachment_image_src( $series_image_attachment_id, 'thumbnail' );
				$img_medium = wp_get_attachment_image_src( $series_image_attachment_id, 'medium' );
			} ?>
		<?php if ( $series_image ) : ?>
			<a class="link-no-hover" href="<?php the_permalink(); ?>">
				<img class="recent-episodes__image" loading="lazy" alt="" srcset="<?php echo $img_medium[0] . ' ' . $img_medium[1] . 'w, ' . $img_small[0] . ' ' . $img_small[1] . 'w'; ?>" sizes="150px" src="<?php echo $img_small[0]; ?>">
			</a>
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

function lhi_breadcrumb() {
	if ( is_front_page() ) {
		return;
	}

	// Define
	global $post;
	$custom_taxonomy  = ''; // If you have custom taxonomy place it here

	$defaults = array(
		'id'          =>  'lhi-breadcrumb',
		'classes'     =>  'lhi-breadcrumb',
		'home_title'  =>  esc_html__( 'Home', '' )
	);

	// Start the breadcrumb with a link to your homepage
	echo '<ul id="'. esc_attr( $defaults['id'] ) .'" class="'. esc_attr( $defaults['classes'] ) .'">';

	// Creating home link
	echo '<li class="lhi-breadcrumb__item"><a href="'. get_home_url() .'">'. esc_html( $defaults['home_title'] ) .'</a></li>';

	if ( is_single() ) {

		// Get posts type
		$post_type = get_post_type();

		// If post type is not post
		if ( $post_type != 'post' ) {

			$post_type_object   = get_post_type_object( $post_type );
			$post_type_link     = get_post_type_archive_link( $post_type );

			echo '<li class="lhi-breadcrumb__item lhi-breadcrumb__item-cat"><a href="' . $post_type_link . '">' . $post_type_object->labels->name . '</a></li>';

		}

		// Get categories
		$category = get_the_category( $post->ID );

		// If category not empty
		if ( !empty( $category ) ) {

			// Arrange category parent to child
			$category_values      = array_values( $category );
			$get_last_category    = end( $category_values );
			// $get_last_category    = $category[count($category) - 1];
			$get_parent_category  = rtrim( get_category_parents( $get_last_category->term_id, true, ',' ), ',' );
			$cat_parent           = explode( ',', $get_parent_category );

			// Store category in $display_category
			$display_category = '';
			foreach( $cat_parent as $p ) {
				$display_category .=  '<li class="lhi-breadcrumb__item lhi-breadcrumb__item-cat">'. $p .'</li>';
			}

		}

		// If it's a custom post type within a custom taxonomy
		$taxonomy_exists = taxonomy_exists( $custom_taxonomy );

		if ( empty( $get_last_category ) && !empty( $custom_taxonomy ) && $taxonomy_exists ) {

			$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
			$cat_id         = $taxonomy_terms[0]->term_id;
			$cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
			$cat_name       = $taxonomy_terms[0]->name;

		}

		// Check if the post is in a category
		if ( !empty( $get_last_category ) ) {

			echo $display_category;
			echo '<li class="lhi-breadcrumb__item lhi-breadcrumb__item--current">'. get_the_title() .'</li>';

		} else if ( !empty( $cat_id ) ) {

			echo '<li class="lhi-breadcrumb__item lhi-breadcrumb__item-cat"><a href="'. $cat_link .'">'. $cat_name .'</a></li>';
			echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">'. get_the_title() .'</li>';

		} else {

			echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">'. get_the_title() .'</li>';

		}

	} else if ( is_archive() ) {

		if ( is_tax() ) {
			// Get posts type
			$post_type = get_post_type();

			// If post type is not post
			if ( $post_type != 'post' ) {

				$post_type_object   = get_post_type_object( $post_type );
				$post_type_link     = get_post_type_archive_link( $post_type );

				echo '<li class="lhi-breadcrumb__item lhi-breadcrumb__item-cat lhi-breadcrumb__item-custom-post-type-' . $post_type . '"><a href="' . $post_type_link . '">' . $post_type_object->labels->name . '</a></li>';

			}

			$custom_tax_name = get_queried_object()->name;
			echo '<li class="lhi-breadcrumb__item lhi-breadcrumb__item--current">'. $custom_tax_name .'</li>';

		} else if ( is_category() ) {

			$parent = get_queried_object()->category_parent;

			if ( $parent !== 0 ) {

				$parent_category = get_category( $parent );
				$category_link   = get_category_link( $parent );

				echo '<li class="lhi-breadcrumb__item"><a href="'. esc_url( $category_link ) .'">'. $parent_category->name .'</a></li>';

			}

			echo '<li class="lhi-breadcrumb__item lhi-breadcrumb__item--current">'. single_cat_title( '', false ) .'</li>';

		} else if ( is_tag() ) {

			// Get tag information
			$term_id        = get_query_var('tag_id');
			$taxonomy       = 'post_tag';
			$args           = 'include=' . $term_id;
			$terms          = get_terms( $taxonomy, $args );
			$get_term_name  = $terms[0]->name;

			// Display the tag name
			echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">'. $get_term_name .'</li>';

		} else if ( is_day() ) {

			// Day archive

			// Year link
			echo '<li class="lhi-breadcrumb__item-year lhi-breadcrumb__item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') . ' Archives</a></li>';

			// Month link
			echo '<li class="lhi-breadcrumb__item-month lhi-breadcrumb__item"><a href="'. get_month_link( get_the_time('Y'), get_the_time('m') ) .'">'. get_the_time('M') .' Archives</a></li>';

			// Day display
			echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">'. get_the_time('jS') .' '. get_the_time('M'). ' Archives</li>';

		} else if ( is_month() ) {

			// Month archive

			// Year link
			echo '<li class="lhi-breadcrumb__item-year lhi-breadcrumb__item"><a href="'. get_year_link( get_the_time('Y') ) .'">'. get_the_time('Y') . ' Archives</a></li>';

			// Month Display
			echo '<li class="lhi-breadcrumb__item-month lhi-breadcrumb__item--current lhi-breadcrumb__item">'. get_the_time('M') .' Archives</li>';

		} else if ( is_year() ) {

			// Year Display
			echo '<li class="lhi-breadcrumb__item-year lhi-breadcrumb__item--current lhi-breadcrumb__item">'. get_the_time('Y') .' Archives</li>';

		} else if ( is_author() ) {

			// Auhor archive

			// Get the author information
			global $author;
			$userdata = get_userdata( $author );

			// Display author name
			echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">'. 'Author: '. $userdata->display_name . '</li>';

		} else {

			echo '<li class="lhi-breadcrumb__item lhi-breadcrumb__item--current">' . post_type_archive_title('', false) . '</li>';

		}

	} else if ( is_page() ) {

		// Standard page
		if ( $post->post_parent ) {

			// If child page, get parents
			$anc = get_post_ancestors( $post->ID );

			// Get parents in the right order
			$anc = array_reverse( $anc );

			// Parent page loop
			if ( !isset( $parents ) ) $parents = null;
			foreach ( $anc as $ancestor ) {

				$parents .= '<li class="lhi-breadcrumb__item-parent lhi-breadcrumb__item"><a href="'. get_permalink( $ancestor ) .'">'. get_the_title( $ancestor ) .'</a></li>';

			}

			// Display parent pages
			echo $parents;

			// Current page
			echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">'. get_the_title() .'</li>';

		} else {

			// Just display current page if not parents
			echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">'. get_the_title() .'</li>';

		}

	} else if ( is_search() ) {

		// Search results page
		echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">Search results for: '. get_search_query() .'</li>';

	} else if ( is_404() ) {

		// 404 page
		echo '<li class="lhi-breadcrumb__item--current lhi-breadcrumb__item">' . 'Error 404' . '</li>';

	}

	// End breadcrumb
	echo '</ul>';

}

/**
 * WordPress Cache Busting made simple.
 *
 * @author Recolize GmbH <service@recolize.com>
 * @license http://opensource.org/licenses/GPL-3.0 GNU General Public License Version 3 (GPLv3)
 *
 * This script is based on
 * @see https://medium.com/@futuremediagr/easy-versioning-for-css-and-js-files-in-wordpress-e7dad756586c
 * @see https://gist.github.com/ocean90/1966227
 */
function lhi_set_custom_ver_css_js($src) {
	// Don't touch admin scripts.
	if (is_admin()) {
		return $src;
	}

	$_src = $src;
	if (strpos($_src, '//') === 0) {
		$_src = 'https:' . $_src;
	}

	$_src = parse_url($_src);

	// Give up if malformed URL.
	if (false === $_src) {
		return $src;
	}

	// Check if it's a local URL.
	$wordPressUrl = parse_url(home_url());
	if (isset($_src['host']) && $_src['host'] !== $wordPressUrl['host']) {
		return $src;
	}

	$filePath = ABSPATH . $_src['path'];
	if (file_exists($filePath) && strpos($src, 'ver=') !== false) {
		$src = add_query_arg('ver', filemtime($filePath), $src);
	}

	return $src;
}

function lhi_css_js_versioning() {
	add_filter('style_loader_src', 'lhi_set_custom_ver_css_js', 9999);
	add_filter('script_loader_src', 'lhi_set_custom_ver_css_js', 9999);
}

add_action('init', 'lhi_css_js_versioning');

/**
 * Head hook
 * @see https://developer.wordpress.org/reference/hooks/wp_head/
 */
function lets_hear_it_head() {
	$title = get_the_title();
	$content = "A truly independent podcast artist collective.";
	if ( is_front_page() ) {
		$title = "Let's Hear It - Make More Fun";
	}
	$series = get_the_terms( get_the_ID(), 'series' )[0];
	$series_image = 'https://letshearit.network/wp-content/themes/lets-hear-it/logo.png';
	if ( !empty($series) ) {
		$content = $series->description;
		$series_image = get_option( "ss_podcasting_data_image_{$series->term_id}", false );
	}
	?>

	<meta name="description" content="<?php echo $content; ?>">

	<!-- Google / Search Engine Tags -->
	<meta itemprop="name" content="<?php echo $title; ?>">
	<meta itemprop="description" content="<?php echo $content; ?>">
	<meta itemprop="image" content="<?php echo $series_image; ?>">

	<!-- Facebook Meta Tags -->
	<meta property="og:url" content="<?php get_the_permalink(); ?>">
	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php echo $title; ?>">
	<meta property="og:description" content="<?php echo $content; ?>">
	<meta property="og:image" content="<?php echo $series_image; ?>">

	<!-- Twitter Meta Tags -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo $title; ?>">
	<meta name="twitter:description" content="<?php echo $content; ?>">
	<meta name="twitter:image" content="<?php echo $series_image; ?>">

	<?php
}
add_action( 'wp_head', 'lets_hear_it_head' );

/**
 * Enqueue scripts and styles.
 */
function lets_hear_it_scripts() {
	wp_dequeue_style( 'ssp-recent-episodes' );
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
