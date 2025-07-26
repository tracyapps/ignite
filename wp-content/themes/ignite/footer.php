<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ITS
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site_footer">
		<section class="footer_info background-subtle_grey">
			<div class="container narrow">

				<div class="footer_text">
					<?php echo ITS_options_footer_text(); ?>
				</div>
				<?php ITS_options_social_links( 'icon-', '', 'social-links', 'footer-social-links', true ); ?>
			</div>
		</section>
		<?php
		$legalNavProps = array(
			'theme_location' => 'legal-nav',
			'container' => false,
			'depth'		=> 1,
		);
		?>
		<nav class="footer_legal_nav">
			<?php wp_nav_menu($legalNavProps); ?>
		</nav>
	</footer>
</div><!-- #page -->
<?php wp_footer(); ?>

<div class="hidden svg-decoration-inject hide-this" aria-hidden="true">
	<?php include '_assets/svg/output/icons.svg'; ?>
</div>

</body>
</html>
