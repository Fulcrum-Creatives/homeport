<?php

function chp_sc_event_list($atts = null, $inner_content = null) {
  global $wpdb;
  
	extract( shortcode_atts( array(
		'limit' => 6
	), $atts ) );
	
  $args = array(
   'post_type'=>'post',
   'meta_key'=>'is_event',
   'meta_value'=>'1',
   'orderby'=> 'ID',
   'order'=> 'DESC',
   'post_status' => 'publish'
  );
	
	$images="";
  $events = get_posts($args);
  
  // $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  // $loop = new WP_Query( 
  //   array(
  //     'post_type' => 'post',
  //     'posts_per_page' => 6,
  //     'orderby'=> 'ID',
  //     'order'=> 'DESC',
  //     'meta_key'=>'is_event',
  //     'meta_value'=>'1',
  //     'paged'=>$paged
  //   ) 
  // );
  
	foreach( $events as $event ):
	  if( $attachments = get_children( 
			array(
				'post_parent' 		=> $event->ID, 
				'post_status' 		=> 'inherit', 
				'post_type' 		  => 'attachment', 
				'post_mime_type'	=> 'image', 
				'order' 			    => 'ASC', 
				'orderby' 			  => 'menu_order ID',
				'numberposts' 		=> 1
			) 
		) ):
		  $post_thumbnail_id = get_post_thumbnail_id( $feature->ID );
		  $size = "main-size";
		  
		  foreach( $attachments as $k=>$att ):
		    if( $post_thumbnail_id== $att->ID) continue; // skip featured/thumbnail added images
  		  // $custom = get_post_custom( $att->ID );
  		  $src = wp_get_attachment_image_src( $att->ID, $size, false );
        
        // ThemeUtil::mpuke($src);
        
			  $imgsrc = $src[0];
				$width = $src[1];
				if($width<530) continue;
				
				$height = $src[2];
        $name = stripslashes($att->post_name);
        $caption = stripslashes($att->post_title);
        if( !empty($caption_prefix) ) $caption_prefix .= ": ";
	    
	      $the_images .= '<a href="' . get_permalink($event->ID) . '">';
		    $the_images .= '<img title="' . $caption_prefix . ' ' . $caption . '" class="single-property-view" src="' . $imgsrc . '" style="width:' . $width . 'px;height:' . $height . '" border="0" />';
		    $the_images .= "</a>";
        $found_posts++;
	    endforeach;
	    
		endif; ?>
  <?php endforeach;
	
	if(""!=$the_images): ?>
  <div id="slider-wrapper">
    <div id="slider" class="nivoSlider">
      <?php echo $the_images; ?>
    </div>		  
  </div>    
  <?php endif;
	
	$args = array(
		'post_type'=>'post',
		'meta_key'=>'is_event',
		'meta_value'=>'1',
		'orderby'=> 'ID',
		'order'=> 'DESC'
	);
	$events = get_posts($args);
	
	foreach( $events as $event ):
	  $event->custom = get_post_custom($event->ID);
	endforeach;
	
	usort( $events, "chp_event_sort" );
	
	ob_start(); ?>
  <div id="masonry-container">
    <?php 

      $cnt=0; foreach( $events as $event ): 
      
        // chp_month_num, chp_day_num, chp_year
        $this_year = date("Y");
        $this_month = date("n");
        $this_day = date("j");
      
        if( $event->custom['chp_year'][0] < $this_year):
          continue;
        endif;
        
        if( $event->custom['chp_year'][0] == $this_year && $event->custom['chp_month_num'][0] < $this_month ):
          continue;
        endif;
        
          if( $event->custom['chp_year'][0] == $this_year && $event->custom['chp_month_num'][0] == $this_month ):
            if( $event->custom['chp_day_num'][0] == $this_day ):
              continue;
            endif;
          endif
          
		 
		  
      ?>
      <!-- <?php // ThemeUtil::mpuke( $event ); ?> -->
      <div class="masonry-box <?php if ($cnt%2==0){ echo " add-rm "; }?>">
        <h2 class="avenir">
          <a href="<?php echo get_permalink($event->ID); ?>"><?php echo $event->post_title; ?></a>
       </h2>
        <!--small><pre><?php // ThemeUtil::mpuke( $event->custom ); ?></pre></small-->
        <div style="clear:both"></div>
        <div>
          <?php
          $attachments = get_children( 
        		array(
        			'post_parent' 		=> $event->ID, 
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

      		    <a href="<?php echo get_permalink($event->ID); ?>"><img 
      		      src="<?php echo $img_src[0]; ?>" class="masonry-img" title="<?php echo $att->post_title; ?>"
      		      id="masonry-img-<?php echo $att->ID; ?>" /></a>

      		  <?php endforeach; ?>
    	    
      	  <?php endif; ?>
      	    
        	  
        	  <?php
            $excerpt = trim(chp_excerpt( $event->post_content, '<div class="more-right">more</div>', get_permalink( $event->ID ), 20 )); 
  	        if(''!=$excerpt){
  	          echo $excerpt;
  	        }
  	        else{
  	          echo 'No description is available.';
  	        }
            ?>
            <div style="clear:both"></div>
        </div>
        <div style="clear:both"></div>
      </div>
      <?php $cnt++; endforeach;
    ?>
    <div style="clear:both"></div>
  </div>
  <div style="clear:both"></div>
  <?php
  $contents = ob_get_contents();
  ob_end_clean();
  return $contents;
}
add_shortcode( 'event_list', 'chp_sc_event_list' );

function chp_sc_rentals($atts = null, $inner_content = null) {
	extract( shortcode_atts( array(
		'limit' => 6
	), $atts ) );
	
	$return = "<div class='rentals'>";
	$limit = (int)$limit;
	$items = ThemeAdmin::fetch_rentals();
	
	foreach($items as $item) {
		if($item->post_excerpt) {
  				$content = chp_excerpt($item->post_excerpt, '...more', get_permalink($item->ID), 30);
  		}
  		else {
  			$content = chp_excerpt($item->post_content, '...more', get_permalink($item->ID), 30);
  		}
  		$link = get_permalink( $item->ID );
		
		ob_start();
		Property::singleViewFirstImage( $item->ID, 'main-size' );
		$property_image = ob_get_contents();
  	ob_end_clean();
  	
		$return .= "<div class='item rental'>";
		$return .= "	<div class='image'>" . $property_image . "</div>";
		$return .= "	<div class='data'>";
		$return .= "		<div class='title'><a href='{$link}'>{$item->post_title}</a></div>";
		$return .= "		<div class='excerpt'>{$content}</div>";
		$return .= "	</div>";
		$return .= "</div>";
	}
	$return .= "</div>";
	return $return;
}
add_shortcode( 'rentals', 'chp_sc_rentals' );


function chp_sc_community_programs($atts=null,$inner_content=null) {
	$return = "";
	$items = ThemeAdmin::fetch_community_programs();
	// menu
	$return .= "<div class='community-programs menu'>";
	foreach($items as $item) {
		$return .= "<div class='item'><a href='#item-{$item->ID}'>{$item->post_title}</a></div>";
	}
	$return .= "</div>";
	
	// listing
	$return .= "<div class='community-programs listing'>";
	foreach($items as $item) {
		$return .= "<a name='item-{$item->ID}'></a>";
		$return .= "<div class='item'>";
		$return .= "	<h3 class='title'>{$item->post_title}</h3>";
		$return .= "	<div class='content'>{$item->post_content}</div>";
		$return .= "</div>";
	}
	return $return;
}
add_shortcode( 'community_programs', 'chp_sc_community_programs' );

function chp_sc_chp_map($atts=null,$inner_content=null) {
	$return = "";
	$return .= "<iframe width='500' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://maps.google.com/maps/ms?ie=UTF8&hl=en&oe=UTF8&msa=0&msid=212800702847642071502.000495bb6a1f50d2abb11&ll=39.955498,-82.96552&spn=0.158857,0.319827&output=embed'></iframe>";
	$return .= "<p clear='both'>View <a href='http://maps.google.com/maps/ms?ie=UTF8&hl=en&oe=UTF8&msa=0&msid=212800702847642071502.000495bb6a1f50d2abb11&ll=39.955498,-82.96552&spn=0.158857,0.319827&source=embed' target='_blank'>CHP Communities and Offices</a> in a larger map</p>";
	return $return;
}
add_shortcode( 'chp_map', 'chp_sc_chp_map' );

function chp_sc_news($atts = null, $inner_content = null) {
	extract( shortcode_atts( array(
		'limit' => 6
	), $atts ) );
	
	$return = "<div class='box news'><h4>News</h4>";
	$limit = (int)$limit;
	$news = ThemeAdmin::fetch_nonevent_posts($limit);
	foreach($news as $item) {
		if($item->post_excerpt) {
			$content = chp_excerpt($item->post_excerpt, '...more', get_permalink($content->ID), 10);
		}
		else {
			$content = chp_excerpt($item->post_content, '...more', get_permalink($content->ID), 10);
		}
		$link = get_permalink( $item->ID );
		$return .= "<div class='fp-news'><a href='{$link}'>{$content}</a></div>";
	}
	$return .= "</div>";
	return $return;
}
add_shortcode( 'news', 'chp_sc_news' );


function chp_sc_answer($atts = null, $inner_content = null) {
	extract( shortcode_atts( array(
		'link' => ''
	), $atts ) );
	
	return "<div class='answer'><a href='/learn/{$link}'>{$inner_content}</a></div>"; 
}
add_shortcode( 'answer', 'chp_sc_answer' );

function chp_sc_question($atts = null, $inner_content = "") {
	$content = "<h3 class='question'>{$inner_content}</h3>";
	return $content;
}
add_shortcode( 'question', 'chp_sc_question' );

// box can be used to create generic boxes, or can have a caption set and be used for images
function chp_sc_box($atts=null, $inner_content=null) {
	/*extract( shortcode_atts( array(
		'align' => '',
		'caption' => '',
		'style'=>'',
		'type'=>'',
		'icon'=>''
	), $atts ) );
	
	$icon_img = '';
	if(strlen($icon)) {
		$icon_img = "<img src='{$dir}/images/icons/{$icon}.png' class='icon {$icon}' />";
	}
	
	if($align!="left" && $align!="right") {
		$align = 'none';
	}
	if(strlen($caption)) {
		$caption = "<div clas='caption'>{$caption}</div>";
		$has_caption = " has-caption ";
	}
	else {
		$has_caption = " no-caption ";
	}
	
	return "<div class='box align-{$align} {$has_caption}' style='{$style}'>{$icon_img}" . do_shortcode($inner_content) . "{$caption}</div>";*/
	
	extract( shortcode_atts( array(
		'icon'=>'',
		'align'=>'',
		'caption'=>'',
		'arrow'=>'',
		'url'=>'',
		'link'=>'',
		'linkbox'=>false,
	), $atts ) );
	
	// linkbox
	if(!strlen($link)) {
		$link = $url;
	}
	$link_open = '';
	$link_close = '';
	if($linkbox && $link) {
		$link_open = "<a href='{$link}'>";
		$link_close = "</a>";
		$linkbox = ' link ';
	}
	
	// icon
	$dir = get_bloginfo('template_url');
	$icon_img = '';
	$has_icon = '';
	if(strlen($icon)) {
		$has_icon = ' has-icon ';
		$icon_img = "<div class='icon'>{$link_open}<img src='{$dir}/images/icons/{$icon}.png' class='icon-img {$icon}' />{$link_close}</div>";
	}
	// if there's any html found, leave it alone, otherwise wrap it in h3
	if(strlen($inner_content) == strlen(strip_tags($inner_content))) {
		$inner_content = "{$link_open}<h3>{$inner_content}</h3>{$link_close}";
	}
	// alignment
	if($align!="left" && $align!="right") {
		$align = 'none';
	}
	// caption
	$has_caption = '';
	if(strlen($caption)) {
		$caption = "<div clas='caption'>{$link_open}{$caption}{$link_close}</div>";
		$has_caption = " has-caption ";
	}
	// arrow
	if($arrow) {
		$arrow = "<div class='arrow'>{$link_open}<img src='{$dir}/images/arrow-large.png' />{$link_close}</div>";
	}
	else {
		$arrow = "";
	}
	// content
	if(strpos($inner_content,'[question]')===false) {
		$inner_content = "<div class='boxtext'>{$link_open}" . $inner_content . "{$link_close}</div>";
	}
	else {
		$inner_content = "<div class='boxtext'>{$link_open}" . do_shortcode($inner_content) . "{$link_close}</div>";
	}
	$return = "
			<div class='box align-{$align} {$linkbox} {$has_icon} {$has_caption}'>
				<div class='boxcontent icon-{$icon}'>
					{$icon_img}
					{$inner_content}
					{$arrow}
				</div>
				{$caption}
				<div class='clear crush'>&nbsp;</div>
			</div>";

	return $return;
	
}
add_shortcode( 'box', 'chp_sc_box' );

// linkbox creates a box intended to be entirely linked to one place
function chp_sc_linkbox($atts=null, $inner_content=null) {
	$atts['linkbox'] = true;
	$atts['arrow'] = true;
	if(strlen(strip_tags($inner_content)) == strlen($inner_content)) {
		$inner_content = "<h3>{$inner_content}</h3>";
	}
	return chp_sc_box($atts, $inner_content);
}
add_shortcode( 'linkbox', 'chp_sc_linkbox' );

// questionbox creates a box intended to hold question/choice options
function chp_sc_questionbox($atts=null, $inner_content=null) {
	$atts['question'] = true;
	$atts['arrow'] = false;
	return chp_sc_box($atts, $inner_content);
}
add_shortcode( 'questionbox', 'chp_sc_questionbox' );




function chp_sc_certificates($atts = null, $content = null) {
	$content = "<table cellpadding='0' cellspacing='0' class='media_boxes2' width='100%'>
  <tr>
    <td><p><strong>Certifications and Associations</strong></p>
        <p>Homeport (Columbus Housing Partnership) is a Nationally Recognized Adopter of the National Industry Standards for Homeownership Education and Counseling,
		and a Member in Good Standing with the following organizations:</p>
      <p align='center'>
		<a href='http://www.oano.org/' target='_blank'><img src='" . get_bloginfo('template_directory') . "/images/cert_oano.png' alt='OANO' width='145' height='130' border='0' /></a>
		<a href='http://www.give.org/seal.asl?ID=178152632010' target='_blank'><img src='" . get_bloginfo('template_directory') . "/images/cert_bbb.png' alt='BBB Accredited Charity' width='145' height='130' border='0' /></a>
		<a href='http://www.nw.org/network/home.asp' target='_blank'><img src='" . get_bloginfo('template_directory') . "/images/cert_nw.png' alt='Neighborworks' width='217' height='130' border='0' /></a></p></td>
  </tr>
</table>";

	return $content;
}
add_shortcode( 'certificates', 'chp_sc_certificates' );


function chp_style_one( $atts, $content = null ) 
{
  ob_start();  

  echo '<div class="chp-sc-style-one">' . $content . '</div>';

  $ret = ob_get_contents();
	ob_end_clean();
  return $ret;
}
add_shortcode( 'style1', 'chp_style_one' ); 

function chp_style_two( $atts, $content = null ) 
{
  ob_start();  

  echo '<div class="chp-sc-style-two">' . $content . '</div>';

  $ret = ob_get_contents();
	ob_end_clean();
  return $ret;
}
add_shortcode( 'style2', 'chp_style_two' );

function chp_header_one( $atts, $content = null ) 
{
  ob_start();  

  echo '<h1 class="chp-sc-header-one">' . $content . '</h1>';

  $ret = ob_get_contents();
	ob_end_clean();
  return $ret;
}
add_shortcode( 'header1', 'chp_header_one' );

function chp_header_two( $atts, $content = null ) 
{
  ob_start();  

  echo '<h1 class="chp-sc-header-two">' . $content . '</h1>';

  $ret = ob_get_contents();
	ob_end_clean();
  return $ret;
}
add_shortcode( 'header2', 'chp_header_two' );