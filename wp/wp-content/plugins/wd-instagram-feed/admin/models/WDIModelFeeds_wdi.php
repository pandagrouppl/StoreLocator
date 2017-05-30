<?php

class WDIModelFeeds_wdi {
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
   public function get_slides_row_data($slider_id) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . WDI_FEED_TABLE. " WHERE slider_id='%d' ORDER BY `order` ASC", $slider_id));
   if ($rows) {
        //  $row->image_url = $row->image_url ? $row->image_url : WD_S_URL . '/images/no-image.png';
        //  $row->thumb_url = $row->thumb_url ? $row->thumb_url : WD_S_URL . '/images/no-image.png';
    }
    return $row;
  }


  public function get_rows_data() {
    global $wpdb;
    $where = ((isset($_POST['search_value'])) ? 'WHERE feed_name LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"' : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc']) && esc_html($_POST['asc_or_desc']) == 'desc') ? 'desc' : 'asc');
    $order_by_arr = array('id', 'feed_name', 'published');
    $order_by = ((isset($_POST['order_by']) && in_array(esc_html($_POST['order_by']), $order_by_arr)) ? esc_html($_POST['order_by']) : 'id');
    $order_by = ' ORDER BY `' . $order_by . '` ' . $asc_or_desc;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $query = "SELECT * FROM " . $wpdb->prefix . WDI_FEED_TABLE .' '. $where . $order_by . " LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
    return $rows;
  }

  public function get_slider_prev_img($slider_id) { 
    global $wpdb;
    $prev_img_url = $wpdb->get_var($wpdb->prepare("SELECT `feed_thumb` FROM " . $wpdb->prefix . WDI_FEED_TABLE . " WHERE id='%d'", $slider_id));
    $prev_img_url = $prev_img_url ? $prev_img_url : WDI_URL . '/images/no-image.png';
    return $prev_img_url;
  }

  public function page_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE feed_name LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
    $total = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . WDI_FEED_TABLE. ' ' . $where);
    $page_nav['total'] = $total;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / 20 + 1);
    return $page_nav;
  }

  public static function wdi_get_feed_defaults(){
  global $wdi_options;
  global $wpdb;
  $query = $wpdb->prepare("SELECT id FROM ". $wpdb->prefix.WDI_THEME_TABLE." WHERE default_theme='%d'",1);
  $default_theme = WDILibrary::objectToArray($wpdb->get_results($query));
  $default_user = new stdClass();
  $default_user->username = $wdi_options['wdi_user_name'];
  $default_user->id = $wdi_options['wdi_user_id'];
  $settings = array(
    'thumb_user'=> $wdi_options['wdi_user_name'],
    'feed_name' => 'Sample Feed',
    'feed_thumb'=>  WDI_URL . '/images/no-image.png',
    'published' => '1',
    'theme_id'=> $default_theme[0]['id'],
    'feed_users'=>  json_encode(array($default_user)),
    'feed_display_view' =>'load_more_btn',
    'sort_images_by' => 'date',
    'display_order'=>  'desc',
    'follow_on_instagram_btn' => '1',
    'display_header'=>  '0',
    'number_of_photos'=>  '20',
    'load_more_number' => '4',
    'pagination_per_page_number'=>'12',
    'pagination_preload_number'=>'10',
    'image_browser_preload_number'=>'10',
    'image_browser_load_number'=>'10',
    'number_of_columns'=>  '4',
    'resort_after_load_more' => '0',
    'show_likes'=>  '0',
    'show_description'=> '0' ,
    'show_comments'=>  '0',

    'show_usernames' =>'1',
    'display_user_info'=>'1',
    'display_user_post_follow_number' => '1',
    'show_full_description'=>'1',
    'disable_mobile_layout'=>'0',
    'feed_type' => 'thumbnails',
    'feed_item_onclick' => 'lightbox',
    //lightbox defaults

    'popup_fullscreen'=>'0',
    'popup_width'=>'640',
    'popup_height'=>'640',
    'popup_type'=>'none',
    'popup_autoplay'=>'0',
    'popup_interval'=>'5',
    'popup_enable_filmstrip'=>'0',
    'popup_filmstrip_height'=>'70',
    'autohide_lightbox_navigation'=>'1',
    'popup_enable_ctrl_btn'=>'1',
    'popup_enable_fullscreen'=>'1',
    'popup_enable_info'=>'0',
    'popup_info_always_show'=>'0',
    'popup_info_full_width'=>'0',
    'popup_enable_comment'=>'0',
    'popup_enable_fullsize_image'=>'1',
    'popup_enable_download'=>'0',
    'popup_enable_share_buttons'=>'0',
    'popup_enable_facebook'=>'0',
    'popup_enable_twitter'=>'0',
    'popup_enable_google'=>'0',
    'popup_enable_pinterest'=>'0',
    'popup_enable_tumblr'=>'0',
    'show_image_counts'=>'0',
    'enable_loop'=>'1',
    'popup_image_right_click'=> '1',

    'conditional_filters' => '',
    'conditional_filter_type' => 'none',
    'show_username_on_thumb'=>'0',
    'conditional_filter_enable'=>'0',
    'liked_feed' => 'userhash',
    'mobile_breakpoint' => '640',
    );
  return $settings;
}

 public function get_sanitize_types(){
  $sanitize_types = array(
    'thumb_user'=>'string',
    'feed_name' => 'string',
    'feed_thumb'=>  'url',
    'published' => 'bool',
    'theme_id'=> 'number'/*$options['wdi_default_theme']*/,
    'feed_users'=>  'string',
    'feed_display_view' =>'string',
    'sort_images_by' => 'string',
    'display_order'=>  'string',
    'follow_on_instagram_btn' => 'bool',
    'display_header'=>  'bool',
    'number_of_photos'=>  'number',
    'load_more_number' => 'number',
    'pagination_per_page_number'=>'number',
    'pagination_preload_number'=>'number',
    'image_browser_preload_number'=>'number',
    'image_browser_load_number'=>'number',
    'number_of_columns'=>  'number',
    'resort_after_load_more'=>'bool',
    'show_likes'=>  'bool',
    'show_description'=> 'bool' ,
    'show_comments'=>  'bool',
    'show_username_on_thumb'=>'bool',
    'show_usernames'=>'bool',
    'display_user_info'=>'bool',
    'display_user_post_follow_number'=>'bool',
    'show_full_description'=>'bool',
    'disable_mobile_layout'=>'bool',
    'feed_type' => 'string',
    'feed_item_onclick' => 'string',
    //lightbox defaults

    'popup_fullscreen'=>'bool',
    'popup_width'=>'number',
    'popup_height'=>'number',
    'popup_type'=>'string',
    'popup_autoplay'=>'bool',
    'popup_interval'=>'number',
    'popup_enable_filmstrip'=>'bool',
    'popup_filmstrip_height'=>'number',
    'autohide_lightbox_navigation'=>'bool',
    'popup_enable_ctrl_btn'=>'bool',
    'popup_enable_fullscreen'=>'bool',
    'popup_enable_info'=>'bool',
    'popup_info_always_show'=>'bool',
    'popup_info_full_width'=>'bool',
    'popup_enable_comment'=>'bool',
    'popup_enable_fullsize_image'=>'bool',
    'popup_enable_download'=>'bool',
    'popup_enable_share_buttons'=>'bool',
    'popup_enable_facebook'=>'bool',
    'popup_enable_twitter'=>'bool',
    'popup_enable_google'=>'bool',
    'popup_enable_pinterest'=>'bool',
    'popup_enable_tumblr'=>'bool',
    'show_image_counts'=>'bool',
    'enable_loop'=>'bool',
    'popup_image_right_click'=>'bool',
    
    'conditional_filters' => 'string',
    'conditional_filter_enable'=>'number',
    'conditional_filter_type' => 'string',
    'liked_feed' => 'string',
    'mobile_breakpoint' => 'number',
    );
  return $sanitize_types;
}

public function get_feed_row($current_id){
  global $wpdb;
  $feed_row = $this->check_settings($wpdb->get_row($wpdb->prepare("SELECT * FROM ". $wpdb->prefix.WDI_FEED_TABLE. " WHERE id ='%d' ",$current_id)));
  return $feed_row;
}
private function check_settings($settings){

 
      $settings = WDILibrary::objectToArray($settings);

      if(isset($settings['feed_users'])){
            $settings['feed_users'] = json_decode($settings['feed_users']);
            $settings['feed_users'] = json_encode(array($settings['feed_users'][0])); 
      };
      if(isset($settings['theme_id']) && intval($settings['theme_id']) > 1){
          $settings['theme_id'] = '1';
      };
      if(isset($settings['feed_display_view']) && $settings['feed_display_view'] === 'infinite_scroll'){
        $settings['feed_display_view'] = 'load_more_btn';
      }
      if(isset($settings['feed_type']) && $settings['feed_type'] === 'masonry' || $settings['feed_type'] === 'blog_style'){
        $settings['feed_type'] = 'thumbnails';
      }
      if(isset($settings['popup_enable_filmstrip']) && $settings['popup_enable_filmstrip'] == '1'){
        $settings['popup_enable_filmstrip'] = '0';
      }

      if(isset($settings['show_username_on_thumb']) && $settings['show_username_on_thumb'] == '1'){
        $settings['show_username_on_thumb'] = '0';
      }

      if(isset($settings['conditional_filter_enable']) && $settings['conditional_filter_enable'] == '1'){
        $settings['conditional_filter_enable'] = '0';
      }

      if(isset($settings['popup_filmstrip_height']) && $settings['popup_filmstrip_height'] != '70'){
        $settings['popup_filmstrip_height'] = '70';
      }
      if(isset($settings['popup_enable_comment']) && $settings['popup_enable_comment'] == '1'){
        $settings['popup_enable_comment'] = '0';
      }
      if(isset($settings['popup_enable_share_buttons']) && $settings['popup_enable_share_buttons'] == '1'){
        $settings['popup_enable_share_buttons'] = '0';
      }
    $settings = WDILibrary::arrayToObject($settings);
    return $settings;
}

}
  ?>