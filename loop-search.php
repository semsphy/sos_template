<?php
/**
 * Loop - Search
 *
 * This is the loop logic used on the search results screen.
 *
 * @package WooFramework
 * @subpackage Template
 */
 global $more; $more = 0;
 
woo_loop_before();
if (have_posts()) { $count = 0;

	$title_before = '<span class="archive_header">';
	$title_after = '</span>';
	
	echo $title_before . sprintf( __( 'លទ្ធផលនៃការស្វែងរកពាក្យ &quot;%s&quot;', 'woothemes' ), get_search_query() ) . $title_after;
?>

<div class="fix"></div>

<?php
	while (have_posts()) { the_post(); $count++;
	
		if (get_option('woo_woo_tumblog_switch') == 'true') { $is_tumblog = woo_tumblog_test(); } else { $is_tumblog = false; }

		woo_get_template_part( 'content', get_post_type() );

	} // End WHILE Loop
} else {
	get_template_part( 'content', 'noposts' );
} // End IF Statement

woo_loop_after();

woo_pagenav();
?>