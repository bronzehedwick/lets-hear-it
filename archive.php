<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Let\'s_Hear_It
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				if ( get_post_type() == 'podcast' ) {
					$series = get_the_terms( get_the_ID(), 'series' )[0];
					$series_id = $series->term_id;
					$series_image = get_option( "ss_podcasting_data_image_{$series_id}", false );
					if ( $series_image ) {
						$series_image_attachment_id = attachment_url_to_postid( $series_image );
						$img_large = wp_get_attachment_image_src( $series_image_attachment_id, 'large' );
						echo '<div class="archive-image"><img class="recent-episodes__image" src="' . $img_large[0] . '" width="' . $img_large[1] / 2 . '" height="' . $img_large[2] / 2 . '" alt=""></div>';
					}
				}
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
