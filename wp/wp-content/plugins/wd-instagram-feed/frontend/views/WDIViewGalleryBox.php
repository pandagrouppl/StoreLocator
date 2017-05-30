<?php

class WDIViewGalleryBox {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    global $WD_WDI_UPLOAD_DIR;
    global $wp;
    require_once(WDI_DIR . '/framework/WDILibraryEmbed.php');

    //////////////////////////////////////////////////////////////////
    $current_feed = $this->model->get_feed_row_data($_POST['feed_id']);

    $theme_row = WDILibrary::arrayToObject($this->model->get_theme_row_data($current_feed['theme_id']));
    $option_row = WDILibrary::arrayToObject($current_feed);
    $option_row->preload_images_count = 0;
    $option_row->enable_addthis = 0;
    $option_row->popup_enable_captcha = 0;
    $option_row->popup_enable_email = 0;

    //////////////////////////////////////////////////////////////////

    $current_url = (isset($_GET['current_url']) ? add_query_arg(esc_html($_GET['current_url']), '', home_url($wp->request)) : '');
    $tag_id = (isset($_GET['tag_id']) ? esc_html($_GET['tag_id']) : 0);
    $gallery_id = (isset($current_feed['id']) ? esc_html($current_feed['id']) : 0);
    $wdi = (isset($_POST['feed_counter']) ? esc_html($_POST['feed_counter']) : 0);
    $current_image_id = (isset($_GET['image_id']) ? esc_html($_GET['image_id']) : 0);
    $theme_id = (isset($current_feed['theme_id']) ? esc_html($current_feed['theme_id']) : 1);

    $thumb_width = (isset($_GET['thumb_width']) ? esc_html($_GET['thumb_width']) : 90);/*watch later*/
    $thumb_height = (isset($_GET['thumb_height']) ? esc_html($_GET['thumb_height']) : 90);/*watch later*/

    $open_with_fullscreen = (isset($current_feed['popup_fullscreen']) ? esc_html($current_feed['popup_fullscreen']) : 0);
    $open_with_autoplay = (isset($current_feed['popup_autoplay']) ? esc_html($current_feed['popup_autoplay']) : 0);
    $image_width = (isset($current_feed['popup_width']) ? esc_html($current_feed['popup_width']) : 800);
    $image_height = (isset($current_feed['popup_height']) ? esc_html($current_feed['popup_height']) : 500);

    $image_effect = (isset($current_feed['popup_type']) && esc_html(isset($current_feed['popup_type'])) ? esc_html($current_feed['popup_type']) : 'fade');

    $sort_by = (isset($_GET['wd_sor']) ? esc_html($_GET['wd_sor']) : 'order');/*watch later*/
    $order_by = (isset($_GET['wd_ord']) ? esc_html($_GET['wd_ord']) : 'asc');/*watch later*/

    $enable_image_filmstrip = (isset($current_feed['popup_enable_filmstrip']) ? esc_html($current_feed['popup_enable_filmstrip']) : 0);
    $enable_image_fullscreen = (isset($current_feed['popup_enable_fullscreen']) ? esc_html($current_feed['popup_enable_fullscreen']) : 0);/*watch later*/
    $popup_enable_info = (isset($current_feed['popup_enable_info']) ? esc_html($current_feed['popup_enable_info']) : 1);
    $popup_info_always_show = (isset($current_feed['popup_info_always_show']) ? esc_html($current_feed['popup_info_always_show']) : 0);
    $popup_info_full_width = (isset($current_feed['popup_info_full_width']) ? esc_html($current_feed['popup_info_full_width']) : 0);
    $popup_enable_rate = (isset($current_feed['lightbox_rating']) ? esc_html($current_feed['lightbox_rating']) : 0);/*deprecated*/
    $popup_hit_counter = (isset($current_feed['lightbox_hit_counter']) ? esc_html($current_feed['lightbox_hit_counter']) : 0);/*deprecated*/
    if ($enable_image_filmstrip) {
      $image_filmstrip_height = (isset($current_feed['popup_filmstrip_height']) ? esc_html($current_feed['popup_filmstrip_height']) : '20');
      $thumb_ratio = $thumb_width / $thumb_height;
      $image_filmstrip_width = round($thumb_ratio * $image_filmstrip_height);
    }
    else {
      $image_filmstrip_height = 0;
      $image_filmstrip_width = 0;
    }

    $slideshow_interval = (isset($current_feed['popup_interval']) ? (int) $current_feed['popup_interval'] : 5);
    $enable_image_ctrl_btn = (isset($current_feed['popup_enable_ctrl_btn']) ? esc_html($current_feed['popup_enable_ctrl_btn']) : 0);
    $enable_comment_social = (isset($current_feed['popup_enable_comment']) ? esc_html($current_feed['popup_enable_comment']) : 0);
    $enable_image_facebook = (isset($current_feed['popup_enable_facebook']) ? esc_html($current_feed['popup_enable_facebook']) : 0);
    $enable_image_twitter = (isset($current_feed['popup_enable_twitter']) ? esc_html($current_feed['popup_enable_twitter']) : 0);
    $enable_image_google = (isset($current_feed['popup_enable_google']) ? esc_html($current_feed['popup_enable_google']) : 0);
    $enable_image_pinterest = (isset($current_feed['popup_enable_pinterest']) ? esc_html($current_feed['popup_enable_pinterest']) : 0);
    $enable_image_tumblr = (isset($current_feed['popup_enable_tumblr']) ? esc_html($current_feed['popup_enable_tumblr']) : 0);
    $enable_share_buttons = (isset($current_feed['popup_enable_share_buttons']) ? esc_html($current_feed['popup_enable_share_buttons']) : 1);
    $image_right_click = (isset($current_feed['popup_image_right_click']) ? esc_html($current_feed['popup_image_right_click']) : 1);


    $watermark_type = (isset($_GET['watermark_type']) ? esc_html($_GET['watermark_type']) : 'none');/*watch later*//*deprecated*/
    $watermark_text = (isset($_GET['watermark_text']) ? esc_html($_GET['watermark_text']) : '');/*watch later*//*deprecated*/
    $watermark_font_size = (isset($_GET['watermark_font_size']) ? esc_html($_GET['watermark_font_size']) : 12);/*watch later*//*deprecated*/
    $watermark_font = (isset($_GET['watermark_font']) ? esc_html($_GET['watermark_font']) : 'Arial');/*watch later*//*deprecated*/
    $watermark_color = (isset($_GET['watermark_color']) ? esc_html($_GET['watermark_color']) : 'FFFFFF');/*watch later*//*deprecated*/
    $watermark_opacity = (isset($_GET['watermark_opacity']) ? esc_html($_GET['watermark_opacity']) : 30);/*watch later*//*deprecated*/
    $watermark_position = explode('-', (isset($_GET['watermark_position']) ? esc_html($_GET['watermark_position']) : 'bottom-right'));/*watch later*//*deprecated*/
    $watermark_link = (isset($_GET['watermark_link']) ? esc_html($_GET['watermark_link']) : '');/*watch later*//*deprecated*/
    $watermark_url = (isset($_GET['watermark_url']) ? esc_html($_GET['watermark_url']) : '');/*watch later*//*deprecated*/
    $watermark_width = (isset($_GET['watermark_width']) ? esc_html($_GET['watermark_width']) : 90);/*watch later*//*deprecated*/
    $watermark_height = (isset($_GET['watermark_height']) ? esc_html($_GET['watermark_height']) : 90);/*watch later*//*deprecated*/




    //$option_row = $this->model->get_option_row_data();


    // $image_right_click = 0; //$option_row->image_right_click;/*watch later havent this options*/
    $comment_moderation = 0;//$option_row->comment_moderation;/*watch later havent this options*/

    $filmstrip_direction = 'horizontal';
    if ($theme_row->lightbox_filmstrip_pos == 'right' || $theme_row->lightbox_filmstrip_pos == 'left') {
      $filmstrip_direction = 'vertical';
    }
    if ($enable_image_filmstrip) {
      if ($filmstrip_direction == 'horizontal') {
        $image_filmstrip_height = (isset($current_feed['popup_filmstrip_height']) ? esc_html($current_feed['popup_filmstrip_height']) : '20');
        $thumb_ratio = $thumb_width / $thumb_height;
        $image_filmstrip_width = round($thumb_ratio * $image_filmstrip_height);
      }
      else {
        $image_filmstrip_width = (isset($current_feed['popup_filmstrip_height']) ? esc_html($current_feed['popup_filmstrip_height']) : '50');
        $thumb_ratio = $thumb_height / $thumb_width;
        $image_filmstrip_height = round($thumb_ratio * $image_filmstrip_width);
      }
    }
    else {
      $image_filmstrip_height = 0;
      $image_filmstrip_width = 0;
    }

    if ($tag_id != 0) {
      //$image_rows = $this->model->get_image_rows_data_tag($tag_id, $sort_by, $order_by);
    }
    else {
      // $image_rows = $this->model->get_image_rows_data($gallery_id, $sort_by, $order_by);
    }


    $json =  (($_POST['image_rows']));
    str_replace('"',"&quot;", $json);

    $image_rows = json_decode(stripslashes($json));
    $image_rows_count = count($image_rows);
    $image_rows = WDILibrary::arrayToObject($image_rows);

    /////////////////////////////////Parametes for deprecated content////////////////////
    $theme_row->lightbox_rate_stars_count = 0;
    $theme_row->lightbox_rate_size = 0;
    /////////////////////////////////////////////////////////////////////////////////////

    $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : $current_image_id);
    //$comment_rows = $this->model->get_comment_rows_data($image_id);

    $params_array = array(
      'action' => 'GalleryBox',
      'image_id' => $current_image_id,
      'gallery_id' => $gallery_id,
      'theme_id' => $theme_id,
      'thumb_width' => $thumb_width,
      'thumb_height' => $thumb_height,
      'open_with_fullscreen' => $open_with_fullscreen,
      'image_width' => $image_width,
      'image_height' => $image_height,
      'image_effect' => $image_effect,
      'wd_sor' => $sort_by,
      'wd_ord' => $order_by,
      'enable_image_filmstrip' => $enable_image_filmstrip,
      'image_filmstrip_height' => $image_filmstrip_height,
      'enable_image_ctrl_btn' => $enable_image_ctrl_btn,
      'enable_image_fullscreen' => $enable_image_fullscreen,
      'popup_enable_info' => $popup_enable_info,
      'popup_info_always_show' => $popup_info_always_show,
      'popup_info_full_width' => $popup_info_full_width,
      'popup_hit_counter' => $popup_hit_counter,
      'popup_enable_rate' => $popup_enable_rate,
      'slideshow_interval' => $slideshow_interval,
      'enable_comment_social' => $enable_comment_social,
      'enable_image_facebook' => $enable_image_facebook,
      'enable_image_twitter' => $enable_image_twitter,
      'enable_image_google' => $enable_image_google,
      'enable_image_pinterest' => $enable_image_pinterest,
      'enable_image_tumblr' => $enable_image_tumblr,
      'watermark_type' => $watermark_type,
      'current_url' => $current_url
    );
    if ($watermark_type != 'none') {
      $params_array['watermark_link'] = $watermark_link;
      $params_array['watermark_opacity'] = $watermark_opacity;
      $params_array['watermark_position'] = $watermark_position;
    }
    if ($watermark_type == 'text') {
      $params_array['watermark_text'] = $watermark_text;
      $params_array['watermark_font_size'] = $watermark_font_size;
      $params_array['watermark_font'] = $watermark_font;
      $params_array['watermark_color'] = $watermark_color;
    }
    elseif ($watermark_type == 'image') {
      $params_array['watermark_url'] = $watermark_url;
      $params_array['watermark_width'] = $watermark_width;
      $params_array['watermark_height'] = $watermark_height;
    }
    $popup_url = add_query_arg(array($params_array), admin_url('admin-ajax.php'));
    $filmstrip_thumb_margin = $theme_row->lightbox_filmstrip_thumb_margin;
    $margins_split = explode(" ", $filmstrip_thumb_margin);
    $filmstrip_thumb_margin_right = 0;
    $filmstrip_thumb_margin_left = 0;
    $temp_iterator = ($filmstrip_direction == 'horizontal' ? 1 : 0);
    if (isset($margins_split[$temp_iterator])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[$temp_iterator];
      if (isset($margins_split[$temp_iterator + 2])) {
        $filmstrip_thumb_margin_left = (int) $margins_split[$temp_iterator + 2];
      }
      else {
        $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
      }
    }
    elseif (isset($margins_split[0])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[0];
      $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
    }

    $filmstrip_thumb_margin_hor = $filmstrip_thumb_margin_right + $filmstrip_thumb_margin_left;
    $rgb_wdi_image_info_bg_color = WDILibrary::wdi_spider_hex2rgb($theme_row->lightbox_info_bg_color);
    $rgb_lightbox_ctrl_cont_bg_color = WDILibrary::wdi_spider_hex2rgb($theme_row->lightbox_ctrl_cont_bg_color);
    if (!$enable_image_filmstrip) {
      if ($theme_row->lightbox_filmstrip_pos == 'left') {
        $theme_row->lightbox_filmstrip_pos = 'top';
      }
      if ($theme_row->lightbox_filmstrip_pos == 'right') {
        $theme_row->lightbox_filmstrip_pos = 'bottom';
      }
    }
    $left_or_top = 'left';
    $width_or_height= 'width';
    $outerWidth_or_outerHeight = 'outerWidth';
    if (!($filmstrip_direction == 'horizontal')) {
      $left_or_top = 'top';
      $width_or_height = 'height';
      $outerWidth_or_outerHeight = 'outerHeight';
    }

    $current_filename = '';

    if ($option_row->enable_addthis && $option_row->addthis_profile_id) {
      ?>
      <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $option_row->addthis_profile_id; ?>" async="async"></script>
      <?php
    }

    ?>
    <style>
      /*@import url(https://fonts.googleapis.com/css?family=Maven+Pro:400,500);*/
      .wdi_spider_popup_wrap * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      .wdi_spider_popup_overlay{
        background-color: <?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo (intval($theme_row->lightbox_overlay_bg_transparent)/100); ?>;
      }
      .wdi_spider_popup_wrap {
        background-color: <?php echo $theme_row->lightbox_bg_color; ?>;
        display: inline-block;
        left: 50%;
        outline: medium none;
        position: fixed;
        text-align: center;
        top: 50%;
        z-index: 100000;
      }
      .wdi_popup_image {
        max-width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        max-height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;
        vertical-align: middle;
        display: inline-block;
      }
      .wdi_popup_embed {
        width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;
        vertical-align: middle;
        text-align: center;
        display: inline-block;
      }
      .wdi_ctrl_btn {
        color: <?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_ctrl_btn_height; ?>px;
        margin: <?php echo $theme_row->lightbox_ctrl_btn_margin_top; ?>px <?php echo $theme_row->lightbox_ctrl_btn_margin_left; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
      }
      .wdi_toggle_btn {
        color: <?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_toggle_btn_height; ?>px;
        margin: 0;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
        padding: 0;
      }
      .wdi_btn_container {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
      }

      .wdi_ctrl_btn_container {
        background-color: rgba(<?php echo $rgb_lightbox_ctrl_cont_bg_color['red']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['green']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>);
        /*background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;*/
      <?php
      if ($theme_row->lightbox_ctrl_btn_pos == 'top') {
        ?>
        border-bottom-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        border-bottom-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
      <?php
    }
    else {
      ?>
        bottom: 0;
        border-top-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        border-top-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
      <?php
    }?>
        height: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;
        /*opacity: <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_cont_transparent; ?>);*/
        position: absolute;
        text-align: <?php echo $theme_row->lightbox_ctrl_btn_align; ?>;
        width: 100%;
        z-index: 10150;
      }
      .wdi_toggle_container {
        background: none repeat scroll 0 0 <?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;
      <?php
      if ($theme_row->lightbox_ctrl_btn_pos == 'top') {
        ?>
        border-bottom-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        border-bottom-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        /****/
        border-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        /****/
        top: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;
      <?php
    }
    else {
      ?>
        border-top-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        border-top-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        /****/
        border-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        /****/
        bottom: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;
      <?php
    }?>
        cursor: pointer;
        left: 50%;
        line-height: 0;
        margin-left: -<?php echo $theme_row->lightbox_toggle_btn_width / 2; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_cont_transparent; ?>);
        position: absolute;
        text-align: center;
        width: <?php echo $theme_row->lightbox_toggle_btn_width; ?>px;
        z-index: 10150;
      }
      .wdi_close_btn {
        opacity: <?php echo number_format($theme_row->lightbox_close_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_close_btn_transparent; ?>);
      }
      .wdi_spider_popup_close {
        background-color: <?php echo $theme_row->lightbox_close_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_close_btn_border_radius; ?>px;
        border: <?php echo $theme_row->lightbox_close_btn_border_width; ?>px <?php echo $theme_row->lightbox_close_btn_border_style; ?> <?php echo $theme_row->lightbox_close_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->lightbox_close_btn_box_shadow; ?>;
        color: <?php echo $theme_row->lightbox_close_btn_color; ?>;
        height: <?php echo $theme_row->lightbox_close_btn_height; ?>px;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: <?php echo $theme_row->lightbox_close_btn_right; ?>px;
        top: <?php echo $theme_row->lightbox_close_btn_top; ?>px;
        width: <?php echo $theme_row->lightbox_close_btn_width; ?>px;
      }
      .wdi_spider_popup_close_fullscreen {
        color: <?php echo $theme_row->lightbox_close_btn_full_color; ?>;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: 15px;
      }
      .wdi_spider_popup_close span,
      #wdi_spider_popup_left-ico span,
      #wdi_spider_popup_right-ico span {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
      }
      #wdi_spider_popup_left-ico,
      #wdi_spider_popup_right-ico {
        background-color: <?php echo $theme_row->lightbox_rl_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_rl_btn_border_radius; ?>;
        /***/
        border-radius: <?php echo $theme_row->lightbox_rl_btn_border_radius; ?>px;
        /***/
        border: <?php echo $theme_row->lightbox_rl_btn_border_width; ?>px <?php echo $theme_row->lightbox_rl_btn_border_style; ?> <?php echo $theme_row->lightbox_rl_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->lightbox_rl_btn_box_shadow; ?>;
        color: <?php echo $theme_row->lightbox_rl_btn_color; ?>;
        height: <?php echo $theme_row->lightbox_rl_btn_height; ?>px;
        font-size: <?php echo $theme_row->lightbox_rl_btn_size; ?>px;
        width: <?php echo $theme_row->lightbox_rl_btn_width; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_rl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_rl_btn_transparent; ?>);
      }
      <?php
      if($option_row->autohide_lightbox_navigation){?>
      #wdi_spider_popup_left-ico{
        left: -9999px;
      }
      #wdi_spider_popup_right-ico{
        left: -9999px;
      }
      <?php }
      else { ?>
      #wdi_spider_popup_left-ico {
        left: 20px;
      }
      #wdi_spider_popup_right-ico {
        left: auto;
        right: 20px;
      }
      <?php } ?>
      .wdi_ctrl_btn:hover,
      .wdi_toggle_btn:hover,

      #wdi_spider_popup_left-ico:hover,
      #wdi_spider_popup_right-ico:hover {
        color: <?php echo $theme_row->lightbox_close_rl_btn_hover_color; ?>;
        cursor: pointer;
      }
      .wdi_spider_popup_close:hover,
      .wdi_spider_popup_close_fullscreen:hover{
        color:  <?php echo $theme_row->lightbox_close_btn_hover_color;?>;
        cursor: pointer;
      }
      .wdi_image_wrap {
        height: inherit;
        display: table;
        position: absolute;
        text-align: center;
        width: inherit;
      }
      .wdi_image_wrap * {
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .wdi_embed_frame{
        text-align:center;
      }
      .wdi_comment_wrap {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
        z-index: -1;
      }
      .wdi_comment_container {
        -moz-box-sizing: border-box;
        background-color: <?php echo $theme_row->lightbox_comment_bg_color; ?>;
        color: <?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        overflow: hidden;
        position: absolute;
      <?php echo $theme_row->lightbox_comment_pos; ?>: -<?php echo $theme_row->lightbox_comment_width; ?>px;
        top: 0;
        width: <?php echo $theme_row->lightbox_comment_width; ?>px;
        z-index: 10103;
      }

      .wdi_comments {
        bottom: 0;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        left: 0;
        overflow-x: hidden;
        overflow-y: auto;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 10101;
      }
      .wdi_comments p,
      .wdi_comment_body_p {
        margin: 5px !important;
        text-align: left;
        word-wrap: break-word;
        word-break: break-word;
      }
      .wdi_no_comment{
        text-align: center !important;
      }
      .wdi_comments input[type="submit"] {
        background: none repeat scroll 0 0 <?php echo $theme_row->lightbox_comment_button_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_button_border_width; ?>px <?php echo $theme_row->lightbox_comment_button_border_style; ?> <?php echo $theme_row->lightbox_comment_button_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>px;
        color: <?php echo $theme_row->lightbox_comment_font_color; ?>;
        cursor: pointer;
        padding: <?php echo $theme_row->lightbox_comment_button_padding; ?>;
      }
      .wdi_comments input[type="text"],
      .wdi_comments textarea {
        background: none repeat scroll 0 0 <?php echo $theme_row->lightbox_comment_input_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_input_border_width; ?>px <?php echo $theme_row->lightbox_comment_input_border_style; ?> <?php echo $theme_row->lightbox_comment_input_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_input_border_radius; ?>;
        /***/
        border-radius: <?php echo $theme_row->lightbox_comment_input_border_radius; ?>px;
        /***/
        color: <?php echo $theme_row->lightbox_comment_font_color; ?>;
        padding: <?php echo $theme_row->lightbox_comment_input_padding; ?>;
        width: 100%;
      }
      .wdi_comments textarea {
        resize: vertical;
      }
      .wdi_comment_header_p {
        border-top: <?php echo $theme_row->lightbox_comment_separator_width; ?>px <?php echo $theme_row->lightbox_comment_separator_style; ?> <?php echo $theme_row->lightbox_comment_separator_color; ?>;
      }
      .wdi_comment_header {
        margin-top: 2px;
        display: inline-block;
        color: <?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_author_font_size; ?>px;
      }
      .wdi_comment_date {
        color: <?php echo $theme_row->lightbox_comment_font_color; ?>;
        float: right;
        font-size: <?php echo $theme_row->lightbox_comment_date_font_size; ?>px;
      }
      .wdi_comment_body {
        color: <?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_body_font_size; ?>px;
      }
      .wdi_comment_delete_btn {
        color: #FFFFFF;
        cursor: pointer;
        float: right;
        font-size: 14px;
        margin: 2px;
      }


      .wdi_comments_close {
        cursor: pointer;
        line-height: 0;
        position: absolute;
        font-size: 13px;
        text-align: <?php echo (($theme_row->lightbox_comment_pos == 'left') ? 'right' : 'left'); ?>;
        margin: 5px;
      <?php echo (($theme_row->lightbox_comment_pos == 'left') ? 'right' : 'left'); ?> : 0;
        top: 0;
        background-color: <?php echo $theme_row->lightbox_comment_bg_color; ?>;
        z-index: 10202;
      }
      .wdi_comments_close i{
        z-index: 10201;
      }
      .wdi_comment_textarea::-webkit-scrollbar {
        width: 4px;
      }
      .wdi_comment_textarea::-webkit-scrollbar-track {
      }
      .wdi_comment_textarea::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.55);
        border-radius: 2px;
      }
      .wdi_comment_textarea::-webkit-scrollbar-thumb:hover {
        background-color: #D9D9D9;
      }
      .wdi_ctrl_btn_container a,
      .wdi_ctrl_btn_container a:hover {
        text-decoration: none;
      }

      .wdi_facebook:hover {
        color: #3B5998 !important;
        cursor: pointer;
      }
      .wdi_twitter:hover {
        color: #4099FB !important;
        cursor: pointer;
      }
      .wdi_google:hover {
        color: #DD4B39 !important;
        cursor: pointer;
      }
      .wdi_pinterest:hover {
        color: #cb2027 !important;
        cursor: pointer;
      }
      .wdi_tumblr:hover {
        color: #2F5070 !important;
        cursor: pointer;
      }
      .wdi_linkedin:hover {
        color: #0077B5 !important;
        cursor: pointer;
      }
      .wdi_facebook,
      .wdi_twitter,
      .wdi_google,
      .wdi_pinterest,
      .wdi_tumblr,
      .wdi_linkedin {
        color: <?php echo $theme_row->lightbox_comment_share_button_color; ?> !important;
      }
      .wdi_image_container {
        display: table;
        position: absolute;
        text-align: center;
      <?php echo $theme_row->lightbox_filmstrip_pos; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : $image_filmstrip_width); ?>px;
        vertical-align: middle;
        width: 100%;
      }
      .wdi_filmstrip_container {
        display: <?php echo ($filmstrip_direction == 'horizontal'? 'table' : 'block'); ?>;
        height: <?php echo ($filmstrip_direction == 'horizontal'? $image_filmstrip_height : $image_height); ?>px;
        position: absolute;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_filmstrip_width); ?>px;
        z-index: 10105;
      <?php echo $theme_row->lightbox_filmstrip_pos; ?>: 0;
      }
      .wdi_filmstrip {
      <?php echo $left_or_top; ?>: 20px;
        overflow: hidden;
        position: absolute;
      <?php echo $width_or_height; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width - 40 : $image_height - 40); ?>px;
        z-index: 10106;
      }
      .wdi_filmstrip_thumbnails {
        height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : ($image_filmstrip_height + $filmstrip_thumb_margin_hor + 2 * $theme_row->lightbox_filmstrip_thumb_border_width) * $image_rows_count); ?>px;
      <?php echo $left_or_top; ?>: 0px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? ($image_filmstrip_width + $filmstrip_thumb_margin_hor + 2 * $theme_row->lightbox_filmstrip_thumb_border_width) * $image_rows_count : $image_filmstrip_width); ?>px;
      }
      .wdi_filmstrip_thumbnail {
        position: relative;
        background: none;
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>px <?php echo $theme_row->lightbox_filmstrip_thumb_border_style; ?> <?php echo $theme_row->lightbox_filmstrip_thumb_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_filmstrip_thumb_border_radius; ?>;
        /***/
        border-radius: <?php echo $theme_row->lightbox_filmstrip_thumb_border_radius; ?>px;
        /***/
        cursor: pointer;
        float: left;
        height: <?php echo $image_filmstrip_height; ?>px;
        margin: <?php echo $theme_row->lightbox_filmstrip_thumb_margin; ?>;
        width: <?php echo $image_filmstrip_width; ?>px;
        overflow: hidden;
      }
      .wdi_thumb_active {
        opacity: 1;
        filter: Alpha(opacity=100);
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_active_border_width; ?>px solid <?php echo $theme_row->lightbox_filmstrip_thumb_active_border_color; ?>;
      }
      .wdi_thumb_deactive {
        opacity: <?php echo number_format($theme_row->lightbox_filmstrip_thumb_deactive_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_filmstrip_thumb_deactive_transparent; ?>);
      }
      .wdi_filmstrip_thumbnail_img {
        display: block;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      .wdi_filmstrip_left {
        background-color: <?php echo $theme_row->lightbox_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
      <?php echo $width_or_height; ?>: 20px;
        z-index: 10106;
      <?php echo $left_or_top; ?>: 0;
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?>
      }
      .wdi_filmstrip_right {
        background-color: <?php echo $theme_row->lightbox_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
      <?php echo($filmstrip_direction == 'horizontal' ? 'right' : 'bottom') ?>: 0;
      <?php echo $width_or_height; ?>: 20px;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
        z-index: 10106;
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
      <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?>
      }
      .wdi_filmstrip_left i,
      .wdi_filmstrip_right i {
        color: <?php echo $theme_row->lightbox_filmstrip_rl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_filmstrip_rl_btn_size; ?>px;
      }
      .wdi_none_selectable {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .wdi_watermark_container {
        display: table-cell;
        margin: 0 auto;
        position: relative;
        vertical-align: middle;
      }
      .wdi_watermark_spun {
        display: table-cell;
        overflow: hidden;
        position: relative;
        text-align: <?php echo $watermark_position[1]; ?>;
        vertical-align: <?php echo $watermark_position[0]; ?>;
        /*z-index: 10140;*/
      }
      .wdi_watermark_image {
        margin: 4px;
        max-height: <?php echo $watermark_height; ?>px;
        max-width: <?php echo $watermark_width; ?>px;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 10141;
      }
      .wdi_watermark_text,
      .wdi_watermark_text:hover {
        text-decoration: none;
        margin: 4px;
        font-size: <?php echo $watermark_font_size; ?>px;
        font-family: <?php echo $watermark_font; ?>;
        color: <?php echo $watermark_color; ?> !important;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 10141;
      }
      .wdi_slide_container {
        display: table-cell;
        position: absolute;
        vertical-align: middle;
        width: 100%;
        height: 100%;
      }
      .wdi_slide_bg {
        margin: 0 auto;
        width: inherit;
        height: inherit;
      }
      .wdi_slider {
        height: inherit;
        width: inherit;
      }
      .wdi_popup_image_spun {
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=100);
        opacity: 1;
        position: absolute;
        vertical-align: middle;
        width: inherit;
        z-index: 2;
      }
      .wdi_popup_image_second_spun {
        width: inherit;
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=0);
        opacity: 0;
        position: absolute;
        vertical-align: middle;
        z-index: 1;
      }
      .wdi_grid {
        display: none;
        height: 100%;
        overflow: hidden;
        position: absolute;
        width: 100%;
      }
      .wdi_gridlet {
        opacity: 1;
        filter: Alpha(opacity=100);
        position: absolute;
      }
      .wdi_image_info_container1 {
        display: <?php echo $popup_info_always_show ? 'table-cell' : 'none'; ?>;
      }

      .wdi_image_info_spun {
        text-align: <?php echo $theme_row->lightbox_info_align; ?>;
        vertical-align: <?php echo $theme_row->lightbox_info_pos; ?>;
      }


      .wdi_image_info {
        background: rgba(<?php echo $rgb_wdi_image_info_bg_color['red']; ?>, <?php echo $rgb_wdi_image_info_bg_color['green']; ?>, <?php echo $rgb_wdi_image_info_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_info_bg_transparent / 100, 2, ".", ""); ?>);
        border: <?php echo $theme_row->lightbox_info_border_width; ?>px <?php echo $theme_row->lightbox_info_border_style; ?> <?php echo $theme_row->lightbox_info_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_info_border_radius; ?>px;
      <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') ? 'bottom: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
      <?php  if($params_array['popup_info_full_width']) { ?>
        width: 100%;
      <?php } else { ?>
        width: 33%;
        margin: <?php echo $theme_row->lightbox_info_margin; ?>;
      <?php } ?>
        padding: <?php echo $theme_row->lightbox_info_padding; ?>;
      <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') ? 'top: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
        overflow: auto;
      }
      .wdi_image_title,
      .wdi_image_title * {
        color: <?php echo $theme_row->lightbox_title_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_title_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_title_font_weight; ?>;
      }
      .wdi_image_description,
      .wdi_image_description * {
        color: <?php echo $theme_row->lightbox_description_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_description_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_description_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_description_font_weight; ?>;
      }
      .wdi_title{
        display: inline-table;
      }
      .wdi_title:hover{
        cursor: pointer;
      }
      .wdi_title .wdi_users_img_wrap{
        width: 50px;
        display: table-cell;
        vertical-align: middle;
        float: right;
        padding-left: 10px;
        overflow: hidden;
      }
      .wdi_title .wdi_users_img_wrap img{
        width: 100%;
      }
      .wdi_title .wdi_header_text{
        display: table-cell;
        vertical-align: middle;
        word-break: break-all;
      }

      .wdi_share_btns{
        position: absolute;
      <?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top')? 'bottom' : 'top';?>: -115%;
        width: <?php echo $theme_row->lightbox_ctrl_btn_height*7.5;?>px;
        border-radius: 3px;
        left: -150%;
        margin: 0;
        z-index: 200000;
        background-color: rgba(<?php echo $rgb_lightbox_ctrl_cont_bg_color['red']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['green']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>);

      }
      .wdi_share_btns_container{
        position: relative;
        display: inline-block;
      }
      .wdi_share_popup_btn{
        color: <?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        padding: 3px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
        font-size: <?php echo $theme_row->lightbox_ctrl_btn_height;?>px;
      }
      .wdi_share_caret{
        font-size: 15px;
        color: <?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>;
        position: absolute;
        top: -25px;
        left: 13px;
      }
      .wdi_share_toggler{
        display: block !important;
      }
      .wdi_facebook_btn:hover{
        color: #45619D;
      }
      .wdi_facebook_btn:hover{
        color: #4099FB;
      }


      .wdi_comment_header a,.wdi_comment_header a:visited{
        color: <?php echo $theme_row->lightbox_comment_author_font_color; ?> !important;
      }
      .wdi_comm_text_link,a.wdi_comm_text_link:visited{
        color: <?php echo $theme_row->lightbox_comment_author_font_color; ?>!important;
      }
      .wdi_comment_header a:hover,a.wdi_comm_text_link:hover{
        text-decoration: none;
        color: <?php echo $theme_row->lightbox_comment_author_font_color_hover; ?>!important;
      }
      .wdi_load_more_comments{
        display: inline-block;
        color: <?php echo $theme_row->lightbox_comment_load_more_color; ?>;
        background-color: <?php echo $theme_row->lightbox_comment_bg_color; ?>;
        -webkit-user-select: none; /* Chrome/Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* IE10+ */
        /* Rules below not implemented in browsers yet */
        -o-user-select: none;
        user-select: none;
        font-family: 'Verdana', sans-serif;
        text-transform: capitalize;
        font-size: 17px;
      }
      .wdi_load_more_comments:hover{
        color: <?php echo $theme_row->lightbox_comment_load_more_color_hover;?>;
        cursor: pointer;
      }

      @media (max-width: 480px) {
        .wdi_image_count_container {
          display: none;
        }
        .wdi_image_title,
        .wdi_image_title * {
          font-size: 12px;
        }
        .wdi_image_description,
        .wdi_image_description * {
          font-size: 10px;
        }
      }
      .wdi_image_count_container {
        left: 0;
        line-height: 1;
        position: absolute;
        vertical-align: middle;
      }
    </style>
    <script>
      var wdi_data = [];
      var event_stack = [];
      <?php
      $image_id_exist = FALSE;
      foreach ($image_rows as $key => $image_row) {
      if ($image_row->id == $image_id) {
        $current_avg_rating = $image_row->avg_rating;
        $current_rate = $image_row->rate;
        $current_rate_count = $image_row->rate_count;
        $current_image_key = $key;
      }
      if ($image_row->id == $current_image_id) {
        $current_image_alt = $image_row->alt;
        $current_image_user_name = $image_row->username;
        $current_image_user_pic = $image_row->profile_picture;
        $current_image_hit_count = $image_row->hit_count;
        $current_image_description = str_replace('#',' #',str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description));
        $current_image_url = $image_row->image_url;
        $current_thumb_url = $image_row->thumb_url;
        $current_filetype = $image_row->filetype;
        $current_filename = $image_row->filename;
        $image_id_exist = TRUE;
      }


      //sanitizing image description
      $instaDesc = $image_row->description;
      $instaDesc = preg_replace('/\\\\/', esc_html('&#92;'), $instaDesc);

      /*last two are ZWSP and P-SEP*/
      $instaDesc = str_replace(array("\r\n", "\n", "\r",' ', ' '), esc_html(''), str_replace('"','\"',$instaDesc));
      $instaDesc = str_replace('#', ' #', $instaDesc);
      ?>

      wdi_data["<?php echo $key; ?>"] = [];
      wdi_data["<?php echo $key; ?>"]["number"] = <?php echo $key + 1; ?>;
      wdi_data["<?php echo $key; ?>"]["id"] = "<?php echo $image_row->id; ?>";
      wdi_data["<?php echo $key; ?>"]["alt"] = "<?php echo $image_row->alt; str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->alt); ?>";
      wdi_data["<?php echo $key; ?>"]["description"] = "<?php echo $instaDesc; ?>";
      wdi_data["<?php echo $key; ?>"]['username'] = "<?php echo $image_row->username;?>";
      wdi_data["<?php echo $key; ?>"]['profile_picture'] = "<?php echo $image_row->profile_picture;?>";

      wdi_data["<?php echo $key; ?>"]["image_url"] = "<?php echo $image_row->image_url; ?>";
      wdi_data["<?php echo $key; ?>"]["thumb_url"] = "<?php echo $image_row->thumb_url; ?>";
      wdi_data["<?php echo $key; ?>"]["date"] = "<?php echo $image_row->date; ?>";
      wdi_data["<?php echo $key; ?>"]["comment_count"] = "<?php echo $image_row->comment_count; ?>";
      wdi_data["<?php echo $key; ?>"]["filetype"] = "<?php echo $image_row->filetype; ?>";
      wdi_data["<?php echo $key; ?>"]["filename"] = "<?php echo $image_row->filename; ?>";
      wdi_data["<?php echo $key; ?>"]["avg_rating"] = "<?php echo $image_row->avg_rating; ?>";
      wdi_data["<?php echo $key; ?>"]["rate"] = "<?php echo $image_row->rate; ?>";
      wdi_data["<?php echo $key; ?>"]["rate_count"] = "<?php echo $image_row->rate_count; ?>";
      wdi_data["<?php echo $key; ?>"]["hit_count"] = "<?php echo $image_row->hit_count; ?>";
      wdi_data["<?php echo $key; ?>"]["comments_data"] = <?php echo isset($image_row->comments_data) ? json_encode($image_row->comments_data) : 'null'; ?>;
      <?php
      }
      ?>

    </script>

    <?php
    if (!$image_id_exist) {
      echo WDILibrary::message(__('The image has been deleted.', "wd-instagram-feed"), 'error');
      die();
    }
    ?>
    <div class="wdi_image_wrap">
      <?php
      if ($enable_image_ctrl_btn) {
        $share_url = add_query_arg(array('action' => 'Share', 'curr_url' => $current_url, 'image_id' => $current_image_id), admin_url('admin-ajax.php')) . '#wdi' . $gallery_id . '/' . $current_image_id;
        ?>
        <div class="wdi_btn_container">
          <div class="wdi_ctrl_btn_container">
            <?php
            if ($option_row->show_image_counts) {
              ?>
              <span class="wdi_image_count_container wdi_ctrl_btn">
              <span class="wdi_image_count"><?php echo $current_image_key + 1; ?></span> / 
              <span><?php echo $image_rows_count; ?></span>
            </span>
              <?php
            }
            ?>
            <i title="<?php echo __('Play', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_play_pause fa fa-play"></i>
            <?php if ($enable_image_fullscreen) {
              if (!$open_with_fullscreen) {
                ?>
                <i title="<?php echo __('Maximize', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_resize-full fa fa-expand "></i>
                <?php
              }
              ?>
              <i title="<?php echo __('Fullscreen', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_fullscreen fa fa-arrows-alt"></i>
            <?php } if ($popup_enable_info) { ?>
              <i title="<?php echo __('Show info', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_info fa fa-info"></i>
            <?php } if ($enable_comment_social) { ?>
              <i title="<?php echo __('Show comments', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_comment fa fa-comment"></i>
            <?php } if ($popup_enable_rate) { ?>
              <i title="<?php echo __('Show rating', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_rate fa fa-star"></i>
            <?php }
            $is_embed = preg_match('/EMBED/', $current_filetype) == 1 ? TRUE : FALSE;
            $share_image_url = urlencode( $is_embed ? $current_thumb_url : site_url() . '/' . $WD_WDI_UPLOAD_DIR . $current_image_url);
            if ($enable_image_facebook) {
              ?>
              <a id="wdi_facebook_a" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Facebook', "wd-instagram-feed"); ?>">
                <i title="<?php echo __('Share on Facebook', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_facebook fa fa-facebook"></i>
              </a>
              <?php
            }
            if ($enable_image_twitter) {
              ?>
              <a id="wdi_twitter_a" href="https://twitter.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Twitter', "wd-instagram-feed"); ?>">
                <i title="<?php echo __('Share on Twitter', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_twitter fa fa-twitter"></i>
              </a>
              <?php
            }
            if ($enable_image_google) {
              ?>
              <a id="wdi_google_a" href="https://plus.google.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Google+', "wd-instagram-feed"); ?>">
                <i title="<?php echo __('Share on Google+', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_google fa fa-google-plus"></i>
              </a>
              <?php
            }
            if ($enable_image_pinterest) {
              ?>
              <a id="wdi_pinterest_a" href="http://pinterest.com/pin/create/button/?s=100&url=<?php echo urlencode($share_url); ?>&media=<?php echo $share_image_url; ?>&description=<?php echo $current_image_description; ?>" target="_blank" title="<?php echo __('Share on Pinterest', "wd-instagram-feed"); ?>">
                <i title="<?php echo __('Share on Pinterest', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_pinterest fa fa-pinterest"></i>
              </a>
              <?php
            }
            if ($enable_image_tumblr) {
              ?>
              <a id="wdi_tumblr_a" href="https://www.tumblr.com/share/photo?source=<?php echo $share_image_url; ?>&caption=<?php echo urlencode($current_image_alt); ?>&clickthru=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Tumblr', "wd-instagram-feed"); ?>">
                <i title="<?php echo __('Share on Tumblr', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn wdi_tumblr fa fa-tumblr"></i>
              </a>
              <?php
            }

            if ($enable_share_buttons) {
              ?>
              <span class="wdi_share_btns_container">
              <i onclick="jQuery(this).parent().find('.wdi_share_btns').toggleClass('wdi_share_toggler');jQuery(this).parent().find('.wdi_share_caret').toggleClass('wdi_share_toggler')" title="<?php echo __('Share', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn fa fa-share"></i>
              <p class="wdi_share_btns" style="display:none">
                <a id="wdi_popup_fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_image_url;?>" target="_blank" class='wdi_share_popup_btn wdi_facebook fa fa-facebook'></a>
                <a id="wdi_popup_tw" href="https://twitter.com/home?status=<?php echo $current_image_url;?>" target="_blank" class='wdi_share_popup_btn wdi_twitter fa fa-twitter'></a>
                <a id="wdi_popup_gp" href="https://plus.google.com/share?url=<?php echo $current_image_url;?>" target="_blank" class='wdi_share_popup_btn wdi_google fa fa-google-plus'></a>
                <a id="wdi_popup_li" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $current_image_url;?>&title=<?php echo $current_image_description?>" target="_blank" class='wdi_share_popup_btn wdi_linkedin fa fa-linkedin'></a>
                <a id="wdi_popup_pt" href="https://pinterest.com/pin/create/button/?url=<?php echo $current_image_url;?>&media=<?php echo $current_image_url;?>media/?size=l&description=<?php echo $current_image_description?>" target="_blank" class='wdi_share_popup_btn wdi_pinterest fa fa-pinterest'></a>
              </p>
              <i class="wdi_share_caret " style="display:none;"></i>
            </span>


              <?php
            }


            if ($option_row->popup_enable_fullsize_image) {
              ?>
              <a id="wdi_fullsize_image" href="<?php echo !$is_embed ? site_url() . '/' . $WD_WDI_UPLOAD_DIR . $current_image_url : $current_image_url; ?>" target="_blank">
                <i title="<?php echo __('Open image in Instagram.', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn fa fa-instagram"></i>
              </a>
              <?php
            }
            if ($option_row->popup_enable_download) {
              $style = 'none';
              $current_image_arr = explode('/', $current_image_url);
              if (!$is_embed) {
                $download_href = site_url() . '/' . $WD_WDI_UPLOAD_DIR . $current_image_url;
                $style = 'inline-block';
              }
              elseif (preg_match('/FLICKR/', $current_filetype) == 1) {
                $download_href = $current_filename;
                $style = 'inline-block';
              }
              elseif (preg_match('/INSTAGRAM/', $current_filetype) == 1) {
                if(preg_match('/IMAGE/',$current_filetype) == 1){
                  $download_href = substr_replace($current_thumb_url, 'l', -1);
                }
                if(preg_match('/VIDEO/',$current_filetype) == 1){
                  $download_href = $current_filename;
                }


                $style = 'inline-block';
              }
              ?>
              <a id="wdi_download" href="<?php echo $download_href; ?>" target="_blank" download="<?php echo end($current_image_arr); ?>" style="display: <?php echo $style; ?>;">
                <i title="<?php echo __('Download original image', "wd-instagram-feed"); ?>" class="wdi_ctrl_btn fa fa-download"></i>
              </a>
              <?php
            }
            ?>
          </div>
          <div class="wdi_toggle_container">
            <i class="wdi_toggle_btn fa <?php echo (($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'); ?>"></i>
          </div>
        </div>
        <?php
      }
      $current_pos = 0;

      if ($enable_image_filmstrip) {
        ?>
        <div class="wdi_filmstrip_container">
          <div class="wdi_filmstrip_left"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-left' : 'fa-angle-up'); ?> "></i></div>
          <div class="wdi_filmstrip">
            <div class="wdi_filmstrip_thumbnails">
              <?php
              foreach ($image_rows as $key => $image_row) {
                if ($image_row->id == $current_image_id) {
                  $current_pos = $key * (($filmstrip_direction == 'horizontal' ? $image_filmstrip_width : $image_filmstrip_height) + $filmstrip_thumb_margin_hor);
                  $current_key = $key;
                }

                $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
                if (!$is_embed) {
                  list($image_thumb_width, $image_thumb_height) = getimagesize(htmlspecialchars_decode(ABSPATH . $WD_WDI_UPLOAD_DIR . $image_row->thumb_url, ENT_COMPAT | ENT_QUOTES));
                }
                else {
                  if (isset($image_row->resolution) &&  $image_row->resolution!= '') {
                    $resolution_arr = explode(" ",$image_row->resolution);
                    $resolution_w = intval($resolution_arr[0]);
                    $resolution_h = intval($resolution_arr[2]);
                    if($resolution_w != 0 && $resolution_h != 0){
                      $scale = $scale = max($image_filmstrip_width / $resolution_w, $image_filmstrip_height / $resolution_h);
                      $image_thumb_width = $resolution_w * $scale;
                      $image_thumb_height = $resolution_h * $scale;
                    }
                    else{
                      $image_thumb_width = $image_filmstrip_width;
                      $image_thumb_height = $image_filmstrip_height;
                    }
                  }
                  else {
                    $image_thumb_width = $image_filmstrip_width;
                    $image_thumb_height = $image_filmstrip_height;
                  }
                }
                $scale = max($image_filmstrip_width / $image_thumb_width, $image_filmstrip_height / $image_thumb_height);
                $image_thumb_width *= $scale;
                $image_thumb_height *= $scale;
                $thumb_left = ($image_filmstrip_width - $image_thumb_width) / 2;
                $thumb_top = ($image_filmstrip_height - $image_thumb_height) / 2;
                ?>
                <div id="wdi_filmstrip_thumbnail_<?php echo $key; ?>" class="wdi_filmstrip_thumbnail <?php echo (($image_row->id == $current_image_id) ? 'wdi_thumb_active' : 'wdi_thumb_deactive'); ?>">
                  <img style="width:<?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" class="wdi_filmstrip_thumbnail_img" src="<?php echo ($is_embed ? "" : site_url() . '/' . $WD_WDI_UPLOAD_DIR) . $image_row->thumb_url; ?>" onclick="wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), '<?php echo $key; ?>', wdi_data)" ontouchend="wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), '<?php echo $key; ?>', wdi_data)" image_id="<?php echo $image_row->id; ?>" image_key="<?php echo $key; ?>" alt="<?php echo $image_row->alt; ?>" />
                </div>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="wdi_filmstrip_right"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-right' : 'fa-angle-down'); ?>"></i></div>
        </div>
        <?php
      }
      if ($watermark_type != 'none') {
        ?>
        <div class="wdi_image_container">
          <div class="wdi_watermark_container">
            <div style="display:table; margin:0 auto;">
            <span class="wdi_watermark_spun" id="wdi_watermark_container">
              <?php
              if ($watermark_type == 'image') {
                ?>
                <a href="<?php echo urldecode($watermark_link); ?>" target="_blank">
                <img class="wdi_watermark_image wdi_watermark" src="<?php echo $watermark_url; ?>" />
              </a>
                <?php
              }
              elseif ($watermark_type == 'text') {
                ?>
                <a class="wdi_none_selectable wdi_watermark_text wdi_watermark" target="_blank" href="<?php echo $watermark_link; ?>"><?php echo $watermark_text; ?></a>
                <?php
              }
              ?>
            </span>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
      <div id="wdi_image_container" class="wdi_image_container">
        <div class="wdi_image_info_container1">
          <div class="wdi_image_info_container2">
            <span class="wdi_image_info_spun">
              <div class="wdi_image_info" <?php if(trim($current_image_alt) == '' && trim($current_image_description) == '') { echo 'style="background:none;"'; } ?>>
                <div class="wdi_image_title">
                    <div class="wdi_title" onclick="window.open('//instagram.com/<?php echo $current_image_user_name; ?>','_blank')">
                      <div class="wdi_header_text"><?php echo $current_image_user_name;?></div>
                      <div class="wdi_users_img_wrap"><img src="<?php echo  $current_image_user_pic;?>" alt=""></div>
                    </div>
                </div>
                <div class="wdi_image_description"><?php echo html_entity_decode($current_image_description); ?></div>
              </div>
            </span>
          </div>
        </div>

        <div class="wdi_slide_container">
          <div class="wdi_slide_bg">
            <div class="wdi_slider">
              <?php
              $current_key = -6;
              foreach ($image_rows as $key => $image_row) {

                $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
                $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/',$image_row->filetype)==1 ? true :false;
                if ($image_row->id == $current_image_id) {
                  $current_key = $key;
                  ?>
                  <span class="wdi_popup_image_spun" id="wdi_popup_image" image_id="<?php echo $image_row->id; ?>">
                <span class="wdi_popup_image_spun1" style="display: table; width: inherit; height: inherit;">
                  <span class="wdi_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">
                    <?php
                    if (!$is_embed) {
                      ?>
                      <img class="wdi_popup_image wdi_popup_watermark" src="<?php echo site_url() . '/' . $WD_WDI_UPLOAD_DIR . $image_row->image_url; ?>" alt="<?php echo $image_row->alt; ?>" />

                      <?php
                    }
                    else {  /*$is_embed*/ ?>
                      <span class="wdi_popup_embed wdi_popup_watermark" style="display:table; table-layout:fixed;">
                        <?php
                        if($is_embed_instagram_post){
                          $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0);
                          $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0);
                          if($post_height <$post_width +88 ){
                            $post_width =$post_height -88;
                          }
                          else{
                            $post_height =$post_width +88;
                          }
                          WDILibraryEmbed::display_embed($image_row->filetype, $image_row->filename, array('class'=>"wdi_embed_frame", 'frameborder'=>"0", 'style'=>"width:".$post_width."px; height:".$post_height."px; vertical-align:middle; display:inline-block; position:relative;"));
                        }
                        else{
                          WDILibraryEmbed::display_embed($image_row->filetype, $image_row->filename, array('class'=>"wdi_embed_frame", 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=>"width:inherit; height:inherit; vertical-align:middle; display:table-cell;"));
                        }
                        ?>
                      </span>
                      <?php
                    }
                    ?>                    
                  </span>
                </span>
              </span>
                  <span class="wdi_popup_image_second_spun">                
              </span>
                  <input type="hidden" id="wdi_current_image_key" value="<?php echo $key; ?>" />
                  <?php
                  break;
                }
              }
              ?>
            </div>
          </div>
        </div>
        <a id="wdi_spider_popup_left" <?php echo ($option_row->enable_loop == 0 && $current_key == 0) ? 'style="display: none;"' : ''; ?>><span id="wdi_spider_popup_left-ico"><span><i class="wdi_prev_btn fa <?php echo $theme_row->lightbox_rl_btn_style; ?>-left"></i></span></span></a>
        <a id="wdi_spider_popup_right" <?php echo ($option_row->enable_loop == 0 && $current_key == count($image_rows) - 1) ? 'style="display: none;"' : ''; ?>><span id="wdi_spider_popup_right-ico"><span><i class="wdi_next_btn fa <?php echo $theme_row->lightbox_rl_btn_style; ?>-right"></i></span></span></a>
      </div>
    </div>
    <?php if ($enable_comment_social) { ?>
      <div class="wdi_comment_wrap">
        <div class="wdi_comment_container wdi_close">
          <div id="ajax_loading" style="position:absolute;">
            <div id="opacity_div" style="display:none; background-color:rgba(255, 255, 255, 0.2); position:absolute; z-index:10150;"></div>
          <span id="loading_div" style="display:none; text-align:center; position:relative; vertical-align:middle; z-index:10170">
            <img src="<?php echo WDI_URL . '/images/ajax_loader.png'; ?>" class="wdi_spider_ajax_loading" style="width:50px;">
          </span>
          </div>
          <div class="wdi_comments">
            <?php
            $captcha_error_message = '';
            $email_error_message = '';
            $wdi_name = '';
            $wdi_comment = '';
            $wdi_email = '';
            if (isset($_POST['ajax_task']) && (esc_html(stripslashes($_POST['ajax_task'])) === 'save')) {
              if ($option_row->popup_enable_captcha) {
                $wdi_captcha_input = (isset($_POST['wdi_captcha_input']) ? esc_html(stripslashes($_POST['wdi_captcha_input'])) : '');
                @session_start();
                $wdi_captcha_code = (isset($_SESSION['wdi_captcha_code']) ? esc_html(stripslashes($_SESSION['wdi_captcha_code'])) : '');
                if ($wdi_captcha_input !== $wdi_captcha_code) {
                  $captcha_error_message = __('Error. Incorrect Verification Code.', "wd-instagram-feed");
                  $wdi_name = (isset($_POST['wdi_name']) ? esc_html(stripslashes($_POST['wdi_name'])) : '');
                  $wdi_comment = (isset($_POST['wdi_comment']) ? esc_html(stripslashes($_POST['wdi_comment'])) : '');
                  $wdi_email = (isset($_POST['wdi_email']) ? esc_html(stripslashes($_POST['wdi_email'])) : '');
                }
              }
              if ($option_row->popup_enable_email && isset($_POST['wdi_email']) && !is_email(stripslashes($_POST['wdi_email']))) {
                $email_error_message = __( 'This is not a valid email address.', "wd-instagram-feed" );
                $wdi_name = (isset($_POST['wdi_name']) ? esc_html(stripslashes($_POST['wdi_name'])) : '');
                $wdi_comment = (isset($_POST['wdi_comment']) ? esc_html(stripslashes($_POST['wdi_comment'])) : '');
                $wdi_email = (isset($_POST['wdi_email']) ? esc_html(stripslashes($_POST['wdi_email'])) : '');
              }
            }
            ?>
            <div id="wdi_comments">
              <div title="<?php echo __('Hide Comments', "wd-instagram-feed"); ?>" class="wdi_comments_close">
                <i class="wdi_comments_close_btn fa fa-arrow-<?php echo $theme_row->lightbox_comment_pos; ?>"></i>
              </div>

              <form id="wdi_comment_form" style="display:none !important;"> method="post" action="<?php echo $popup_url; ?>"><!--Deprecated-->
                <p><label for="wdi_name"><?php echo __('Name', "wd-instagram-feed"); ?> * </label></p>
                <p><input type="text" name="wdi_name" id="wdi_name" <?php echo ((get_current_user_id() != 0) ? 'readonly="readonly"' : ''); ?>
                          value="<?php echo ((get_current_user_id() != 0) ? get_userdata(get_current_user_id())->display_name : $wdi_name); ?>" /></p>
                <?php
                if ($option_row->popup_enable_email) {
                  ?>
                  <p><label for="wdi_email"><?php echo __('Email', "wd-instagram-feed"); ?> * </label></p>
                  <p><input type="text" name="wdi_email" id="wdi_email"
                            value="<?php echo ((get_current_user_id() != 0) ? get_userdata(get_current_user_id())->user_email : $wdi_email); ?>" /></p>
                  <p><span class="wdi_comment_error"><?php echo $email_error_message; ?></span></p>
                  <?php
                }
                ?>
                <p><label for="wdi_comment"><?php echo __('Comment', "wd-instagram-feed"); ?> * </label></p>
                <p><textarea class="wdi_comment_textarea" name="wdi_comment" id="wdi_comment"><?php echo $wdi_comment; ?></textarea></p>
                <?php
                if ($option_row->popup_enable_captcha) {
                  ?>
                  <p><label for="wdi_captcha_input"><?php echo __('Verification Code', "wd-instagram-feed"); ?></label></p>
                  <p>
                    <input id="wdi_captcha_input" name="wdi_captcha_input" class="wdi_captcha_input" type="text">
                    <img id="wdi_captcha_img" class="wdi_captcha_img" type="captcha" digit="6" src="<?php echo add_query_arg(array('action' => 'wdi_captcha', 'digit' => 6, 'i' => ''), admin_url('admin-ajax.php')); ?>" onclick="wdi_captcha_refresh('wdi_captcha')" ontouchend="wdi_captcha_refresh('wdi_captcha')" />
                    <span id="wdi_captcha_refresh" class="wdi_captcha_refresh" onclick="wdi_captcha_refresh('wdi_captcha')" ontouchend="wdi_captcha_refresh('wdi_captcha')"></span>
                  </p>
                  <p><span class="wdi_comment_error"><?php echo $captcha_error_message; ?></span></p>
                  <?php
                }
                ?>
                <p><input onclick="if (wdi_spider_check_required('wdi_name', '<?php echo __('Name', "wd-instagram-feed"); ?>') <?php if ($option_row->popup_enable_email) { ?> || wdi_spider_check_required('wdi_email', '<?php echo __('Email', "wd-instagram-feed"); ?>') || wdi_spider_check_email('wdi_email') <?php } ?> || wdi_spider_check_required('wdi_comment', '<?php echo __('Comment', "wd-instagram-feed"); ?>')) { return false;}
                    var cur_image_key = parseInt(jQuery('#wdi_current_image_key').val());
                    ++wdi_data[cur_image_key]['comment_count'];
                    wdi_spider_set_input_value('ajax_task', 'save');
                    wdi_spider_set_input_value('image_id', jQuery('#wdi_popup_image').attr('image_id'));
                    wdi_spider_ajax_save('wdi_comment_form');
                    return false;"
                          ontouchend="if (wdi_spider_check_required('wdi_name', '<?php echo __('Name', "wd-instagram-feed"); ?>') <?php if ($option_row->popup_enable_email) { ?> || wdi_spider_check_required('wdi_email', '<?php echo __('Email', "wd-instagram-feed"); ?>') || wdi_spider_check_email('wdi_email') <?php } ?> || wdi_spider_check_required('wdi_comment', '<?php echo __('Comment', "wd-instagram-feed"); ?>')) {return false;}
                                 var cur_image_key = parseInt(jQuery('#wdi_current_image_key').val());
                                 ++wdi_data[cur_image_key]['comment_count'];
                                 wdi_spider_set_input_value('ajax_task', 'save');
                                 wdi_spider_set_input_value('image_id', jQuery('#wdi_popup_image').attr('image_id'));
                                 wdi_spider_ajax_save('wdi_comment_form');
                                 return false;" class="wdi_submit" type="submit" name="wdi_submit" id="wdi_submit" value="<?php echo __('Submit', "wd-instagram-feed"); ?>" /></p>
                <?php echo (!current_user_can('manage_options') && ($comment_moderation && (isset($_POST['wdi_comment']) && esc_html($_POST['wdi_comment'])))) ? __('Your comment is awaiting moderation', "wd-instagram-feed") : ''; ?>
                <input id="ajax_task" name="ajax_task" type="hidden" value="" />
                <input id="image_id" name="image_id" type="hidden" value="<?php echo $image_id; ?>" />
                <input id="comment_id" name="comment_id" type="hidden" value="" />
              </form>
            </div>
            <div id="wdi_added_comments">
              <p class="wdi_no_comment"><?php _e('There are no comments to show','wd-instagram-feed');?></p>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    <a class="wdi_spider_popup_close" onclick="wdi_spider_destroypopup(1000); return false;" ontouchend="wdi_spider_destroypopup(1000); return false;"><span><i class="wdi_close_btn fa fa-times"></i></span></a>

    <script>

      <?php
      if ($option_row->enable_addthis && $option_row->addthis_profile_id) {
      ?>
      var addthis_share = {
        url: "<?php echo urlencode($share_url); ?>"
      }
      <?php
      }
      ?>
      var wdi_trans_in_progress = false;
      var wdi_transition_duration = <?php echo (($slideshow_interval < 4) && ($slideshow_interval != 0)) ? ($slideshow_interval * 1000) / 4 : 800; ?>;
      var wdi_playInterval;
      if ((jQuery("#wdi_spider_popup_wrap").width() >= jQuery(window).width()) || (jQuery("#wdi_spider_popup_wrap").height() >= jQuery(window).height())) {
        jQuery(".wdi_spider_popup_close").attr("class", "wdi_ctrl_btn wdi_spider_popup_close_fullscreen");
      }
      /* Stop autoplay.*/
      window.clearInterval(wdi_playInterval);
      /* Set watermark container size.*/
      function wdi_change_watermark_container() {
        jQuery(".wdi_slider").children().each(function() {
          if (jQuery(this).css("zIndex") == 2) {
            /* This may be neither img nor iframe.*/
            var wdi_current_image_span = jQuery(this).find("img");
            if (!wdi_current_image_span.length) {
              wdi_current_image_span = jQuery(this).find("iframe");
            }
            if (!wdi_current_image_span.length) {
              wdi_current_image_span = jQuery(this).find("video");
            }
            /*set timeout for video to get size according to style, and then put watermark*/
            setTimeout(function () {
              var width = wdi_current_image_span.width();
              var height = wdi_current_image_span.height();


              jQuery(".wdi_watermark_spun").width(width);
              jQuery(".wdi_watermark_spun").height(height);
              jQuery(".wdi_watermark").css({display: ''});
              /* Set watermark image size.*/
              var comment_container_width = 0;
              if (jQuery(".wdi_comment_container").hasClass("wdi_open")) {
                comment_container_width = <?php echo $theme_row->lightbox_comment_width; ?>;
              }
              if (width <= (jQuery(window).width() - comment_container_width)) {
                jQuery(".wdi_watermark_image").css({
                  width: ((jQuery(".wdi_spider_popup_wrap").width() - comment_container_width) * <?php echo $watermark_width / $image_width; ?>)
                });
                jQuery(".wdi_watermark_text, .wdi_watermark_text:hover").css({
                  fontSize: ((jQuery(".wdi_spider_popup_wrap").width() - comment_container_width) * <?php echo $watermark_font_size / $image_width; ?>)
                });
              }
            }, 800);
          }
        });



      }
      var wdi_current_key = '<?php echo $current_key; ?>';
      var wdi_current_filmstrip_pos = <?php echo $current_pos; ?>;
      /* Set filmstrip initial position.*/
      function wdi_set_filmstrip_pos(filmStripWidth) {
        var selectedImagePos = -wdi_current_filmstrip_pos - (jQuery(".wdi_filmstrip_thumbnail").<?php echo $outerWidth_or_outerHeight; ?>(true)) / 2;
        var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".wdi_filmstrip_thumbnails").<?php echo $width_or_height; ?>(), selectedImagePos + filmStripWidth / 2));
        jQuery(".wdi_filmstrip_thumbnails").animate({
        <?php echo $left_or_top; ?>: imagesContainerLeft
      }, {
          duration: 500,
            complete: function () { wdi_filmstrip_arrows(); }
        });
      }
      function wdi_move_filmstrip() {
        var image_left = jQuery(".wdi_thumb_active").position().<?php echo $left_or_top; ?>;
        var image_right = jQuery(".wdi_thumb_active").position().<?php echo $left_or_top; ?> + jQuery(".wdi_thumb_active").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var wdi_filmstrip_width = jQuery(".wdi_filmstrip").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var wdi_filmstrip_thumbnails_width = jQuery(".wdi_filmstrip_thumbnails").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var long_filmstrip_cont_left = jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>;
        var long_filmstrip_cont_right = Math.abs(jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>) + wdi_filmstrip_width;
        if (wdi_filmstrip_width > wdi_filmstrip_thumbnails_width) {
          return;
        }
        if (image_left < Math.abs(long_filmstrip_cont_left)) {
          jQuery(".wdi_filmstrip_thumbnails").animate({
          <?php echo $left_or_top; ?>: -image_left
        }, {
            duration: 500,
              complete: function () { wdi_filmstrip_arrows(); }
          });
        }
        else if (image_right > long_filmstrip_cont_right) {
          jQuery(".wdi_filmstrip_thumbnails").animate({
          <?php echo $left_or_top; ?>: -(image_right - wdi_filmstrip_width)
        }, {
            duration: 500,
              complete: function () { wdi_filmstrip_arrows(); }
          });
        }
      }
      /* Show/hide filmstrip arrows.*/
      function wdi_filmstrip_arrows() {
        if (jQuery(".wdi_filmstrip_thumbnails").<?php echo $width_or_height; ?>() < jQuery(".wdi_filmstrip").<?php echo $width_or_height; ?>()) {
          jQuery(".wdi_filmstrip_left").hide();
          jQuery(".wdi_filmstrip_right").hide();
        }
        else {
          jQuery(".wdi_filmstrip_left").show();
          jQuery(".wdi_filmstrip_right").show();
        }
      }
      function wdi_testBrowser_cssTransitions() {
        return wdi_testDom('Transition');
      }
      function wdi_testBrowser_cssTransforms3d() {
        return wdi_testDom('Perspective');
      }
      function wdi_testDom(prop) {
        /* Browser vendor CSS prefixes.*/
        var browserVendors = ['', '-webkit-', '-moz-', '-ms-', '-o-', '-khtml-'];
        /* Browser vendor DOM prefixes.*/
        var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
        var i = domPrefixes.length;
        while (i--) {
          if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
            return true;
          }
        }
        return false;
      }
      function wdi_cube(tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction) {
        /* If browser does not support 3d transforms/CSS transitions.*/
        if (!wdi_testBrowser_cssTransitions()) {
          return wdi_fallback(current_image_class, next_image_class, direction);
        }
        if (!wdi_testBrowser_cssTransforms3d()) {
          return wdi_fallback3d(current_image_class, next_image_class, direction);
        }
        wdi_trans_in_progress = true;
        /* Set active thumbnail.*/
        jQuery(".wdi_filmstrip_thumbnail").removeClass("wdi_thumb_active").addClass("wdi_thumb_deactive");
        jQuery("#wdi_filmstrip_thumbnail_" + wdi_current_key).removeClass("wdi_thumb_deactive").addClass("wdi_thumb_active");
        jQuery(".wdi_slide_bg").css('perspective', 1000);
        jQuery(current_image_class).css({
          transform : 'translateZ(' + tz + 'px)',
          backfaceVisibility : 'hidden'
        });
        jQuery(next_image_class).css({
          opacity : 1,
          filter: 'Alpha(opacity=100)',
          backfaceVisibility : 'hidden',
          transform : 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY('+ nry +'deg) rotateX('+ nrx +'deg)'
        });
        jQuery(".wdi_slider").css({
          transform: 'translateZ(-' + tz + 'px)',
          transformStyle: 'preserve-3d'
        });
        /* Execution steps.*/
        setTimeout(function () {
          jQuery(".wdi_slider").css({
            transition: 'all ' + wdi_transition_duration + 'ms ease-in-out',
            transform: 'translateZ(-' + tz + 'px) rotateX('+ wrx +'deg) rotateY('+ wry +'deg)'
          });
        }, 20);
        /* After transition.*/
        jQuery(".wdi_slider").one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(wdi_after_trans));
        function wdi_after_trans() {
          jQuery(current_image_class).removeAttr('style');
          jQuery(next_image_class).removeAttr('style');
          jQuery(".wdi_slider").removeAttr('style');
          jQuery(current_image_class).css({'opacity' : 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, filter: 'Alpha(opacity=100)', 'z-index' : 2});

          wdi_trans_in_progress = false;
          jQuery(current_image_class).html('');
          if (typeof event_stack !== 'undefined') {
            if (event_stack.length > 0) {
              key = event_stack[0].split("-");
              event_stack.shift();
              wdi_change_image(key[0], key[1], wdi_data, true);
            }
          }
          wdi_change_watermark_container();
        }
      }
      function wdi_cubeH(current_image_class, next_image_class, direction) {
        /* Set to half of image width.*/
        var dimension = jQuery(current_image_class).width() / 2;
        if (direction == 'right') {
          wdi_cube(dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          wdi_cube(dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction);
        }
      }
      function wdi_cubeV(current_image_class, next_image_class, direction) {
        /* Set to half of image height.*/
        var dimension = jQuery(current_image_class).height() / 2;
        /* If next slide.*/
        if (direction == 'right') {
          wdi_cube(dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          wdi_cube(dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction);
        }
      }
      /* For browsers that does not support transitions.*/
      function wdi_fallback(current_image_class, next_image_class, direction) {
        wdi_fade(current_image_class, next_image_class, direction);
      }
      /* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
      function wdi_fallback3d(current_image_class, next_image_class, direction) {
        wdi_sliceV(current_image_class, next_image_class, direction);
      }
      function wdi_none(current_image_class, next_image_class, direction) {
        jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
        jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
        /* Set active thumbnail.*/
        jQuery(".wdi_filmstrip_thumbnail").removeClass("wdi_thumb_active").addClass("wdi_thumb_deactive");
        jQuery("#wdi_filmstrip_thumbnail_" + wdi_current_key).removeClass("wdi_thumb_deactive").addClass("wdi_thumb_active");
        wdi_trans_in_progress = false;
        jQuery(current_image_class).html('');
        wdi_change_watermark_container();
      }
      function wdi_fade(current_image_class, next_image_class, direction) {
        /* Set active thumbnail.*/
        jQuery(".wdi_filmstrip_thumbnail").removeClass("wdi_thumb_active").addClass("wdi_thumb_deactive");
        jQuery("#wdi_filmstrip_thumbnail_" + wdi_current_key).removeClass("wdi_thumb_deactive").addClass("wdi_thumb_active");
        if (wdi_testBrowser_cssTransitions()) {
          jQuery(next_image_class).css('transition', 'opacity ' + wdi_transition_duration + 'ms linear');
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          wdi_change_watermark_container();
        }
        else {
          jQuery(current_image_class).animate({'opacity' : 0, 'z-index' : 1}, wdi_transition_duration);
          jQuery(next_image_class).animate({
            'opacity' : 1,
            'z-index': 2
          }, {
            duration: wdi_transition_duration,
            complete: function () {

              wdi_trans_in_progress = false;
              jQuery(current_image_class).html('');
              wdi_change_watermark_container(); }
          });
          /* For IE.*/
          jQuery(current_image_class).fadeTo(wdi_transition_duration, 0);
          jQuery(next_image_class).fadeTo(wdi_transition_duration, 1);
        }
      }
      function wdi_grid(cols, rows, ro, tx, ty, sc, op, current_image_class, next_image_class, direction) {
        /* If browser does not support CSS transitions.*/
        if (!wdi_testBrowser_cssTransitions()) {
          return wdi_fallback(current_image_class, next_image_class, direction);
        }
        wdi_trans_in_progress = true;
        /* Set active thumbnail.*/
        jQuery(".wdi_filmstrip_thumbnail").removeClass("wdi_thumb_active").addClass("wdi_thumb_deactive");
        jQuery("#wdi_filmstrip_thumbnail_" + wdi_current_key).removeClass("wdi_thumb_deactive").addClass("wdi_thumb_active");
        /* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
        var count = (wdi_transition_duration) / (cols + rows);
        /* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
        function wdi_gridlet(width, height, top, img_top, left, img_left, src, imgWidth, imgHeight, c, r) {
          var delay = (c + r) * count;
          /* Return a gridlet elem with styles for specific transition.*/
          return jQuery('<span class="wdi_gridlet" />').css({
            display : "block",
            width : width,
            height : height,
            top : top,
            left : left,
            backgroundImage : 'url("' + src + '")',
            backgroundColor: jQuery(".wdi_spider_popup_wrap").css("background-color"),
            /*backgroundColor: 'rgba(0, 0, 0, 0)',*/
            backgroundRepeat: 'no-repeat',
            backgroundPosition : img_left + 'px ' + img_top + 'px',
            backgroundSize : imgWidth + 'px ' + imgHeight + 'px',
            transition : 'all ' + wdi_transition_duration + 'ms ease-in-out ' + delay + 'ms',
            transform : 'none'
          });
        }
        /* Get the current slide's image.*/
        var cur_img = jQuery(current_image_class).find('img');
        /* Create a grid to hold the gridlets.*/
        var grid = jQuery('<span style="display: block;" />').addClass('wdi_grid');
        /* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
        jQuery(current_image_class).prepend(grid);
        /* Vars to calculate positioning/size of gridlets.*/
        var cont = jQuery(".wdi_slide_bg");
        var imgWidth = cur_img.width();
        var imgHeight = cur_img.height();
        var contWidth = cont.width(),
          contHeight = cont.height(),
          colWidth = Math.floor(contWidth / cols),
          rowHeight = Math.floor(contHeight / rows),
          colRemainder = contWidth - (cols * colWidth),
          colAdd = Math.ceil(colRemainder / cols),
          rowRemainder = contHeight - (rows * rowHeight),
          rowAdd = Math.ceil(rowRemainder / rows),
          leftDist = 0,
          img_leftDist = Math.ceil((jQuery(".wdi_slide_bg").width() - cur_img.width()) / 2);
        var imgSrc = typeof cur_img.attr('src')=='undefined' ? '' :cur_img.attr('src');
        /* tx/ty args can be passed as 'auto'/'min-auto' (meaning use slide width/height or negative slide width/height).*/
        tx = tx === 'auto' ? contWidth : tx;
        tx = tx === 'min-auto' ? - contWidth : tx;
        ty = ty === 'auto' ? contHeight : ty;
        ty = ty === 'min-auto' ? - contHeight : ty;
        /* Loop through cols.*/
        for (var i = 0; i < cols; i++) {
          var topDist = 0,
            img_topDst = Math.floor((jQuery(".wdi_slide_bg").height() - cur_img.height()) / 2),
            newColWidth = colWidth;
          /* If imgWidth (px) does not divide cleanly into the specified number of cols, adjust individual col widths to create correct total.*/
          if (colRemainder > 0) {
            var add = colRemainder >= colAdd ? colAdd : colRemainder;
            newColWidth += add;
            colRemainder -= add;
          }
          /* Nested loop to create row gridlets for each col.*/
          for (var j = 0; j < rows; j++)  {
            var newRowHeight = rowHeight,
              newRowRemainder = rowRemainder;
            /* If contHeight (px) does not divide cleanly into the specified number of rows, adjust individual row heights to create correct total.*/
            if (newRowRemainder > 0) {
              add = newRowRemainder >= rowAdd ? rowAdd : rowRemainder;
              newRowHeight += add;
              newRowRemainder -= add;
            }
            /* Create & append gridlet to grid.*/
            grid.append(wdi_gridlet(newColWidth, newRowHeight, topDist, img_topDst, leftDist, img_leftDist, imgSrc, imgWidth, imgHeight, i, j));
            topDist += newRowHeight;
            img_topDst -= newRowHeight;
          }
          img_leftDist -= newColWidth;
          leftDist += newColWidth;
        }
        /* Set event listener on last gridlet to finish transitioning.*/
        var last_gridlet = grid.children().last();
        /* Show grid & hide the image it replaces.*/
        grid.show();
        cur_img.css('opacity', 0);
        /* Add identifying classes to corner gridlets (useful if applying border radius).*/
        grid.children().first().addClass('rs-top-left');
        grid.children().last().addClass('rs-bottom-right');
        grid.children().eq(rows - 1).addClass('rs-bottom-left');
        grid.children().eq(- rows).addClass('rs-top-right');
        /* Execution steps.*/
        setTimeout(function () {
          grid.children().css({
            opacity: op,
            transform: 'rotate('+ ro +'deg) translateX('+ tx +'px) translateY('+ ty +'px) scale('+ sc +')'
          });
        }, 1);
        jQuery(next_image_class).css('opacity', 1);
        /* After transition.*/
        jQuery(last_gridlet).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(wdi_after_trans));
        function wdi_after_trans() {
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          cur_img.css('opacity', 1);
          grid.remove();
          wdi_trans_in_progress = false;
          jQuery(current_image_class).html('');
          if (typeof event_stack !== 'undefined') {
            if (event_stack.length > 0) {
              key = event_stack[0].split("-");
              event_stack.shift();
              wdi_change_image(key[0], key[1], wdi_data, true);
            }
          }
          wdi_change_watermark_container();
        }
      }
      function wdi_sliceH(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        wdi_grid(1, 8, 0, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function wdi_sliceV(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'min-auto';
        }
        else if (direction == 'left') {
          var translateY = 'auto';
        }
        wdi_grid(10, 1, 0, 0, translateY, 1, 0, current_image_class, next_image_class, direction);
      }
      function wdi_slideV(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'auto';
        }
        else if (direction == 'left') {
          var translateY = 'min-auto';
        }
        wdi_grid(1, 1, 0, 0, translateY, 1, 1, current_image_class, next_image_class, direction);
      }
      function wdi_slideH(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        wdi_grid(1, 1, 0, translateX, 0, 1, 1, current_image_class, next_image_class, direction);
      }
      function wdi_scaleOut(current_image_class, next_image_class, direction) {
        wdi_grid(1, 1, 0, 0, 0, 1.5, 0, current_image_class, next_image_class, direction);
      }
      function wdi_scaleIn(current_image_class, next_image_class, direction) {
        wdi_grid(1, 1, 0, 0, 0, 0.5, 0, current_image_class, next_image_class, direction);
      }
      function wdi_blockScale(current_image_class, next_image_class, direction) {
        wdi_grid(8, 6, 0, 0, 0, .6, 0, current_image_class, next_image_class, direction);
      }
      function wdi_kaleidoscope(current_image_class, next_image_class, direction) {
        wdi_grid(10, 8, 0, 0, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function wdi_fan(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var rotate = 45;
          var translateX = 100;
        }
        else if (direction == 'left') {
          var rotate = -45;
          var translateX = -100;
        }
        wdi_grid(1, 10, rotate, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function wdi_blindV(current_image_class, next_image_class, direction) {
        wdi_grid(1, 8, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function wdi_blindH(current_image_class, next_image_class, direction) {
        wdi_grid(10, 1, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function wdi_random(current_image_class, next_image_class, direction) {
        var anims = ['sliceH', 'sliceV', 'slideH', 'slideV', 'scaleOut', 'scaleIn', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV'];
        /* Pick a random transition from the anims array.*/
        this["wdi_" + anims[Math.floor(Math.random() * anims.length)]](current_image_class, next_image_class, direction);
      }
      function wdi_pause_stream(parent){
        jQuery(parent).find('video').each(function(){
          jQuery(this).get(0).pause();
        });
      }
      function wdi_change_image(current_key, key, wdi_data, from_effect) {
        wdi_pause_stream('#wdi_image_container');
        jQuery("#wdi_spider_popup_left").show();
        jQuery("#wdi_spider_popup_right").show();
        if (<?php echo $option_row->enable_loop; ?> == 0) {
          if (key == (parseInt(wdi_data.length) - 1)) {
            jQuery("#wdi_spider_popup_right").hide();
          }
          if (key == 0) {
            jQuery("#wdi_spider_popup_left").hide();
          }
        }
        /* Pause videos.*/
        jQuery("#wdi_image_container").find("iframe").each(function () {
          jQuery(this)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
          jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
          jQuery(this)[0].contentWindow.postMessage('pause', '*');
        });

        if (typeof wdi_data[key] != 'undefined') {
          if (typeof wdi_data[current_key] != 'undefined') {
            if (jQuery('.wdi_ctrl_btn').hasClass('fa-pause')) {

              wdi_play();
            }
            if (!from_effect) {
              /* Change image key.*/
              jQuery("#wdi_current_image_key").val(key);
              /*if (current_key == '-1') {
               current_key = jQuery(".wdi_thumb_active").children("img").attr("image_key");
               }*/
            }
            if (wdi_trans_in_progress) {
              event_stack.push(current_key + '-' + key);
              return;
            }
            var direction = 'right';
            if (wdi_current_key > key) {
              var direction = 'left';
            }
            else if (wdi_current_key == key) {
              return;
            }
            /*jQuery("#wdi_spider_popup_left").hover().css({"display": "inline"});
             jQuery("#wdi_spider_popup_right").hover().css({"display": "inline"});*/
            jQuery(".wdi_image_count").html(wdi_data[key]["number"]);
            /* Set filmstrip initial position.*/
            jQuery(".wdi_watermark").css({display: 'none'});
            /* Set active thumbnail position.*/
            wdi_current_filmstrip_pos = key * (jQuery(".wdi_filmstrip_thumbnail").<?php echo $width_or_height; ?>() + 2 + 2 * <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>);
            wdi_current_key = key;
            /* Change hash.*/
            window.location.hash = "wdi<?php echo $gallery_id; ?>/" + wdi_data[key]["id"];
            /* Change image id for rating.*/
            <?php if ($popup_enable_rate) { ?>
            jQuery("#wdi_rate_form input[name='image_id']").val(wdi_data[key]["id"]);
            jQuery("#wdi_star").attr("data-score", wdi_data[key]["avg_rating"]);
            jQuery("#wdi_star").removeAttr("title");
            wdi_rating(wdi_data[key]["rate"], wdi_data[key]["rate_count"], wdi_data[key]["avg_rating"], key);
            <?php } ?>
            /* Increase image hit counter.*/
            wdi_spider_set_input_value('rate_ajax_task', 'save_hit_count');
            //wdi_spider_rate_ajax_save('wdi_rate_form');
            jQuery(".wdi_image_hits span").html(++wdi_data[key]["hit_count"]);
            /* Change image id.*/
            jQuery("#wdi_popup_image").attr('image_id', wdi_data[key]["id"]);


            //creating image title template
            var wdi_title_div = jQuery('<div class="wdi_title"></div>').on('click',function(){window.open('//instagram.com/'+wdi_data[key]['username'],'_blank');});
            wdi_title_div.append(jQuery('<div class="wdi_header_text">' +wdi_data[key]['username']+'</div>'));
            wdi_title_div.append(jQuery('<div class="wdi_users_img_wrap"><img src="' +wdi_data[key]['profile_picture']+'" alt=""></div>'));

            //resetting title and description for custom scrollbar
            jQuery('.wdi_image_info_spun').html('<div class="wdi_image_info" >'+
              '<div class="wdi_image_title"></div>'+
              '<div class="wdi_image_description"></div>'+
              '</div>');

            /* Change image title, description.*/
            jQuery(".wdi_image_title").html(jQuery('<span style="display: block;" />').append(wdi_title_div));
            jQuery(".wdi_image_description").html(jQuery('<span style="display: block;" />').html(wdi_data[key]["description"]).text());
            jQuery(".wdi_image_info").removeAttr("style");
            if (wdi_data[key]["alt"].trim() == "") {
              if (wdi_data[key]["description"].trim() == "") {
                jQuery(".wdi_image_info").css("background", "none");
              }
            }
            if (jQuery(".wdi_image_info_container1").css("display") != 'none') {
              jQuery(".wdi_image_info_container1").css("display", "table-cell");
            }
            else {
              jQuery(".wdi_image_info_container1").css("display", "none");
            }
            /* Change image rating.*/
            if (jQuery(".wdi_image_rate_container1").css("display") != 'none') {
              jQuery(".wdi_image_rate_container1").css("display", "table-cell");
            }
            else {
              jQuery(".wdi_image_rate_container1").css("display", "none");
            }
            var current_image_class = jQuery(".wdi_popup_image_spun").css("zIndex") == 2 ? ".wdi_popup_image_spun" : ".wdi_popup_image_second_spun";
            var next_image_class = current_image_class == ".wdi_popup_image_second_spun" ? ".wdi_popup_image_spun" : ".wdi_popup_image_second_spun";

            var is_embed = wdi_data[key]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            var is_embed_instagram_post = wdi_data[key]['filetype'].indexOf('INSTAGRAM_POST') > -1 ? true :false;
            var cur_height = jQuery(current_image_class).height();
            var cur_width = jQuery(current_image_class).width();
            var innhtml = '<span class="wdi_popup_image_spun1" style="display: table; width: inherit; height: inherit;"><span class="wdi_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">';
            if (!is_embed) {
              innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="wdi_popup_image wdi_popup_watermark" src="<?php echo site_url() . '/' . $WD_WDI_UPLOAD_DIR; ?>' + jQuery('<span style="display: block;" />').html(wdi_data[key]["image_url"]).text() + '" alt="' + wdi_data[key]["alt"] + '" />';
            }
            else { /*is_embed*/

              innhtml += '<span style="display:table; table-layout:fixed; height: ' + cur_height + 'px; width: ' + cur_width + 'px;" class="wdi_popup_embed wdi_popup_watermark" >';
              if(is_embed_instagram_post){
                var post_width = 0;
                var post_height = 0;
                if(cur_height <cur_width +88 ){
                  post_height = cur_height;
                  post_width = post_height -88;
                }
                else{
                  post_width = cur_width;
                  post_height = post_width +88 ;
                }
                innhtml += wdi_spider_display_embed(wdi_data[key]['filetype'], wdi_data[key]['filename'], {class:"wdi_embed_frame",  frameborder:"0", allowfullscreen:"allowfullscreen", style:"width:"+post_width+"px; height:"+post_height+"px; vertical-align:middle; display:inline-block; position:relative; top: "+0.5*(cur_height-post_height)+ "px; " });
              }
              else{
                innhtml += wdi_spider_display_embed(wdi_data[key]['filetype'], wdi_data[key]['filename'], {class:"wdi_embed_frame", frameborder:"0", allowfullscreen:"allowfullscreen", style:"width:inherit; height:inherit; vertical-align:middle; display:table-cell;" });
              }
              innhtml += "</span>";
            }
            innhtml += '</span></span>';
            jQuery(next_image_class).html(innhtml);

            function wdi_afterload() {


              //setting max-heigth for custom scrollbar
              var wdi_desc_max_height = jQuery('.wdi_image_info_container1').height()-jQuery(".wdi_ctrl_btn_container").height()-parseInt(jQuery('.wdi_image_info').css('margin-bottom'))-parseInt(jQuery('.wdi_image_info').css('padding-bottom'));
              jQuery('.wdi_image_info').css('max-height',wdi_desc_max_height);
              jQuery('.wdi_image_info').mCustomScrollbar({scrollInertia: 250});





              <?php
              if (isset($option_row->preload_images)) {
                echo 'wdi_preload_images(key);';
              }
              ?>
              wdi_<?php echo $image_effect; ?>(current_image_class, next_image_class, direction);
              jQuery("#wdi_download").show();
              if (!is_embed) {
                jQuery("#wdi_fullsize_image").attr("href", "<?php echo site_url() . '/' . $WD_WDI_UPLOAD_DIR; ?>" + wdi_data[key]['image_url']);
                jQuery("#wdi_download").attr("href", "<?php echo site_url() . '/' . $WD_WDI_UPLOAD_DIR; ?>" + wdi_data[key]['image_url']);
              }
              else {
                jQuery("#wdi_fullsize_image").attr("href", wdi_data[key]['image_url']);
                if (wdi_data[key]['filetype'].indexOf("FLICKR_") > -1) {
                  jQuery("#wdi_download").attr("href", wdi_data[key]['filename']);
                }
                else if (wdi_data[key]['filetype'].indexOf("INSTAGRAM_") > -1) {
                  jQuery("#wdi_download").attr("href", wdi_data[key]['thumb_url'].substring(0, wdi_data[key]['thumb_url'].length - 1) + 'l');
                }
                else {
                  jQuery("#wdi_download").hide();
                }
              }
              var image_arr = wdi_data[key]['image_url'].split("/");
              var wdi_image_url = wdi_data[key]['image_url'];
              var wdi_image_description = wdi_data[key]['description'];
              jQuery("#wdi_download").attr("download", image_arr[image_arr.length - 1]);
              /* Change image social networks urls.*/
              var wdi_share_url = encodeURIComponent("<?php echo add_query_arg(array('action' => 'Share', 'curr_url' => $current_url, 'image_id' => ''), admin_url('admin-ajax.php')); ?>") + "=" + wdi_data[key]['id'] + encodeURIComponent('#wdi<?php echo $gallery_id; ?>/') + wdi_data[key]['id'];

              if (is_embed) {
                var wdi_share_image_url = encodeURIComponent(wdi_data[key]['thumb_url']);
              }
              else {
                var wdi_share_image_url = "<?php echo urlencode(site_url() . '/' . $WD_WDI_UPLOAD_DIR); ?>" + wdi_data[key]['image_url'];
              }
              if (typeof addthis_share != "undefined") {
                addthis_share.url = wdi_share_url;
              }
              jQuery("#wdi_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + wdi_share_url);
              jQuery("#wdi_twitter_a").attr("href", "https://twitter.com/share?url=" + wdi_share_url);
              jQuery("#wdi_google_a").attr("href", "https://plus.google.com/share?url=" + wdi_share_url);
              jQuery("#wdi_pinterest_a").attr("href", "http://pinterest.com/pin/create/button/?s=100&url=" + wdi_share_url + "&media=" + wdi_share_image_url + "&description=" + wdi_data[key]['description']);
              jQuery("#wdi_tumblr_a").attr("href", "https://www.tumblr.com/share/photo?source=" + wdi_share_image_url + "&caption=" + wdi_data[key]['alt'] + "&clickthru=" + wdi_share_url);


              //share popup urls
              jQuery("#wdi_popup_fb").attr('href',"https://www.facebook.com/sharer/sharer.php?u="+wdi_image_url);
              jQuery("#wdi_popup_tw").attr('href',"https://twitter.com/share?url=" + wdi_image_url);
              jQuery("#wdi_popup_gp").attr('href',"https://plus.google.com/share?url=" + wdi_image_url);
              jQuery('#wdi_popup_li').attr('href',"https://www.linkedin.com/shareArticle?mini=true&url="+wdi_image_url+"&title="+wdi_image_description);
              jQuery('#wdi_popup_pt').attr('href',"https://pinterest.com/pin/create/button/?url="+wdi_image_url+"&media="+wdi_image_url+"media/?size=l&description="+wdi_image_description);
              /* Load comments.*/
              if (jQuery(".wdi_comment_container").hasClass("wdi_open")) {
                if (wdi_data[key]["comment_count"] == 0) {
                  jQuery("#wdi_added_comments").html('<p class="wdi_no_comment"><?php _e('There are no comments to show','wd-instagram-feed');?></p>');
                }
                else {
                  jQuery("#wdi_added_comments").show();
                  wdi_spider_set_input_value('ajax_task', 'display');
                  wdi_spider_set_input_value('image_id', jQuery('#wdi_popup_image').attr('image_id'));
                  var cur_image_key = parseInt(jQuery("#wdi_current_image_key").val());
                  wdi_spider_ajax_save('wdi_comment_form',cur_image_key);
                }
              }
              /* Update custom scroll.*/
              // if (typeof jQuery().mCustomScrollbar !== 'undefined') {
              //   if (jQuery.isFunction(jQuery().mCustomScrollbar)) {
              //     jQuery(".wdi_comments").mCustomScrollbar({
              //       advanced:{
              //         updateOnContentResize: true
              //       }
              //     });
              //   }
              // }
              // jQuery(".mCSB_scrollTools").hide();
              <?php
              if ($enable_image_filmstrip) {
              ?>
              wdi_move_filmstrip();
              <?php
              }
              ?>

            }
            if (!is_embed) {
              var cur_img = jQuery(next_image_class).find('img');
              cur_img.one('load', function() {
                wdi_afterload();
              }).each(function() {
                if(this.complete) jQuery(this).load();
              });
            }
            else {
              wdi_afterload();
            }
          }
        }
      }
      jQuery(document).on('keydown', function (e) {
        if (jQuery("#wdi_name").is(":focus") || jQuery("#wdi_email").is(":focus") || jQuery("#wdi_comment").is(":focus") || jQuery("#wdi_captcha_input").is(":focus")) {
          return;
        }
        if (e.keyCode === 39) { /* Right arrow.*/
          wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), parseInt(jQuery('#wdi_current_image_key').val()) + 1, wdi_data)
        }
        else if (e.keyCode === 37) { /* Left arrow.*/
          wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), parseInt(jQuery('#wdi_current_image_key').val()) - 1, wdi_data)
        }
        else if (e.keyCode === 27) { /* Esc.*/
            wdi_spider_destroypopup(1000);
          }
          else if (e.keyCode === 32) { /* Space.*/
              jQuery(".wdi_play_pause").trigger('click');
            }
      });
      function wdi_preload_images(key) {
        count = <?php echo (int) $option_row->preload_images_count / 2; ?>;
        var count_all = wdi_data.length;
        if (count_all < <?php echo $option_row->preload_images_count; ?>) {
          count = 0;
        }
        if (count != 0) {
          for (var i = key - count; i < key + count; i++) {
            var index = parseInt((i + count_all) % count_all);
            var is_embed = wdi_data[index]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            if (typeof wdi_data[index] != "undefined") {
              if (!is_embed) {
                jQuery("<img/>").attr("src", '<?php echo site_url() . '/' . $WD_WDI_UPLOAD_DIR; ?>' + jQuery('<span style="display: block;" />').html(wdi_data[index]["image_url"]).text());
              }
            }
          }
        }
        else {
          for (var i = 0; i < wdi_data.length; i++) {
            var is_embed = wdi_data[i]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            if (typeof wdi_data[index] != "undefined") {
              if (!is_embed) {
                jQuery("<img/>").attr("src", '<?php echo site_url() . '/' . $WD_WDI_UPLOAD_DIR; ?>' + jQuery('<span style="display: block;" />').html(wdi_data[i]["image_url"]).text());
              }
            }
          }
        }
      }
      function wdi_popup_resize() {
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isFullScreen()) {
              jQuery(".wdi_resize-full").show();
              jQuery(".wdi_resize-full").attr("class", "wdi_ctrl_btn wdi_resize-full fa fa-expand");
              jQuery(".wdi_resize-full").attr("title", "<?php echo __('Maximize', "wd-instagram-feed"); ?>");
              jQuery(".wdi_fullscreen").attr("class", "wdi_ctrl_btn wdi_fullscreen fa fa-arrows-alt");
              jQuery(".wdi_fullscreen").attr("title", "<?php echo __('Fullscreen', "wd-instagram-feed"); ?>");
            }
          }
        }
        var comment_container_width = 0;
        if (jQuery(".wdi_comment_container").hasClass("wdi_open")) {
          comment_container_width = <?php echo $theme_row->lightbox_comment_width; ?>;
        }
        if (comment_container_width > jQuery(window).width()) {
          comment_container_width = jQuery(window).width();
          jQuery(".wdi_comment_container").css({
            width: comment_container_width
          });
          jQuery(".wdi_spider_popup_close_fullscreen").hide();
        }
        else {
          jQuery(".wdi_spider_popup_close_fullscreen").show();
        }

        if (!(!(jQuery(window).height() > <?php echo $image_height; ?>) || !(<?php echo $open_with_fullscreen; ?> != 1))) {
          jQuery("#wdi_spider_popup_wrap").css({
            height: <?php echo $image_height; ?>,
            top: '50%',
            marginTop: -<?php echo $image_height / 2; ?>,
            zIndex: 100000
          });

          jQuery(".wdi_image_container").css({height: (<?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)});

          jQuery(".wdi_popup_image").css({
            maxHeight: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          jQuery(".wdi_popup_embed").css({
            height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          wdi_resize_instagram_post();
          <?php if ($filmstrip_direction == 'vertical') { ?>
          jQuery(".wdi_filmstrip_container").css({height: <?php echo $image_height; ?>});
          jQuery(".wdi_filmstrip").css({height: (<?php echo $image_height; ?> - 40)});
          <?php } ?>
          wdi_popup_current_height = <?php echo $image_height; ?>;
        }
      else {
          jQuery("#wdi_spider_popup_wrap").css({
            height: jQuery(window).height(),
            top: 0,
            marginTop: 0,
            zIndex: 100000
          });
          jQuery(".wdi_image_container").css({height: (jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)});

          jQuery(".wdi_popup_image").css({
            maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          jQuery(".wdi_popup_embed").css({
            height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          wdi_resize_instagram_post();
          <?php if ($filmstrip_direction == 'vertical') { ?>
          jQuery(".wdi_filmstrip_container").css({height: (jQuery(window).height())});
          jQuery(".wdi_filmstrip").css({height: (jQuery(window).height() - 40)});
          <?php } ?>
          wdi_popup_current_height = jQuery(window).height();
        }
        if (!(!(jQuery(window).width() >= <?php echo $image_width; ?>) || !(<?php echo $open_with_fullscreen; ?> != 1))) {
          jQuery("#wdi_spider_popup_wrap").css({
            width: <?php echo $image_width; ?>,
            left: '50%',
            marginLeft: -<?php echo $image_width / 2; ?>,
            zIndex: 100000
          });
          jQuery(".wdi_image_wrap").css({width: <?php echo $image_width; ?> - comment_container_width});
          jQuery(".wdi_image_container").css({width: (<?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width)});

          jQuery(".wdi_popup_image").css({
            maxWidth: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          jQuery(".wdi_popup_embed").css({
            width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          wdi_resize_instagram_post();
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".wdi_filmstrip_container").css({width: <?php echo $image_width; ?> - comment_container_width});
          jQuery(".wdi_filmstrip").css({width: (<?php echo $image_width; ?>  - comment_container_width- 40)});
          <?php } ?>
          wdi_popup_current_width = <?php echo $image_width; ?>;
        }
      else {
          jQuery("#wdi_spider_popup_wrap").css({
            width: jQuery(window).width(),
            left: 0,
            marginLeft: 0,
            zIndex: 100000
          });
          jQuery(".wdi_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
          jQuery(".wdi_image_container").css({width: (jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width)});

          jQuery(".wdi_popup_image").css({
            maxWidth: jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          jQuery(".wdi_popup_embed").css({
            width: jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          wdi_resize_instagram_post();
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".wdi_filmstrip_container").css({width: (jQuery(window).width() - comment_container_width)});
          jQuery(".wdi_filmstrip").css({width: (jQuery(window).width() - comment_container_width - 40)});
          <?php } ?>
          wdi_popup_current_width = jQuery(window).width();
        }
        /* Set watermark container size.*/
        wdi_change_watermark_container();
        if (!(!(jQuery(window).height() > <?php echo $image_height - 2 * $theme_row->lightbox_close_btn_top; ?>) || !(jQuery(window).width() >= <?php echo $image_width - 2 * $theme_row->lightbox_close_btn_right; ?>) || !(<?php echo $open_with_fullscreen; ?> != 1))) {
          jQuery(".wdi_spider_popup_close_fullscreen").attr("class", "wdi_spider_popup_close");
        }
      else {
          if (!(!(jQuery("#wdi_spider_popup_wrap").width() <= jQuery(window).width()) || !(jQuery("#wdi_spider_popup_wrap").height() <= jQuery(window).height()))) {
            jQuery(".wdi_spider_popup_close").attr("class", "wdi_ctrl_btn wdi_spider_popup_close_fullscreen");
          }
        }
      }
      jQuery(window).resize(function() {
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isFullScreen()) {
              wdi_popup_resize();
            }
          }
        }
      });
      /* Popup current width/height.*/
      var wdi_popup_current_width = <?php echo $image_width; ?>;
      var wdi_popup_current_height = <?php echo $image_height; ?>;
      /* Open/close comments.*/
      function wdi_comment() {

        jQuery(".wdi_watermark").css({display: 'none'});
        if (jQuery(".wdi_comment_container").hasClass("wdi_open")) {

          /* Close comment.*/
          var border_width = parseInt(jQuery(".wdi_comment_container").css('borderRightWidth'));
          if (!border_width) {
            border_width = 0;
          }
          jQuery(".wdi_comment_container").animate({<?php echo $theme_row->lightbox_comment_pos; ?>: -jQuery(".wdi_comment_container").width() - border_width}, 500);
          jQuery(".wdi_image_wrap").animate({
          <?php echo $theme_row->lightbox_comment_pos; ?>: 0,
            width: jQuery("#wdi_spider_popup_wrap").width()
        }, 500);
          jQuery(".wdi_image_container").animate({
            width: jQuery("#wdi_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
          jQuery(".wdi_popup_image").animate({
            maxWidth: jQuery("#wdi_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
            complete: function () { wdi_change_watermark_container(); }
          });
          jQuery(".wdi_popup_embed").animate({
            width: jQuery("#wdi_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
            complete: function () {
              wdi_resize_instagram_post();
              wdi_change_watermark_container(); }
          });
          jQuery(".wdi_filmstrip_container").animate({<?php echo $width_or_height; ?>: jQuery(".wdi_spider_popup_wrap").<?php echo $width_or_height; ?>()}, 500);
          jQuery(".wdi_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(".wdi_spider_popup_wrap").<?php echo $width_or_height; ?>() - 40}, 500);
          /* Set filmstrip initial position.*/
          wdi_set_filmstrip_pos(jQuery(".wdi_spider_popup_wrap").width() - 40);
          jQuery(".wdi_comment_container").attr("class", "wdi_comment_container wdi_close");
          jQuery(".wdi_comment").attr("title", "<?php echo __('Show Comments', "wd-instagram-feed"); ?>");
          jQuery(".wdi_spider_popup_close_fullscreen").show();
          // console.log("has open:"+jQuery(".wdi_comment_container").hasClass("wdi_open"));
          // console.log("has clos:"+jQuery(".wdi_comment_container").hasClass("wdi_close"));
        }
        else {

          /* Open comment.*/
          var comment_container_width = <?php echo $theme_row->lightbox_comment_width; ?>;
          if (comment_container_width > jQuery(window).width()) {
            comment_container_width = jQuery(window).width();
            jQuery(".wdi_comment_container").css({
              width: comment_container_width
            });
            jQuery(".wdi_spider_popup_close_fullscreen").hide();
            if (jQuery(".wdi_ctrl_btn").hasClass("fa-pause")) {
              var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
              jQuery(".wdi_play_pause").trigger(isMobile ? 'touchend' : 'click');
            }
          }
          else {
            jQuery(".wdi_spider_popup_close_fullscreen").show();
          }
          jQuery(".wdi_comment_container").animate({<?php echo $theme_row->lightbox_comment_pos; ?>: 0}, 500);
          jQuery(".wdi_image_wrap").animate({
          <?php echo $theme_row->lightbox_comment_pos; ?>: jQuery(".wdi_comment_container").width(),
            width: jQuery("#wdi_spider_popup_wrap").width() - jQuery(".wdi_comment_container").width()}, 500);
          jQuery(".wdi_image_container").animate({
            width: jQuery("#wdi_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - jQuery(".wdi_comment_container").width()}, 500);
          jQuery(".wdi_popup_image").animate({
            maxWidth: jQuery("#wdi_spider_popup_wrap").width() - jQuery(".wdi_comment_container").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
            complete: function () { wdi_change_watermark_container(); }
          });
          jQuery(".wdi_popup_embed").animate({
            width: jQuery("#wdi_spider_popup_wrap").width() - jQuery(".wdi_comment_container").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
            complete: function () {
              wdi_resize_instagram_post();
              wdi_change_watermark_container(); }
          });
          jQuery(".wdi_filmstrip_container").css({<?php echo $width_or_height; ?>: jQuery("#wdi_spider_popup_wrap").<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'vertical' ? 0: 'jQuery(".wdi_comment_container").width()'); ?>});
          jQuery(".wdi_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(".wdi_filmstrip_container").<?php echo $width_or_height; ?>() - 40}, 500);
          /* Set filmstrip initial position.*/
          wdi_set_filmstrip_pos(jQuery(".wdi_filmstrip_container").<?php echo $width_or_height; ?>() - 40);
          jQuery(".wdi_comment_container").attr("class", "wdi_comment_container wdi_open");
          jQuery(".wdi_comment").attr("title", "<?php echo __('Hide Comments', "wd-instagram-feed"); ?>");
          /* Load comments.*/

          var cur_image_key = parseInt(jQuery("#wdi_current_image_key").val());
          if (wdi_data[cur_image_key]["comment_count"] != 0) {
            jQuery("#wdi_added_comments").show();/*deprecated*/
            wdi_spider_set_input_value('ajax_task', 'display');/*deprecated*/
            wdi_spider_set_input_value('image_id', jQuery('#wdi_popup_image').attr('image_id'));/*deprecated*/
            wdi_spider_ajax_save('wdi_comment_form',cur_image_key);/*deprecated*/
          }else{
            jQuery("#wdi_added_comments").html('<p class="wdi_no_comment"><?php _e('There are no comments to show','wd-instagram-feed');?></p>');
          }
          // console.log("has open:"+jQuery(".wdi_comment_container").hasClass("wdi_open"));
          // console.log("has clos:"+jQuery(".wdi_comment_container").hasClass("wdi_close"));
        }
      }
      function wdi_reset_zoom() {
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var viewportmeta = document.querySelector('meta[name="viewport"]');
        if (isMobile) {
          if (viewportmeta) {
            viewportmeta.content = 'width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0';
          }
        }
      }
      jQuery(document).ready(function () {


        <?php
        if ($option_row->enable_addthis && $option_row->addthis_profile_id) {
        ?>
        jQuery(".at4-share-outer").show();
        <?php
        }
        ?>
        /* Increase image hit counter.*/
        // wdi_spider_set_input_value('rate_ajax_task', 'save_hit_count');
        // wdi_spider_rate_ajax_save('wdi_rate_form');
        // jQuery(".wdi_image_hits span").html(++wdi_data["<?php echo $current_image_key; ?>"]["hit_count"]);
        // var wdi_hash = window.location.hash;
        // if (!wdi_hash || wdi_hash.indexOf("wdi") == "-1") {
        //   window.location.hash = "wdi<?php echo $gallery_id; ?>/<?php echo $current_image_id; ?>";
        // }
        <?php
        if ($image_right_click) {
        ?>
        /* Disable right click.*/
        jQuery(".wdi_image_wrap").bind("contextmenu", function (e) {
          return false;
        });
        <?php
        }
        ?>
        if (typeof jQuery().swiperight !== 'undefined') {
          if (jQuery.isFunction(jQuery().swiperight)) {
            jQuery('#wdi_spider_popup_wrap').swiperight(function () {
              wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), parseInt(jQuery('#wdi_current_image_key').val()) - 1, wdi_data)
              return false;
            });
          }
        }
        if (typeof jQuery().swipeleft !== 'undefined') {
          if (jQuery.isFunction(jQuery().swipeleft)) {
            jQuery('#wdi_spider_popup_wrap').swipeleft(function () {
              wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), parseInt(jQuery('#wdi_current_image_key').val()) + 1, wdi_data);
              return false;
            });
          }
        }

        wdi_reset_zoom();
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var wdi_click = isMobile ? 'touchend' : 'click';
        jQuery("#wdi_spider_popup_left").on(wdi_click, function () {
          wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), (parseInt(jQuery('#wdi_current_image_key').val()) + wdi_data.length - 1) % wdi_data.length, wdi_data);
          return false;
        });
        jQuery("#wdi_spider_popup_right").on(wdi_click, function () {

          wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), (parseInt(jQuery('#wdi_current_image_key').val()) + 1) % wdi_data.length, wdi_data);
          return false;
        });
        if (navigator.appVersion.indexOf("MSIE 10") != -1 || navigator.appVersion.indexOf("MSIE 9") != -1) {
          setTimeout(function () {
            wdi_popup_resize();

          }, 1);
        }
        else {
          wdi_popup_resize();
        }
        jQuery(".wdi_watermark").css({display: 'none'});
        setTimeout(function () {
          wdi_change_watermark_container();
        }, 500);
        /* If browser doesn't support Fullscreen API.*/
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isNativelySupported()) {
              jQuery(".wdi_fullscreen").hide();
            }
          }
        }
        /* Set image container height.*/
        <?php if ($filmstrip_direction == 'horizontal') { ?>
        jQuery(".wdi_image_container").height(jQuery(".wdi_image_wrap").height() - <?php echo $image_filmstrip_height; ?>);
        jQuery(".wdi_image_container").width(jQuery(".wdi_image_wrap").width());


        <?php }
        else {
        ?>
        jQuery(".wdi_image_container").height(jQuery(".wdi_image_wrap").height());
        jQuery(".wdi_image_container").width(jQuery(".wdi_image_wrap").width() - <?php echo $image_filmstrip_width; ?>);

        <?php
        } ?>
        /* Change default scrollbar in comments.*/
        // if (typeof jQuery().mCustomScrollbar !== 'undefined') {
        //   if (jQuery.isFunction(jQuery().mCustomScrollbar)) {
        //     jQuery(".wdi_comments").mCustomScrollbar({scrollInertia: 150});
        //   }
        // }


        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel" /*FF doesn't recognize mousewheel as of FF3.x*/
        jQuery('.wdi_filmstrip').on(mousewheelevt, function(e) {
          var evt = window.event || e; /* Equalize event object.*/
          evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
          var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
          var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
          if (delta > 0) {
            /* Scroll up.*/
            jQuery(".wdi_filmstrip_left").trigger(isMobile ? 'touchend' : 'click');
          }
          else {
            /* Scroll down.*/
            jQuery(".wdi_filmstrip_right").trigger(isMobile ? 'touchend' : 'click');
          }
        });


        jQuery(".wdi_filmstrip_right").on(wdi_click, function () {
          jQuery( ".wdi_filmstrip_thumbnails" ).stop(true, false);
          if (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> >= -(jQuery(".wdi_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".wdi_filmstrip").<?php echo $width_or_height; ?>())) {
            jQuery(".wdi_filmstrip_left").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> < -(jQuery(".wdi_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".wdi_filmstrip").<?php echo $width_or_height; ?>() - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>)) {
              jQuery(".wdi_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: -(jQuery(".wdi_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".wdi_filmstrip").<?php echo $width_or_height; ?>())}, 500, 'linear');
            }
            else {
              jQuery(".wdi_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>)}, 500, 'linear');
            }
          }
          /* Disable right arrow.*/
          window.setTimeout(function(){
            if (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == -(jQuery(".wdi_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".wdi_filmstrip").<?php echo $width_or_height; ?>())) {
              jQuery(".wdi_filmstrip_right").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });

        jQuery(".wdi_filmstrip_left").on(wdi_click, function () {
          jQuery( ".wdi_filmstrip_thumbnails" ).stop(true, false);
          if (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> < 0) {
            jQuery(".wdi_filmstrip_right").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> > - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>) {
              jQuery(".wdi_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: 0}, 500, 'linear');
            }
            else {
              jQuery(".wdi_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> + <?php echo $image_filmstrip_width + $filmstrip_thumb_margin_hor; ?>)}, 500, 'linear');
            }
          }
          /* Disable left arrow.*/
          window.setTimeout(function(){
            if (jQuery(".wdi_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == 0) {
              jQuery(".wdi_filmstrip_left").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });

        /* Set filmstrip initial position.*/
        wdi_set_filmstrip_pos(jQuery(".wdi_filmstrip").<?php echo $width_or_height; ?>());
        /* Show/hide image title/description.*/
        jQuery(".wdi_info").on(wdi_click, function() {
          if (jQuery(".wdi_image_info_container1").css("display") == 'none') {
            jQuery(".wdi_image_info_container1").css("display", "table-cell");
            jQuery(".wdi_info").attr("title", "<?php echo __('Hide info', "wd-instagram-feed"); ?>");



            if(!jQuery('.wdi_image_info').hasClass('mCustomScrollbar')){
              var wdi_desc_max_height = <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?> -jQuery(".wdi_ctrl_btn_container").height()-parseInt(jQuery('.wdi_image_info').css('margin-bottom'))-parseInt(jQuery('.wdi_image_info').css('padding-bottom'));
              jQuery('.wdi_image_info').css('max-height',wdi_desc_max_height+"px");
              jQuery('.wdi_image_info').mCustomScrollbar({scrollInertia: 250});
            }


          }
          else {
            jQuery(".wdi_image_info_container1").css("display", "none");
            jQuery(".wdi_info").attr("title", "<?php echo __('Show info', "wd-instagram-feed"); ?>");
          }


        });
        /* Show/hide image rating.*/
        jQuery(".wdi_rate").on(wdi_click, function() {
          if (jQuery(".wdi_image_rate_container1").css("display") == 'none') {
            jQuery(".wdi_image_rate_container1").css("display", "table-cell");
            jQuery(".wdi_rate").attr("title", "<?php echo __('Hide rating', "wd-instagram-feed"); ?>");
          }
          else {
            jQuery(".wdi_image_rate_container1").css("display", "none");
            jQuery(".wdi_rate").attr("title", "<?php echo __('Show rating', "wd-instagram-feed"); ?>");
          }
        });
        /* Open/close comments.*/

        jQuery(".wdi_comment, .wdi_comments_close_btn").on(wdi_click, function() { wdi_comment()});
        /* Open/close control buttons.*/
        jQuery(".wdi_toggle_container").on(wdi_click, function () {

          var wdi_open_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'; ?>";
          var wdi_close_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-down' : 'fa-angle-up'; ?>";
          if (jQuery(".wdi_toggle_container i").hasClass(wdi_open_toggle_btn_class)) {
            /* Close controll buttons.*/
            <?php
            if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') {
            ?>
            jQuery(".wdi_image_info").animate({bottom: 0}, 500);
            <?php
            }
            elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {
            ?>
            jQuery(".wdi_image_info").animate({top: 0}, 500);
            <?php
            }

            ?>
            jQuery(".wdi_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: '-' + jQuery(".wdi_ctrl_btn_container").height()}, 500);
            jQuery(".wdi_toggle_container").animate({
            <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0
          }, {
              duration: 500,
                complete: function () { jQuery(".wdi_toggle_container i").attr("class", "wdi_toggle_btn fa " + wdi_close_toggle_btn_class) }
            });
          }
          else {
            /* Open controll buttons.*/
            <?php
            if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') {
            ?>
            jQuery(".wdi_image_info").animate({bottom: jQuery(".wdi_ctrl_btn_container").height()}, 500);
            <?php
            }
            elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {
            ?>
            jQuery(".wdi_image_info").animate({top: jQuery(".wdi_ctrl_btn_container").height()}, 500);
            <?php
            }

            ?>
            jQuery(".wdi_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0}, 500);
            jQuery(".wdi_toggle_container").animate({
            <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: jQuery(".wdi_ctrl_btn_container").height()
          }, {
              duration: 500,
                complete: function () { jQuery(".wdi_toggle_container i").attr("class", "wdi_toggle_btn fa " + wdi_open_toggle_btn_class) }
            });
          }


          //close share buttons popup if open
          jQuery('.wdi_share_btns').removeClass('wdi_share_toggler');
          jQuery('.wdi_share_caret').removeClass('wdi_share_toggler');
        });
        /* Maximize/minimize.*/
        jQuery(".wdi_resize-full").on(wdi_click, function () {
          jQuery(".wdi_watermark").css({display: 'none'});
          var comment_container_width = 0;
          if (jQuery(".wdi_comment_container").hasClass("wdi_open")) {
            comment_container_width = jQuery(".wdi_comment_container").width();
          }
          if (jQuery(".wdi_resize-full").hasClass("fa-compress")) {
            if (jQuery(window).width() > <?php echo $image_width; ?>) {
              wdi_popup_current_width = <?php echo $image_width; ?>;
            }
            if (jQuery(window).height() > <?php echo $image_height; ?>) {
              wdi_popup_current_height = <?php echo $image_height; ?>;
            }
            /* Minimize.*/
            jQuery("#wdi_spider_popup_wrap").animate({
              width: wdi_popup_current_width,
              height: wdi_popup_current_height,
              left: '50%',
              top: '50%',
              marginLeft: -wdi_popup_current_width / 2,
              marginTop: -wdi_popup_current_height / 2,
              zIndex: 100000
            }, 500);
            jQuery(".wdi_image_wrap").animate({width: wdi_popup_current_width - comment_container_width}, 500);
            jQuery(".wdi_image_container").animate({height: wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>, width: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
            jQuery(".wdi_popup_image").animate({
              maxWidth: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              maxHeight: wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () {
                wdi_change_watermark_container();
                if ((jQuery("#wdi_spider_popup_wrap").width() < jQuery(window).width())) {
                  if (jQuery("#wdi_spider_popup_wrap").height() < jQuery(window).height()) {
                    jQuery(".wdi_spider_popup_close_fullscreen").attr("class", "wdi_spider_popup_close");
                  }
                }
              }
            });
            jQuery(".wdi_popup_embed").animate({
              width: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              height: wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () {
                wdi_resize_instagram_post();
                wdi_change_watermark_container();
                if (jQuery("#wdi_spider_popup_wrap").width() < jQuery(window).width()) {
                  if (jQuery("#wdi_spider_popup_wrap").height() < jQuery(window).height()) {
                    jQuery(".wdi_spider_popup_close_fullscreen").attr("class", "wdi_spider_popup_close");
                  }
                }
              }
            });
            jQuery(".wdi_filmstrip_container").animate({<?php echo $width_or_height; ?>: wdi_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>}, 500);
            jQuery(".wdi_filmstrip").animate({<?php echo $width_or_height; ?>: wdi_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40}, 500);
            /* Set filmstrip initial position.*/
            wdi_set_filmstrip_pos(wdi_popup_current_<?php echo $width_or_height; ?> - 40);
            jQuery(".wdi_resize-full").attr("class", "wdi_ctrl_btn wdi_resize-full fa fa-expand");
            jQuery(".wdi_resize-full").attr("title", "<?php echo __('Maximize', "wd-instagram-feed"); ?>");
          }
          else {
            wdi_popup_current_width = jQuery(window).width();
            wdi_popup_current_height = jQuery(window).height();
            /* Maximize.*/
            jQuery("#wdi_spider_popup_wrap").animate({
              width: jQuery(window).width(),
              height: jQuery(window).height(),
              left: 0,
              top: 0,
              margin: 0,
              zIndex: 100000
            }, 500);
            jQuery(".wdi_image_wrap").animate({width: (jQuery(window).width() - comment_container_width)}, 500);
            jQuery(".wdi_image_container").animate({height: (wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
            jQuery(".wdi_popup_image").animate({
              maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () { wdi_change_watermark_container(); }
            });
            jQuery(".wdi_popup_embed").animate({
              width: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () {
                wdi_resize_instagram_post();
                wdi_change_watermark_container(); }
            });
            jQuery(".wdi_filmstrip_container").animate({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>}, 500);
            jQuery(".wdi_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40}, 500);
            /* Set filmstrip initial position.*/
            wdi_set_filmstrip_pos(jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);
            jQuery(".wdi_resize-full").attr("class", "wdi_ctrl_btn wdi_resize-full fa fa-compress");
            jQuery(".wdi_resize-full").attr("title", "<?php echo __('Restore', "wd-instagram-feed"); ?>");
            jQuery(".wdi_spider_popup_close").attr("class", "wdi_ctrl_btn wdi_spider_popup_close_fullscreen");
          }
        });
        /* Fullscreen.*/
        /*Toggle with mouse click*/
        jQuery(".wdi_fullscreen").on(wdi_click, function () {
          jQuery(".wdi_watermark").css({display: 'none'});
          var comment_container_width = 0;
          if (jQuery(".wdi_comment_container").hasClass("wdi_open")) {
            comment_container_width = jQuery(".wdi_comment_container").width();
          }
          function wdi_exit_fullscreen() {
            if (jQuery(window).width() > <?php echo $image_width; ?>) {
              wdi_popup_current_width = <?php echo $image_width; ?>;
            }
            if (jQuery(window).height() > <?php echo $image_height; ?>) {
              wdi_popup_current_height = <?php echo $image_height; ?>;
            }
            <?php
            /* "Full width lightbox" sets yes.*/
            if ($open_with_fullscreen) {
            ?>
            wdi_popup_current_width = jQuery(window).width();
            wdi_popup_current_height = jQuery(window).height();
            <?php
            }
            ?>
            jQuery("#wdi_spider_popup_wrap").on("fscreenclose", function() {
              jQuery("#wdi_spider_popup_wrap").css({
                width: wdi_popup_current_width,
                height: wdi_popup_current_height,
                left: '50%',
                top: '50%',
                marginLeft: -wdi_popup_current_width / 2,
                marginTop: -wdi_popup_current_height / 2,
                zIndex: 100000
              });
              jQuery(".wdi_image_wrap").css({width: wdi_popup_current_width - comment_container_width});
              jQuery(".wdi_image_container").css({height: wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>, width: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});

              /*jQuery(".wdi_slide_bg").css({height: wdi_popup_current_height - <?php echo $image_filmstrip_height; ?>});
               jQuery(".wdi_popup_image_spun1").css({height: wdi_popup_current_height - <?php echo $image_filmstrip_height; ?>});*/
              jQuery(".wdi_popup_image").css({
                maxWidth: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });
              jQuery(".wdi_popup_embed").css({
                width: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                height: wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });
              wdi_resize_instagram_post();
              /* Set watermark container size.*/
              wdi_change_watermark_container();
              jQuery(".wdi_filmstrip_container").css({<?php echo $width_or_height; ?>: wdi_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>});
              jQuery(".wdi_filmstrip").css({<?php echo $width_or_height; ?>: wdi_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>- 40});
              /* Set filmstrip initial position.*/
              wdi_set_filmstrip_pos(wdi_popup_current_<?php echo $width_or_height; ?> - 40);
              jQuery(".wdi_resize-full").show();
              jQuery(".wdi_resize-full").attr("class", "wdi_ctrl_btn wdi_resize-full fa fa-expand");
              jQuery(".wdi_resize-full").attr("title", "<?php echo __('Maximize', "wd-instagram-feed"); ?>");
              jQuery(".wdi_fullscreen").attr("class", "wdi_ctrl_btn wdi_fullscreen fa fa-arrows-alt");
              jQuery(".wdi_fullscreen").attr("title", "<?php echo __('Fullscreen', "wd-instagram-feed"); ?>");
              if (jQuery("#wdi_spider_popup_wrap").width() < jQuery(window).width()) {
                if (jQuery("#wdi_spider_popup_wrap").height() < jQuery(window).height()) {
                  jQuery(".wdi_spider_popup_close_fullscreen").attr("class", "wdi_spider_popup_close");
                }
              }
            });
          }
          if (typeof jQuery().fullscreen !== 'undefined') {
            if (jQuery.isFunction(jQuery().fullscreen)) {
              if (jQuery.fullscreen.isFullScreen()) {
                /* Exit Fullscreen.*/
                jQuery.fullscreen.exit();
                wdi_exit_fullscreen();
              }
              else {
                /* Fullscreen.*/
                jQuery("#wdi_spider_popup_wrap").fullscreen();
                /*jQuery("#wdi_spider_popup_wrap").on("fscreenopen", function() {
                 if (jQuery.fullscreen.isFullScreen()) {*/
                var screen_width = screen.width;
                var screen_height = screen.height;
                jQuery("#wdi_spider_popup_wrap").css({
                  width: screen_width,
                  height: screen_height,
                  left: 0,
                  top: 0,
                  margin: 0,
                  zIndex: 100000
                });
                jQuery(".wdi_image_wrap").css({width: screen_width - comment_container_width});
                jQuery(".wdi_image_container").css({height: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
                /* jQuery(".wdi_slide_bg").css({height: screen_height - <?php echo $image_filmstrip_height; ?>});*/
                jQuery(".wdi_popup_image").css({
                  maxWidth: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                  maxHeight: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
                });

                jQuery(".wdi_popup_embed").css({
                  width: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                  height: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
                });

                wdi_resize_instagram_post();

                /* Set watermark container size.*/
                wdi_change_watermark_container();
                jQuery(".wdi_filmstrip_container").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>)});
                jQuery(".wdi_filmstrip").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40)});
                /* Set filmstrip initial position.*/
                wdi_set_filmstrip_pos(screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);
                jQuery(".wdi_resize-full").hide();
                jQuery(".wdi_fullscreen").attr("class", "wdi_ctrl_btn wdi_fullscreen fa fa-compress");
                jQuery(".wdi_fullscreen").attr("title", "<?php echo __('Exit Fullscreen', "wd-instagram-feed"); ?>");
                jQuery(".wdi_spider_popup_close").attr("class", "wdi_ctrl_btn wdi_spider_popup_close_fullscreen");
                /*});
                 }*/
              }
            }
          }
          return false;
        });
        /* Play/pause.*/
        jQuery(".wdi_play_pause, .wdi_popup_image").on(wdi_click, function () {
          if (jQuery(".wdi_ctrl_btn").hasClass("fa-play")) {

            /* PLay.*/
            wdi_play();
            jQuery(".wdi_play_pause").attr("title", "<?php echo __('Pause', "wd-instagram-feed"); ?>");
            jQuery(".wdi_play_pause").attr("class", "wdi_ctrl_btn wdi_play_pause fa fa-pause");
          }
          else {
            /* Pause.*/
            window.clearInterval(wdi_playInterval);
            jQuery(".wdi_play_pause").attr("title", "<?php echo __('Play', "wd-instagram-feed"); ?>");
            jQuery(".wdi_play_pause").attr("class", "wdi_ctrl_btn wdi_play_pause fa fa-play");
          }
        });
        /* Open with autoplay.*/
        <?php

        if ($open_with_autoplay) {
        ?>

        wdi_play();
        jQuery(".wdi_play_pause").attr("title", "<?php echo __('Pause', "wd-instagram-feed"); ?>");
        jQuery(".wdi_play_pause").attr("class", "wdi_ctrl_btn wdi_play_pause fa fa-pause");
        <?php
        }
        ?>
        /* Open with fullscreen.*/
        <?php
        if ($open_with_fullscreen) {
        ?>
        wdi_open_with_fullscreen();
        <?php
        }
        ?>

        jQuery(".wdi_popup_image").removeAttr("width");
        jQuery(".wdi_popup_image").removeAttr("height");

        //if info is displayed by defult then double trigger 
        //info click for displaying it with proper scrollbar
        if(<?php echo $popup_info_always_show?>){
          setTimeout(function(){
            jQuery(".wdi_info").trigger(wdi_click);
            jQuery(".wdi_info").trigger(wdi_click);
          },100);
        }


      });
      /* Open with fullscreen.*/
      function wdi_open_with_fullscreen() {
        jQuery(".wdi_watermark").css({display: 'none'});
        var comment_container_width = 0;
        if (jQuery(".wdi_comment_container").hasClass("wdi_open")) {
          comment_container_width = jQuery(".wdi_comment_container").width();
        }
        wdi_popup_current_width = jQuery(window).width();
        wdi_popup_current_height = jQuery(window).height();
        jQuery("#wdi_spider_popup_wrap").css({
          width: jQuery(window).width(),
          height: jQuery(window).height(),
          left: 0,
          top: 0,
          margin: 0,
          zIndex: 100000
        });
        jQuery(".wdi_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
        jQuery(".wdi_image_container").css({height: (wdi_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: wdi_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
        jQuery(".wdi_popup_image").css({
          maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
          maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () { wdi_change_watermark_container(); }
        });
        jQuery(".wdi_popup_video").css({
          width: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
          height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () { wdi_change_watermark_container(); }
        });
        jQuery(".wdi_popup_embed").css({
          width: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
          height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () {
            wdi_resize_instagram_post();
            wdi_change_watermark_container(); }
        });
        jQuery(".wdi_filmstrip_container").css({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>});
        jQuery(".wdi_filmstrip").css({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40});
        /* Set filmstrip initial position.*/
        wdi_set_filmstrip_pos(jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);

        jQuery(".wdi_resize-full").attr("class", "wdi_ctrl_btn wdi_resize-full fa fa-compress");
        jQuery(".wdi_resize-full").attr("title", "<?php echo __('Restore', "wd-instagram-feed"); ?>");
        jQuery(".wdi_spider_popup_close").attr("class", "wdi_ctrl_btn wdi_spider_popup_close_fullscreen");
      }

      function wdi_resize_instagram_post(){
        jQuery('.wdi_embed_frame').css({'width':'inherit', 'height':'inherit', 'vertical-align':'middle', 'display':'table-cell'});
        /*jQuery.fn.exists = function(){return this.length>0;}*/

        if (jQuery('.inner_instagram_iframe_wdi_embed_frame').length) {
          var w = jQuery(".wdi_popup_embed").width();
          var h = jQuery(".wdi_popup_embed").height();
          var post_width = 0;
          var post_height = 0;

          if(h <w +88 ){
            post_height = h;
            post_width = h -88;
          }
          else{
            post_width = w;
            post_height = w +88 ;
          }

          jQuery('.inner_instagram_iframe_wdi_embed_frame').each(function(){

            post_height = post_height;
            post_width = post_width;

            var top_pos = (0.5 *( h-post_height));
            jQuery(this).parent().css({
              height: post_height,
              width: post_width,
              top:  top_pos
            });
          });


          wdi_change_watermark_container();
        }
      }

      function wdi_play() {
        window.clearInterval(wdi_playInterval);
        wdi_playInterval = setInterval(function () {
          if (!wdi_data[parseInt(jQuery('#wdi_current_image_key').val()) + 1]) {
            if (<?php echo $option_row->enable_loop; ?> == 1) {
              /* Wrap around.*/
              wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), 0, wdi_data);
            }
            return;
          }
          wdi_change_image(parseInt(jQuery('#wdi_current_image_key').val()), parseInt(jQuery('#wdi_current_image_key').val()) + 1, wdi_data)
        }, '<?php echo $slideshow_interval * 1000; ?>');
      }
      jQuery(window).focus(function() {

        /* event_stack = [];*/
        if (!jQuery(".wdi_ctrl_btn").hasClass("fa-play")) {
          wdi_play();
        }
        /*var i = 0;
         jQuery(".wdi_slider").children("span").each(function () {
         if (jQuery(this).css('opacity') == 1) {
         jQuery("#wdi_current_image_key").val(i);
         }
         i++;
         });*/
      });
      jQuery(window).blur(function() {
        event_stack = [];
        window.clearInterval(wdi_playInterval);
      });

    </script>
    <?php
    die();
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}