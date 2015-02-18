<?php
/* single property view template */
get_header(); ?>
<!-- single-developments.php -->
  <div id="page-wrap">
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
        
        <?php if ( have_posts() ) : ?>
          
          <?php while ( have_posts() ) : the_post(); 
            
            ?>
            
            <div class="single-development-image">
              <?php Development::sliderFromPropertyImages( $post->ID, 'main-size' ); ?>
            </div>
            
            
            <h2 class="property-title"><?php the_title(); ?></h2>
            
            <div class="single-development-content">
              <?php 
                $content = trim(get_the_content());
                if( ""==$content ){
                  echo "No development description is available.";
                } 
                else{
                  echo $content;
                }
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
    
    <?php
    
      $args = array(
    		'post_type'=>'property',
    		'meta_key'=>'development',
    		'meta_value'=>$post->post_title,
    		'orderby'=> 'ID',
    		'order'=> 'DESC',
    		'numberposts' => -1
    	);

    	$properties_for = get_posts($args); 
    	if( count( $properties_for ) ):
      ?>
      <div class="single-development-subcontent">
      <div id="propertySearchScroll">
      <?php
      shuffle($properties_for);
      
      foreach( $properties_for as $i=>$prop ): 
        // print '<!-- propid: ' . $prop->ID . ' -->';
        $prop = Property::loadPropertyArray($prop->ID);
        ?>
        <!--
        <?php print_r( $prop ); ?>
        -->
        <div class="property-listing">
          <div class="property-body">
            <div class="property-details">
              <div class="property-title"><a href="<?php echo $prop['permalink']; ?>" class="property-name" rel="<?php echo $i; ?>" id="property-<?php echo $i; ?>"><?php echo $prop['name']; ?></a></div>
              <div class="property-address"><?php echo $prop['address']; ?></div>
              <div class="property-beds">Bedrooms: <?php echo $prop['num_beds']; ?></div>
              <div class="property-baths">Baths: <?php echo $prop['num_baths']; ?></div>
              <div class="property-more"><a href="<?php echo $prop['permalink']; ?>">More Info &raquo;</a></div>
              
              </div>
              <div class="property-desc">
                <div class="property-excerpt">
                <?php if (!empty($prop['price']) && is_numeric($prop['price'])): ?> 
                  <?php echo '$' . $prop['price'] . '.00'; // money_format('%.2n', $prop['price']); ?>
                <?php else: ?>
                  Please Call
                <?php endif; ?>
                
                <?php /* ?>
                (_e(v.price) ? '<div class="property-excerpt">' + formatPrice(v.price, v.property_type) + '</div>' : 'Price currently unavailable. Please call.' ) + '<br/>' +
                (_e(v.excerpt) ? '<div class="property-excerpt">' + v.excerpt + '</div>' : 'No description available.' ) +
                <?php */ ?>
              </div>
            </div>
            
            
              <?php if (!empty($prop['img_src'])): ?> 
                <div class="property-img"><a href="<?php echo $prop['permalink']; ?>">
                  <img src="<?php echo $prop['img_src']; ?>" />
                </a></div>
              <?php else: ?>
                <!--img src="/wp-content/themes/chp/images/no-image.scroll.gif" /-->
                
              <?php endif; ?>
            <!-- </a></div> -->
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        
      <?php endforeach;
    ?>
    </div>
    </div>
    <?php endif; ?>
  </div><!-- #page-wrap -->
<?php get_footer(); 

?>