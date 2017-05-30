<?php
class WDIControllerThemes_wdi {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $data_format;
  private $view,$model;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
    $this->setDataFormat();
    require_once (WDI_DIR . "/admin/models/WDIModelThemes_wdi.php");
    $this->model = new WDIModelThemes_wdi();

    require_once (WDI_DIR . "/admin/views/WDIViewThemes_wdi.php");
    $this->view = new WDIViewThemes_wdi($this->model);
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

  public function display() {
    require_once (WDI_DIR . "/admin/models/WDIModelThemes_wdi.php");
    $model = new WDIModelThemes_wdi();

    require_once (WDI_DIR . "/admin/views/WDIViewThemes_wdi.php");
    $view = new WDIViewThemes_wdi($model);
    $view->display();
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  private function setDataFormat(){
  $this->data_format = array(
          '%s',/*theme_name*/
          '%s',/*default_theme*/
          '%s',/*feed_container_bg_color*/
          '%s',/*feed_wrapper_width*/
          '%s',/*feed_container_width*/
          '%s',/*feed_wrapper_bg_color*/
          '%s',/*active_filter_bg_color*/
          '%s',/*header_margin*/
          '%s',/*header_padding*/
          '%s',/*header_border_size*/
          '%s',/*header_border_color*/
          '%s',/*header_position*/
          '%s',/*header_img_width*/
          '%s',/*header_border_radius*/
          '%s',/*header_text_padding*/
          '%s',/*header_text_color*/
          '%d',/*header_font_weight*/
          '%s',/*header_text_font_size*/
          '%s',/*header_text_font_style*/
          '%s',/*'follow_btn_border_radius'=>'number'*/
          '%s',/*'follow_btn_padding'=>'number'*/
          '%d',/*'follow_btn_margin'=>'number'*/
          '%s',//'follow_btn_bg_color'=>'color',
          '%s',//'follow_btn_border_color'=>'color',
          '%s',//'follow_btn_text_color'=>'color',
          '%d',//'follow_btn_font_size'=>'number',
          '%s',//'follow_btn_border_hover_color'=>'color',
          '%s',//'follow_btn_text_hover_color'=>'color',
          '%s',//'follow_btn_background_hover_color'=>'color',
          
          '%s',/*user_horizontal_margin*/
          '%s',/*user_padding*/
          '%s',/*user_border_size*/
          '%s',/*user_border_color*/
          '%d',//'user_img_width'
          '%d',/*user_border_radius*/
          '%s',/*user_background_color*/
          '%s',/*users_border_size*/
          '%s',/*users_border_color*/
          '%s',/*users_background_color*/
          '%s',//users_text_color
          '%d',//users_font_weight
          '%s',//users_text_font_size
          '%s',//users_text_font_style
          '%s',//user_description_font_size
          '%s',//'lightbox_overlay_bg_color'=>'color',
          '%d',//'lightbox_overlay_bg_transparent'=>'number_max_100',
          '%s',//'lightbox_bg_color'=>'color',
          '%d',//'lightbox_ctrl_btn_height'=>'number',
          '%d',//'lightbox_ctrl_btn_margin_top'=>'number',
          '%d',//'lightbox_ctrl_btn_margin_left'=>'number',
          '%s',//'lightbox_ctrl_btn_pos'=>'string',
          '%s',//'lightbox_ctrl_cont_bg_color'=>'color',
          '%d',//'lightbox_ctrl_cont_border_radius'=>'number',
          '%d',//'lightbox_ctrl_cont_transparent'=>'number_max_100',
          '%s',//'lightbox_ctrl_btn_align'=>'position',
          '%s',//'lightbox_ctrl_btn_color'=>'color',
          '%d',//'lightbox_ctrl_btn_transparent'=>'number_max_100',
          '%d',//'lightbox_toggle_btn_height'=>'number',
          '%d',//'lightbox_toggle_btn_width'=>'number',
          '%d',//'lightbox_close_btn_border_radius'=>'number',
          '%d',//'lightbox_close_btn_border_width'=>'number',
          '%s',//'lightbox_close_btn_border_style'=>'string',
          '%s',//'lightbox_close_btn_border_color'=>'color',
          '%s',//'lightbox_close_btn_box_shadow'=>'css_box_shadow',
          '%s',//'lightbox_close_btn_bg_color'=>'color',
          '%d',//'lightbox_close_btn_transparent'=>'number_max_100',
          '%d',//'lightbox_close_btn_width'=>'number',
          '%d',//'lightbox_close_btn_height'=>'number',
          '%d',//'lightbox_close_btn_top'=>'number_neg',
          '%d',//'lightbox_close_btn_right'=>'number_neg',
          '%d',//'lightbox_close_btn_size'=>'number',
          '%s',//'lightbox_close_btn_color'=>'color',
          '%s',//'lightbox_close_btn_full_color'=>'color',
          '%s',//'lightbox_close_btn_hover_color'=>'color'
          '%s',//'lightbox_comment_share_button_color'=>'color',
          '%s',//'lightbox_rl_btn_style'=>'string',
          '%s',//'lightbox_rl_btn_bg_color'=>'color',
          '%d',//'lightbox_rl_btn_transparent'=>'number_max_100',
          '%s',//'lightbox_rl_btn_box_shadow'=>'css_box_shadow',
          '%d',//'lightbox_rl_btn_height'=>'number',
          '%d',//'lightbox_rl_btn_width'=>'number',
          '%d',//'lightbox_rl_btn_size'=>'number',
          '%s',//'lightbox_close_rl_btn_hover_color'=>'color',
          '%s',//'lightbox_rl_btn_color'=>'color',
          '%d',//'lightbox_rl_btn_border_radius'=>'number',
          '%d',//'lightbox_rl_btn_border_width'=>'number',
          '%s',//'lightbox_rl_btn_border_style'=>'string',
          '%s',//'lightbox_rl_btn_border_color'=>'color',
          '%s',//'lightbox_filmstrip_pos'=>'position',
          '%s',//'lightbox_filmstrip_thumb_margin'=>'length_multi',
          '%d',//'lightbox_filmstrip_thumb_border_width'=>'number',
          '%s',//'lightbox_filmstrip_thumb_border_style'=>'string',
          '%s',//'lightbox_filmstrip_thumb_border_color'=>'color',
          '%d',//'lightbox_filmstrip_thumb_border_radius'=>'number',
          '%d',//'lightbox_filmstrip_thumb_active_border_width'=>'number',
          '%s',//'lightbox_filmstrip_thumb_active_border_color'=>'color',
          '%s',//'lightbox_filmstrip_thumb_deactive_transparent'=>'number_max_100',
          '%d',//'lightbox_filmstrip_rl_btn_size'=>'number',
          '%s',//'lightbox_filmstrip_rl_btn_color'=>'color',
          '%s',//'lightbox_filmstrip_rl_bg_color'=>'color',
          '%s',//'lightbox_info_pos'=>'position',
          '%s',//'lightbox_info_align'=>'string',
          '%s',//'lightbox_info_bg_color'=>'color',
          '%d',//'lightbox_info_bg_transparent'=>'number_max_100',
          '%d',//'lightbox_info_border_width'=>'number',
          '%s',//'lightbox_info_border_style'=>'string',
          '%s',//'lightbox_info_border_color'=>'color',
          '%d',//'lightbox_info_border_radius'=>'number',
          '%s',//'lightbox_info_padding'=>'length_multi',
          '%s',//'lightbox_info_margin'=>'length_multi',
          '%s',//'lightbox_title_color'=>'color',
          '%s',//'lightbox_title_font_style'=>'string',
          '%s',//'lightbox_title_font_weight'=>'string',
          '%d',//'lightbox_title_font_size'=>'number',
          '%s',//'lightbox_description_color'=>'color',
          '%s',//'lightbox_description_font_style'=>'string',
          '%s',//'lightbox_description_font_weight'=>'string',
          '%d',//'lightbox_description_font_size'=>'number',
          '%d',//'lightbox_info_height'=>'number_max_100'
          '%d',//'lightbox_comment_width'=>'number',
          '%s',//'lightbox_comment_pos'=>'string',
          '%s',//'lightbox_comment_bg_color'=>'color',
          '%d',//'lightbox_comment_font_size'=>'number',
          '%s',//'lightbox_comment_font_color'=>'color',
          '%s',//'lightbox_comment_font_style'=>'string',
          '%d',//'lightbox_comment_author_font_size'=>'number',
          '%s',//'lightbox_comment_author_font_color'=>'color',
          '%s',//'lightbox_comment_author_font_color_hover'=>'color'
          '%d',//'lightbox_comment_date_font_size'=>'number',
          '%d',//'lightbox_comment_body_font_size'=>'number',
          '%d',//'lightbox_comment_input_border_width'=>'number',
          '%s',//'lightbox_comment_input_border_style'=>'string',
          '%s',//'lightbox_comment_input_border_color'=>'color',
          '%d',//'lightbox_comment_input_border_radius'=>'number',
          '%s',//'lightbox_comment_input_padding'=>'length_multi',
          '%s',//'lightbox_comment_input_bg_color'=>'color',
          '%s',//'lightbox_comment_button_bg_color'=>'color',
          '%s',//'lightbox_comment_button_padding'=>'length_multi',
          '%d',//'lightbox_comment_button_border_width'=>'number',
          '%s',//'lightbox_comment_button_border_style'=>'string',
          '%s',//'lightbox_comment_button_border_color'=>'color',
          '%d',//'lightbox_comment_button_border_radius'=>'number',
          '%d',//'lightbox_comment_separator_width'=>'number',
          '%s',//'lightbox_comment_separator_style'=>'string',
          '%s',//'lightbox_comment_separator_color'=>'color',
          '%s',//'lightbox_comment_load_more_color' =>'color',
          '%s',//'lightbox_comment_load_more_color_hover' =>'color',

          '%s',/*th_photo_wrap_padding*/
          '%s',/*th_photo_wrap_border_size*/
          '%s',/*th_photo_wrap_border_color*/
          '%s',/*th_photo_img_border_radius*/
          '%s',/*th_photo_wrap_bg_color*/
          '%s',/*th_photo_meta_bg_color*/
          '%s',/*th_photo_meta_one_line*/
          '%s',/*th_like_text_color*/
          '%s',/*th_comment_text_color*/
          '%s',/*th_photo_caption_font_size*/
          '%s',/*th_photo_caption_color*/
          '%s',/*th_feed_item_margin*/
          '%s',/*th_photo_caption_hover_color*/
          '%s',/*th_like_comm_font_size*/
          '%s',//'th_overlay_hover_color'=>'color',
          '%d',//'th_overlay_hover_transparent'=>'number',
          '%s',//'th_overlay_hover_icon_color'=>'color',
          '%s',//'th_overlay_hover_icon_font_size'=>'length',

          '%s',//th_photo_img_hover_effect

          '%s',/*mas_photo_wrap_padding*/
          '%s',/*mas_photo_wrap_border_size*/
          '%s',/*mas_photo_wrap_border_color*/
          '%s',/*mas_photo_img_border_radius*/
          '%s',/*mas_photo_wrap_bg_color*/
          '%s',/*mas_photo_meta_bg_color*/
          '%s',/*mas_photo_meta_one_line*/
          '%s',/*mas_like_text_color*/
          '%s',/*mas_comment_text_color*/
          '%s',/*mas_photo_caption_font_size*/
          '%s',/*mas_photo_caption_color*/
          '%s',/*mas_feed_item_margin*/
          '%s',/*mas_photo_caption_hover_color*/
          '%s',/*mas_like_comm_font_size*/
          '%s',//'mas_overlay_hover_color'=>'color',
          '%d',//'mas_overlay_hover_transparent'=>'number',
          '%s',//'mas_overlay_hover_icon_color'=>'color',
          '%s',//'mas_overlay_hover_icon_font_size'=>'length',

          '%s',//mas_photo_img_hover_effect
          
          '%s',/*blog_style_photo_wrap_padding*/
          '%s',/*blog_style_photo_wrap_border_size*/
          '%s',/*blog_style_photo_wrap_border_color*/
          '%s',/*blog_style_photo_img_border_radius*/
          '%s',/*blog_style_photo_wrap_bg_color*/
          '%s',/*blog_style_photo_meta_bg_color*/
          '%s',/*blog_style_photo_meta_one_line*/
          '%s',/*blog_style_like_text_color*/
          '%s',/*blog_style_comment_text_color*/
          '%s',/*blog_style_photo_caption_font_size*/
          '%s',/*blog_style_photo_caption_color*/
          '%s',/*blog_style_feed_item_margin*/
          '%s',/*blog_style_photo_caption_hover_color*/
          '%s',/*blog_style_like_comm_font_size*/


          '%s',/*image_browser_photo_wrap_padding*/
          '%s',/*image_browser_photo_wrap_border_size*/
          '%s',/*image_browser_photo_wrap_border_color*/
          '%s',/*image_browser_photo_img_border_radius*/
          '%s',/*image_browser_photo_wrap_bg_color*/
          '%s',/*image_browser_photo_meta_bg_color*/
          '%s',/*image_browser_photo_meta_one_line*/
          '%s',/*image_browser_like_text_color*/
          '%s',/*image_browser_comment_text_color*/
          '%s',/*image_browser_photo_caption_font_size*/
          '%s',/*image_browser_photo_caption_color*/
          '%s',/*image_browser_feed_item_margin*/
          '%s',/*image_browser_photo_caption_hover_color*/
          '%s',/*image_browser_like_comm_font_size*/

          '%s',/*load_more_position*/
          '%s',/*load_more_padding*/
          '%s',/*load_more_bg_color*/
          '%s',/*load_more_border_radius*/
          '%s',/*load_more_height*/
          '%s',/*load_more_width*/
          '%s',/*load_more_border_size*/
          '%s',/*load_more_border_color*/
          '%s',/*load_more_text_color*/
          '%s',/*load_more_text_font_size*/
          '%s',/*load_more_wrap_hover_color*/
          '%s',// 'pagination_ctrl_color' => 'color',
          '%s',// 'pagination_size' => 'length',
          '%s',// 'pagination_ctrl_margin' => 'length_multi',
          '%s',// 'pagination_ctrl_hover_color' => 'color'
          '%s',//'pagination_position'=>'position'
          '%s',//'pagination_position_vert'=>'position'

          /* since v1.0.6. keep order, defaults*/
          
          '%s',//'th_thumb_user_bg_color'=>'color',
          '%s',//'th_thumb_user_color'=>'color'
          '%s',//'mas_thumb_user_bg_color'=>'color',
          '%s',//'mas_thumb_user_color'=>'color'
            );
}

  private function add() {
    require_once WDI_DIR . "/admin/models/WDIModelThemes_wdi.php";
    $model = new WDIModelThemes_wdi();

    require_once WDI_DIR . "/admin/views/WDIViewThemes_wdi.php";
    $view = new WDIViewThemes_wdi($model);
    $view->edit(0);
  }


   private function edit($customId = '') {

    require_once WDI_DIR . "/admin/models/WDIModelThemes_wdi.php";
    $model = new WDIModelThemes_wdi();

    require_once WDI_DIR . "/admin/views/WDIViewThemes_wdi.php";
    $view = new WDIViewThemes_wdi($model);
    if($customId != ''){
      $id = $customId;
    }else{
          $id = ((isset($_POST['current_id']) && esc_html(stripslashes($_POST['current_id'])) != '') ? esc_html(stripslashes($_POST['current_id'])) : 0);
    }
    $view->edit($id);
    
  }
  private function apply() {
    $this->save_slider_db();
    $this->save_slide_db();
    $this->edit();
  }
  private function duplicate_all($id) {

    global $wpdb;
    $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . WDI_THEME_TABLE);
    foreach ($sliders_ids_col as $theme_id) {
      if (isset($_POST['check_' . $theme_id])) {
        $msg = $this->duplicate_tabels($theme_id);
      }
    }
    if(!isset($msg)){
       echo WDILibrary::message(__('Please select at least one item',"wd-instagram-feed"), 'error');
    }
    elseif($msg['msg'] == false){
      echo WDILibrary::message(__('Cannot Write on database. Check database permissions.',"wd-instagram-feed"), 'error');
    }else{
      echo WDILibrary::message(__('Items Succesfully Duplicated.', "wd-instagram-feed"), 'updated');
    }
    
    $this->display();
  }

  private function duplicate_tabels($theme_id) {
    global $wpdb;
    if ($theme_id) {
      $theme_row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . WDI_THEME_TABLE.' where id="%d"', $theme_id));    
    }
    
    if ($theme_row) {
      $duplicate_values = WDILibrary::objectToArray($theme_row);
      unset($duplicate_values['id']);
      $duplicate_values['default_theme'] = 0;
      $save = $wpdb->insert($wpdb->prefix . WDI_THEME_TABLE,$duplicate_values, $this->data_format);  
      $new_theme_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . WDI_THEME_TABLE);
    }
    return array('id'=>$new_theme_id,"msg"=>$save);
  }
  

  private function delete($id) {
    global $wpdb;
    //checking for default
    $theme_row =  $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . WDI_THEME_TABLE." WHERE id = %d",$id));
    if($theme_row->default_theme == '0'){

      $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . WDI_THEME_TABLE. ' WHERE id="%d"', $id);
      
      if ($wpdb->query($query)) {
        echo WDILibrary::message(__('Item Succesfully Deleted.',"wd-instagram-feed"), 'updated');
      }
      else {
        echo WDILibrary::message(__('Error. Please install plugin again.',"wd-instagram-feed"), 'error');
      }
    }else{
      echo WDILibrary::message(__('You cannot delete default theme.',"wd-instagram-feed"), 'error');
    }
    $this->display();
  }
  
  private function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $defaultFalg = false;
    $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . WDI_THEME_TABLE);
    foreach ($sliders_ids_col as $theme_id) {
       $defaulFlag = false;
        $theme_row =  $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . WDI_THEME_TABLE." WHERE id = %d",$theme_id));
        
          if (isset($_POST['check_' . $theme_id]) || isset($_POST['check_all_items'])) {
            if($theme_row->default_theme == '0'){
            $flag = TRUE;
            $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . WDI_THEME_TABLE.' WHERE id="%d"', $theme_id);
            $wpdb->query($query);
          }else{
            $defaulFlag = true;
            echo WDILibrary::message(__('You cannot delete default theme.',"wd-instagram-feed"), 'error');
          }
        }
    }
    if ($flag) {
      echo WDILibrary::message(__('Items Succesfully Deleted.',"wd-instagram-feed"), 'updated');
    }
    else {
      if($defaulFlag==false){
         echo WDILibrary::message(__('You must select at least one item.',"wd-instagram-feed"), 'error');
      }
     
    }
    $this->display();
  }

  private function set_default($id) {
    global $wpdb;
    $reset =$wpdb->update($wpdb->prefix . WDI_THEME_TABLE, array('default_theme' => 0), array('default_theme' => '1'));
    $save = $wpdb->update($wpdb->prefix . WDI_THEME_TABLE, array('default_theme' => 1), array('id' => $id));
    $this->display();
  }





  private function sanitize_input($settings,$defaults){

    require_once WDI_DIR . "/admin/models/WDIModelThemes_wdi.php";
    $model = new WDIModelThemes_wdi();
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
        case 'length':{
            $sanitized_val=$this->sanitize_length($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'length_multi':{
            $sanitized_val=$this->sanitize_length_multi($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'color':{
            $sanitized_val=$this->sanitize_color($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'position':{
            $sanitized_val=$this->sanitize_position($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'css_box_shadow':{
            $sanitized_val=$this->sanitize_css_box_shadow($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'number_max_100':{
            $sanitized_val=$this->sanitize_number_max_100($value,$defaults[$setting_name]);
            $sanitized_output[$setting_name] = $sanitized_val;
            break;
        }
        case 'number_neg':{
            $sanitized_val=$this->sanitize_number_neg($value,$defaults[$setting_name]);
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
    if(is_numeric($value) && $value>=0){
        return $value;
    }else{
        return $default;
    }
}
private function sanitize_number_neg($value,$default){
    if(is_numeric($value)){
        return $value;
    }else{
        return $default;
    }
}
private function sanitize_number_max_100($value,$default){
    if(is_numeric($value) && $value>=0 && $value<=100){
        return $value;
    }else{
        return $default;
    }
}
private function sanitize_css_box_shadow($value,$default){
  $value = trim($value);
  $values = explode(' ',$value);

  if($value === 'none' || $value === 'initial' || $value =="0"){
    return $value;
  }
  if(count($values)<3) {return $default;}
  //first check test
  $first_check_flag = false;
  for($i=0;$i<count($values);$i++){
    if($i != 2){
      if($this->sanitize_length($values[$i], 'error') === 'error'){
        $first_check_flag = true;
      }
    }else{
      if($this->sanitize_color($values[$i], 'error') === 'error'){
        $first_check_flag = true;
      }
    }
  }
  if($first_check_flag == false) {return $value;}

  //second check test
  //if(count($values) < 4) {return $default;}
  $second_check_flag = false;
  for($i=0;$i<count($values);$i++){
    if($i != 3){
      if($this->sanitize_length($values[$i], 'error') === 'error'){
        $second_check_flag = true;
      }
    }else{
      if($this->sanitize_color($values[$i], 'error') === 'error'){
        $second_check_flag = true;
      }
    }
  }
  if($second_check_flag == false) {return $value;}
  $third_check_flag = false;
  for($i=0;$i<count($values);$i++){
    if($i != 4){
      if($this->sanitize_length($values[$i], 'error') === 'error'){
        $third_check_flag = true;
      }
    }else{
      if($this->sanitize_color($values[$i], 'error') === 'error'){
        $third_check_flag = true;
      }
    }
  }
  if($third_check_flag === false) {
    return $value;
  }
  return $default;
 
}
private function sanitize_url($value,$default){
    if (!filter_var($value, FILTER_VALIDATE_URL) === false) {
    return $value;
    } else {
    return $default;
   }
}

private function sanitize_length($value,$default){

  $value = trim($value);

  if($value == 0){
    return $value;
  }

  $opt1 = substr($value,strlen($value)-2,strlen($value));
  $opt2 = substr($value,strlen($value)-1,strlen($value));
  $val  = floatval($value);
  $val1 = substr($value,0,strlen($value)-2);
  $val2 = substr($value,0,strlen($value)-1);
  if(is_numeric($val)){
    if( is_numeric(substr($val1,-1)) && ($opt1=='px' || $opt1=='em')){
      return $value;
    }else if(is_numeric(substr($val2,-1)) && $opt2=='%'){
      return $value;
    }
    else{
      return $default;
    }
  }else{
    return $default;
  }  
}
private function sanitize_color($value,$default){
  $val = WDILibrary::regexColor($value,1);

  if($val == false){
    return $default;
  }else{
    return $val;
  }

}
private function sanitize_position($value,$default){
  $value = strtolower(trim($value));
  if($value == 'left' || $value == 'right' || $value == 'center' || $value == 'top' || $value == 'bottom'){
    return $value;
  }else{
    return $default;
  }
}

private function sanitize_length_multi($value1,$default){
  $value1 = trim($value1);
  $output = '';
  $values = explode(' ',$value1);
  $flag = false;
  $counter = 0;
  foreach ($values as $value) {
    if($value == '')
      continue;
    $counter++;
    $value = trim($value);
    $opt1 = substr($value,strlen($value)-2,strlen($value));
    $opt2 = substr($value,strlen($value)-1,strlen($value));
    $val  = floatval($value);
    $val1 = substr($value,0,strlen($value)-2);
    $val2 = substr($value,0,strlen($value)-1);
    if(is_numeric($val)){
      if( is_numeric(substr($val1,-1)) && ($opt1=='px' || $opt1=='em')){
        $output.=' '.$value.' ';
      }else if((is_numeric(substr($val2,-1)) && $opt2=='%') || ($val == 0)){
        $output.=' '.$value .' ';
      }
      else{
        $flag = true;
      }
    }else{
      $flag = true;
    }  
  }
  $output = trim($output);
  if($flag == false && $counter<=4){
    return $output;
  }else{
    return $default;
  }
  
}

private function message($text,$type){
  require_once WDI_DIR . "/admin/models/WDIModelThemes_wdi.php";
          $model = new WDIModelThemes_wdi();
          require_once WDI_DIR . "/admin/views/WDIViewThemes_wdi.php";
          $view = new WDIViewThemes_wdi($model);
          echo WDILibrary::message($text, $type);
}
}
?>