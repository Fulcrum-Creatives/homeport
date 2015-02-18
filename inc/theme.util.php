<?php

class ThemeUtil{

	public static function mpuke( $obj ){
		echo '<pre>';
		print_r( $obj );
		echo '</pre>';
	}
	
	public static function mpuke_ta( $obj ){
		echo '<textarea style="width:100%;height:300px">';
		print_r( $obj );
		echo '</textarea>';
	}
	
	public static function log_something( $str ){
	  // return;
    $filepath = $_SERVER['DOCUMENT_ROOT'] . '/utl.log.txt';
    
    if( $fp = fopen( $filepath, "a" ) ){
      fputs ( $fp, date("m/d/y : H:i:s", time())  . "\n" );
      fputs ( $fp, $str . "\n" );
      fputs ( $fp, "------------------------------\n" );
      fclose ( $fp );
    }

	}
	
}