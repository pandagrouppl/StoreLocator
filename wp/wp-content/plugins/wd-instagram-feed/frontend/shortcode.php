<?php
//global counter for webpage feeds
/*ttt!!! this will not work in case of AJAX request, note that for future versions*/
$wdi_feed_counter = 0;

add_action('init', 'wdi_frontend_init');

function wdi_frontend_init()
{
  global $wdi_options;
  $wdi_options = get_option(WDI_OPT);
}

add_shortcode('wdi_feed', 'wdi_feed');
// [wdi_feed id="feed_id"]
function wdi_feed($atts, $widget_params = '')
{


  ob_start();
  global $wdi_feed_counter;

  if (defined('DOING_AJAX') && DOING_AJAX) {
    if ($wdi_feed_counter == 0) {

      $wdi_feed_counter = rand(1000, 9999);
      global $wdi_feed_counter_init;
      $wdi_feed_counter_init = $wdi_feed_counter;
    }

    wdi_load_frontend_scripts_styles_ajax();
  } else {

    wdi_load_frontend_scripts();
    wdi_load_frontend_styles();
  }


  require_once(WDI_DIR . '/framework/WDILibrary.php');


  $attributes = shortcode_atts(array(
    'id' => 'no_id',
  ), $atts);
  if ($attributes['id'] == 'no_id') {
    //including feed model
    require_once(WDI_DIR . '/admin/models/WDIModelEditorShortcode.php');
    $shortcode_feeds_model = new WDIModelEditorShortcode();
    /*if there are feeds select first one*/
    $first_feed_id = $shortcode_feeds_model->get_first_feed_id();

    $attributes['id'] = isset($first_feed_id) ? $first_feed_id : $attributes['id'];

    if($attributes['id'] == 'no_id'){
      ob_get_clean();
      return __('No feed. Create and publish a feed to display it.', "wd-instagram-feed");
    }
    /*else continue*/

  }


  //including feed model
  require_once(WDI_DIR . '/admin/models/WDIModelFeeds_wdi.php');
  $feed_model = new WDIModelFeeds_wdi();
  //getting all feed information from db
  $feed_row = WDILibrary::objectToArray($feed_model->get_feed_row($attributes['id']));


  //checking if access token is not set or removed display proper error message
  global $wdi_options;
  if (!isset($wdi_options['wdi_access_token']) || $wdi_options['wdi_access_token'] == '') {
    ob_get_clean();
    return __('Access Token is invalid, please get it again ', "wd-instagram-feed");
  }

  if (!isset($feed_row) || $feed_row == NULL) {
    ob_get_clean();
    return __('Feed with such ID does not exist', "wd-instagram-feed");
  }


  $feed_row['widget'] = false;
  if ($widget_params != '' && $widget_params['widget'] == true) {
    $feed_row['widget'] = true;
    $feed_row['number_of_photos'] = (string)$widget_params['widget_image_num'];
    $feed_row['show_likes'] = (string)$widget_params['widget_show_likes_and_comments'];
    $feed_row['show_comments'] = (string)$widget_params['widget_show_likes_and_comments'];
    $feed_row['show_usernames'] = '0';
    $feed_row['display_header'] = '0';
    $feed_row['show_description'] = '0';
    $feed_row['number_of_columns'] = (string)$widget_params['number_of_columns'];

    if ($widget_params['enable_loading_buttons'] == 0) {
      $feed_row['feed_display_view'] = 'widget';
    }
  }

  if (isset($feed_row['published']) && $feed_row['published'] === '0') {
    ob_get_clean();
    return __('Unable to display unpublished feed ', "wd-instagram-feed");
  }
  //checking feed type and using proper MVC
  $feed_type = isset($feed_row['feed_type']) ? $feed_row['feed_type'] : '';
  switch ($feed_type) {
    case 'thumbnails': {
      //including thumbnails controller
      require_once(WDI_DIR . '/frontend/controllers/WDIControllerThumbnails_view.php');
      $controller = new WDIControllerThumbnails_view();
      $controller->execute($feed_row, $wdi_feed_counter);
      $wdi_feed_counter++;
      break;
    }
    case 'image_browser': {
      //including thumbnails controller
      require_once(WDI_DIR . '/frontend/controllers/WDIControllerImageBrowser_view.php');
      $controller = new WDIControllerImageBrowser_view();
      $controller->execute($feed_row, $wdi_feed_counter);
      $wdi_feed_counter++;
      break;
    }
    default: {
      ob_get_clean();
      return __('Invalid feed type', "wd-instagram-feed");
    }

  }


  global $wdi_options;
  if (isset($wdi_options['wdi_custom_css'])) {
    ?>
    <style>
      <?php echo $wdi_options['wdi_custom_css'];?>
    </style>
    <?php
  }
  if (isset($wdi_options['wdi_custom_js'])) {
    ?>
    <script>
      <?php echo htmlspecialchars_decode(stripcslashes($wdi_options['wdi_custom_js']));?>
    </script>
    <?php
  }


  return ob_get_clean();
}


function wdi_load_frontend_scripts()
{

  wp_enqueue_script('wdi_instagram', plugins_url('../js/wdi_instagram.js', __FILE__), array("jquery"), WDI_VERSION, true);

  wp_enqueue_script('underscore');
  wp_enqueue_script('wdi_frontend', plugins_url('../js/wdi_frontend.js', __FILE__), array("jquery", 'wdi_instagram', 'underscore'), WDI_VERSION, true);

  wp_enqueue_script('wdi_responsive', plugins_url('../js/wdi_responsive.js', __FILE__), array("jquery", "wdi_frontend"), WDI_VERSION, true);
  wp_localize_script("wdi_frontend", 'wdi_ajax', array('ajax_url' => admin_url('admin-ajax.php')), WDI_VERSION);
  wp_localize_script("wdi_frontend", 'wdi_url', array('plugin_url' => plugin_dir_url(__FILE__),
    'ajax_url' => admin_url('admin-ajax.php')), WDI_VERSION);

  $user_is_admin = current_user_can('manage_options');

  wp_localize_script("wdi_frontend", 'wdi_front_messages',
    array('connection_error' => __('Connection Error, try again later :(', 'wd-instagram-feed'),
      'user_not_found' => __('Username not found', 'wd-instagram-feed'),
      'network_error' => __('Network error, please try again later :(', 'wd-instagram-feed'),
      'hashtag_nodata' => __('There is no data for that hashtag', 'wd-instagram-feed'),
      'filter_title' => __('Click to filter images by this user', 'wd-instagram-feed'),
      'invalid_users_format' => __('Provided feed users are invalid or obsolete for this version of plugin','wd-instagram-feed'),
      'feed_nomedia' => __('There is no media in this feed', 'wd-instagram-feed'),
      'follow' => __('Follow', 'wd-instagram-feed'),
      'show_alerts' => $user_is_admin,
    ), WDI_VERSION);


  wdi_front_end_scripts();
}

function wdi_load_frontend_styles()
{
  wp_register_style('wdi_frontend_thumbnails', plugins_url('../css/wdi_frontend.css', __FILE__), array(), WDI_VERSION);
  wp_enqueue_style('wdi_frontend_thumbnails');
  wp_register_style('font-awesome', plugins_url('../css/font-awesome/css/font-awesome.css', __FILE__), array(), WDI_VERSION);
  wp_enqueue_style('font-awesome');
}


////////////////////////////GALLERY BOX//////////////////////////////
function wdi_front_end_scripts()
{
  /* ttt!!! petq chi*/
  /*
  global $wp_scripts;
  if (isset($wp_scripts->registered['jquery'])) {
    $jquery = $wp_scripts->registered['jquery'];
    if (!isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<')) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', FALSE, array('jquery-core', 'jquery-migrate'), '1.10.2' );
    }
  }
  */
  // Styles/Scripts for popup.
  wp_enqueue_script('jquery-mobile', WDI_FRONT_URL . '/js/gallerybox/jquery.mobile.js', array('jquery'), WDI_VERSION);
  wp_enqueue_script('jquery-mCustomScrollbar', WDI_FRONT_URL . '/js/gallerybox/jquery.mCustomScrollbar.concat.min.js', array('jquery'), WDI_VERSION);
  wp_enqueue_style('wdi_mCustomScrollbar', WDI_FRONT_URL . '/css/gallerybox/jquery.mCustomScrollbar.css', array(), WDI_VERSION);
  wp_enqueue_script('jquery-fullscreen', WDI_FRONT_URL . '/js/gallerybox/jquery.fullscreen-0.4.1.js', array('jquery'), WDI_VERSION);
  /*ttt!!! gallery fullscreeni het conflict chka ?? arje stugel ete fullscreen script ka, apa el chavelacnel*/
  wp_enqueue_script('wdi_gallery_box', WDI_FRONT_URL . '/js/gallerybox/wdi_gallery_box.js', array('jquery'), WDI_VERSION);
  wp_localize_script('wdi_gallery_box', 'wdi_objectL10n', array(
    'wdi_field_required' => __('Field is required.', "wd-instagram-feed"),
    'wdi_mail_validation' => __('This is not a valid email address.', "wd-instagram-feed"),
    'wdi_search_result' => __('There are no images matching your search.', "wd-instagram-feed"),
  ));

}

/*load all scripts and styles directly without dependency on jquery*/

function wdi_load_frontend_scripts_styles_ajax()
{

  wp_dequeue_script('jquery');

  wp_enqueue_script('wdi_instagram', plugins_url('../js/wdi_instagram.js', __FILE__), array(), WDI_VERSION, true);

  wp_enqueue_script('underscore');
  wp_enqueue_script('wdi_frontend', plugins_url('../js/wdi_frontend.js', __FILE__), array('wdi_instagram', 'underscore'), WDI_VERSION, true);
  wp_enqueue_script('wdi_responsive', plugins_url('../js/wdi_responsive.js', __FILE__), array("wdi_instagram"), WDI_VERSION, true);


  global $wdi_feed_counter_init;
  $wdi_feed_counter_init = isset($wdi_feed_counter_init) ? $wdi_feed_counter_init : 0;
  wp_localize_script("wdi_frontend", 'wdi_feed_counter_init', array('wdi_feed_counter_init' => $wdi_feed_counter_init), WDI_VERSION);

  wp_localize_script("wdi_frontend", 'wdi_ajax', array('ajax_url' => admin_url('admin-ajax.php'), 'ajax_response' => 1), WDI_VERSION);
  wp_localize_script("wdi_frontend", 'wdi_url', array('plugin_url' => plugin_dir_url(__FILE__),
    'ajax_url' => admin_url('admin-ajax.php')), WDI_VERSION);

  $user_is_admin = current_user_can('manage_options');

  wp_localize_script("wdi_frontend", 'wdi_front_messages',
    array('connection_error' => __('Connection Error, try again later :(', 'wd-instagram-feed'),
      'user_not_found' => __('Username not found', 'wd-instagram-feed'),
      'network_error' => __('Network error, please try again later :(', 'wd-instagram-feed'),
      'hashtag_nodata' => __('There is no data for that hashtag', 'wd-instagram-feed'),
      'filter_title' => __('Click to filter images by this user', 'wd-instagram-feed'),
      'invalid_users_format' => __('Provided feed users are invalid or obsolete for this version of plugin','wd-instagram-feed'),
      'feed_nomedia' => __('There is no media in this feed', 'wd-instagram-feed'),
      'follow' => __('Follow', 'wd-instagram-feed'),
      'show_alerts' => $user_is_admin,
    ), WDI_VERSION);

  // Styles/Scripts for popup.
  wp_enqueue_script('jquery-mobile', WDI_FRONT_URL . '/js/gallerybox/jquery.mobile.js', array(), WDI_VERSION);
  wp_enqueue_script('jquery-mCustomScrollbar', WDI_FRONT_URL . '/js/gallerybox/jquery.mCustomScrollbar.concat.min.js', array(), WDI_VERSION);
  wp_enqueue_style('wdi_mCustomScrollbar', WDI_FRONT_URL . '/css/gallerybox/jquery.mCustomScrollbar.css', array(), WDI_VERSION);
  wp_enqueue_script('jquery-fullscreen', WDI_FRONT_URL . '/js/gallerybox/jquery.fullscreen-0.4.1.js', array(), WDI_VERSION);
  /*ttt!!! gallery fullscreeni het conflict chka ?? arje stugel ete fullscreen script ka, apa el chavelacnel*/
  wp_enqueue_script('wdi_gallery_box', WDI_FRONT_URL . '/js/gallerybox/wdi_gallery_box.js', array(), WDI_VERSION);
  wp_localize_script('wdi_gallery_box', 'wdi_objectL10n', array(
    'wdi_field_required' => __('Field is required.', "wd-instagram-feed"),
    'wdi_mail_validation' => __('This is not a valid email address.', "wd-instagram-feed"),
    'wdi_search_result' => __('There are no images matching your search.', "wd-instagram-feed"),
  ));


  wdi_load_frontend_styles();

}


function wdi_feed_frontend_messages(){

  $class = current_user_can('manage_options') ? '' : 'wdi_hidden';
  $js_error_message = __("Something is wrong. Response takes too long or there is JS error. Press Ctrl+Shift+J or Cmd+Shift+J on a Mac.", "wd-instagram-feed");
  $ajax_error_message = (defined('DOING_AJAX') && DOING_AJAX) ? __("Warning: Instagram Feed is loaded using AJAX request. It might not display properly.", "wd-instagram-feed") : '';

  echo '<div class="wdi_js_error '.$class.'">'.
    $js_error_message ."<br/>". $ajax_error_message .'</div>';

}