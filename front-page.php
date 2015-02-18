<?php
/**
 * Template Name: Front Page
 */


get_header(); ?>
<!-- front-page.php -->
  <div id="page-wrap">
    <!-- <textarea style="width:100%;height:200px"> -->
      <?php // ThemeUtil::mpuke ($current_user); ?>
    <!-- </textarea> -->
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">

			   <div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">

            <?php ThemeTheme::front_page_slider(); ?>

            <div class="fp-left-col">

              <!--  <div class="homeport-gallery">
                    <h3 class="fp-entry-headding">Homeport Gallery</h3>
                    <?php
                    $gallery_query = new WP_Query(array(
                        'post_type'         =>'gallery',
                        'posts_per_page'    => '1'
                    ));
                    if (have_posts()) : while ($gallery_query->have_posts()) : $gallery_query->the_post();
                    $attachment_id = get_field('hp_gallery_image');
                    $size = "single-post"; // (thumbnail, medium, large, full or custom size)
                    $image = wp_get_attachment_image_src( $attachment_id, $size );
                    ?>
                    <div class="featured-gallery">
                        <a href="<?php home_url(); ?>/get-involved/homeport-gallery/">
                            <img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" class="featured-gallery-image">
                            <div class="featured-gallery-caption">Featured Artist: <?php echo get_field('hp_featured_artist');?></div>
                        </a>
                    </div>
                    <?php endwhile; endif; wp_reset_query(); ?>
                </div> -->

                <div id="home-page-twitter">
                    <?php if ( ! dynamic_sidebar( 'home-twitter' ) ) : ?>
                    <?php endif; ?>
                </div>

            </div>
            <div class="fp-right-col">
                <h3 class="fp-entry-headding">Featured Properties</h3>
                
                    <?php
                    $post_counter = 0;
                    $featured_query = new WP_Query(array(
                        'post_type'         =>'property',
                        'posts_per_page'    => '3',
                        'category_name'     => 'featured'

                    ));
                    if (have_posts()) : while ($featured_query->have_posts()) : $featured_query->the_post();
                    $prop = Property::loadPropertyArray($prop->ID);
                    $post_counter++;
                    $title = get_the_title();
                    ?>
                    <div class="fp-featured-properties cf post-<?php echo $post_counter; ?>">
                        <div class="featured-property-image">
                            <?php if (!empty($prop['img_src'])) : ?> 
                                <div class="property-img">
                                    <a href="<?php echo $prop['permalink']; ?>">
                                        <img src="<?php echo $prop['img_src']; ?>" />
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="featured-property-info">
                            <div class="fp-property-title">
                                <?php echo $title; ?>
                            </div>
                            <div class="property-address">
                                <?php 
                                if( strpos($prop['address'],',') !== false) :
                                    echo str_replace( $title . ',', '', $prop['address']); 
                                else :
                                    echo str_replace( $title, '', $prop['address']);
                                endif;
                                ?>
                            </div>
                            <div class="property-beds">
                                Bedrooms: <?php echo $prop['num_beds']; ?>
                            </div>
                            <div class="property-baths">
                                Baths: <?php echo $prop['num_baths']; ?>
                            </div>
                            <?php if (!empty($prop['price']) && is_numeric($prop['price'])): ?> 
                              <?php echo '$' . $prop['price'] . '.00'; ?>
                            <?php else: ?>
                              Please Call
                            <?php endif; ?>
                            <div class="property-more">
                                <a href="<?php echo $prop['permalink']; ?>">More Info</a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; endif; wp_reset_query(); ?>
            </div>
            
            <div id="home-page-logos">

                <p class="sponsors-heading"><?php echo get_option( THEME_NICE_NAME . "_home_logo_text", 'Our Sponsors');?></p>

                <ul id="mycarousel" class="jcarousel-skin-tango">
                    <?php 
                    $_request = array(
                    'post_id' => 0,
                    'metakey' => 'logo',
                    'size'	=> 'home-logo'
                    );
                    $logos = ThemeAdmin::post_imgs_callback_by_meta( 30, $_request );
                    // ThemeUtil::mpuke( $logos );
                    shuffle($logos['imgs']);
                    $ct = 0;
                    if( count($logos['imgs']) > 0 ){
                    foreach( $logos['imgs'] as $logo ){
                    // if($ct++ > 4) break;
                    // ThemeUtil::mpuke( $logo );

                    $imgsrc = $logo['data'][0];
                    $width = $logo['data'][1];
                    $height = $logo['data'][2];
                    $caption = $logo['caption'];

                    ?>
                    <li>
                    <!--<a href="/partners">--><img title="Homeport Sponsor" src="<?php echo $imgsrc; ?>" /><!--</a>-->
                    </li>
                    <?php }
                    }
                    else{ ?>
                    <!-- <div class="home-logo"> -->
                    <img src="http://placehold.it/500x40&text=Please+upload+logos+using+admin+interface" />
                    <!-- </div> -->
                    <?php } ?>
                </ul>

            </div>

            

        </div><!-- #content -->

		</div><!-- #primary -->
  
    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>