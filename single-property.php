<?php
/* single property view template */
get_header(); ?>
<!-- single-property.php -->
  <div id="page-wrap">
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
        
        <?php if ( have_posts() ) : ?>
          
          <?php while ( have_posts() ) : the_post(); 
            $custom = get_post_custom( get_the_ID() );
            // $address = $custom['address'][0]; 
            // $lat = $custom['lat'][0]; 
            // $lng = $custom['lng'][0]; 
            $property = Property::loadPropertyArray( $post->ID );
            // ThemeUtil::mpuke( $property );
            ?>
            
            <div class="single-property-image">
              <?php Property::singleViewImages( $post->ID, 'main-size' ); ?>
            </div>
            
            <h2 class="property-title <?php if (postHasImages($post->ID)) echo " has-slider"; ?>"><?php the_title(); ?></h2>
            <!-- <div class="property-meta">
              <span class="category">Category</span> | <span class="date">Date</span> | <span class="time">Time</span>
            </div> -->
            
            <div class="single-property-content">
              <?php 
                $content = trim(get_the_content());
                if( ""==$content ){
                  echo "No property description is available.";
                } 
                else{
                  echo $content;
                }
                // ThemeUtil::mpuke( $custom );
              ?>
            </div>
            
            <?php edit_post_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>' ); ?>
            
            <?php // ThemeUtil::mpuke($custom); ?>    
            
          <?php endwhile; ?>
          
        <?php endif; ?>

      </div><!-- #content -->
		</div><!-- #primary -->
      
    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); 

?>