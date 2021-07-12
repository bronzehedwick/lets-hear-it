<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Lets_Hear_It
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php lets_hear_it_post_thumbnail(); ?>

	<div class="entry-content">
		<ul class="artists">
			<?php foreach ( get_users() as $user ) {
				echo '<li class="artist">';
				echo '<a class="artist__link" href="/author/' . $user->data->user_nicename . '">';
				echo get_avatar($user->data->ID);
				echo '<span class="artist__name">' . $user->data->display_name . '</span>';
				echo '</a>';
				echo '</li>';
			} ?>
		</ul>

		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lets-hear-it' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'lets-hear-it' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->

<!--
vim: tabstop=4 shiftwidth=4 noexpandtab nolist
-->
