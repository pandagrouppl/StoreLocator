<?php

class WDIControllerGalleryBox {
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
  public function execute() {
    $ajax_task = (isset($_POST['ajax_task']) ? esc_html($_POST['ajax_task']) : '');
    if (method_exists($this, $ajax_task)) {
      $this->$ajax_task();
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WDI_DIR . "/frontend/models/WDIModelGalleryBox.php";
    $model = new WDIModelGalleryBox();

    require_once WDI_DIR . "/frontend/views/WDIViewGalleryBox.php";
    $view = new WDIViewGalleryBox($model);

    $view->display();
  }

  public function save() {
    require_once WDI_DIR . "/frontend/models/WDIModelGalleryBox.php";
    $model = new WDIModelGalleryBox();
    $option_row = $model->get_option_row_data();
    if ($option_row->popup_enable_email) {
      // Email validation.
      $email = (isset($_POST['wdi_email']) ? is_email(stripslashes($_POST['wdi_email'])) : FALSE);
    }
    else {
      $email = TRUE;
    }
    if ($option_row->popup_enable_captcha) {
      $wdi_captcha_input = (isset($_POST['wdi_captcha_input']) ? esc_html(stripslashes($_POST['wdi_captcha_input'])) : '');
      @session_start();
      $wdi_captcha_code = (isset($_SESSION['wdi_captcha_code']) ? esc_html(stripslashes($_SESSION['wdi_captcha_code'])) : '');
      if ($wdi_captcha_input === $wdi_captcha_code) {
        $captcha = TRUE;
      }
      else {
        $captcha = FALSE;
      }
    }
    else {
      $captcha = TRUE;
    }

    if ($email && $captcha) {
      global $wpdb;
      $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : 0);
      $name = (isset($_POST['wdi_name']) ? esc_html(stripslashes($_POST['wdi_name'])) : '');
      $wdi_comment = (isset($_POST['wdi_comment']) ? esc_html(stripslashes($_POST['wdi_comment'])) : '');
      $wdi_email = (isset($_POST['wdi_email']) ? esc_html(stripslashes($_POST['wdi_email'])) : '');
      $published = (current_user_can('manage_options') || !$option_row->comment_moderation) ? 1 : 0;
      $save = $wpdb->insert($wpdb->prefix . 'wdi_image_comment', array(
        'image_id' => $image_id,
        'name' => $name,
        'date' => date('Y-m-d H:i'),
        'comment' => $wdi_comment,
        'url' => '',
        'mail' => $wdi_email,
        'published' => $published,
      ), array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
      ));
      $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'wdi_image SET comment_count=comment_count+1 WHERE id="%d"', $image_id));
    }
    $this->display();
  }

  public function save_rate() {
    global $wpdb;
    $image_id = (isset($_POST['image_id']) ? esc_html(stripslashes($_POST['image_id'])) : 0);
    $rate = (isset($_POST['rate']) ? esc_html(stripslashes($_POST['rate'])) : '');
    if (!$wpdb->get_var($wpdb->prepare('SELECT image_id FROM ' . $wpdb->prefix . 'wdi_image_rate WHERE ip="%s" AND image_id="%d"', $_SERVER['REMOTE_ADDR'], $image_id))) {
      $wpdb->insert($wpdb->prefix . 'wdi_image_rate', array(
        'image_id' => $image_id,
        'rate' => $rate,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'date' => date('Y-m-d H:i:s'),
      ), array(
        '%d',
        '%f',
        '%s',
        '%s',
      ));
      $rates = $wpdb->get_row($wpdb->prepare('SELECT AVG(`rate`) as `average`, COUNT(`rate`) as `rate_count` FROM ' . $wpdb->prefix . 'wdi_image_rate WHERE image_id="%d"', $image_id));
      $wpdb->update($wpdb->prefix . 'wdi_image', array('avg_rating' => $rates->average, 'rate_count' => $rates->rate_count), array('id' => $image_id));
    }
    $this->display();
  }

  public function save_hit_count() {
    global $wpdb;
    $image_id = (isset($_POST['image_id']) ? esc_html(stripslashes($_POST['image_id'])) : 0);
    $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'wdi_image SET hit_count = hit_count + 1 WHERE id="%d"', $image_id));
  }

  public function delete() {
    global $wpdb;
    $comment_id = (isset($_POST['comment_id']) ? (int) $_POST['comment_id'] : 0);
    $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : 0);
    $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wdi_image_comment WHERE id="%d"', $comment_id));
    $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'wdi_image SET comment_count=comment_count-1 WHERE id="%d"', $image_id));
    $this->display();
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