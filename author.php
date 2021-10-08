<?php
/**
 * The template for displaying all author (user) pages.
 *
 * @link https://codex.wordpress.org/Author_Templates
 *
 * @package Lets_Hear_It
 */

get_header();
?>

	<?php
	$author = (isset($_GET['author_name'])) ?
	get_user_by('slug', $author_name) :
	get_userdata(intval($author));
	?>

	<main id="primary" class="site-main">

		<article>
			<header class="entry-header">
				<h1 class="entry-title"><?php echo $author->display_name; ?></h1>
			</header><!-- .entry-header -->

			<?php lets_hear_it_post_thumbnail(); ?>

			<div class="entry-content">
				<?php echo get_avatar(
					$author->ID,
					200,
					'retro',
					'Image of ' . $author->display_name,
					[
						'class' => 'alignleft',
					]
			   	); ?>
				<?php if ( !empty( $author->description ) ) : ?>
					<?php foreach ( explode( "\n", $author->description ) as $line ) : ?>
						<p><?php echo $line; ?></p>
					<?php endforeach; ?>
				<?php else : ?>
					<p><?php echo $author->display_name; ?>'s biography is shrouded in mystery. Check back later and see if we've dug anything up.</p>
				<?php endif; ?>
				<?php if ( !empty( $author->user_url ) ) : ?>
					<p>Website: <a href="<?php echo $author->user_url?>"><?php echo $author->user_url?></a></p>
				<?php endif; ?>
				<?php $match_count = 0; ?>
				<?php foreach( get_terms( 'series' ) as $series ): ?>
					<?php $series_meta = get_option( "taxonomy_term_$series->term_id" ); ?>
					<?php if ( in_array( $author->ID, $series_meta['podcast_hosts'] ) ) : ?>
						<?php if ( $match_count < 1 ) : ?>
							<?php $match_count++; ?>
							<h2><?php echo explode( ' ', $author->display_name )[0]; ?> is the host of…</h2>
						<?php endif; ?>
						<div class="episode-card">
							<?php
								$series_image = get_option( "ss_podcasting_data_image_{$series->term_id}", false );
								if ( $series_image ) {
									$series_image_attachment_id = attachment_url_to_postid( $series_image );
									$img_small = wp_get_attachment_image_src( $series_image_attachment_id, 'thumbnail' );
									$img_medium = wp_get_attachment_image_src( $series_image_attachment_id, 'medium' );
								}
							?>
							<?php if ( $series_image ) : ?>
								<a class="link-no-hover" tabindex="-1" aria-hidden="true" href="/series/<?php echo $series->slug; ?>">
									<img class="recent-episodes__image" loading="lazy" alt="<?php echo $series->name; ?>" srcset="<?php echo $img_medium[0] . ' ' . $img_medium[1] . 'w, ' . $img_small[0] . ' ' . $img_small[1] . 'w'; ?>" sizes="150px" src="<?php echo $img_small[0]; ?>">
								</a>
							<?php endif; ?>
						</div>
					<?php endif ?>
				<?php endforeach; ?>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();


/* vim: tabstop=4 shiftwidth=4 noexpandtab nolist
 */
