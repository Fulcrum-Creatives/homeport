<?php
/**
 * Template Name: Test Front Page
 */


get_header(); ?>
  <div id="page-wrap">
    <!-- <textarea style="width:100%;height:200px"> -->
      <?php // ThemeUtil::mpuke ($current_user); ?>
    <!-- </textarea> -->
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">

        <?php ThemeTheme::front_page_slider(); ?>
        
        <div id="home-page-boxarea">
          <div class="boxarea">
            <div class="fp-header rental-header">
              <p><a href="/rent">RENTAL LIVING</a></p>
            </div>
            <a href="/rent"><img class="boxarea-img" src="<?php bloginfo('stylesheet_directory');?>/images/home-rent.jpg"  alt="Rental Living"/></a>
          </div>
          <div class="boxarea">
            <div class="fp-header learn-header">
              <p><a href="/learn">ADVISORY CENTER</a></p>
            </div>
            <a href="/learn"><img class="boxarea-img" src="<?php bloginfo('stylesheet_directory');?>/images/home-learn.jpg"  alt="Advisory Center"/></a>
          </div>
          <div class="boxarea">
            <div class="fp-header live-header">
              <p><a href="/live">COMMUNITY LIFE</a></p>
            </div>
            <a href="/live"><img class="boxarea-img" src="<?php bloginfo('stylesheet_directory');?>/images/home-live.jpg"  alt="Comunity Life"/></a>
          </div>
          <div class="boxarea last">
            <div class="fp-header buy-header">
              <p><a href="/buy">HOME OWNERSHIP</a></p>
            </div>
            <a href="/buy"><img class="boxarea-img" src="<?php bloginfo('stylesheet_directory');?>/images/home-buy.jpg"  alt="Home Ownership"/></a>
          </div>
        </div>
        
        <div id="home-page-copy">
          <?php echo get_option( THEME_NICE_NAME . "_home_page_copy", 'Please add home page copy in the admin.' ); ?>
        </div>
        <div id="home-page-logos">
       
          <p><?php echo get_option( THEME_NICE_NAME . "_home_logo_text", 'Our Sponsors');?></p>
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
           <div id="home-page-twitter">
           <?php if ( ! dynamic_sidebar( 'home-twitter' ) ) : ?>
  			<?php endif; ?>
           </div>
           <div id="twitter-link">
           <a href="https://twitter.com/#!/HomeportOH" target="_blank" >All Tweets</a>
           </div>
            
        
			</div><!-- #content -->
		</div><!-- #primary -->
  
    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>