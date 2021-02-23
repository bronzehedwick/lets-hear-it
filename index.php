<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Lets_Hear_It
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		global $wp;
		if ( strpos( $wp->request, 'artist' ) !== FALSE ) :

			echo '<ul class="artists">';

			foreach ( get_users() as $user ) {
				echo '<li class="artist">';
				echo get_avatar($user->data->ID);
				echo '<span class="artist__name">' . $user->data->display_name . '</span>';
				echo '</li>';
			}

			echo '</ul>';

		elseif ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			if ( is_front_page() && is_active_sidebar( 'homepage-content' ) ) : ?>
				<div class="homepage-content">
					<?php dynamic_sidebar( 'homepage-content' ); ?>
				</div><!-- #homepage-content -->
			<?php endif;

			/* Start the Loop */
			if ( ! is_front_page() ) :
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;
			endif;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();

/*
vim: tabstop=4 shiftwidth=4 noexpandtab nolist
*/
