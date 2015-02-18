<?php

add_action( 'widgets_init', 'news_widget' );

function news_widget() {
	register_widget( 'News_Widget' );
}
class News_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'news_widget', // Base ID
			'News', // Name
			array( 'description' => __( 'News Widget', DOMAIN ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		// This is the outputted HTML and PHP
		echo $before_widget;
		?>
<div id="fp-news-listing" class="sidebar-listings">
    <h4 class="sidebar-header"><a href="/news/"><?php if ( ! empty( $title ) ) echo $title; ?></a><div class="sidebar-header-arrow"></div></h4>
  	<?php
  	  // double using posts as also events, so I had to filter out those marked as such
  		$news = ThemeAdmin::fetch_nonevent_posts(3);
      // ThemeUtil::mpuke_ta( $news );
      
  		foreach($news as $item) {
  		  $content = "";
  		  $d = date("F j, Y", strtotime($item->post_date));
  			if($item->post_excerpt) {
  				$content = '<span class="sidebar-meta-date">' . $d . '</span><p class="sidebar-content">' . chp_excerpt($item->post_excerpt, '', get_permalink($item->ID), 10) . '</p>';
  			}
  			else {
  				// $content = "<span class="sidebar-meta-date">" . $d . "</span></br>" . chp_excerpt($item->post_content, '', get_permalink($item->ID), 10);
  				$content = '<span class="sidebar-meta-date">' . $d . '</span><p class="sidebar-content">' . $item->post_title . '</p>';
  			}
  			$link = get_permalink( $item->ID );
  			echo "<div class='fp-news sidebar-listings-content'><a href='{$link}'>{$content}</a>";
  			echo '<div style="clear:both"></div>';
  			echo "</div>";
  		}
  	?>
     <a href="/news/" class="sidebar-link">More News</a>
  </div>
		<?php
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// This is for custom field for the widget
		// 
		// check is field is empty. This is needed for every field
		if( $instance) {
		     $title = esc_attr($instance['title']);
		} else {
		     $title = '';
		}
		// output the html for the custom fields
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget
?>