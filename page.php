<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>
<!-- page.php  -->
  <div id="page-wrap">

		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php // comments_template( '', true ); ?>

<?php if (is_page(5833)) { ?>
  
				<div id="masonry-container">
                
				<?php
				
          // ThemeUtil::mpuke( $category );
				  $cat_id = 130;
				  
          // $args = array(
          //            'post_type'=>'post',
          //            'orderby'=> 'ID',
          //            'order'=> 'DESC',
          //            'category' => $cat_id->term_id
          //          );
          //          
          //          $this_cat_posts = get_posts($args);
          
          
          $loop = new WP_Query( 
            array(
              'post_type' => 'post',
              'posts_per_page' => 2,
              'orderby'=> 'date',
              'order'=> 'DESC',
              'cat' => 130,
              'paged'=>$paged
            ) 
          );
          
          // if(!$paged):
  				  $cnt=0;
  				  if ( $loop->have_posts() ) :
  				    while ( $loop->have_posts() ) : $loop->the_post();
  				      $tcp = get_post(get_the_ID());
  				  // foreach( $this_cat_posts as $k=>$tcp ){ 
  				    ?>
				    
  				    <div class="masonry-box <?php if ($cnt%2==0){ echo " add-rm "; }?>">
  				      <h2 class="avenir <?php if (postHasImages($tcp->ID)) echo " has-slider"; ?>">
                  <a href="<?php echo get_permalink($tcp->ID); ?>"><?php echo $tcp->post_title; ?></a>
                </h2>
                <div style="clear:both"></div>
                <div class="masonry-date">
                  <?php chp_posted_on(); ?>
                </div>
                <div style="clear:both"></div>
                  <?php
                  $attachments = get_children( 
                		array(
                			'post_parent' 		=> $tcp->ID, 
                			'post_status' 		=> 'inherit', 
                			'post_type' 		  => 'attachment', 
                			'post_mime_type'	=> 'image', 
                			'order' 		  	  => 'ASC', 
                			'orderby' 			  => 'menu_order ID',
                			'numberposts'     => 1
                		) 
                	);

                	$size = 'masonry-size';
                	if( count( $attachments ) ): ?>
                    <?php foreach( $attachments as $att ):
              		    $src = wp_get_attachment_image_src( $att->ID );
              		    $img_args['id'] = $att->ID;
              			  $img = wp_get_attachment_image( $att->ID, $size, false, $img_args );
              		    $img_src = wp_get_attachment_image_src( $att->ID, $size ); ?>

              		    <a href="<?php echo get_permalink($tcp->ID); ?>"><img 
              		      src="<?php echo $img_src[0]; ?>" class="masonry-img" title="<?php echo $att->post_title; ?>"
              		      id="masonry-img-<?php echo $att->ID; ?>" /></a>

              		  <?php endforeach; ?>

              	  <?php endif; ?>
				      
  				      <?php 
						$excerpt = strip_tags($excerpt,'<a><b><i><em><strong>');
  				        $excerpt = trim(chp_excerpt( $tcp->post_content, '<div class="more-right">more</div>', get_permalink( $tcp->ID), 30 )); 
  				        if(''!=$excerpt){
  				          echo $excerpt;
  				        }
  				        else{
  				          'No description is available.';
  				        }
  				      ?>
  				      <div style="clear:both"></div>
  				    </div>

  				    <?php
  				    $cnt++;
  				    endwhile;
  				  endif;
  				// endif;
				?>
        </div>
         <?php rewind_posts(); ?> 
        <a href="http://homeportohio.org/category/donornews/"><b>View all Donor News Posts.</b></a><br /><br />
        
        <p><b>Join the Legacy Society:</b> Learn more about the opportunities and benefits of making a positive impact on people needing affordable housing, forever, with a planned gift through your estate plan, please contact Peter Tripp, Manager of Donor/Investor Relations at 614-545-4853 or <a href="mailto:peter.tripp@homeportohio.org">peter.tripp@homeportohio.org</a>.</p>
        </div><!-- #content -->
		</div><!-- #primary -->
        
           <?php 
      $post = $old_post;
      get_sidebar(); 
    ?>
        
<?php } else { ?>


			</div><!-- #content -->
		</div><!-- #primary -->
      
    <?php get_sidebar(); ?>
    
    <?php } ?>




    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>