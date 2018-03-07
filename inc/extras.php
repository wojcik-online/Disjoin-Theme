<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package disjoin
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function disjoin_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
add_filter( 'wp_page_menu_args', 'disjoin_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function disjoin_body_classes( $classes ) {
  // Adds a class of group-blog to blogs with more than 1 published author.
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }

  return $classes;
}
add_filter( 'body_class', 'disjoin_body_classes' );


// Mark Posts/Pages as Untiled when no title is used
add_filter( 'the_title', 'disjoin_title' );

function disjoin_title( $title ) {
  if ( $title == '' ) {
    return 'Untitled';
  } else {
    return $title;
  }
}

/**
 * Allow shortcodes in Dynamic Sidebar
 */
add_filter('widget_text', 'do_shortcode');

/**
 * Prevent page scroll when clicking the more link
 */
function disjoin_remove_more_link_scroll( $link ) {
  $link = preg_replace( '|#more-[0-9]+|', '', $link );
  return $link;
}
add_filter( 'the_content_more_link', 'disjoin_remove_more_link_scroll' );

/**
 * Change default "Read More" button when using the_excerpt
 */
function disjoin_excerpt_more( $more ) {
  return ' <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">'. __( 'Continue reading', 'disjoin' ) .'<i class="fa fa-chevron-right"></i></a>';
}
add_filter( 'excerpt_more', 'disjoin_excerpt_more' );

/**
 * Add Bootstrap classes for table
 */
function disjoin_add_custom_table_class( $content ) {
    return str_replace( '<table>', '<table class="table table-hover">', $content );
}
add_filter( 'the_content', 'disjoin_add_custom_table_class' );


if ( ! function_exists( 'custom_password_form' ) ) :
/**
 * password protected post form
 */
function custom_password_form() {
  global $post;
  $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
  $o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
        <div class="row">
          <div class="col-lg-10">
              ' . __( "<p>This post is password protected. To view it please enter your password below:</p>" ,'disjoin') . '
              <label for="' . $label . '">' . __( "Password:" ,'disjoin') . ' </label>
            <div class="input-group">
              <input class="form-control" value="' . get_search_query() . '" name="post_password" id="' . $label . '" type="password">
              <span class="input-group-btn"><button type="submit" class="btn btn-primary" name="submit" id="searchsubmit" vvalue="' . esc_attr__( "Submit",'disjoin' ) . '">' . __( "Submit" ,'disjoin') . '</button>
              </span>
            </div>
          </div>
        </div>
      </form>';
  return $o;
}
endif;
add_filter( 'the_password_form', 'custom_password_form' );


if ( ! function_exists( 'disjoin_social' ) ) :
/**
 * Process social links from Theme Options
 */
function disjoin_social(){
  $output = '<div id="social" class="social">';
  $output .= disjoin_social_item(of_get_option('social_facebook'), 'Facebook', 'facebook');
  $output .= disjoin_social_item(of_get_option('social_twitter'), 'Twitter', 'twitter');
  $output .= disjoin_social_item(of_get_option('social_google'), 'Google Plus', 'google-plus');
  $output .= disjoin_social_item(of_get_option('social_youtube'), 'YouTube', 'youtube');
  $output .= disjoin_social_item(of_get_option('social_linkedin'), 'LinkedIn', 'linkedin');
  $output .= disjoin_social_item(of_get_option('social_pinterest'), 'Pinterest', 'pinterest');
  $output .= disjoin_social_item(of_get_option('social_feed'), 'Feed', 'rss');
  $output .= disjoin_social_item(of_get_option('social_tumblr'), 'Tumblr', 'tumblr');
  $output .= disjoin_social_item(of_get_option('social_flickr'), 'Flickr', 'flickr');
  $output .= disjoin_social_item(of_get_option('social_instagram'), 'Instagram', 'instagram');
  $output .= disjoin_social_item(of_get_option('social_dribbble'), 'Dribbble', 'dribbble');
  $output .= disjoin_social_item(of_get_option('social_skype'), 'Skype', 'skype');
  $output .= disjoin_social_item(of_get_option('social_vimeo'), 'Vimeo', 'vimeo-square');
  $output .= '</div>';
  echo $output;
}
endif;


if ( ! function_exists( 'disjoin_social_item' ) ) :
/**
 * Output social links on frontend.
 */
function disjoin_social_item($url, $title = '', $icon = ''){
  if($url != ''):
    $output = '<a class="social-profile '.$icon.'" href="'.esc_url($url).'" target="_blank" title="'.$title.'">';
    if($icon != '') $output .= '<span class="social_icon fa fa-'.$icon.'"></span>';
    $output .= '</a>';
    return $output;
  endif;
}
endif;


if ( ! function_exists( 'disjoin_footer_links' ) ) :
/**
 * footer menu (should you choose to use one)
 */
function disjoin_footer_links() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'container'       => '',                              // remove nav container
    'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
    'menu'            => __( 'Footer Links', 'disjoin' ),   // nav name
    'menu_class'      => 'nav footer-nav clearfix',      // adding custom nav class
    'theme_location'  => 'footer-links',             // where it's located in the theme
    'before'          => '',                                 // before the menu
    'after'           => '',                                  // after the menu
    'link_before'     => '',                            // before each link
    'link_after'      => '',                             // after each link
    'depth'           => 0,                                   // limit the depth of the nav
    'fallback_cb'     => 'disjoin_footer_links_fallback'  // fallback function
  ));
} /* end disjoin footer link */
endif;

/**
 * Get Post Views - for Popular Posts widget
 */
function disjoin_getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
function disjoin_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


/**
 * function to show the footer info, copyright information
 */
function disjoin_footer_info() {
   printf( esc_html__( '%1$s powered by %2$s', 'disjoin' ) , '<a title="Disjoin Theme" href="http://colorlib.com/wp/disjoin/" target="_blank">Disjoin Theme</a>', '<a href="http://wordpress.org/" target="_blank" title="WordPress">WordPress</a>');
}
add_action( 'disjoin_footer', 'disjoin_footer_info', 30 );


if ( ! function_exists( 'get_disjoin_theme_options' ) )  {
/**
 * Generate custom CSS output in website source from Theme Options.
 */
    function get_disjoin_theme_options(){

      echo '<style type="text/css">';

      if ( of_get_option('link_color')) {
        echo 'a, #infinite-handle span {color:' . of_get_option('link_color') . '}';
      }
      if ( of_get_option('link_hover_color')) {
        echo 'a:hover {color: '.of_get_option('link_hover_color', '#000').';}';
      }
      if ( of_get_option('link_active_color')) {
        echo 'a:active {color: '.of_get_option('link_active_color', '#000').';}';
      }
      if ( of_get_option('element_color')) {
        echo '.btn-primary, .label-primary, .carousel-caption h4 {background-color: '.of_get_option('element_color', '#000').'; border-color: '.of_get_option('element_color', '#000').';} hr.section-divider:after, .entry-meta .fa { color: '.of_get_option('element_color', '#000').'}';
      }
      if ( of_get_option('element_color_hover')) {
        echo '.btn-primary:hover, .label-primary[href]:hover, .label-primary[href]:focus, #infinite-handle span:hover, .btn.btn-primary.read-more:hover, .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .site-main [class*="navigation"] a:hover, .more-link:hover, #image-navigation .nav-previous a:hover, #image-navigation .nav-next a:hover  { background-color: '.of_get_option('element_color_hover', '#000').'; border-color: '.of_get_option('element_color_hover', '#000').'; }';
      }
      if ( of_get_option('heading_color')) {
        echo 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .entry-title {color: '.of_get_option('heading_color', '#000').';}';
      }
      if ( of_get_option('top_nav_bg_color')) {
        echo '.navbar.navbar-default {background-color: '.of_get_option('top_nav_bg_color', '#000').';}';
      }
      if ( of_get_option('top_nav_link_color')) {
        echo '.navbar-default .navbar-nav > li > a, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus { color: '.of_get_option('top_nav_link_color', '#000').';}';
      }
      if ( of_get_option('top_nav_dropdown_bg')) {
        echo '.dropdown-menu, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {background-color: '.of_get_option('top_nav_dropdown_bg', '#000').';}';
      }
      if ( of_get_option('top_nav_dropdown_item')) {
        echo '.navbar-default .navbar-nav .open .dropdown-menu > li > a { color: '.of_get_option('top_nav_dropdown_item', '#000').';}';
      }
      if ( of_get_option('footer_bg_color')) {
        echo '#colophon {background-color: '.of_get_option('footer_bg_color', '#000').';}';
      }
      if ( of_get_option('footer_text_color')) {
        echo '.copyright {color: '.of_get_option('footer_text_color', '#000').';}';
      }
      if ( of_get_option('footer_link_color')) {
        echo '.site-info a {color: '.of_get_option('footer_link_color', '#000').';}';
      }
      if ( of_get_option('social_color')) {
        echo '.social-icons li a {color: '.of_get_option('social_color', '#000').' !important;}';
      }
      $typography = of_get_option('main_body_typography');
      if ( $typography ) {
        echo '.entry-content {font-family: ' . $typography['face'] . '; font-size:' . $typography['size'] . '; font-weight: ' . $typography['style'] . '; color:'.$typography['color'] . ';}';
      }
      if ( of_get_option('custom_css')) {
        echo html_entity_decode( of_get_option( 'custom_css', 'no entry' ) );
      }
        echo '</style>';
    }
}
add_action('wp_head','get_disjoin_theme_options',10);

/**
 *  Theme Options sidebar
 */
add_action( 'optionsframework_after','disjoin_options_display_sidebar' );

function disjoin_options_display_sidebar() { ?>
  <!-- Twitter -->
  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

  <!-- Facebook -->
    <div id="fb-root"></div>
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=328285627269392";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <div id="optionsframework-sidebar" class="metabox-holder">
    <div id="optionsframework" class="postbox">
        <h3><?php _e('Support and Documentation','disjoin') ?></h3>
          <div class="inside">
              <div id="social-share">
                <div class="fb-like" data-href="https://www.facebook.com/colorlib" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true"></div>
                <div class="tw-follow" ><a href="https://twitter.com/colorlib" class="twitter-follow-button" data-show-count="false">Follow @colorlib</a></div>
              </div>
                <p><b><a href="http://colorlib.com/wp/support/disjoin"><?php _e('Disjoin Documentation','disjoin'); ?></a></b></p>
                <p><?php _e('The best way to contact us with <b>support questions</b> and <b>bug reports</b> is via','disjoin') ?> <a href="http://colorlib.com/wp/forums"><?php _e('Colorlib support forum','disjoin') ?></a>.</p>
                <p><?php _e('If you like this theme, I\'d appreciate any of the following:','disjoin') ?></p>
                <ul>
                    <li><a class="button" href="http://wordpress.org/support/view/theme-reviews/disjoin?filter=5" title="<?php esc_attr_e('Rate this Theme', 'disjoin'); ?>" target="_blank"><?php printf(__('Rate this Theme','disjoin')); ?></a></li>
                    <li><a class="button" href="http://www.facebook.com/colorlib" title="Like Colorlib on Facebook" target="_blank"><?php printf(__('Like on Facebook','disjoin')); ?></a></li>
                    <li><a class="button" href="http://twitter.com/colorlib/" title="Follow Colrolib on Twitter" target="_blank"><?php printf(__('Follow on Twitter','disjoin')); ?></a></li>
                </ul>
          </div>
      </div>
    </div>
<?php }



/*
 * Basic WooCommerce setup.
 */
add_action('woocommerce_before_main_content', 'disjoin_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'disjoin_wrapper_end', 10);

function disjoin_wrapper_start() {
  echo '<div id="primary" class="col-md-8">';
}

function disjoin_wrapper_end() {
  echo '</div>';
}


if ( ! function_exists( 'disjoin_woocommerce_menucart' ) ) :
/**
 * Place a cart icon with number of items and total cost in the menu bar.
 *
 * https://gist.github.com/srikat/8264387#file-functions-php
 */
function disjoin_woocommerce_menucart($menu, $args) {

  // Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
  if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || 'primary' !== $args->theme_location )
    return $menu;

  ob_start();
    global $woocommerce;
    $viewing_cart = __('View your shopping cart', 'disjoin');
    $start_shopping = __('Start shopping', 'disjoin');
    $cart_url = $woocommerce->cart->get_cart_url();
    $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
    $cart_contents_count = $woocommerce->cart->cart_contents_count;
    $cart_contents = sprintf(_n('%d item', '%d items', $cart_contents_count, 'disjoin'), $cart_contents_count);
    $cart_total = $woocommerce->cart->get_cart_total();
    // Uncomment the line below to hide nav menu cart item when there are no items in the cart
    // if ( $cart_contents_count > 0 ) {
      if ($cart_contents_count == 0) {
        $menu_item = '</ul><ul class="nav navbar-nav navbar-right"><li><a class="woomenucart-menu-item" href="'. $shop_page_url .'" title="'. $start_shopping .'">';
      } else {
        $menu_item = '</ul><ul class="nav navbar-nav navbar-right"><li><a class="woomenucart-menu-item" href="'. $cart_url .'" title="'. $viewing_cart .'">';
      }

      $menu_item .= '<i class="fa fa-shopping-cart"></i> ';

      $menu_item .= $cart_contents.' - '. $cart_total;
      $menu_item .= '</a></li></ul>';
    // Uncomment the line below to hide nav menu cart item when there are no items in the cart
    // }
    echo $menu_item;
  $social = ob_get_clean();
  return $menu . $social;

}
endif;
add_filter('wp_nav_menu_items','disjoin_woocommerce_menucart', 10, 2);


if ( ! function_exists( 'disjoin_custom_favicon' ) ) :
/**
 * Add custom favicon displayed in WordPress dashboard and frontend
 */
function disjoin_custom_favicon() {
  if ( of_get_option( 'custom_favicon' ) ) {
    echo '<link rel="shortcut icon" type="image/x-icon" href="' . of_get_option( 'custom_favicon' ) . '" />'. "\n";
  }
}
endif;
add_action( 'wp_head', 'disjoin_custom_favicon', 0 );
add_action( 'admin_head', 'disjoin_custom_favicon', 0 );


/**
 * Allows users to save skype protocol skype: in menu URL
 */
function disjoin_allow_skype_protocol( $protocols ){
    $protocols[] = 'skype';
    return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'disjoin_allow_skype_protocol' );

?>