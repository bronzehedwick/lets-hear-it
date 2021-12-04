<?php
/**
 * Template part for displaying series
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Lets_Hear_It
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				lets_hear_it_posted_on();
				lets_hear_it_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php lets_hear_it_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'lets-hear-it' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		$guests = get_field( 'select_guests' );
		if ( $guests ) {
			$pluralized_guest = count($guests) > 1 ? 'Guests' : 'Guest';
			echo '<h2>Episode ' . $pluralized_guest . '</h2>';
			foreach ( $guests as $guest_id ) {
				$guest = get_post( $guest_id );
				$guest_image = get_field( 'image', $guest_id );
				echo '<div class="teaser">';
				echo '<h3 class="teaser__title"><a href="' . get_permalink( $guest_id ) . '">' . $guest->post_title . '</a></h3>';
				echo '<img class="teaser__image" src="' . $guest_image['sizes']['medium'] . '" alt="' . $guest_image['alt'] . '" width="' . $guest_image['sizes']['medium-width'] . '" height="' . $guest_image['sizes']['medium-height'] . '">';
				echo '<div class="teaser__content">' . $guest->post_content . '</div>';
				echo '</div>';
			}
		}

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lets-hear-it' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php lets_hear_it_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
<?php
// vim: tabstop=4 shiftwidth=4 noexpandtab nolist
?>
