<?php

/**
 * Class for handling embedded media in gallery
 *
 */

class WDILibraryEmbed {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }


  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function get_provider($oembed, $url, $args = '') {
		$provider = false;
		if (!isset($args['discover'])) {
			$args['discover'] = true;
    }
		foreach ($oembed->providers as $matchmask => $data ) {
			list( $providerurl, $regex ) = $data;
			// Turn the asterisk-type provider URLs into regex
			if ( !$regex ) {
				$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
				$matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
			}
			if ( preg_match( $matchmask, $url ) ) {
				$provider = str_replace( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
				break;
			}
		}
		if ( !$provider && $args['discover'] ) {
			$provider = $oembed->discover($url);
    }
		return $provider;
	}

/** 
 * check host and get data for a given url
 * @return encode_json(associative array of data) on success
 * @return encode_json(array[false, "error message"]) on failure
 *
 * EMBED TYPES
 *
 *  EMBED_OEMBED_YOUTUBE_VIDEO
 *  EMBED_OEMBED_VIMEO_VIDEO
 *  EMBED_OEMBED_DAILYMOTION_VIDEO
 *  EMBED_OEMBED_INSTAGRAM_IMAGE
 *  EMBED_OEMBED_INSTAGRAM_VIDEO
 *  EMBED_OEMBED_INSTAGRAM_POST
 *  EMBED_OEMBED_FLICKR_IMAGE
 *
 *  RULES FOR NEW TYPES
 *
 *  1. begin type name with EMBED_
 *  2. if using WP native OEMBED class, add _OEMBED then
 *  3. add provider name
 *  4. add _VIDEO, _IMAGE FOR embedded media containing only video or image
 *  5. add _DIRECT_URL from static URL of image (not implemented yet)
 *
 */

  public static function add_embed($url) {

    $url = sanitize_text_field(urldecode($url));


    $embed_type = '';
    $host = '';
    /*returns this array*/
    $embedData = array(
      'name' => '',
      'description' => '',
      'filename' => '',
      'url' => '',
      'reliative_url' => '',
      'thumb_url' => '',
      'thumb' => '',
      'size' => '',
      'filetype' => '',
      'date_modified' => '',
      'resolution' => '',
      'redirect_url' => '');

    $accepted_oembeds = array(
      'YOUTUBE' => '/youtube/',
      'VIMEO' => '/vimeo/',
      'FLICKR' => '/flickr/',
      'INSTAGRAM' => '/instagram/',
      'DAILYMOTION' => '/dailymotion/'
      );
    
    /*check if we can embed this using wordpress class WP_oEmbed */
    if ( !function_exists( '_wp_oembed_get_object' ) )
      include( ABSPATH . WPINC . '/class-oembed.php' );
    // get an oembed object
    $oembed = _wp_oembed_get_object();
    if (method_exists($oembed, 'get_provider')) {
      // Since 4.0.0
      $provider = $oembed->get_provider($url);
    }
    else {
      $provider = self::get_provider($oembed, $url);
    }
    foreach ($accepted_oembeds as $oembed_provider => $regex) {
      if(preg_match($regex, $provider)==1){
        $host = $oembed_provider;
      }
    }
    /*return json_encode($host); for test*/

    /*handling oembed cases*/    
    if($host){
      /*instagram is exception*/
      /*standard oembed fetching does not return thumbnail_url! so we do it manually*/
      if($host == 'INSTAGRAM' && substr($url,-4)!='post' && substr($url,-4!='POST')){
        $embed_type = 'EMBED_OEMBED_INSTAGRAM';

        $insta_host_and_id= strtok($url, '/')."/".strtok('/')."/".strtok('/')."/".strtok('/');
        $insta_host= strtok($url, '/')."/".strtok('/')."/".strtok('/')."/";
        $filename = str_replace($insta_host, "", $insta_host_and_id);
        
        $get_embed_data = wp_remote_get("http://api.instagram.com/oembed?url=http://instagram.com/p/".$filename); 
        if ( is_wp_error( $get_embed_data ) ) {
          return json_encode(array("error", "cannot get Instagram data"));
        }
        $result  = json_decode(wp_remote_retrieve_body($get_embed_data));
        if(empty($result)){
          return json_encode(array("error", wp_remote_retrieve_body($get_embed_data)));
        }              
        

        $embedData = array(
          'name' => htmlspecialchars($result->title),
          'description' => htmlspecialchars($result->title),
          'filename' => $filename,
          'url' => $url,
          'reliative_url' => $url,
          'thumb_url' => $result->thumbnail_url,
          'thumb' => $result->thumbnail_url,
          'size' => '',
          'filetype' => $embed_type,
          'date_modified' => date('d F Y, H:i'),
          'resolution' => $result->thumbnail_width." x ".$result->thumbnail_height." px",
          'redirect_url' => ''
          );
        
        /*get instagram post html page, parse its DOM to find video URL*/
        $DOM = new DOMDocument;
        libxml_use_internal_errors(true);
        $html_code = wp_remote_get($url);
        if ( is_wp_error( $html_code ) ) {
          return json_encode(array("error", "cannot get Instagram data"));
        }
        $html_body = wp_remote_retrieve_body($html_code);
        if(empty($html_body)){
          return json_encode(array("error", wp_remote_retrieve_body($html_code)));
        }    
        
        $DOM->loadHTML($html_body);  
        $finder = new DomXPath($DOM);              
        $query = "//meta[@property='og:video']";                            
        $nodes = $finder->query($query);
        $node = $nodes->item(0);              
        if($node){
          $length = $node->attributes->length;
          
            for ($i = 0; $i < $length; ++$i) {
              $name = $node->attributes->item($i)->name;
              $value = $node->attributes->item($i)->value;
   
              if($name == 'content'){
                $filename = $value;
              }
          }
          $embedData['filename'] = $filename;
          $embedData['filetype'] .= '_VIDEO';
        }
        else{
          $embedData['filetype'] .= '_IMAGE'; 
        }
        
        return json_encode($embedData);
      }
      if($host == 'INSTAGRAM' && (substr($url,-4)=='post'||substr($url,-4)=='POST')){
        /*check if instagram post*/
        $url = substr($url,0,-4);
        $embed_type = 'EMBED_OEMBED_INSTAGRAM_POST';  
        parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
        $matches = array();
        $filename = '';
        $regex = "/^.*?instagram\.com\/p\/(.*?)[\/]?$/";
        if(preg_match($regex, $url, $matches)){
          $filename = $matches[1];
        }
        $get_embed_data = wp_remote_get("http://api.instagram.com/oembed?url=http://instagram.com/p/".$filename); 
        if ( is_wp_error( $get_embed_data ) ) {
          return json_encode(array("error", "cannot get Instagram data"));
        }
        $result  = json_decode(wp_remote_retrieve_body($get_embed_data));
        if(empty($result)){
          return json_encode(array("error", wp_remote_retrieve_body($get_embed_data)));
        }  
                      
        $embedData = array(
          'name' => htmlspecialchars($result->title),
          'description' => htmlspecialchars($result->title),
          'filename' => $filename,
          'url' => $url,
          'reliative_url' => $url,
          'thumb_url' => $result->thumbnail_url,
          'thumb' => $result->thumbnail_url,
          'size' => '',
          'filetype' => $embed_type,
          'date_modified' => date('d F Y, H:i'),
          'resolution' => $result->width." x ".$result->width." px",
          'redirect_url' => '');
 
        return json_encode($embedData);      
      }

      $result = $oembed->fetch( $provider, $url);
      /*no data fetched for a known provider*/
      if(!$result){
          return json_encode(array("error", "please enter ". $host . " correct single media URL"));
      }
      else{/*one of known oembed types*/
        $embed_type = 'EMBED_OEMBED_'.$host;
        switch ($embed_type) {
          case 'EMBED_OEMBED_YOUTUBE':
            $youtube_regex = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#";
            $matches = array();
            preg_match($youtube_regex , $url , $matches);
            $filename = $matches[0];

            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $filename,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type."_VIDEO",
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            
            break;
          case 'EMBED_OEMBED_VIMEO':
            
            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $result->video_id,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type."_VIDEO",
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            
            break;
          case 'EMBED_OEMBED_FLICKR':
            $matches = preg_match('~^.+/(\d+)~',$url,$matches);
            $filename = $matches[1];
            /*if($result->type =='photo')
              $embed_type .= '_IMAGE';
            if($result->type =='video')
              $embed_type .= '_VIDEO';*/
              /*flickr video type not implemented yet*/
              $embed_type .= '_IMAGE';
                         
            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' =>substr($result->thumbnail_url, 0, -5)."b.jpg", 
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type,
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            break;
          
          case 'EMBED_OEMBED_DAILYMOTION':
            $filename = strtok(basename($url), '_');

            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $filename,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type."_VIDEO",
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
            
            break;
          case 'EMBED_OEMBED_GETTYIMAGES':
          /*not working yet*/
            $filename = strtok(basename($url), '_');
            
            $embedData = array(
              'name' => htmlspecialchars($result->title),
              'description' => htmlspecialchars($result->title),
              'filename' => $filename,
              'url' => $url,
              'reliative_url' => $url,
              'thumb_url' => $result->thumbnail_url,
              'thumb' => $result->thumbnail_url,
              'size' => '',
              'filetype' => $embed_type,
              'date_modified' => date('d F Y, H:i'),
              'resolution' => $result->width." x ".$result->height." px",
              'redirect_url' => '');

            return json_encode($embedData);
         
          default:
            return json_encode(array("error", "unknown URL host"));
            break;
        }
      }
    }/*end of oembed cases*/
    else {
      /*check for direct image url*/
      /*check if something else*/
      /*not implemented yet*/
      return json_encode(array("error", "unknown URL"));
    }
    return json_encode(array("error", "unknown URL"));
  }


/** 
 * client side analogue is function wdi_spider_display_embed in wdi_embed.js
 *
 * @param embed_type: string , one of predefined accepted types
 * @param embed_id: string, id of media in corresponding host, or url if no unique id system is defined for host
 * @param attrs: associative array with html attributes and values format e.g. array('width'=>"100px", 'style'=>"display:inline;")
 * 
 */

  public static function display_embed($embed_type, $embed_id='', $attrs = array()) {
    $html_to_insert = '';

    switch ($embed_type) {
      case 'EMBED_OEMBED_YOUTUBE_VIDEO':
        $oembed_youtube_html ='<iframe ';
        if($embed_id!=''){
          $oembed_youtube_html .= ' src="' . '//www.youtube.com/embed/'. $embed_id . '?enablejsapi=1&wmode=transparent"';
        }
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_youtube_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_youtube_html .= " ></iframe>";
        $html_to_insert .= $oembed_youtube_html;
        break;
      case 'EMBED_OEMBED_VIMEO_VIDEO':
        $oembed_vimeo_html ='<iframe ';
        if($embed_id!=''){
          $oembed_vimeo_html .= ' src="' . '//player.vimeo.com/video/'. $embed_id . '?enablejsapi=1"';
        }
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_vimeo_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_vimeo_html .= " ></iframe>";
        $html_to_insert .= $oembed_vimeo_html;
        break;
      case 'EMBED_OEMBED_FLICKR_IMAGE':
         $oembed_flickr_html ='<div ';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_flickr_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_flickr_html .= " >";
        if($embed_id!=''){
        $oembed_flickr_html .= '<img src="'.$embed_id.'"'. 
        ' style="'.
        'max-width:'.'100%'." !important".
        '; max-height:'.'100%'." !important".
        '; width:'.'auto !important'.
        '; height:'. 'auto !important' . 
        ';">';      
        }
        $oembed_flickr_html .="</div>";

        $html_to_insert .= $oembed_flickr_html;
        break;
      case 'EMBED_OEMBED_FLICKR_VIDEO':
        # code...not implemented yet
        break;  
      case 'EMBED_OEMBED_INSTAGRAM_VIDEO':
        $oembed_instagram_html ='<div ';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_instagram_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_instagram_html .= " >";
        if($embed_id!=''){
        $oembed_instagram_html .= '<video onclick="wdi_play_pause(jQuery(this));" style="width:auto !important; height:auto !important; max-width:100% !important; max-height:100% !important; margin:0 !important;" controls>'.
        '<source src="'. $embed_id .
        '" type="video/mp4"> Your browser does not support the video tag. </video>'; 
        }
        $oembed_instagram_html .="</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      case 'EMBED_OEMBED_INSTAGRAM_IMAGE':
        $oembed_instagram_html ='<div ';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_instagram_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_instagram_html .= " >";
        if($embed_id!=''){
        $oembed_instagram_html .= '<img src="//instagram.com/p/'.$embed_id.'/media/?size=l"'. 
        ' style="'.
        'max-width:'.'100%'." !important".
        '; max-height:'.'100%'." !important".
        '; width:'.'auto !important'.
        '; height:'. 'auto !important' . 
        ';">';
        }
        $oembed_instagram_html .="</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      case 'EMBED_OEMBED_INSTAGRAM_POST':
        $oembed_instagram_html ='<div ';
        $id = '';
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_instagram_html .= ' '. $attr . '="'. $value . '"';
              if($attr == 'class' || $attr =='CLASS' || $attr =='Class'){
                $class = $value;
              }
            }
          }
        }
        $oembed_instagram_html .= " >";       
        if($embed_id!=''){
        $oembed_instagram_html .= '<iframe class="inner_instagram_iframe_'.$class.'" src="//instagr.am/p/'.$embed_id.'/embed/?enablejsapi=1"'. 
        ' style="'.
        'max-width:'.'100%'." !important".
        '; max-height:'.'100%'." !important".
        '; width:'.'100%'.
        '; height:'. '100%' .
        '; margin:0'.
        '; display:table-cell; vertical-align:middle;"'.
        'frameborder="0" scrolling="no" allowtransparency="false" allowfullscreen'. 
        '></iframe>';
        }
        $oembed_instagram_html .="</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      case 'EMBED_OEMBED_DAILYMOTION_VIDEO':
        $oembed_dailymotion_html ='<iframe ';
        if($embed_id!=''){
          $oembed_dailymotion_html .= ' src="' . '//www.dailymotion.com/embed/video/'. $embed_id . '?api=postMessage"';
        }
        foreach ($attrs as $attr => $value) {
          if(preg_match('/src/i', $attr)===0){
            if($attr != '' && $value != ''){
              $oembed_dailymotion_html .= ' '. $attr . '="'. $value . '"';
            }
          }
        }
        $oembed_dailymotion_html .= " ></iframe>";
        $html_to_insert .= $oembed_dailymotion_html;
        break;
      default:
        # code...
        break;
    }

    echo $html_to_insert;

  }


/**
 *
 * @return json_encode(array("error","error message")) on failure
 * @return json_encode(array of data of instagram user recent posts) on success 
 */
  public static function add_instagram_gallery($instagram_user, $client_id, $whole_post, $autogallery_image_number) {
    
    $instagram_user = sanitize_text_field(urldecode($instagram_user));
    $instagram_user_response = wp_remote_get("https://api.instagram.com/v1/users/search?q=".$instagram_user."&client_id=".$client_id."&count=1"); 
    if ( is_wp_error( $instagram_user_response ) ) {
      return json_encode(array("error", "cannot get Instagram user parameters"));
    }
    $user_json = wp_remote_retrieve_body($instagram_user_response);
    $response_code = json_decode($user_json)->meta->code;

    /*
    instagram API returns
    
    *wrong username
    {"meta":{"code":200},"data":[]}
    
    *wrong client_id
    {"meta":{"error_type":"OAuthParameterException","code":400,"error_message":"The client_id provided is invalid and does not match a valid application."}}
  
    */

    if($response_code != 200){
      return json_encode(array("error", json_decode($user_json)->meta->error_message));
    }
    

    if(!property_exists(json_decode($user_json), 'data')){
      return json_encode(array("error", "cannot get Instagram user parameters"));
    }
    if(empty(json_decode($user_json)->data)){
      return json_encode(array("error", "wrong Instagram username"));
    }
    $user_data = json_decode($user_json)->data[0];
    $user_id = $user_data->id;  
    
    
    $instagram_posts_response = wp_remote_get("https://api.instagram.com/v1/users/".$user_id."/media/recent/?client_id=".$client_id."&count=".$autogallery_image_number); 
    if ( is_wp_error( $instagram_posts_response ) ) {
      return json_encode(array("error", "cannot get Instagram user posts"));
    }
    $posts_json = wp_remote_retrieve_body($instagram_posts_response);
    $response_code = json_decode($posts_json)->meta->code;
    
    /*
    instagram API returns

    *private user
    '{"meta":{"error_type":"APINotAllowedError","code":400,"error_message":"you cannot view this resource"}}'
  
    */
    
    if($response_code != 200){
      return json_encode(array("error", json_decode($posts_json)->meta->error_message));
    }
    if(!property_exists(json_decode($posts_json), 'data')){
      return json_encode(array("error", "cannot get Instagram user posts data"));
    }
    /*
    if instagram user has no posts
    */
    if(empty(json_decode($posts_json)->data)){
      return json_encode(array("error", "Instagram user has no posts"));
    }
    

    $posts_array = json_decode($posts_json)->data;
    $instagram_album_data = array();
    if($whole_post==1){
      $post_flag ="post";
    }
    else{
      $post_flag =''; 
    }
    foreach ($posts_array as $post_data) {
      $url = $post_data ->link . $post_flag;
      
      $post_to_embed = json_decode(self::add_embed($url), true);
      
      /* if add_embed function did not indexed array because of error */
      if(!isset($post_to_embed[0]) ){
        array_push($instagram_album_data, $post_to_embed);
      }

    }
    
    return json_encode($instagram_album_data);
    
  }
  
/**
 *
 * @return array of galleries,
 * @return array(false, "error message"); 
 *  
 */  

  public static function check_instagram_galleries(){
    
    global $wpdb;
    $instagram_galleries = $wpdb->get_results( "SELECT id, gallery_type, gallery_source, update_flag, autogallery_image_number  FROM " . $wpdb->prefix . "wdi_gallery WHERE gallery_type='instagram' OR gallery_type='instagram_post'", OBJECT );
       
    $galleries_to_update = array();
    if($instagram_galleries){
      foreach ($instagram_galleries as $gallery) {
        if($gallery->update_flag == 'add' || $gallery->update_flag == 'replace'){
          array_push($galleries_to_update, $gallery);
        }
      }
      if(!empty($galleries_to_update)){
        return $galleries_to_update;
      }
      else{
        return array(false, "No instagram gallery has to be updated");
      }
    }
    else{
      return array(false,"There is no instagram gallery");
    }
  }

/**
 *
 * @return array(true, "refresh time"); 
 * @return array(false, "error message"); 
 *  
 */

  public static function refresh_instagram_gallery($gallery){
    global $wpdb;
    $id = $gallery->id;
    $type = $gallery->gallery_type;
    $update_flag = $gallery->update_flag;
    $autogallery_image_number = $gallery->autogallery_image_number;

    if($type=='instagram'){
      $whole_post = 0;
    }
    if($type=='instagram_post'){
      $whole_post = 1;
    }
    $source =$gallery->gallery_source;
    if(!$id || !$type || !$source){
      return array(false, "Gallery id, type or source are empty");
    }

    $get_client_id = $wpdb->get_results( "SELECT instagram_client_id FROM " . $wpdb->prefix . "wdi_option", OBJECT );
    if(!$get_client_id){
      return array(false, "Cannot get CLIENT_ID from the database");
    }
    $client_id = $get_client_id[0]->instagram_client_id;

    $images = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wdi_image WHERE gallery_id='" . $id . "' ", OBJECT);
    $images_count = sizeof($images);
    $new_images_data = self::add_instagram_gallery($source, $client_id, $whole_post, $autogallery_image_number);
    if(!$new_images_data){
      return array(false, "Cannot get instagram data");
    }
    $images_new = json_decode($new_images_data);
    if(empty($images_new)){
      return array(false, "Cannot get instagram data"); 
    }
    if($images_new[0] == "error"){
      return array(false, "Cannot get instagram data"); 
    }

    $images_count_new = sizeof($images_new);
    
    $images_update = array(); /*ids and orders of images existing in both arrays*/
    $images_insert = array(); /*data of new images*/
    $images_dated = array(); /*ids and orders of images not existing in the array of new images*/
    $new_order = 0; /*how many images should be added*/
    if($images_count!=0){
      $author = $images[0]->author; /* author is the same for the images in the gallery */
    }
    else{
      $author = 1; 
    }

    /*loops to compare new and existing images*/
    foreach ($images_new as $image_new) {
      $to_add = true;
      if($images_count != 0){
        foreach($images as $image){
          if($image_new->filename == $image->filename){
            /*if that image exist, do not update*/
            $to_add = false;
          }
        }
      }
      if($to_add){
        /*if image does not exist, insert*/
        $new_order++;
        $new_image_data = array(
          'gallery_id' => $id,
          'slug' => sanitize_title($image_new->name),
          'filename' => $image_new->filename,
          'image_url' => $image_new->url,
          'thumb_url' => $image_new->thumb_url,
          'description' => $image_new->description,
          'alt' => $image_new->name,
          'date' => $image_new->date_modified,
          'size' => $image_new->size,
          'filetype' => $image_new->filetype,
          'resolution' => $image_new->resolution,
          'author' => $author,
          'order' => $new_order,
          'published' => 1,
          'comment_count' => 0,
          'avg_rating' => 0,
          'rate_count' => 0,
          'hit_count' => 0,
          'redirect_url' => $image_new->redirect_url,
        );
        array_push($images_insert, $new_image_data);
      }
    }


    if($images_count != 0){
      foreach ($images as $image) {
        $is_dated = true;
        foreach($images_new as $image_new){
          if($image_new->filename == $image->filename){
            /* if that image exist, do not update */
            /* shift order by a number of new images */
            $image_update = array(
              'id' => $image->id ,
              'order'=> intval($image->order) + $new_order,
              "slug" => sanitize_title($image_new->name),
              "description" => $image_new->description,
              "alt" => $image_new->name,
              "date" => $image_new->date_modified);
            array_push($images_update, $image_update);
            $is_dated = false;
          }
        }
        if($is_dated){
        	$image_dated = array(
            'id' => $image->id ,
            'order'=> intval($image->order) + $new_order,
            );
          array_push($images_dated, $image_dated);
        }
      }
    }
    /*endof comparing loops*/
    
    $to_unpublish = true;
    if($update_flag == 'add'){
      $to_unpublish = false;
    }
    if($update_flag == 'replace'){
      $to_unpublish = true;
    }

    
    /*update old images*/
    if($images_count != 0){
      if($to_unpublish){
    		foreach ($images_dated as $image) {
    			$q = 'UPDATE ' .  $wpdb->prefix . 'wdi_image SET published=0, `order` ='.$image['order'].' WHERE `id`='.$image['id'];
          $wpdb->query($q);
    		}
    	}
    	else{
    		foreach ($images_dated as $image) {
          $q = 'UPDATE ' .  $wpdb->prefix . 'wdi_image SET `order` ='.$image['order'].' WHERE `id`='.$image['id'];
    			$wpdb->query($q);
    		}		
    	}

      foreach ($images_update as $image) {
        $save = $wpdb->update($wpdb->prefix . 'wdi_image', array(
          'order' => $image['order'],
          'slug' => $image['slug'],
          'description' => $image['description'],
          'alt' => $image['alt'],
          'date' => $image['date']
          ), array('id' => $image['id']));
      }
    }
  	/*add new images*/
  	foreach($images_insert as $image){
      $save = $wpdb->insert($wpdb->prefix . 'wdi_image', array(
        'gallery_id' => $image['gallery_id'],
        'slug' => $image['slug'],
        'filename' => $image['filename'],
        'image_url' => $image['image_url'],
        'thumb_url' => $image['thumb_url'],
        'description' => $image['description'],
        'alt' => $image['alt'],
        'date' => $image['date'],
        'size' => $image['size'],
        'filetype' => $image['filetype'],
        'resolution' => $image['resolution'],
        'author' => $image['author'],
        'order' => $image['order'],
        'published' => $image['published'],
        'comment_count' => $image['comment_count'],
        'avg_rating' => $image['avg_rating'],
        'rate_count' => $image['rate_count'],
        'hit_count' => $image['hit_count'],
        'redirect_url' => $image['redirect_url'],
      ), array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
      ));
   	}
    $time = date('d F Y, H:i');

    /*return time of last update*/
    return array(true, $time);

  }

  public static function get_autoupdate_interval(){
    global $wpdb;

    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdi_option WHERE id="%d"', 1));
    if(!isset($row)){
      return 30;
    }
    if(!isset($row->autoupdate_interval)){
      return 30;
    }
    $autoupdate_interval = $row->autoupdate_interval;
    return $autoupdate_interval;
  }



  


/**
 * @return user id by username
 * @return  flase on failure
 */
  public static function get_instagram_id_by_username($instagram_user) {
    
    $instagram_user_response = wp_remote_get('https://api.instagram.com/v1/users/search?q=' . $instagram_user . '&access_token=2276055961.145c5c2.c37b17e530f245a29716f748137c84a9'); 
    if ( is_wp_error( $instagram_user_response ) ) {
      return false;
    }
    $user_json = wp_remote_retrieve_body($instagram_user_response);
    $response_code = json_decode($user_json)->meta->code;

    /*
    instagram API returns
    
    *wrong username
    {"meta":{"code":200},"data":[]}
    
    *wrong client_id
    {"meta":{"error_type":"OAuthParameterException","code":400,"error_message":"The client_id provided is invalid and does not match a valid application."}}
  
    */

    if($response_code != 200){
      return false;
    }
    

    if(!property_exists(json_decode($user_json), 'data')){
      return false;
    }
    if(empty(json_decode($user_json)->data)){
      return false;
    }
    $user_data = json_decode($user_json)->data[0];
    $user_id = $user_data->id;  
    
    return $user_id;
    
  }




  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}