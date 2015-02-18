<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<!-- category.php -->
		<div id="page-wrap">
  		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
  			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
  			  <div class="content">
  			  <h1 class="entry-title" style="margin-top: 50px;"><?php echo single_cat_title( '', false ); ?></h1>
          <div id="masonry-container">
          <?php if ( have_posts() ) : $cnt=0; while ( have_posts() ) : the_post(); $tcp = get_post(get_the_ID()); ?>
              
              <div class="masonry-box <?php if ($cnt%2==0){ echo " add-rm "; }?>">
  				      <h2 class="avenir <?php if (postHasImages($tcp->ID)) echo " has-slider"; ?>">
                  <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
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
            <?php $cnt++; endwhile; ?>
          <?php endif; ?>
			    </div>
			    </div>
                
          <div class="nagivation">
            <?php if(function_exists('wp_pagenavi')) { wp_pagenavi( ); } ?>
          </div>    
                
			</div><!-- #content -->
		</div><!-- #primary -->
      
    <?php 
      $post = $old_post;
      get_sidebar(); 
    ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>