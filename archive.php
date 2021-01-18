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
						$series_custom_fields = get_option( "taxonomy_term_$series_id" );
						echo '<div class="archive-meta">';
						echo '<img class="recent-episodes__image" src="' . $img_large[0] . '" width="' . $img_large[1] / 2 . '" height="' . $img_large[2] / 2 . '" alt="">';
						echo '<ul class="archive-meta__social">';
						if ( !empty( $series_custom_fields['twitter_url'] ) ) {
							echo '<li class="archive-meta__social-item">';
							echo '<a title="' . $series->name . ' on Twitter" href="' . $series_custom_fields['twitter_url'] . '" class="archive-meta__social-link">';
							echo '<svg width="36" height="36" xmlns="http://www.w3.org/2000/svg" aria-label="Twitter" role="img" viewBox="0 0 512 512"><rect width="512" height="512" rx="15%" fill="#1da1f2"/><path fill="#fff" d="M437 152a72 72 0 01-40 12a72 72 0 0032-40a72 72 0 01-45 17a72 72 0 00-122 65a200 200 0 01-145-74a72 72 0 0022 94a72 72 0 01-32-7a72 72 0 0056 69a72 72 0 01-32 1a72 72 0 0067 50a200 200 0 01-105 29a200 200 0 00309-179a200 200 0 0035-37"/></svg>';
							echo '</a>';
							echo '</li>';
						}
						if ( !empty( $series_custom_fields['instagram_url'] ) ) {
							echo '<li class="archive-meta__social-item">';
							echo '<a title="' . $series->name . ' on Instagram" href="' . $series_custom_fields['instagram_url'] . '" class="archive-meta__social-link">';
							echo '<svg width="36" height="36" xmlns="http://www.w3.org/3600/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-label="Instagram" role="img" viewBox="0 0 512 512"><rect width="512" height="512" rx="15%" id="b"/><use fill="url(#a)" xlink:href="#b"/><use fill="url(#c)" xlink:href="#b"/><radialGradient id="a" cx=".4" cy="1" r="1"><stop offset=".1" stop-color="#fd5"/><stop offset=".5" stop-color="#ff543e"/><stop offset="1" stop-color="#c837ab"/></radialGradient><linearGradient id="c" x2=".2" y2="1"><stop offset=".1" stop-color="#3771c8"/><stop offset=".5" stop-color="#60f" stop-opacity="0"/></linearGradient><g fill="none" stroke="#fff" stroke-width="30"><rect width="308" height="308" x="102" y="102" rx="81"/><circle cx="256" cy="256" r="72"/><circle cx="347" cy="165" r="6"/></g></svg>';
							echo '</a>';
							echo '</li>';
						}
						if ( !empty( $series_custom_fields['youtube_url'] ) ) {
							echo '<li class="archive-meta__social-item">';
							echo '<a title="' . $series->name . ' on YouTube" href="' . $series_custom_fields['youtube_url'] . '" class="archive-meta__social-link">';
							echo '<svg width="36" height="36" xmlns="http://www.w3.org/2000/svg" aria-label="YouTube" role="img" viewBox="0 0 512 512" fill="#ed1d24"><rect width="512" height="512" rx="15%"/><path d="m427 169c-4-15-17-27-32-31-34-9-239-10-278 0-15 4-28 16-32 31-9 38-10 135 0 174 4 15 17 27 32 31 36 10 241 10 278 0 15-4 28-16 32-31 9-36 9-137 0-174" fill="#fff"/><path d="m220 203v106l93-53"/></svg>';
							echo '</a>';
							echo '</li>';
						}
						echo '<li class="archive-meta__social-item">';
						echo '<a title="' . $series->name . ' RSS feed" class="archive-meta__social-link" href="/feed/podcast/' . $series->slug . '">';
						echo '<svg width="36" height="36" xmlns="http://www.w3.org/3600/svg" aria-label="RSS" role="img" viewBox="0 0 512 512"><rect width="512" height="512" rx="15%" fill="#f80"/><circle cx="145" cy="367" r="35" fill="#fff"/><path fill="none" stroke="#fff" stroke-width="60" d="M109 241c89 0 162 73 162 162m114 0c0-152-124-276-276-276"/></svg>';
						echo '</a>';
						echo '</li>';
						echo '</ul>';
						echo '</div>';
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
