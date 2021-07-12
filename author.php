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
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();


/* vim: tabstop=4 shiftwidth=4 noexpandtab nolist
 */
