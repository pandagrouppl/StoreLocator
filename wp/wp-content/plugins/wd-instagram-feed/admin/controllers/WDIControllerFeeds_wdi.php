<?php
class WDIControllerFeeds_wdi {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $dataFormat;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
    $this->setDataFormat();
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = WDILibrary::get('task');
    $id = WDILibrary::get('current_id', 0);
    $message = WDILibrary::get('message');
    echo WDILibrary::message_id($message);
    if (method_exists($this, $task)) {
      check_admin_referer('nonce_wd', 'nonce_wd');
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  private function setDataFormat(){
    $this->dataFormat = array(
            '%s',/*feed_name*/
            '%s',/*feed_thumb*/
            '%s',/*thumb_user*/
            '%d',/*published*/
            '%d',/*theme_id*/

            '%s',/*feed_users*/
            '%s',/*feed_display_view*/
            '%s',/*sort_images_by*/
            '%s',/*display_order*/
            '%d',/*follow_on_instagram_btn*/

            '%d',/*display_header*/
            '%d',/*number_of_photos*/
            '%d',/*load_more_number*/
            '%d',/*'pagination_per_page_number'*/
            '%d',/*'pagination_preload_number'*/

            '%d',/*image_browser_preload_number*/
            '%d',/*image_browser_load_number*/
            '%d',/*number_of_columns*/
            '%d',/*resort_after_load_more*/
            '%d',/*show_likes*/

            '%d',/*show_description*/
            '%d',/*show_comments*/
            '%d',/*show_usernames*/
            '%d',/*display_user_info*/
            '%d',//'display_user_post_follow_number'

            '%d',/*show_full_description*/
            '%d',/*disable_mobile_layout*/
            '%s',/*feed_type*/
            '%s',/*feed_item_onclick*/
            '%d',//'popup_fullscreen'=>'bool',

            '%d',//'popup_width'=>'number',
            '%d',//'popup_height'=>'number',
            '%s',//'popup_type'=>'string',
            '%d',//'popup_autoplay'=>'bool',
            '%d',//'popup_interval'=>'number',

            '%d',//'popup_enable_filmstrip'=>'bool',
            '%d',//'popup_filmstrip_height'=>'number',
            '%d',//'autohide_lightbox_navigation'=>'bool',
            '%d',//'popup_enable_ctrl_btn'=>'bool',
            '%d',//'popup_enable_fullscreen'=>'bool',

            '%d',//'popup_enable_info'=>'bool',
            '%d',//'popup_info_always_show'=>'bool',
            '%d',//'popup_info_full_width'=>'bool',
            '%d',//'popup_enable_comment'=>'bool',
            '%d',//'popup_enable_fullsize_image'=>'bool',

            '%d',//'popup_enable_download'=>'bool',
            '%d',//popup_enable_share_buttons=>'bool',
            '%d',//'popup_enable_facebook'=>'bool',
            '%d',//'popup_enable_twitter'=>'bool',
            '%d',//'popup_enable_google'=>'bool',

            '%d',//'popup_enable_pinterest'=>'bool',
            '%d',//'popup_enable_tumblr'=>'bool',
            '%d',//'show_image_counts'=>'bool',
            '%d',//'enable_loop'=>'bool'
            '%d',//popup_image_right_click=>'bool'

            '%s',//'conditional_filters' => 'string',
            '%s',//'conditional_filter_type' => 'string'
            '%d',/*show_username_on_thumb*/
            '%d',//'conditional_filter_enable'=>'0',
            '%s',//'liked_feed' => 'string'
            '%d',//'mobile_breakpoint'=>'640',
            

            );
  }
  private function display() {
    require_once (WDI_DIR . "/admin/models/WDIModelFeeds_wdi.php");
    $model = new WDIModelFeeds_wdi();

    require_once (WDI_DIR . "/admin/views/WDIViewFeeds_wdi.php");
    $view = new WDIViewFeeds_wdi($model);
    $view->display();
  }

  private function add() {
    require_once WDI_DIR . "/admin/models/WDIModelFeeds_wdi.php";
    $model = new WDIModelFeeds_wdi();

    require_once WDI_DIR . "/admin/views/WDIViewFeeds_wdi.php";
    $view = new WDIViewFeeds_wdi($model);
    $view->edit(0);
  }


   private function edit($customId = '') {

    require_once WDI_DIR . "/admin/models/WDIModelFeeds_wdi.php";
    $model = new WDIModelFeeds_wdi();

    require_once WDI_DIR . "/admin/views/WDIViewFeeds_wdi.php";
    $view = new WDIViewFeeds_wdi($model);
    if($customId != ''){
      $id = $customId;
    }else{
          $id = ((isset($_POST['current_id']) && esc_html(stripslashes($_POST['current_id'])) != '') ? esc_html(stripslashes($_POST['current_id'])) : 0);
    }
    $view->edit($id);   
  }

  // private function save() {
  //   $page = WDILibrary::get('page');
  //   WDILibrary::wdi_spider_redirect(add_query_arg(array('page' => $page, 'task' => 'display', 'message' => 1), admin_url('admin.php')));
  // }

  private function apply() {
    $this->save_slider_db();
    $this->save_slide_db();
    $this->edit();
  }

  private function save_feed(){
    require_once WDI_DIR . "/admin/models/WDIModelFeeds_wdi.php";
    $model = new WDIModelFeeds_wdi();

    $settings = ($_POST[WDI_FSN]);
    $defaults = $model->wdi_get_feed_defaults();

    $settings = $this->sanitize_input($settings,$defaults);
    $settings = wp_parse_args( $settings, $defaults );

    $settings = $this->check_settings($settings);
    

    global $wpdb;
    $action = $_POST['add_or_edit'];
    
    
    if($action==''){
        $wpdb->insert($wpdb->prefix. WDI_FEED_TABLE, $settings,$this->dataFormat);
        if($wpdb->insert_id == false){
        $this->message(__('Cannot Write on database. Check database permissions.',"wd-instagram-feed"),'error');
      }
    }else{
        $msg = $wpdb->update($wpdb->prefix. WDI_FEED_TABLE, $settings, array('id'=>$action), $this->dataFormat,array('%d'));
        if($msg == false){
         $this->message(__("You have not made new changes","wd-instagram-feed"),'notice');
        }else{
          $this->message(__("Successfully saved","wd-instagram-feed"),"updated");
        }
    }
    $this->display();
  }

  private function apply_changes(){
    require_once WDI_DIR . "/admin/models/WDIModelFeeds_wdi.php";
    $model = new WDIModelFeeds_wdi();

    $settings = ($_POST[WDI_FSN]);
    $defaults = $model->wdi_get_feed_defaults();

    $settings = $this->sanitize_input($settings,$defaults);
    $settings = wp_parse_args( $settings, $defaults );
    $settings = $this->check_settings($settings);
    global $wpdb;
    $action = $_POST['add_or_edit'];
    
    
    if($action==''){
        $wpdb->insert($wpdb->prefix. WDI_FEED_TABLE, $settings,$this->dataFormat);
         if($wpdb->insert_id == false){
          $this->message(__("Cannot Write on database. Check database permissions.","wd-instagram-feed"),'error');
          $this->display();
        }else{
           $this->edit($wpdb->insert_id);
        }
    }else{
        $msg = $wpdb->update($wpdb->prefix. WDI_FEED_TABLE, $settings, array('id'=>$action), $this->dataFormat,array('%d'));
         if($msg == false){
          $this->message(__("You have not made new changes","wd-instagram-feed"),'notice');
          $this->edit();
        }else{
          $this->message(__("Changes have been successfully applied","wd-instagram-feed"),"updated");
          $this->edit();
        }
        
    }
    
  }

  private function cancel(){
    $this->display();
  }

   private function reset_changes(){
    require_once WDI_DIR . "/admin/models/WDIModelFeeds_wdi.php";
    $model = new WDIModelFeeds_wdi();
    $defaults = $model->wdi_get_feed_defaults();
    $defaults = $this->check_settings($defaults);
    global $wpdb;
    $action = $_POST['add_or_edit'];
    
    
    if($action==''){
        $wpdb->insert($wpdb->prefix. WDI_FEED_TABLE, $defaults,$this->dataFormat);
        if($wpdb->insert_id == false){
          $this->message(__('Cannot Write on database. Check database permissions.',"wd-instagram-feed"),'error');
          $this->display();
        }else{
           $this->edit($wpdb->insert_id);
        }
    }else{
        $msg = $wpdb->update($wpdb->prefix. WDI_FEED_TABLE, $defaults, array('id'=>$action), $this->dataFormat,array('%d'));
        if($msg == false){
          $this->message(__("You have not made new changes","wd-instagram-feed"),'notice');
+         $this->edit();
        }else{
          $this->message(__("Feed successfully reseted","wd-instagram-feed"),"updated");
          $this->edit();
        }
    }
    
  }

  private function duplicate_all($id) {
    global $wpdb;
    $feed_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . WDI_FEED_TABLE);
    foreach ($feed_ids_col as $slider_id) {
      if (isset($_POST['check_' . $slider_id])) {
        $this->duplicate_tabels($slider_id);
      }
    }
    echo WDILibrary::message(__('Item Succesfully Duplicated.', "wd-instagram-feed"), 'updated');
    $this->display();
  }

  private function duplicate_tabels($feed_id) {
    global $wpdb;
    if ($feed_id) {
      $feed_row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . WDI_FEED_TABLE.' where id="%d"', $feed_id));    
    }
    
    if ($feed_row) {
      $duplicate_values = WDILibrary::objectToArray($feed_row);
      unset($duplicate_values['id']);
      $save = $wpdb->insert($wpdb->prefix . WDI_FEED_TABLE,$duplicate_values, $this->dataFormat);
      $new_slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . WDI_FEED_TABLE);
    }
    return $new_slider_id;
  }

  private function delete($id) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . WDI_FEED_TABLE. ' WHERE id="%d"', $id);
    if ($wpdb->query($query)) {
      echo WDILibrary::message(__('Item Succesfully Deleted.',"wd-instagram-feed"), 'updated');
    }
    else {
      echo WDILibrary::message(__('Error. Please install plugin again.', "wd-instagram-feed"), 'error');
    }
    $this->display();
  }
  
  private function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $feed_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . WDI_FEED_TABLE);
    foreach ($feed_ids_col as $slider_id) {
      if (isset($_POST['check_' . $slider_id]) || isset($_POST['check_all_items'])) {
        $flag = TRUE;
        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . WDI_FEED_TABLE.' WHERE id="%d"', $slider_id);
        $wpdb->query($query);
      }
    }
    if ($flag) {
      echo WDILibrary::message(__('Items Succesfully Deleted.', "wd-instagram-feed"), 'updated');
    }
    else {
      echo WDILibrary::message(__('You must select at least one item.', "wd-instagram-feed"), 'error');
    }
    $this->display();
  }

  private function publish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array('published' => 1), array('id' => $id));
    if ($save !== FALSE) {
      echo WDILibrary::message(__('Item Succesfully Published.', "wd-instagram-feed"), 'updated');
    }
    else {
      echo WDILibrary::message(__('Error. Please install plugin again.', "wd-instagram-feed"), 'error');
    }
    $this->display();
  }
  
  private function publish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . WDI_FEED_TABLE.' SET published=1');
      $flag = TRUE;
    }
    else {
      $feed_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . WDI_FEED_TABLE);
      foreach ($feed_ids_col as $slider_id) {
        if (isset($_POST['check_' . $slider_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array('published' => 1), array('id' => $slider_id));
        }
      }
    }
    if ($flag) {
      echo WDILibrary::message(__('Items Succesfully Published.', "wd-instagram-feed"), 'updated');
    }
    else {
      echo WDILibrary::message(__('You must select at least one item.', "wd-instagram-feed"), 'error');
    }
    $this->display();
  }

  private function unpublish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array('published' => 0), array('id' => $id));
    if ($save !== FALSE) {
      echo WDILibrary::message(__('Item Succesfully Unpublished.', "wd-instagram-feed"), 'updated');
    }
    else {
      echo WDILibrary::message(__('Error. Please install plugin again.', "wd-instagram-feed"), 'error');
    }
    $this->display();
  }
  
  private function unpublish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . WDI_FEED_TABLE.' SET published=0');
      $flag = TRUE;
    }
    else {
      $feed_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . WDI_FEED_TABLE);
      foreach ($feed_ids_col as $slider_id) {
        if (isset($_POST['check_' . $slider_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . WDI_FEED_TABLE, array('published' => 0), array('id' => $slider_id));
        }
      }
    }
    if ($flag) {
      echo WDILibrary::message(__('Items Succesfully Unpublished.', "wd-instagram-feed"), 'updated');
    }
    else {
      echo WDILibrary::message(__('You must select at least one item.', "wd-instagram-feed"), 'error');
    }
    $this->display();
  }

private function check_settings($settings){
      
       if($settings['feed_users']){
            $settings['feed_users'] = json_decode($settings['feed_users']);
            $settings['feed_users'] = json_encode(array($settings['feed_users'][0])); 
      };
      if(intval($settings['theme_id']) > 1){
          $settings['theme_id'] = '1';
      };
      if($settings['feed_display_view'] === 'infinite_scroll'){
        $settings['feed_display_view'] = 'load_more_btn';
      }
      if($settings['feed_type'] === 'masonry' || $settings['feed_type'] === 'blog_style'){
        $settings['feed_type'] = 'thumbnails';
      }
      if($settings['popup_enable_filmstrip'] == '1'){
        $settings['popup_enable_filmstrip'] = '0';
      }
      if($settings['popup_filmstrip_height'] != '70'){
        $settings['popup_filmstrip_height'] = '70';
      }
      if($settings['popup_enable_comment'] == '1'){
        $settings['popup_enable_comment'] = '0';
      }
      if($settings['popup_enable_share_buttons'] == '1'){
        $settings['popup_enable_share_buttons'] = '0';
      }

      if($settings['popup_info_always_show'] == '1'){
        $settings['popup_info_always_show'] = '0';
      }

      if($settings['popup_info_full_width'] == '1'){
        $settings['popup_info_full_width'] = '0';
      }

      if($settings['popup_enable_info'] == '1'){
        $settings['popup_enable_info'] = '0';
      }
      if($settings['show_likes'] == '1'){
        $settings['show_likes'] = '0';
      }

      if($settings['show_description'] == '1'){
        $settings['show_description'] = '0';
      }

      if($settings['show_comments'] == '1'){
        $settings['show_comments'] = '0';
      }

      if($settings['show_username_on_thumb'] == '1'){
        $settings['show_username_on_thumb'] = '0';
      }

      if($settings['conditional_filter_enable'] == '1'){
        $settings['conditional_filter_enable'] = '0';
      }
  if($settings['liked_feed'] == 'liked'){
    $settings['liked_feed'] = 'userhash';
  }
    return $settings;
}


private function sanitize_input($settings,$defaults){

    require_once WDI_DIR . "/admin/models/WDIModelFeeds_wdi.php";
    $model = new WDIModelFeeds_wdi();
    $sanitize_types=$model->get_sanitize_types();
    $sanitized_output = array();
    foreach ($settings as $setting_name => $value) {
       
        switch($sanitize_types[$setting_name]){
        case 'string':{
            $sanitized_val=$this->sanitize_string($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'number':{
            $sanitized_val=$this->sanitize_number($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'bool':{
            $sanitized_val=$this->sanitize_bool($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'url':{
            $sanitized_val=$this->sanitize_url($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
    }
    }
    return $sanitized_output;
}

private function sanitize_bool($value,$default){
    if($value == 1 || $value == 0){
           return $value;
    }
    else{
        return $default;
    }
}
private function sanitize_string($value,$default){
    $sanitized_val = strip_tags(stripslashes($value));
    if($sanitized_val == ''){
        return $default;
    }else{
        return $sanitized_val;
    }
}
private function sanitize_number($value,$default){
    if(is_numeric($value) && $value>0){
        return $value;
    }else{
        return $default;
    }
}
private function sanitize_url($value,$default){
    if (!filter_var($value, FILTER_VALIDATE_URL) === false) {
    return $value;
    } else {
    return $default;
   }
}

private function message($text,$type){
   require_once(WDI_DIR . '/framework/WDILibrary.php');
   echo WDILibrary::message($text, $type);
}

}
?>