<?php

class WDIModelGalleryBox {
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
  public function get_theme_row_data($id) {
    global $wpdb;
    if ($id) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . WDI_THEME_TABLE . ' WHERE id="%d"', $id));
    }
    else {      
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . WDI_THEME_TABLE .' WHERE default_theme="%d"', 1));
    }

    return WDILibrary::objectToArray($row);
  }
public function get_feed_row_data($id) {
   global $wpdb;
   $row = $this->check_settings($wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . WDI_FEED_TABLE . ' WHERE id="%d"', $id)));
   return WDILibrary::objectToArray($row);
}
  public function get_option_row_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdi_option WHERE id="%d"', 1));
    return $row;
  }

  public function get_comment_rows_data($image_id) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdi_image_comment WHERE image_id="%d" AND published=1 ORDER BY `id` DESC', $image_id));
    return $row;
  }

  public function get_image_rows_data($gallery_id, $sort_by, $order_by = 'asc') {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(t1.' . $sort_by . ' AS SIGNED) ';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype') && ($sort_by != 'filename')) {
      $sort_by = 't1.`order`';
    }
    if (preg_replace('/\s+/', '', $order_by) != 'asc') {
      $order_by = 'desc';
    }
    $row = $wpdb->get_results($wpdb->prepare('SELECT t1.*,t2.rate FROM ' . $wpdb->prefix . 'wdi_image as t1 LEFT JOIN (SELECT rate, image_id FROM ' . $wpdb->prefix . 'wdi_image_rate WHERE ip="%s") as t2 ON t1.id=t2.image_id WHERE t1.published=1 AND t1.gallery_id="%d" ORDER BY ' . $sort_by . ' ' . $order_by, $_SERVER['REMOTE_ADDR'], $gallery_id));
    
    return $row;
  }

  public function get_image_rows_data_tag($tag_id, $sort_by, $order_by = 'asc') {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(' . $sort_by . ' AS SIGNED) ';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype')) {
      $sort_by = '`order`';
    }
    if (preg_replace('/\s+/', '', $order_by) != 'asc') {
      $order_by = 'desc';
    }
    $row = $wpdb->get_results($wpdb->prepare('SELECT t1.*,t2.rate FROM (SELECT image.* FROM ' . $wpdb->prefix . 'wdi_image as image INNER JOIN ' . $wpdb->prefix . 'wdi_image_tag as tag ON image.id=tag.image_id WHERE image.published=1 AND tag.tag_id="%d" ORDER BY  ' . $sort_by . ' ' . $order_by. ') as t1 LEFT JOIN (SELECT rate, image_id FROM ' . $wpdb->prefix . 'wdi_image_rate WHERE ip="%s") as t2 ON t1.id=t2.image_id ', $tag_id, $_SERVER['REMOTE_ADDR']));
    return $row;
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  private function check_settings($settings){
      $settings = WDILibrary::objectToArray($settings);
      if(isset($settings['feed_users']) && count(explode(',', $settings['feed_users']))>1){
         $settings['feed_users'] = explode(',', $settings['feed_users']);
          $settings['feed_users'] =  $settings['feed_users'][0];
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
      if(isset($settings['popup_filmstrip_height']) && $settings['popup_filmstrip_height'] != '70'){
        $settings['popup_filmstrip_height'] = '70';
      }
      if(isset($settings['popup_enable_comment']) && $settings['popup_enable_comment'] == '1'){
        $settings['popup_enable_comment'] = '0';
      }
      if(isset($settings['popup_enable_share_buttons']) && $settings['popup_enable_share_buttons'] == '1'){
        $settings['popup_enable_share_buttons'] = '0';
      }
      if(isset($settings['popup_info_always_show']) && $settings['popup_info_always_show'] == '1'){
        $settings['popup_info_always_show'] = '0';
      }

      if(isset($settings['popup_info_full_width']) && $settings['popup_info_full_width'] == '1'){
        $settings['popup_info_full_width'] = '0';
      }

      if(isset($settings['popup_enable_info']) && $settings['popup_enable_info'] == '1'){
        $settings['popup_enable_info'] = '0';
      }


      if(isset($settings['show_likes']) && $settings['show_likes'] == '1'){
        $settings['show_likes'] = '0';
      }

      if(isset($settings['show_description']) && $settings['show_description'] == '1'){
        $settings['show_description'] = '0';
      }

      if(isset($settings['show_comments']) && $settings['show_comments'] == '1'){
        $settings['show_comments'] = '0';
      }
    $settings = WDILibrary::arrayToObject($settings);
    return $settings;
}
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}