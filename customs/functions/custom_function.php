<?php

/**
 * Add button to editor wordpress
 */	
function add_pre_linux_code() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
   if ( get_user_option('rich_editing') == 'true') {
     add_filter('mce_external_plugins', 'add_pre_linux_code_tinymce_plugin');
     add_filter('mce_buttons', 'register_pre_linux_code');
   }
}
add_action('init', 'add_pre_linux_code');

/**
 * [register the button]
 * @param  [array] $buttons [Editor buttons]
 * @return [type]          [description]
 */
function register_pre_linux_code($buttons) {
   array_push($buttons, "|", "prelinuxcode");
   return $buttons;
}

/**
 * [javascript plugin for editor]
 * @param [type] $plugin_array [description]
 */
function add_pre_linux_code_tinymce_plugin($plugin_array) {
   $plugin_array['prelinuxcode'] = get_bloginfo('template_url').'/customs/js/editor_plugin.js';
   return $plugin_array;
}


/**
 * @desc clear cache mce editor
 * @param  [type] $ver [description]
 * @return [type]      [description]
 */
function my_refresh_mce($ver) {
  $ver += 3;
  return $ver;
}
add_filter( 'tiny_mce_version', 'my_refresh_mce');

/**
 * [add shortcut response with the [linux-code]code[/linux-code]]
 * @param  [array] $atts   [shortcut code attribute]
 * @param  [type] $content [code from above]
 * @return [string]          [html tag]
 */
function sos_linux_code( $atts, $content = null ) {
   extract(shortcode_atts(array(  'type' => 'normal',
                    'size' => '',
                    'style' => '',
                    'border' => '',
                    'icon' => ''), $atts));

    //return '<div class="woo-sc-box '.$type.' '.$size.' '.$style.' '.$border.'"'.$custom.'>' . do_shortcode( woo_remove_wpautop($content) ) . '</div>';
    return '<pre class="linux-code"><code>'.do_shortcode( sos_remove_wp_auto_p($content)).'</code></pre>';
}
add_shortcode( 'linux-code', 'sos_linux_code' );


if ( ! function_exists( 'sos_remove_wp_auto_p' ) ) {
  /**
   * [remove the tag p from selected conttent]
   * @param  [string] $content [selected content]
   * @return [string]
   */
  function sos_remove_wp_auto_p( $content ) {
    $content = do_shortcode( shortcode_unautop( $content ) );
    $content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
    return $content;
  }
}

/* Change number to khmer for category*/
add_filter('wp_list_categories', 'cat_count_khmer_inline');
function cat_count_khmer_inline($output) {

      $output = str_replace('</a> (',' <span class="khmer_num">(',$output);
      $output = str_replace(') ','</span></a>',$output);
      
      $dom = new DOMDocument();
      $output = mb_convert_encoding($output, 'HTML-ENTITIES', "UTF-8");
      $dom->loadHTML($output);
      $spans = $dom->getElementsByTagName('span');
      foreach($spans as $span){
        $span->nodeValue = khmer_number($span->nodeValue);
      }
       
      return $dom->saveHTML();;
}


/*Change pagination*/
add_filter('woo_pagination', 'khmer_pagination');

function khmer_pagination($page_links){
    $dom = new DOMDocument();
    $dom->loadHTML($page_links);
    $anchors = $dom->getElementsByTagName('a');
    $span = $dom->getElementsByTagName('span');

    foreach(array($anchors,$span) as $items){
      foreach($items as $item){
        if(is_numeric($item->nodeValue)){
          $item->nodeValue = khmer_number($item->nodeValue);
        }
      }
    }
    $page_links = $dom->saveHTML();
    $page_links = str_replace(array('Next','Previous'),array('ទៅមុខ','ថយក្រោយ'), $page_links);

    return $page_links;
}

#filter category list with menu
add_filter('widget_categories_args','filter_cat_with_menu');
function filter_cat_with_menu( $cat_args ) {
    global $wp_query;
    $cat_ID = get_query_var('cat');
    $cat_args['child_of'] = $cat_ID;
    $cat_args['hide_empty'] = 1;
    return $cat_args;
}

//remove_filter('woo_pagination', 'khmer_pagination');

remove_filter('the_content', 'wptexturize');
remove_filter('the_title', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');

/*
add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'acme_product',
    array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'products')
    )
  );
}

// Change the columns for the edit CPT screen
function change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'url'      => __( 'URL',      'trans' ),
    'referrer' => __( 'Referrer', 'trans' ),
    'host'     => __( 'Host', 'trans' ),
  );
  return $cols;
}
add_filter( "manage_acme_product_posts_columns", "change_columns" );


function custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case "url":
      $url = get_post_meta( $post_id, 'url', true);
      echo '<a href="' . $url . '">' . $url. '</a>';
      break;
    case "referrer":
      $refer = get_post_meta( $post_id, 'referrer', true);
      echo '<a href="' . $refer . '">' . $refer. '</a>';
      break;
    case "host":
      echo get_post_meta( $post_id, 'host', true);
      break;
  }
}
add_action( "manage_posts_custom_column", "custom_columns", 10, 2 );

// Make these columns sortable
function sortable_columns() {
  return array(
    'url'      => 'url',
    'referrer' => 'referrer',
    'host'     => 'host'
  );
}
add_filter( "manage_edit-acme_product_sortable_columns", "sortable_columns" );

// Filter the request to just give posts for the given taxonomy, if applicable.
function taxonomy_filter_restrict_manage_posts() {
    global $typenow;

    // If you only want this to work for your specific post type,
    // check for that $type here and then return.
    // This function, if unmodified, will add the dropdown for each
    // post type / taxonomy combination.

    $post_types = get_post_types( array( '_builtin' => false ) );

    if ( in_array( $typenow, $post_types ) ) {
      $filters = get_object_taxonomies( $typenow );

        foreach ( $filters as $tax_slug ) {
            $tax_obj = get_taxonomy( $tax_slug );
            wp_dropdown_categories( array(
                'show_option_all' => __('Show All '.$tax_obj->label ),
                'taxonomy'    => $tax_slug,
                'name'      => $tax_obj->name,
                'orderby'     => 'name',
                'selected'    => $_GET[$tax_slug],
                'hierarchical'    => $tax_obj->hierarchical,
                'show_count'    => false,
                'hide_empty'    => true
            ) );
        }
    }
}

add_action( 'restrict_manage_posts', 'taxonomy_filter_restrict_manage_posts' );


function taxonomy_filter_post_type_request( $query ) {
  global $pagenow, $typenow;

  if ( 'edit.php' == $pagenow ) {
    $filters = get_object_taxonomies( $typenow );
    foreach ( $filters as $tax_slug ) {
      $var = &$query->query_vars[$tax_slug];
      if ( isset( $var ) ) {
        $term = get_term_by( 'id', $var, $tax_slug );
        $var = $term->slug;
      }
    }
  }
}

add_filter( 'parse_query', 'taxonomy_filter_post_type_request' );
*/

/*
add_action( 'init', 'codex_custom_init' );
function codex_custom_init() {
  $labels = array(
    'name' => _x('Books', 'post type general name'),
    'singular_name' => _x('Book', 'post type singular name'),
    'add_new' => _x('Add New', 'book'),
    'add_new_item' => __('Add New Book'),
    'edit_item' => __('Edit Book'),
    'new_item' => __('New Book'),
    'all_items' => __('All Books'),
    'view_item' => __('View Book'),
    'search_items' => __('Search Books'),
    'not_found' =>  __('No books found'),
    'not_found_in_trash' => __('No books found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'Books'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments','custom-fields'),
    'taxonomies' => array('category', 'post_tag')

  ); 
  register_post_type('book',$args);
  $post = array(
    'comment_status' => 'closed',
    'post_author' => 1,
    'post_date' => date('Y-m-d H:i:s'),
    'post_content' => 'Hello Darling',
    'post_status' => 'publish', 
    'post_title' => "wp_insert", 
    'post_type' => 'book' // custom type
); 

wp_insert_post($post);
}



add_action('init', 'demo_add_default_boxes');
function demo_add_default_boxes() {
    register_taxonomy_for_object_type('category', 'demo');
    register_taxonomy_for_object_type('post_tag', 'demo');
}

*/
function khmer_date($string,$tab=false){

  $tmp_date =trim(get_between(strip_tags($string),'on','in'));
  if($tab){$tmp_date=$string;}
  $T_tmp = explode(',',$tmp_date);
  $year = trim($T_tmp[1]);
  $month_day =explode(' ',$T_tmp[0]);
  $month = strtolower(substr($month_day[0],0,3));
  $day = $month_day[1];
  $kh_date = khmer_number ($day.' '.khmer_month($month).' '.$year);

return str_replace(array($tmp_date,'in'),array($kh_date,''),$string);
}

function khmer_month($mon_en){
  $mon_en = substr($mon_en, 0,3);
  $array_month = array('jan' => 'មករា',
            'feb' => 'គុម្ភះ',
            'mar' => 'មិនា',
            'apr' => 'មេសា',
            'may' => 'ឩ​​សភា',
            'jun' => 'មិថុនា',
            'jul' => 'កក្កដា',
            'aug' => 'សីហា',
            'sep' => 'កញ្ញា',
            'oct' => 'តុលា',
            'nov' => 'វិច្ឆិការ',
            'dec' => 'ធ្នូ');

  return $array_month[strtolower($mon_en)];
} 
  



function khmer_number($str){
  return str_replace  ( array('0','1','2','3','4','5','6','7','8','9'),
              array('០','១','២','៣','៤','៥','៦','៧','៨','៩'),
              $str
            );
}


function get_between($input, $start, $end) 
{ 
  $substr = substr($input, strlen($start)+strpos($input, $start), (strlen($input) - strpos($input, $end))*(-1)); 
  return $substr; 
}

/**
 * Overwrited author post link
 */
function author_posts_link_gplus(){
    $defaults = array(
      'before' => '',
      'after' => ''
    );
    $atts = shortcode_atts( $defaults, $atts );
    ob_start();
    the_author_posts_link();
    $author = ob_get_clean();
    $google_plus = get_user_meta(get_the_author_meta('ID'),'google_profile',true);
    if (!empty($google_plus))
        $author = "<a rel='author' href=".esc_url($google_plus).">".get_the_author()."</a>";
    return sprintf('<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $author, $atts['before'], $atts['after']);
}
add_filter('woo_shortcode_post_author_posts_link','author_posts_link_gplus',998);


#debug function in wordpress
if(!function_exists('_log')){
  function _log( $message ) {
    if( SOS_DEBUG === true ){
      $bt = debug_backtrace();
      $caller = array_shift($bt);
      if( is_array( $message ) || is_object( $message ) ){
        file_put_contents('/tmp/sos_log.txt',"\n".date('d/m/Y').' '.$caller['file'].' ['.$caller['line']."]: \n".print_r( $message, true ),FILE_APPEND);
      } else {
        file_put_contents('/tmp/sos_log.txt',"\n".date('d/m/Y').' '.$caller['file'].' ['.$caller['line']."]: \n".$message,FILE_APPEND);
      }
    }
  }
}
?>