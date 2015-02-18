<?php
define( 'MAX_BYTES', 4193304 ); // 5 MB max image upload 5242880
define( 'MAX_MEGABYTES', 4 ); // 5 MB max image upload 5

// define( 'MIN_HEIGHT', 225 );
// define( 'MIN_WIDTH', 530 );

ini_set( 'post_max_size', 4193304 ); 
ini_set( 'upload_max_filesize', 8386608 ); // 5 MB max image upload
ini_set( 'max_execution_time', 180 );

ob_start();
// ini_set( "display_errors", 0 );
// error_reporting(1);
/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    
    function getName() {
        return $_GET['qqfile'];
    }
    
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }
    
    function checkDimensions($path) {
        if ($sData = getimagesize($path)){
          
          switch ($_REQUEST['post_type']){
            case "logo":
              $min_height  = 20;
              $min_width  = 40;
            break;
            default:
              $min_height  = 225;
              $min_width  = 530;
          }

          
          if( $sData[0] < $min_width ){
            return 'Image must be at least ' . $min_width . ' pixels in width';
          }
          
          if( $sData[1] < $min_height ){
            return 'Image must be at least ' . $min_height . ' pixels in height';
          }
          // $origType = $sData[2];
          // $mimeType = $sData['mime'];
          // 
          // $image = $this->openImage ($mimeType, $path);
          // if ($image === false) {
          //   throw new Exception('Unable to open image.');
          // }
          // $width = imagesx ($image);
          // $height = imagesy ($image);
          // 
          // ThemeUtil::log_something(print_r($sData, 1));
          
          // $img_args['id'] = $att->ID;
    		  // $size = 'current-show';
    		  // $metadata = wp_get_attachment_metadata( $att->ID, $size, false, $img_args );
    		  // print '<!-- wp_get_attachment_metadata: ' . print_r($metadata, 1) . ' -->';
    		  
        } else {
          // ThemeUtil::log_something('Could not get file dimensions.');
          return 'Could not get file dimensions.';
        }      
        return '';
    }   
    
    // function openImage($mimeType, $src){
    //     switch ($mimeType) {
    //             case 'image/jpg': //This isn't a valid mime type so we should probably remove it
    //                     $image = imagecreatefromjpeg ($src);
    //                     break;
    //             case 'image/jpeg':
    //                     $image = imagecreatefromjpeg ($src);
    //                     break;
    // 
    //             case 'image/png':
    //                     $image = imagecreatefrompng ($src);
    //                     break;
    // 
    //             case 'image/gif':
    //                     $image = imagecreatefromgif ($src);
    //                     break;
    //     }
    // 
    //     return $image;
    //   }

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
    
    function checkDimensions($path) {
        if ($sData = getimagesize($path)){
          
          switch ($_REQUEST['post_type']){
            case "logo":
              $min_height  = 20;
              $min_width  = 40;
            break;
            default:
              $min_height  = 225;
              $min_width  = 530;
          }

          
          if( $sData[0] < $min_width ){
            return 'Image must be at least ' . $min_width . ' pixels in width';
          }
          
          if( $sData[1] < $min_height ){
            return 'Image must be at least ' . $min_height . ' pixels in height';
          }
          // $origType = $sData[2];
          // $mimeType = $sData['mime'];
          // 
          // $image = $this->openImage ($mimeType, $path);
          // if ($image === false) {
          //   throw new Exception('Unable to open image.');
          // }
          // $width = imagesx ($image);
          // $height = imagesy ($image);
          // 
          // ThemeUtil::log_something(print_r($sData, 1));
          
          // $img_args['id'] = $att->ID;
    		  // $size = 'current-show';
    		  // $metadata = wp_get_attachment_metadata( $att->ID, $size, false, $img_args );
    		  // print '<!-- wp_get_attachment_metadata: ' . print_r($metadata, 1) . ' -->';
    		  
        } else {
          // ThemeUtil::log_something('Could not get file dimensions.');
          return 'Could not get file dimensions.';
        }      
        return '';
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = MAX_BYTES;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 5242880){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        // if (isset($_GET['qqfile'])) {
        //     $this->file = new qqUploadedFileXhr();
        // } elseif (isset($_FILES['qqfile'])) {
        //     $this->file = new qqUploadedFileForm();
        // } else {
        //     $this->file = false; 
        // }
        if (isset($_GET['qqfile'])) {
            // ThemeUtil::log_something('new qqUploadedFileXhr();');
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            // ThemeUtil::log_something('new qqUploadedFileForm();');
            $this->file = new qqUploadedFileForm();
        } else {
            // ThemeUtil::log_something('false');
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
      $postSize = $this->toBytes(ini_get('post_max_size'));
      $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
      
      if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
          $size = $this->toBytes(max(4, MAX_MEGABYTES) . 'M');             
          die("{'error':'increase post_max_size ($postSize) and upload_max_filesize ($uploadSize) to $size'}");    
          // die("{'error':'maximum file size must not exceed " . MAX_MEGABYTES . "MB'}");  
          
      }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
		  $in_theme_section = $_REQUEST['theme_section'];
		  
		  $debug = array();
  		$status = false;
  		$in_uid = $_REQUEST['uid'];
  		$in_time = $_REQUEST['time'];
		
  		if( !current_user_can( 'edit_posts' ) ){
  			return array('error' => 'Current user not permitted to upload files.');
  		}
		
      if (!is_writable($uploadDirectory)){
          return array('error' => "Server error. Upload directory isn't writable: " . $uploadDirectory);
      }
    
      if (!$this->file){
          return array('error' => 'No files were uploaded.');
      }
    
      $size = $this->file->getSize();
    
      if ($size == 0) {
          return array('error' => 'File is empty');
      }
    
      if ($size > $this->sizeLimit) {
          return array('error' => 'File is too large');
      }
    
      $pathinfo = pathinfo($this->file->getName());
      $debug['path_info'] = $pathinfo;
      
      $original_name = $filename = $pathinfo['filename']; // $filename = md5($filename);
      $debug['original_filename'] = $original_name;
      
      $ext = $pathinfo['extension'];

      if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
          $these = implode(', ', $this->allowedExtensions);
          return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
      }
		
  		if ( ! ( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] ) )
  			return array( 'error'=> 'Something wrong with wp_upload_dir.' );
		
  		$unique_filename_callback = null;
  		$wp_filename = wp_unique_filename( $uploads['path'], $filename . '.' . $ext, $unique_filename_callback );
		  $debug['wp_unique_filename'] = $wp_filename;
		  
  		$new_file = $uploads['path'] . "/$wp_filename";
  		$url = $uploads['url'] . "/$wp_filename";
		  
		  $debug['new_file'] = $new_file;
		  $debug['url'] = $url;
		  
  		if ($this->file->save( $new_file )){ // $uploadDirectory . $filename . '.' . $ext
			
			  $dimcheck = $this->file->checkDimensions( $new_file );
			  if( "" != $dimcheck ){
          return array('error' => $dimcheck);
        }
        else{  
          
    			$status = true;
			
    			$post_id = $_REQUEST['post_id'];
    			$post_type = $_REQUEST['post_type'];
			
    			$stat = stat( dirname( $new_file ));
    			$perms = $stat['mode'] & 0000666;
    			@chmod( $new_file, $perms );
			
    			if( !empty( $url ) ){
    				$wp_filetype = wp_check_filetype( basename($new_file), null );
			
    				$attachment = array(
    					'post_mime_type' => $wp_filetype['type'],
    					'post_title' => preg_replace( '/\.[^.]+$/', '', basename($new_file) ),
    					'post_content' => '',
    					'post_status' => 'inherit'
    				);
			      
			      $debug['attachment'] = $attachment;
			      
    				$attach_id = wp_insert_attachment( $attachment, $new_file, $post_id );
    				$debug['attach_id'] = $attach_id;
    				
    				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				
    				$fullsizepath = get_attached_file( $attach_id );
    				$debug['fullsizepath'] = $fullsizepath;
    				
    				$metadata = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
    				$metadata['post_type'] = $post_type;
				    $debug['metadata'] = $metadata;
				    
    				if ( is_wp_error( $metadata ) )
    					$status = false;
    				if ( empty( $metadata ) )
    					$status = false;
					
    				if( $status )
    					wp_update_attachment_metadata( $attach_id, $metadata );
				
			
    			}
			
			
    			return array(
    				'success' 		  => $status,
    				'post_id' 		  => $post_id,
    				'post_type' 	  => $post_type,
    				'file'			    => $new_file,
    				'fullsizepath'	=> $fullsizepath,
    				'url'		      	=> $url,
    				'attach_id'		  => $attach_id,
    				'metadata'		  => $metadata,
            'debug'         => $debug
    			);
		    }

      } else {
          return array('error'=> 'Could not save uploaded file.' .
              'The upload was cancelled, or server error encountered');
      }
      
    }    
}

function save_image ($mime_type, $image_resized, $file_path) {

    $quality = 90;

    // check to see if we can write to the cache directory
    // $cache_file = get_cache_file ($mime_type);

	if (stristr ($mime_type, 'jpeg')) {
		imagejpeg ($image_resized, $file_path, $quality);
	} else {
		imagepng ($image_resized, $file_path, floor ($quality * 0.09));
	}

	// show_cache_file ($mime_type);

}

function add_exif_data($filepath, &$file_data){
	$exif = @exif_read_data($filepath, 0, true);
	if($exif && is_array($exif)){
		foreach ($exif as $key => $section) {
			$file_data['exif'][$key] = array();
		    foreach ($section as $name => $val) {
				$file_data['exif'][$key][$name] = $val;
		        // echo "$key.$name: $val<br />\n";
		    }
		}
	}
}

/**
 * determine the file mime type, borrowed from TimThumb
 */
function mime_type($file) {

    if (stristr(PHP_OS, 'WIN')) { 
        $os = 'WIN';
    } else { 
        $os = PHP_OS;
    }

    $mime_type = '';

    if (function_exists('mime_content_type')) {
        $mime_type = mime_content_type($file);
    }
    
	// use PECL fileinfo to determine mime type
	if (!valid_src_mime_type($mime_type)) {
		if (function_exists('finfo_open')) {
			$finfo = @finfo_open(FILEINFO_MIME);
			if ($finfo != '') {
				$mime_type = finfo_file($finfo, $file);
				finfo_close($finfo);
			}
		}
	}

    // try to determine mime type by using unix file command
    // this should not be executed on windows
    if (!valid_src_mime_type($mime_type) && $os != "WIN") {
        if (preg_match("/FREEBSD|LINUX/", $os)) {
			$mime_type = trim(@shell_exec('file -bi ' . escapeshellarg($file)));
        }
    }

    // use file's extension to determine mime type
    if (!valid_src_mime_type($mime_type)) {

        // set defaults
        $mime_type = 'image/png';
        // file details
        $fileDetails = pathinfo($file);
        $ext = strtolower($fileDetails["extension"]);
        // mime types
        $types = array(
             'jpg'  => 'image/jpeg',
             'jpeg' => 'image/jpeg',
             'png'  => 'image/png',
             'gif'  => 'image/gif'
         );
        
        if (strlen($ext) && strlen($types[$ext])) {
            $mime_type = $types[$ext];
        }
        
    }
    
    return $mime_type;

}

function valid_src_mime_type($mime_type) {

    if (preg_match("/jpg|jpeg|gif|png/i", $mime_type)) {
        return true;
    }
    
    return false;

}

function open_image ($mime_type, $src) {

	$mime_type = strtolower ($mime_type);

	if (stristr ($mime_type, 'gif')) {

        $image = imagecreatefromgif ($src);

    } elseif (stristr ($mime_type, 'jpeg')) {

        $image = imagecreatefromjpeg ($src);

    } elseif (stristr ($mime_type, 'png')) {

        $image = imagecreatefrompng ($src);

    }

    return $image;

}

function img_resize( $tmpname, $size, $save_dir, $save_name, $maxisheight = 0 ){
    $save_dir     .= ( substr($save_dir,-1) != "/") ? "/" : "";
    $gis        = getimagesize($tmpname);
    $type        = $gis[2];
    switch($type){
    	case "1": $imorig = imagecreatefromgif($tmpname); break;
	    case "2": $imorig = imagecreatefromjpeg($tmpname);break;
	    case "3": $imorig = imagecreatefrompng($tmpname); break;
	    default:  $imorig = imagecreatefromjpeg($tmpname);
    }

    $x = imagesx($imorig);
    $y = imagesy($imorig);
    
    $woh = (!$maxisheight)? $gis[0] : $gis[1];    
        
    if($woh <= $size){
    	$aw = $x;
    	$ah = $y;
    }
    else{
        if(!$maxisheight){
            $aw = $size;
            $ah = $size * $y / $x;
        } else {
            $aw = $size * $x / $y;
            $ah = $size;
        }
    }   
    $im = imagecreatetruecolor($aw,$ah);
    
	if (imagecopyresampled($im,$imorig , 0,0,0,0,$aw,$ah,$x,$y))
        if (imagejpeg($im, $save_dir.$save_name))
            return true;
        else
            return false;
    
}


$typical_wpload_path = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/' ;
// echo $typical_wpload_path; exit;

require_once( $typical_wpload_path . 'wp-load.php' );

// ThemeUtil::log_something('started upload');

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = MAX_BYTES;

// ThemeUtil::log_something(print_r($_REQUEST, 1));
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('./', false);

// to pass data through iframe you will need to encode all html tags
ob_end_clean();
// header("Content-Type: application/json");
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
// echo json_encode($result);

// ThemeUtil::log_something('ended upload');

die;

?>