<?php
/**
 * Archives widget class
 *
 * @since 2.8.0
 */
class SoS_Widget_Archives extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_archive', 'description' => __( 'A monthly archive of your site&#8217;s posts') );
		parent::__construct('archives', __('Archives'), $widget_ops);
	}

	function widget( $args, $instance ) {

		global $wpdb, $wp_locale;
		extract($args);

		$defaults = array(
			'type'            => 'monthly',
			'show_post_count' => 1,
		);

		$r = wp_parse_args( $defaults);
		extract( $r, EXTR_SKIP );


		$title = apply_filters('widget_title', empty($instance['title']) ? __('Archives') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		
		//filters
		$where = apply_filters('getarchives_where', "WHERE post_type = 'post' AND post_status = 'publish'", $r );
		$join = apply_filters('getarchives_join', "", $r);
		if ( 'monthly' == $type ) {
			$query         = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC $limit";
			$key           = md5($query);
			$cache         = wp_cache_get( 'wp_get_archives' , 'general');
			if ( !isset( $cache[ $key ] ) ) {
				$arcresults    = $wpdb->get_results($query);
				$cache[ $key ] = $arcresults;
				wp_cache_set( 'wp_get_archives', $cache, 'general' );
			} else {
				$arcresults    = $cache[ $key ];
			}
			if ( $arcresults ) {
				$afterafter    = $after;
				foreach ( (array) $arcresults as $arcresult ) {
					
					$url    = get_month_link( $arcresult->year, $arcresult->month );
					$text   = sprintf(__('%1$s %2$s'), khmer_month($wp_locale->get_month($arcresult->month)), khmer_number($arcresult->year));
					$text   .= '&nbsp;('.khmer_number($arcresult->posts).')' . $afterafter;
					$output .= get_archives_link($url, $text, $format, '<li>', '</li>');
				}
			}
		}
			
		echo '<ul>' . $output . '</ul>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '',) );
		$title = strip_tags($instance['title']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
<?php
	}
}


function sos_widget_init() {
	// unregister some default widgets
	unregister_widget('WP_Widget_Archives');
 
	// register my own widgets
	register_widget('SoS_Widget_Archives');
}
add_action('widgets_init', 'sos_widget_init');
?>
