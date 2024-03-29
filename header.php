<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 // Setup the tag to be used for the header area (`h1` on the front page and `span` on all others).
 $heading_tag = 'span';
 if ( is_front_page() ) { $heading_tag = 'h1'; }
 
 // Get our website's name, description and URL. We use them several times below so lets get them once.
 $site_title = get_bloginfo( 'name' );
 $site_url = home_url( '/' );
 $site_description = get_bloginfo( 'description' );
 
 global $woo_options;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<link  href="<?=get_template_directory_uri()?>/favicon.ico" rel="icon" type="image/x-icon" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="all" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) { wp_enqueue_script( 'comment-reply' ); } ?>
<?php wp_head(); ?>
<?php woo_head(); ?>
<style>
	body {
		background: none repeat scroll 0 0 #F7F6F5;
		border:none;
	}
</style>
</head>
<body id='custom_css' <?php body_class(); ?>>
<?php include_once("analyticstracking.php") ?>
<?php woo_top(); ?>
<div id="wrapper">        
	<?php woo_header_before(); ?>
    
	<div id="header" class="col-full">
 		
		<?php woo_header_inside(); ?>
       
		<div id="logo">
		<?php
			//if ( $site_description ) { echo '<span class="site-description">' . $site_description . '</span>' . "\n"; }
			// Website heading/logo and description text.
			if ( isset($woo_options['woo_logo']) && $woo_options['woo_logo'] ) {
				echo '<a href="' . $site_url . '" title="' . $site_description . '"><img src="' . $woo_options['woo_logo'] . '" alt="' . $site_title . '" /></a>' . "\n";
			} else {
				//echo '<' . $heading_tag . ' class="site-title"><a href="' . $site_url . '">' . '<span class="site-description">' . $site_description . '</span>  ' . $site_title . '</a></' . $heading_tag . '>' . "\n";
				echo '<' . $heading_tag . ' class="site-title"><a href="' . $site_url . '">' . $site_title . '</a></' . $heading_tag . '>' . "\n";
			} // End IF Statement
			
		?>
		</div><!-- /#logo -->
	       
		<?php if ( ( isset( $woo_options['woo_ad_top'] ) ) && ( $woo_options['woo_ad_top'] == 'true' ) ) { ?>
        <div id="topad">
        
		<?php if ( ( isset( $woo_options['woo_ad_top_adsense'] ) ) && ( $woo_options['woo_ad_top_adsense'] != "") ) { 
            echo stripslashes(get_option('woo_ad_top_adsense'));             
        } else { ?>
            <a href="<?php echo get_option('woo_ad_top_url'); ?>"><img src="<?php echo $woo_options['woo_ad_top_image']; ?>" alt="" /></a>
        <?php } ?>		   	
            
        </div><!-- /#topad -->
        <?php } ?>
       
	</div><!-- /#header -->
	<?php woo_header_after(); ?>
