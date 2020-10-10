<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Lets_Hear_It
 */

?>

	<footer id="colophon" class="site-footer">
        <div class="site-footer__menu">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'footer-menu',
                'menu_id'        => '',
            )
        );
        ?>
        </div>
        <small class="site-legal">
            © <time datetime="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></time> Let's Hear It Network
            | Theme by <a href="https://www.chrisdeluca.me">Chris DeLuca</a>            
        </small><!-- .site-legal -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
