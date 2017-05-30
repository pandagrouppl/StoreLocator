<?php

class WDIViewThemes_wdi {
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

    ?>
    <div class="update-nag wdi_help_bar_wrap">
      <span class="wdi_help_bar_text">
        <?php _e('This section allows you to create, edit and delete Themes.', "wd-instagram-feed"); ?>
        <a class="wdi_hb_t_link" target="_blank"
           href="https://web-dorado.com/wordpress-instagram-feed-wd/editing-themes.html"><?php _e('Read More in User Guide', "wd-instagram-feed"); ?></a>
      </span>
      <div class="wdi_hb_buy_pro">
        <a target="_blank" href="https://web-dorado.com/products/wordpress-instagram-feed-wd.html">
          <img alt="web-dorado.com" title="UPGRADE TO PAID VERSION"
               src="<?php echo WDI_URL . '/images/wd-logo.png'; ?>">
          <span><?php _e('Upgrade to paid version', "wd-instagram-feed"); ?></span>
        </a>
      </div>
    </div>


    <div class="wdi_pro_notice"> <?php _e("This is FREE version, Customizing themes is available only in PRO version","wd-instagram-feed"); ?> </div>
    

<?php
    $this->buildFreeThemeDemo();
}

public function buildFreeThemeDemo(){
  ?>
    <div class="wdi_demo_img" demo-tab="general"><img src="<?php echo WDI_URL . '/demo_images/1.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="header"><img src="<?php echo WDI_URL . '/demo_images/2.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="load_more"><img src="<?php echo WDI_URL . '/demo_images/3.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="thumbnails"><img src="<?php echo WDI_URL . '/demo_images/4.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="masonry"><img src="<?php echo WDI_URL . '/demo_images/5.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="blog_style"><img src="<?php echo WDI_URL . '/demo_images/6.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="image_browser"><img src="<?php echo WDI_URL . '/demo_images/7.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="image_browser"><img src="<?php echo WDI_URL . '/demo_images/8.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_general"><img src="<?php echo WDI_URL . '/demo_images/l1.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_ctrl_btns"><img src="<?php echo WDI_URL . '/demo_images/l2.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_close_btn"><img src="<?php echo WDI_URL . '/demo_images/l3.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_nav_btns"><img src="<?php echo WDI_URL . '/demo_images/l4.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_filmstrip"><img src="<?php echo WDI_URL . '/demo_images/l5.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_info"><img src="<?php echo WDI_URL . '/demo_images/l6.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_comments"><img src="<?php echo WDI_URL . '/demo_images/l7.png'; ?>" alt=""></div>
  <?php
}



}





