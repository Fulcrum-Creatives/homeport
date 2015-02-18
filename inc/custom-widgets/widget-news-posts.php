<?php

add_action( 'widgets_init', 'news_posts_widget' );

function news_posts_widget() {
	register_widget( 'News_posts_Widget' );
}
class News_posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'news_posts_widget', // Base ID
			'News Posts Widget', // Name
			array( 'description' => __( 'News Posts Widget', DOMAIN ), ) // Args
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
        $category = $instance['category'];
        $post_amount = $instance['post_amount'];
        $more_link = $instance['more_link'];
		// This is the outputted HTML and PHP
		echo $before_widget;
		?>
<div id="news-post-listing" class="sidebar-listings">
    <h4 class="sidebar-header">
        <a href="/news/">
            <?php if ( ! empty( $title ) ) echo $title; ?>
        </a><div class="sidebar-header-arrow"></div>
    </h4>
    <?php
    $query = new WP_Query(array(
        'post_type'         => 'post',
        'cat'               => $category,
        'posts_per_page'    => $post_amount
    ));
    while ($query->have_posts()) : $query->the_post();
    ?>
    <div class='fp-news sidebar-listings-content'>
        <a href="<?php the_permalink(); ?>">
            <?php if(has_excerpt( $post->ID )) : ?>
                    <span class="sidebar-meta-date"><?php echo get_the_date('', $post->ID); ?></span>
                    <p class="sidebar-content"><?php chp_excerpt($item->post_excerpt, '', get_permalink($item->ID), 10); ?></p>
                <?php else : ?>
                    <span class="sidebar-meta-date"><?php echo get_the_date('', $post->ID); ?></span>
                    <p class="sidebar-content"><?php the_title(); ?></p>
            <?php endif; ?>
        </a>
    </div>
    <?php endwhile; ?>

     <a href="<?php echo get_category_link( $category ); ?>" class="sidebar-link"><?php echo $more_link; ?></a>
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
        $instance['post_amount'] = strip_tags( $new_instance['post_amount'] );
        $instance['category'] = strip_tags( $new_instance['category'] );
        $instance['more_link'] = strip_tags( $new_instance['more_link'] );
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
             $post_amount = esc_attr($instance['post_amount']);
             $category = esc_attr($instance['category']);
             $more_link = esc_attr($instance['more_link']);
		} else {
		     $title = '';
             $post_amount = '';
             $category = '';
             $more_link = '';
		}
		// output the html for the custom fields
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <p>
        <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category ID:' ); ?></label> 
        <input size="4" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" type="text" value="<?php echo esc_attr( $category ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'post_amount' ); ?>"><?php _e( 'Show Posts Amount:' ); ?></label> 
        <input size="4" id="<?php echo $this->get_field_id( 'post_amount' ); ?>" name="<?php echo $this->get_field_name( 'post_amount' ); ?>" type="text" value="<?php echo esc_attr( $post_amount ); ?>" />
        </p>
        <label for="<?php echo $this->get_field_id( 'more_link' ); ?>"><?php _e( 'Link Text' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'more_link' ); ?>" name="<?php echo $this->get_field_name( 'more_link' ); ?>" type="text" value="<?php echo esc_attr( $more_link ); ?>" />
        </p>
		<?php 
	}

} // class Foo_Widget
?>