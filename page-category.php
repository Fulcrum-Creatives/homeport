<?php
/**
 * Template Name: Category Listing Template
 * Description: A Page Template that adds a sidebar to pages
 */

get_header(); 
global $post;
$old_post = $post;
?>
<!-- page-category.php  -->
		<div id="page-wrap">
  		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
  			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
          <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
              <?php
                $category = $post->post_name;
                // ThemeUtil::mpuke($category);
              ?>
            <?php endwhile; ?>
          <?php endif; ?>
          
            <!-- <textarea style="width:100%;height:280px"> -->
            <?php 
            
              $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
              
              if( !((int)$paged > 1) ):
                $found_posts = 0;
                $post_cats = array();
            
                $args = array(
              		'post_type'=>'post',
              		'meta_key'=>'featured_post',
              		'meta_value'=>'1',
              		'orderby'=> 'ID',
              		'order'=> 'DESC'
              	);
              	$saved_features = get_posts($args);
          	  
            	  // ThemeUtil::mpuke($saved_features);
          	  
                // ob_start();
                foreach( $saved_features as $feature ){
                  $post_cats = array();
                  
                  $post_categories = wp_get_post_categories( $feature->ID );
                  
                  foreach( $post_categories as $c ){
                    $cat = get_category( $c );
                    $post_cats[] = $cat->slug;
                  }
                  // ThemeUtil::mpuke($category);
                  // ThemeUtil::mpuke($post_cats);
                  print '<!--post_cats: ' . print_r($post_cats, 1) . '-->';
                  
                  if( !in_array($category, $post_cats) || ""==$category ) continue;
              
                  if( $attachments = get_children( 
              			array(
              				'post_parent' 		=> $feature->ID, 
              				'post_status' 		=> 'inherit', 
              				'post_type' 		  => 'attachment', 
              				'post_mime_type'	=> 'image', 
              				'order' 			    => 'ASC', 
              				'orderby' 			  => 'menu_order ID',
              				'numberposts' 		=> 1
              			) 
              		) ){
              		  $post_thumbnail_id = get_post_thumbnail_id( $feature->ID );
              		  $size = "main-size";
              		  ?>
            		  
                    <?php
              		  // ThemeUtil::mpuke($attachments);
            		  
                    foreach( $attachments as $k=>$att ){
              		    if( $post_thumbnail_id== $att->ID) continue; // skip featured/thumbnail added images
                		  // $custom = get_post_custom( $att->ID );
                		  $src = wp_get_attachment_image_src( $att->ID, $size, false );
                    
                      // ThemeUtil::mpuke($src);
                    
              			  $imgsrc = $src[0];
              				$width = $src[1];
              				if($width<530) continue;
            				
              				$height = $src[2];
                      $name = stripslashes($att->post_name);
                      // $caption = '<a href="' . get_permalink($feature->ID) . '">' . stripslashes($att->post_title) . '</a>';
                      $caption = stripslashes($att->post_title);
                      if( !empty($caption_prefix) ) $caption_prefix .= ": ";
            	    
              		    $the_images .= '<a href="' . get_permalink($feature->ID) . '"><img title="' . $caption_prefix . '' . $caption . '" class="single-property-view" src="' . $imgsrc . '" style="width:' . $width . 'px;height:' . $height . '" border="0" /></a>';
                      $found_posts++;
              	    } ?>
            	    
            		  <?php }
            		  else{
                    // ThemeUtil::mpuke('no attachments');
            		  }
            		
                }

                if($found_posts): ?>
                <div id="slider-wrapper">
                  <div id="slider" class="nivoSlider">
                    <?php echo $the_images; ?>
                  </div>		  
                </div>    
                <?php endif; ?>
          
		
		  <?php
		    endif;
		  // hide title/content only on specific pages
		  $id = get_the_ID();
		  $suppress_content_ids = array(
		  );
		  if(!in_array($id,$suppress_content_ids)) {
		  ?>
			<div class='content'>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<div class='entry-content'><?php the_content(); ?></div>
			</div>
		<?php } ?>
        
        <h1 class="cat-title">
        <?php // Name Replacement for some categories

if ( is_page( 'buy' ) ) {    
    
   echo  'Homeport Home Ownership News';

} elseif ( is_page( 'learn' ) ) {	
    echo 'Homeport Housing Advisory Center News';

} elseif ( is_page( 'live' ) ) { 
   echo 'Homeport Community Life Programs News';

} elseif ( is_page( 'get-involved' ) ) { 
   echo 'Homeport Volunteering News';

} elseif ( is_page( 'level-with-us' ) ) { 
  echo  'Homeport Testimonials';

} elseif ( is_page( 'careers' ) ) { 
  echo  'Available Jobs';


} else { 
  echo 'Homeport ';
  echo the_title(); 
  echo ' News' ;
}	

?></h1>
        
        
				<div id="masonry-container">
                
				<?php
				
          // ThemeUtil::mpuke( $category );
				  $cat_id = get_category_by_slug( $category );
				  
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
              'posts_per_page' => 8,
              'orderby'=> 'date',
              'order'=> 'DESC',
              'cat' => $cat_id->term_id,
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
        
      
        <div class="nagivation">
          <?php if(function_exists('wp_pagenavi')) { wp_pagenavi( array( 'query' => $loop ) ); } ?>
        </div>
        <?php edit_post_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>', $old_post->ID ); ?>
        
				<?php // comments_template( '', true ); ?>
                
                
               

			</div><!-- #content -->
		</div><!-- #primary -->
      
    <?php 
      $post = $old_post;
      get_sidebar(); 
    ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>