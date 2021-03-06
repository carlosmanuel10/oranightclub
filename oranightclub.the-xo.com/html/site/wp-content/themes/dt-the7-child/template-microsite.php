<?php
/**
 * Mirosite template.
 *
 * @package presscore
 * @since presscore 2.2
 */

/* Template Name: Microsite */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// add filter for theme options here
add_filter( 'dt_of_get_option', 'presscore_microsite_theme_options_filter', 15, 2 );

// add menu filter here
add_filter( 'dt_menu_options', 'presscore_microsite_menu_filter' );

// hide template parts
$hidden_parts = get_post_meta( $post->ID, "_dt_microsite_hidden_parts", false );

$hide_header = in_array( 'header', $hidden_parts );
$hide_floating_menu = in_array( 'floating_menu', $hidden_parts );

if ( ! function_exists( 'homephantom' ) ) :

	function homephantom() { ?>
	<div id="phantomhome"><div class="ph-wrap with-logo"><div class="ph-wrap-content"><div class="ph-wrap-inner"><div class="logo-box"><img width="285" height="100" src="http://rockwell.the-xo.com/site/wp-content/uploads/2015/10/rockwell-logo1.png"></div><div class="menu-box"><ul class="fancy-rollovers wf-mobile-hidden" id="main-nav">
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5097 first"><a href="/home/"><span>HOME</span></a></li> 
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5100"><a href="/events/"><span>EVENTS</span></a></li> 
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5156"><a href="/gallery/"><span>GALLERY</span></a></li> 
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5115"><a href="/press/"><span>PRESS</span></a></li> 
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5105"><a href="/reservations/"><span>RESERVATIONS</span></a></li> 
<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="/contact/"><span>CONTACT</span></a></li> 
</ul>
</div></div></div></div></div>
<!--<div id="phantomhome"><div class="ph-wrap"><div class="ph-wrap-content"><div class="ph-wrap-inner"><div class="logo-box"></div><div class="menu-box"></div></div></div></div></div>-->
	<?php }

endif;

if ( $hide_header && $hide_floating_menu ) {
	add_filter( 'presscore_show_header', '__return_false' );
    add_action( 'wp_footer', 'homephantom' );
} else if ( $hide_header ) {
	// see template-tags.php
	add_filter( 'presscore_header_classes', 'presscore_microsite_header_classes' );
}

if ( in_array( 'bottom_bar', $hidden_parts ) ) {
	add_filter( 'presscore_show_bottom_bar', '__return_false' );
}

if ( get_post_meta( $post->ID, '_dt_microsite_page_loading', true ) ) {
	// see template-tags.php
	add_action( 'presscore_body_top', 'presscore_microsite_add_loader_div' );
}

$config = Presscore_Config::get_instance();
$config->set('template', 'microsite');
$config->base_init();

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>	

			<div id="content" class="content" role="main">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<?php do_action('presscore_before_loop'); ?>

					<?php the_content(); ?>

					<?php presscore_display_share_buttons( 'page' ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'microsite' ); ?>

			<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

		<?php endif; // if content visible ?>

<?php get_footer(); ?>