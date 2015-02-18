<?php

add_action( 'widgets_init', 'events_widget' );

function events_widget() {
	register_widget( 'Events_Widget' );
}
class Events_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'events_widget', // Base ID
			'Events Posts', // Name
			array( 'description' => __( 'Events Widget', DOMAIN ), ) // Args
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
  <div id="fp-event-listing" class="sidebar-listings">
    <h4 class="sidebar-header"><a href="/events/"><?php if ( ! empty( $title ) ) echo $title; ?></a><div class="sidebar-header-arrow"></div></h4>   
    <?php 
      $upcoming = ThemeAdmin::fetch_home_event_posts(2,30,1,'acs');
      if( count($upcoming) ){
        foreach( $upcoming as $event ):
         $content = "";
		 if( $event->custom['is_event_multiday'][0] ) {
			$end_date = $event->custom['event_date_to'][0];
			$end_date_time = strtotime($end_date);
			$end_date_day = date("j",$end_date_time);
			$end_date_month = date("F",$end_date_time);
			$end_date_year = date("Y",$end_date_time);
			$start_date = $event->custom['event_date'][0];
			$start_date_time = strtotime($start_date);
			$start_date_day = date("j",$start_date_time);
			$start_date_month = date("F",$start_date_time);
			$start_date_year = date("Y",$start_date_time);
			
			if($end_date_year == $start_date_year) {
				if($end_date_month == $start_date_month) {
					$d = $start_date_month . ' ' . $start_date_day . "-" . $end_date_day . ', ' . $start_date_year;
				}
				else {
					$d = $start_date_month . ' ' . $start_date_day . " to " . $end_date_month . ' ' . $end_date_day . ', ' . $start_date_year;
				}
			}
			else {
				$d = $start_date_month . ' ' . $start_date_day . ", " . $start_date_year . ' to ' . $end_date_month . ' ' . $end_date_day . ', ' . $end_date_year;
			}
		 }
		 else {
			$d = $event->custom['chp_month_name'][0] . ' ' . $event->custom['chp_day_num'][0] . ", " . $event->custom['chp_year'][0];
          }
          if($event->post_excerpt){
    				$content = '<span class="sidebar-meta-date">Event Date: ' . $d . '</span><p class="sidebar-content">' . chp_excerpt($event->post_excerpt,'', get_permalink($event->ID), 15) . '</p>';
    			}
    			else{
    			  $content = '<span class="sidebar-meta-date">Event Date: ' . $d . '</span><p class="sidebar-content">' .$event->post_title . '</p>';
    			}
    	?>
          <div class="fp-event sidebar-listings-content">
            <a href="<?php echo get_permalink( $event->ID ); ?>"><?php echo $content?></a>
            <div style="clear:both"></div>
          </div>
          <div style="clear:both"></div>
        <?php endforeach;
      }
      else {?>
        <div class="fp-event sidebar-listings-content">
          There are currently no events scheduled.
          <div style="clear:both"></div>
        </div>
        <div style="clear:both"></div>
      <?php }
      
    ?>
   <a href="/events/" class="sidebar-link">More Events</a>
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