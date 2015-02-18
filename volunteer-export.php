<?php /* Template Name: Volunteer Export */ 
  error_reporting(0);
  ob_end_clean();
  header("Content-Type:text/plain");

  $slot_blocks        = get_option('volunteer_slot_blocks', false);
  // ThemeUtil::mpuke( $slot_blocks );
  
  $interests          = get_option('volunteer_interests', false);
  // ThemeUtil::mpuke( $interests );
  
  function vi($arr){
    // ThemeUtil::mpuke($arr);
    $vi = array();
    $keys = array_keys($arr);
    foreach($keys as $key){
      $vi[] = preg_replace("/_/", " ", $key);
    }
    return implode(",", $vi);
  }
  
  function va($arr){
    $morning_keys = implode(",", array_keys($arr['mornings']));
    $afternoon_keys = implode(",", array_keys($arr['afternoons']));
    $evening_keys = implode(",", array_keys($arr['evenings']));
    $weekend_keys = implode(",", array_keys($arr['weekends']));
    
    $ret = '"' . $morning_keys . '",';
    $ret .= '"' . $afternoon_keys . '",';
    $ret .= '"' . $evening_keys . '",';
    $ret .= '"' . $weekend_keys . '",';
    
    return $ret;
    // // ThemeUtil::mpuke($arr);
    // $va = array();
    // foreach($arr as $key=>$value){
    //   
    // }
    // return implode(",", $arr);
  }
  
  $current_user = wp_get_current_user();
  $redir = "/wp-login.php";
  if($current_user->data->ID==0) wp_redirect($redir);
  
  $volunteer_args = array(
     'post_type'       => 'volunteer',
     'numberposts' => -1
  );
  $volunteers = get_posts( $volunteer_args ); 
?>firstname, lastname, email, phone, interests, mornings, afternoons, evenings, weekends, comments
<?php
  foreach( $volunteers as $volunteer ){
    $volunteer->custom = get_post_custom( $volunteer->ID );
    $volunteer->custom['volunteer_avail'][0] = unserialize($volunteer->custom['volunteer_avail'][0]);
    $volunteer->custom['volunteer_interests'][0] = unserialize($volunteer->custom['volunteer_interests'][0]);
    
    // ThemeUtil::mpuke( $volunteer );
    echo '"' . $volunteer->custom['first_name'][0] . '"' . ",";
    echo '"' . $volunteer->custom['last_name'][0] . '"' . ",";
    echo '"' . $volunteer->custom['email'][0] . '"' . ",";
    echo '"' . $volunteer->custom['phone'][0] . '"' . ",";
    echo '"' . vi($volunteer->custom['volunteer_interests'][0]) . '"' . ",";
    echo '' . va($volunteer->custom['volunteer_avail'][0]) . '' . "";
    echo '"' . $volunteer->custom['other'][0] . '"' . "\n";
  }
?>