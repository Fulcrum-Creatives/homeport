<?php

$custom             = get_post_custom( $post->ID );
$slots_days_inner   = $slot_days = get_option('volunteer_slot_days', false);
$slot_blocks        = get_option('volunteer_slot_blocks', false);
$interests          = get_option('volunteer_interests', false);

$vol_avail          = $custom['volunteer_avail'][0];
$vol_interests      = $custom['volunteer_interests'][0];

$first_name         = $custom['first_name'][0];
$last_name          = $custom['last_name'][0];
$email              = $custom['email'][0];
$phone              = $custom['phone'][0];
$other              = $custom['other'][0];

if(!is_array($vol_avail)) $vol_avail = unserialize($vol_avail);
if(!is_array($vol_interests)) $vol_interests = unserialize($vol_interests);
// ThemeUtil::mpuke( $vol_interests );
$nonce = wp_create_nonce( NONCE_STRING );
?>

<div class="clearfix"></div>

<div class="custom-admin">
  <input type="hidden" name="wp_meta_box_nonce" value="<?php echo $nonce; ?>" />
  <div id="admin-reposition">
    <div class="volunteer-form">
      
      <?php if( TRUE || true==$front_end ): ?>
      <div class="postarea" style="float:left;width:100%">
        <!-- <div class="titlediv"> -->
      		<label class="not-fancy-label floated" for="first-name">First Name:</label>
      		<input type="text" id="first-name" name="first_name" class="prompt-text" style="width:420px"
      			value="<?php echo $first_name; ?>" />
      	<!-- </div> -->
      </div>
      <div class="postarea" style="float:left;width:100%">
        <!-- <div class="titlediv"> -->
      		<label class="not-fancy-label floated" for="last-name">Last Name:</label>
      		<input type="text" id="last-name" name="last_name" class="prompt-text" style="width:420px"
      			value="<?php echo $last_name; ?>" />
      	<!-- </div> -->
      </div>
    	<div class="clearfix"></div>
      <?php endif; ?>
    
      <div class="clearfix"></div>

  	  <div class="postarea" style="float:left;width:100%">
        <!-- <div class="titlediv"> -->
      		<label class="not-fancy-label floated" for="email">Email:</label>
      		<input type="text" id="email" name="email" class="prompt-text" style="width:420px"
      		  value="<?php echo $email; ?>" />
      	<!-- </div> -->
      </div>
    	<div class="postarea" style="float:left;width:100%">
        <!-- <div class="titlediv"> -->
      		<label class="not-fancy-label floated" for="phone">Phone:</label>
      		<input type="text" id="phone" name="phone" class="prompt-text" style="width:420px"
      		  value="<?php echo $phone; ?>" />
      	<!-- </div> -->
      </div>
      <div class="clearfix"></div>
      
    </div>
	  
	  <div class="clearfix"></div>
    
    <div class="volunteer-form">
      <h2>I am interested in (check all that apply):</h2>
      <div id="volunteer-interests">
        <?php // ThemeUtil::mpuke( $interests ); ?>
        <?php foreach( $interests as $interest ): 
          $interest_safe = preg_replace("/\W|\s/", "_", trim(strtolower($interest)));
          ?>
    
          <div class="volunteer-interest">
            <input type="checkbox" class="vol-cb" value="1" id="volunteer_interests[<?php echo $interest_safe; ?>]"
              name="volunteer_interests[<?php echo $interest_safe; ?>]" / <?php
                if($vol_interests[$interest_safe]){
                  echo ' CHECKED="CHECKED"';
                }
              ?>>&nbsp;
            <?php echo $interest; ?>
          </div>
  
        <?php endforeach; ?>
        <div class="clearfix"></div>
          
      </div>
      <h2 style="padding-top:10px">Other:</h2>
      <textarea id="other" name="other"><?php echo $other; ?></textarea>
      <div class="clearfix"></div>
    </div>
    
  	<div class="clearfix"></div>
	  
	  <div class="volunteer-form">
	    
    	<h2>My volunteer availability is:</h2>
      <div class="postarea volunteer-slots">
        <table id="volunteer-slot-table">
          <tr>
            <th class="vol-buffer">&nbsp;</th>
            <?php foreach( $slot_days as $slot_day ): ?>
              <th class="vol-day"><?php echo $slot_day; ?></th>
            <?php endforeach; ?>
          </tr>
          <?php foreach( $slot_blocks as $slot_block ): 
            $slot_block_safe = preg_replace("/\W|\s/", "_", trim(strtolower($slot_block)));
            // $slot_block_safe = preg_replace("/\-/", "_", $slot_block_safe);
            ?>
            <tr>
            <td class="vol-block"><?php echo $slot_block; ?></td>
            <?php foreach( $slots_days_inner as $slot_day_inner ): 
              $slot_day_inner_safe = preg_replace("/\W|\s/", "_", trim(strtolower($slot_day_inner)));
              // $slot_day_inner_safe = preg_replace("/\-/", "_", $slot_day_inner_safe);
              ?>
              <td class="vol-cb-wrap">
                <input type="checkbox" class="vol-cb" value="1" id="vol_cb_<?php echo $slot_block_safe; ?>_<?php echo $slot_day_inner_safe; ?>" 
                  name="<?php echo $slot_block_safe; ?>[<?php echo $slot_day_inner_safe; ?>]" <?php
                    if($vol_avail[$slot_block_safe][$slot_day_inner_safe]){
                      echo ' CHECKED="CHECKED"';
                    }
                  ?>
                  />
              </td>
            <?php endforeach; ?>  
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
    
    
    <div class="clearfix"></div>
  </div>
</div>  
<div class="clearfix"></div>
<!--div class="clearfix"></div>

<div class="postarea" style="float:left;width:35%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="street">Street</label>
    <input type="text" id="street" name="street" class="prompt-text" value="<?php echo $street; ?>" />
  </div>
</div>

<div class="postarea" style="float:left;width:25%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="city">City</label>
    <input type="text" id="city" name="city" class="prompt-text" value="<?php echo $city; ?>" />
  </div>
</div>

<div class="postarea" style="float:left;width:25%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="state">State</label>
    <input type="text" id="state" name="state" class="prompt-text" value="<?php echo $state; ?>" />
  </div>
</div>

<div class="postarea" style="float:left;width:15%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="zip">Zip</label>
    <input type="text" id="zip" name="zip" class="prompt-text" value="<?php echo $zip; ?>" />
  </div>
</div-->

<!--div class="clearfix"></div>

<div class="postarea" style="float:left;width:33%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="home-phone">Home Phone</label>
    <input type="text" id="home-phone" name="home_phone" class="prompt-text" value="<?php echo $home_phone; ?>" />
  </div>
</div>
<div class="postarea" style="float:left;width:33%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="cell-phone">Cell Phone</label>
    <input type="text" id="cell-phone" name="cell_phone" class="prompt-text" value="<?php echo $cell_phone; ?>" />
  </div>
</div>
<div class="postarea" style="float:left;width:33%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="work-phone">Work Phone</label>
    <input type="text" id="work-phone" name="work_phone" class="prompt-text" value="<?php echo $work_phone; ?>" />
  </div>
</div-->

<!--div class="clearfix"></div>

<div class="postarea" style="float:left;width:33%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="zip">Employer</label>
    <input type="text" id="employer" name="employer" class="prompt-text" value="<?php echo $employer; ?>" />
  </div>
</div>
<div class="postarea" style="float:left;width:33%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="dob">Date of Birth</label>
    <input type="text" id="dob" name="dob" class="prompt-text" value="<?php echo $dob; ?>" />
  </div>
</div>
<div class="postarea" style="float:left;width:33%">
  <div class="titlediv">
    <label class="not-fancy-label" style="" for="years-in-col">Years living in Columbus</label>
    <input type="text" id="years-in-col" name="years_in_col" class="prompt-text" value="<?php echo $years_in_col; ?>" />
  </div>
</div-->