<?php

class WDIViewLicensing_wdi {
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
      <div class="wdi_hb_buy_pro">
        <a target="_blank" href="https://web-dorado.com/products/wordpress-instagram-feed-wd.html">
          <img alt="web-dorado.com" title="UPGRADE TO PAID VERSION"
               src="<?php echo WDI_URL . '/images/wd-logo.png'; ?>">
          <span><?php _e('Upgrade to paid version', "wd-instagram-feed"); ?></span>
        </a>
      </div>
    </div>
    
    <div id="featurs_tables">
      <div id="featurs_table1">
        <span>WordPress 4.0+ <?php _e("ready", 'wd-instagram-feed'); ?></span>
       
        <span><?php _e("Responsive Design and Layout", 'wd-instagram-feed'); ?></span>
        <span><?php _e("SEO Friendly", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Thumbnails layout", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Image Browser layout", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Lightbox", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Load More Button / Classic Pagination", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Image Sorting", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Widget", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Slideshow/Lightbox Effects", 'wd-instagram-feed'); ?></span>


        <span><?php _e("Conditional Filters", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Feed based on liked media", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Image On Hover Effects", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Infinite Scroll Load More", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Full Style Customization With Themes", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Filmstrip", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Instagram Comments in Lightbox", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Blog Style layout", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Masonry layout", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Videos in BlogStyle, ImageBrowser and Lightbox", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Social Share Buttons", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Multiple User/Hashtag Feeds", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Filtering Images Based on Users/Hashtags", 'wd-instagram-feed'); ?></span>
        <span><?php _e("Support / Updates", 'wd-instagram-feed'); ?></span>
      </div>
      <div id="featurs_table2">
        <span style="padding-top: 18px;height: 39px;"><?php _e("Free", 'wd-instagram-feed'); ?></span>
    
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span>1</span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span> <?php _e('Only Bug Fixes',"wd-instagram-feed"); ?> </span>
      </div>
      <div id="featurs_table3">
        <span><?php _e("Pro Version", 'wd-instagram-feed'); ?></span>

        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span>15</span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span> <?php _e('Full Support',"wd-instagram-feed"); ?> </span>
      </div>




    </div>

    <div style="float: left; clear: both;">
      <p><?php _e("After purchasing the commercial version follow these steps:", 'wd-instagram-feed'); ?></p>
      <ol>
        <li><?php _e("Deactivate Instagram Feed WD plugin.", 'wd-instagram-feed'); ?></li>
        <li><?php _e("Delete Instagram Feed WD plugin.", 'wd-instagram-feed'); ?></li>
        <li><?php _e("Install the downloaded commercial version of the plugin.", 'wd-instagram-feed'); ?></li>
      </ol>
    </div>
    <?php
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