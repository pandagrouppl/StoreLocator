<?php

class WDILibrary {
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
  public static function get($key, $default_value = '') {
    if (isset($_GET[$key])) {
      $value = $_GET[$key];
    }
    elseif (isset($_POST[$key])) {
      $value = $_POST[$key];
    }
    else {
      $value = '';
    }
    if (!$value) {
      $value = $default_value;
    }
    return esc_html($value);
  }

  public static function message_id($message_id) {
    if ($message_id) {
      switch($message_id) {
        case 1: {
          $message = 'Item Succesfully Saved.';
          $type = 'updated';
          break;

        }
        case 2: {
          $message = 'Error. Please install plugin again.';
          $type = 'error';
          break;

        }
        case 3: {
          $message = 'Item Succesfully Deleted.';
          $type = 'updated';
          break;

        }
        case 4: {
          $message = "You can't delete default theme";
          $type = 'error';
          break;

        }
        case 5: {
          $message = 'Items Succesfully Deleted.';
          $type = 'updated';
          break;

        }
        case 6: {
          $message = 'You must select at least one item.';
          $type = 'error';
          break;

        }
        case 7: {
          $message = 'The item is successfully set as default.';
          $type = 'updated';
          break;

        }
        case 8: {
          $message = 'Options Succesfully Saved.';
          $type = 'updated';
          break;

        }
        case 9: {
          $message = 'Item Succesfully Published.';
          $type = 'updated';
          break;

        }
        case 10: {
          $message = 'Items Succesfully Published.';
          $type = 'updated';
          break;

        }
        case 11: {
          $message = 'Item Succesfully Unpublished.';
          $type = 'updated';
          break;

        }
        case 12: {
          $message = 'Items Succesfully Unpublished.';
          $type = 'updated';
          break;

        }
        case 13: {
          $message = 'Ordering Succesfully Saved.';
          $type = 'updated';
          break;

        }
        case 14: {
          $message = 'A term with the name provided already exists.';
          $type = 'error';
          break;

        }
        case 15: {
          $message = 'Name field is required.';
          $type = 'error';
          break;

        }
        case 16: {
          $message = 'The slug must be unique.';
          $type = 'error';
          break;

        }
        case 17: {
          $message = 'Changes must be saved.';
          $type = 'error';
          break;

        }
      }
      return '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
    }
  }

  public static function message($message, $type) {
    return '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
  }

  public static function search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function wdi_spider_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
        function wdi_spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          if (document.getElementById("search_select_value")) {
            document.getElementById("search_select_value").value = 0;
          }
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
        function check_search_key(e, that) {
          var key_code = (e.keyCode ? e.keyCode : e.which);
          if (key_code == 13) { /*Enter keycode*/
            wdi_spider_search();
            return false;
          }
          return true;
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:50px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value" class="wdi_spider_search_value" onkeypress="return check_search_key(event, this);" value="<?php echo esc_html($search_value); ?>" style="width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 28px;' : ''; ?>" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="<?php _e('Search',"wd-instagram-feed");?>" onclick="wdi_spider_search()" class="button-secondary action">
        <input type="button" value="<?php _e('Reset',"wd-instagram-feed");?>" onclick="wdi_spider_reset()" class="button-secondary action">
      </div>
    </div>
    <?php
  }

  public static function search_select($search_by, $search_select_id = 'search_select_value', $search_select_value, $playlists, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function wdi_spider_search_select() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
      </script>
      <div class="alignleft actions" >
        <label for="<?php echo $search_select_id; ?>" style="font-size:14px; width:50px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <select id="<?php echo $search_select_id; ?>" name="<?php echo $search_select_id; ?>" onchange="wdi_spider_search_select();" style="float: none; width: 150px;">
        <?php
          foreach ($playlists as $id => $playlist) {
            ?>
            <option value="<?php echo $id; ?>" <?php echo (($search_select_value == $id) ? 'selected="selected"' : ''); ?>><?php echo $playlist; ?></option>
            <?php
          }
        ?>
        </select>
      </div>
    </div>
    <?php
  }
  
  public static function html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20) {
    $limit = 20;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function wdi_spider_page(x, y) {       
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        document.getElementById('<?php echo $form_id; ?>').submit();
      }
      function check_enter_key(e) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery('#current_page').val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery('#current_page').val();
          }
          document.getElementById('<?php echo $form_id; ?>').submit();
        }
        return true;
      }
    </script>
    <div class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $items_per_page) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" href="javascript:wdi_spider_page(<?php echo $page_number; ?>,-2);">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" href="javascript:wdi_spider_page(<?php echo $page_number; ?>,-1);">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" href="javascript:wdi_spider_page(<?php echo $page_number; ?>,1);">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" href="javascript:wdi_spider_page(<?php echo $page_number; ?>,2);">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
  }

  public static function ajax_search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function wdi_spider_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          wdi_spider_ajax_save('<?php echo $form_id; ?>');
        }
        function wdi_spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          wdi_spider_ajax_save('<?php echo $form_id; ?>');
        }        
        function check_search_key(e, that) {
          var key_code = (e.keyCode ? e.keyCode : e.which);
          if (key_code == 13) { /*Enter keycode*/
            wdi_spider_search();
            return false;
          }
          return true;
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:60px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value" class="wdi_spider_search_value" onkeypress="return check_search_key(event, this);" value="<?php echo esc_html($search_value); ?>" style="width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 28px;' : ''; ?>" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" onclick="wdi_spider_search()" class="button-secondary action">
        <input type="button" value="Reset" onclick="wdi_spider_reset()" class="button-secondary action">
      </div>
    </div>
    <?php
  }

  public static function ajax_html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20, $pager = 0) {
    $limit = $items_per_page;

    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    if (!$pager) {
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function wdi_spider_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        wdi_spider_ajax_save('<?php echo $form_id; ?>');
      }
      function check_enter_key(e, that) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery(that).val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery(that).val();
          }
          wdi_spider_ajax_save('<?php echo $form_id; ?>');
          return false;
        }
       return true;		 
      }
    </script>
    <?php } ?>
    <div id="tablenav-pages" class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $limit) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" onclick="wdi_spider_page(<?php echo $page_number; ?>,-2)">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" onclick="wdi_spider_page(<?php echo $page_number; ?>,-1)">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event, this)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" onclick="wdi_spider_page(<?php echo $page_number; ?>,1)">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" onclick="wdi_spider_page(<?php echo $page_number; ?>,2)">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <?php if (!$pager) { ?>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
    }
  }

  public static function ajax_html_frontend_page_nav($theme_row, $count_items, $page_number, $form_id, $limit = 20, $current_view, $id, $cur_alb_gal_id = 0, $type = 'album', $enable_seo = false, $pagination = 1) {
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : $type);
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : $cur_alb_gal_id);
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    if ($page_number > $items_county) {
      return;
    }
    $first_page = "first-page-" . $current_view;
    $prev_page = "prev-page-" . $current_view;
    $next_page = "next-page-" . $current_view;
    $last_page = "last-page-" . $current_view;
    ?>
    <span class="wdi_nav_cont_<?php echo $current_view; ?>">
    <?php
    if ($pagination == 1) {
      ?>
    <div class="tablenav-pages_<?php echo $current_view; ?>">
      <?php
      if ($theme_row->page_nav_number) {
      ?>
      <span class="displaying-num_<?php echo $current_view; ?>"><?php echo $count_items . __(' item(s)', 'wd-instagram-feed'); ?></span>
      <?php
      }
      if ($count_items > $limit) {
        if ($theme_row->page_nav_button_text) {
          $first_button = __('First', 'wd-instagram-feed');
          $previous_button = __('Previous', 'wd-instagram-feed');
          $next_button = __('Next', 'wd-instagram-feed');
          $last_button = __('Last', 'wd-instagram-feed');
        }
        else {
          $first_button = '«';
          $previous_button = '‹';
          $next_button = '›';
          $last_button = '»';
        }
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
        }
        if ($page_number >= $items_county) {
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links_<?php echo $current_view; ?>">
        <a class="<?php echo $first_page; ?>" title="<?php echo __('Go to the first page', 'wd-instagram-feed'); ?>"><?php echo $first_button; ?></a>
        <a class="<?php echo $prev_page; ?>" title="<?php echo __('Go to the previous page', 'wd-instagram-feed'); ?>" <?php echo  $page_number > 1 && $enable_seo ? 'href="' . add_query_arg(array("page_number_" . $current_view => $page_number - 1), $_SERVER['REQUEST_URI']) . '"' : ""; ?>><?php echo $previous_button; ?></a>
        <span class="paging-input_<?php echo $current_view; ?>">
          <span class="total-pages_<?php echo $current_view; ?>"><?php echo $page_number; ?></span> <?php echo __('of', 'wd-instagram-feed'); ?> <span class="total-pages_<?php echo $current_view; ?>">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="<?php echo __('Go to the next page', 'wd-instagram-feed'); ?>" <?php echo  $page_number + 1 <= $items_county && $enable_seo ? 'href="' . add_query_arg(array("page_number_" . $current_view => $page_number + 1), $_SERVER['REQUEST_URI']) . '"' : ""; ?>><?php echo $next_button; ?></a>
        <a class="<?php echo $last_page ?>" title="<?php echo __('Go to the last page', 'wd-instagram-feed'); ?>"><?php echo $last_button; ?></a>
      </span>
      <?php
      }
      ?>
    </div>
      <?php
    }
    elseif ($pagination == 2) {
      if ($count_items > $limit * $page_number) {
        ?>
		<div id="wdi_load_<?php echo $current_view; ?>" class="tablenav-pages_<?php echo $current_view; ?>">
			<a class="wdi_load_btn_<?php echo $current_view; ?> wdi_load_btn" href="javascript:void(0);"><?php echo __('Load More...', 'wd-instagram-feed'); ?></a>
			<input type="hidden" id="wdi_load_more_<?php echo $current_view; ?>" name="wdi_load_more_<?php echo $current_view; ?>" value="on" />
		</div>
    <?php
      }
    }
    elseif ($pagination == 3) {
      if ($count_items > $limit * $page_number) {
        ?>
		<script type="text/javascript">
		  jQuery(window).on("scroll", function() {
        if (jQuery(document).scrollTop() + jQuery(window).height() > (jQuery('#<?php echo $form_id; ?>').offset().top + jQuery('#<?php echo $form_id; ?>').height())) {
          wdi_spider_page_<?php echo $current_view; ?>('', <?php echo $page_number; ?>, 1, true);
          jQuery(window).off("scroll");
          return false;
			  }
		  });
		</script>
    <?php
      }
    }
    ?>
    <input type="hidden" id="page_number_<?php echo $current_view; ?>" name="page_number_<?php echo $current_view; ?>" value="<?php echo ((isset($_POST['page_number_' . $current_view])) ? (int) $_POST['page_number_' . $current_view] : 1); ?>" />
    <script type="text/javascript">
      function wdi_spider_page_<?php echo $current_view; ?>(cur, x, y, load_more) {
        if (typeof load_more == "undefined") {
          var load_more = false;
        }
        if (jQuery(cur).hasClass('disabled')) {
          return false;
        }
        var items_county_<?php echo $current_view; ?> = <?php echo $items_county; ?>;
        switch (y) {
          case 1:
            if (x >= items_county_<?php echo $current_view; ?>) {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
            }
            else {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
            }
            else {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
            break;
          default:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
        }
        wdi_spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $id; ?>', '<?php echo $album_gallery_id; ?>', '', '<?php echo $type; ?>', 0, '', '', load_more);
      }
      jQuery(document).ready(function() {
        jQuery('.<?php echo $first_page; ?>').on('click', function() {
          wdi_spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -2);
        });
        jQuery('.<?php echo $prev_page; ?>').on('click', function() {
          wdi_spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -1);
          return false;
        });
        jQuery('.<?php echo $next_page; ?>').on('click', function() {
          wdi_spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1);
          return false;
        });
        jQuery('.<?php echo $last_page; ?>').on('click', function() {
          wdi_spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 2);
        });
        jQuery('.wdi_load_btn_<?php echo $current_view; ?>').on('click', function() {
          wdi_spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1, true);
          return false;
        });
      });
    </script>
    </span>
    <?php
  }

  public static function ajax_html_frontend_search_box($form_id, $current_view, $cur_gal_id, $images_count, $search_box_width = 180) {
    $wdi_search = ((isset($_POST['wdi_search_' . $current_view]) && esc_html($_POST['wdi_search_' . $current_view]) != '') ? esc_html($_POST['wdi_search_' . $current_view]) : '');	
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);
    ?>
    <style>
      .wdi_search_container_1 {
        display: inline-block;
        width: 100%;
        text-align: right;
        margin: 0 5px 20px 5px;
        background-color: rgba(0,0,0,0);
      }
      .wdi_search_container_2 {
        display: inline-block;
        position: relative;
        border-radius: 4px;
        box-shadow: 0 0 3px 1px #CCCCCC;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        width: <?php echo $search_box_width; ?>px;
        max-width: 100%;
      }
      #wdi_search_container_1_<?php echo $current_view; ?> #wdi_search_container_2_<?php echo $current_view; ?> .wdi_search_input_container {
        display: block;
        margin-right: 45px;
      }
      #wdi_search_container_1_<?php echo $current_view; ?> #wdi_search_container_2_<?php echo $current_view; ?> .wdi_search_loupe_container {
        display: inline-block; 
        margin-right: 1px;
        vertical-align: middle;
        float: right;
        padding-top: 3px;
      }
      #wdi_search_container_1_<?php echo $current_view; ?> #wdi_search_container_2_<?php echo $current_view; ?> .wdi_search_reset_container {
        display: inline-block;
        margin-right: 5px;
        vertical-align: middle;
        float: right;
        padding-top: 3px;
      }
      #wdi_search_container_1_<?php echo $current_view; ?> #wdi_search_container_2_<?php echo $current_view; ?> .wdi_search,
      #wdi_search_container_1_<?php echo $current_view; ?> #wdi_search_container_2_<?php echo $current_view; ?> .wdi_reset {
        font-size: 18px;
        color: #CCCCCC;
        cursor: pointer;
      }
      #wdi_search_container_1_<?php echo $current_view; ?> #wdi_search_container_2_<?php echo $current_view; ?> .wdi_search_input_<?php echo $current_view; ?>,
      #wdi_search_container_1_<?php echo $current_view; ?> #wdi_search_container_2_<?php echo $current_view; ?> .wdi_search_input_<?php echo $current_view; ?>:focus {
        color: hsl(0, 1%, 3%);
        outline: none;
        border: none;
        box-shadow: none;
        background: none;
        padding: 0 5px;
        font-family: inherit;
        width: 100%;
      }
    </style>
    <script type="text/javascript">
      function clear_input_<?php echo $current_view; ?> (current_view) {
        jQuery("#wdi_search_input_" + current_view).val('');
      }
      function check_enter_key(e) {
        var key_code = e.which || e.keyCode;
        if (key_code == 13) {
          wdi_spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1);
          return false;
        }
        return true;
      }
    </script>
    <div class="wdi_search_container_1" id="wdi_search_container_1_<?php echo $current_view; ?>">
      <div class="wdi_search_container_2" id="wdi_search_container_2_<?php echo $current_view; ?>">
        <span class="wdi_search_reset_container" >
          <i title="<?php echo __('Reset', 'wd-instagram-feed'); ?>" class="wdi_reset fa fa-times" onclick="clear_input_<?php echo $current_view; ?>('<?php echo $current_view; ?>'),wdi_spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
        <span class="wdi_search_loupe_container" >
          <i title="<?php echo __('Search', 'wd-instagram-feed'); ?>" class="wdi_search fa fa-search" onclick="wdi_spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
        <span class="wdi_search_input_container">
          <input id="wdi_search_input_<?php echo $current_view; ?>" class="wdi_search_input_<?php echo $current_view; ?>" type="text" onkeypress="return check_enter_key(event)" name="wdi_search_<?php echo $current_view; ?>" value="<?php echo $wdi_search; ?>" >
          <input id="wdi_images_count_<?php echo $current_view; ?>" class="wdi_search_input" type="hidden" name="wdi_images_count_<?php echo $current_view; ?>" value="<?php echo $images_count; ?>" >
        </span>
      </div>
    </div>
    <?php
  }

  public static function ajax_html_frontend_sort_box($form_id, $current_view, $cur_gal_id, $sort_by = '', $search_box_width = 180) {
    $wdi_search = ((isset($_POST['wdi_search_' . $current_view]) && esc_html($_POST['wdi_search_' . $current_view]) != '') ? esc_html($_POST['wdi_search_' . $current_view]) : '');	
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);
    ?>
    <style>
      .wdi_order_cont_<?php echo $current_view; ?> {
        background-color: rgba(0,0,0,0);
        display: block;
        margin: 0 5px 20px 5px;
        text-align: right;
        width: 100%;
      }
      .wdi_order_label_<?php echo $current_view; ?> {
        border: none;
        box-shadow: none;
        color: #BBBBBB;
        font-family: inherit;
        font-weight: bold;
        outline: none;
      }
      .wdi_order_<?php echo $current_view; ?> {
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        box-shadow: 0 0 3px 1px #CCCCCC;
        border-radius: 4px;
        max-width: 100%;
        width: <?php echo $search_box_width; ?>px;
      }
    </style>
    <div class="wdi_order_cont_<?php echo $current_view; ?>">
      <span class="wdi_order_label_<?php echo $current_view; ?>"><?php echo __('Order by: ', 'wd-instagram-feed'); ?></span>
      <select class="wdi_order_<?php echo $current_view; ?>" onchange="wdi_spider_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1, '', this.value)">
        <option <?php if ($sort_by == 'default') echo 'selected'; ?> value="default"><?php echo __('Default', 'wd-instagram-feed'); ?></option>
        <option <?php if ($sort_by == 'filename') echo 'selected'; ?> value="filename"><?php echo __('Filename', 'wd-instagram-feed'); ?></option>								
        <option <?php if ($sort_by == 'size') echo 'selected'; ?> value="size"><?php echo __('Size', 'wd-instagram-feed'); ?></option>
        <option <?php if ($sort_by == 'RAND()') echo 'selected'; ?> value="random"><?php echo __('Random', 'wd-instagram-feed'); ?></option>
      </select>
    </div>
    <?php
  }

  public static function wdi_spider_hex2rgb($colour) {
    if ($colour[0] == '#') {
      $colour = substr( $colour, 1 );
    }
    if (strlen($colour) == 6) {
      list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    }
    else if (strlen($colour) == 3) {
      list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    }
    else {
      return FALSE;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
  }

  public static function wdi_spider_redirect($url) {
    ?>
    <script>
      window.location = "<?php echo $url; ?>";
    </script>
    <?php
    exit();
  }

 /**
  *  If string argument passed, put it into delimiters for AJAX response to separate from other data.
  */

  public static function delimit_wd_output($data) {
    
    if(is_string ( $data )){
      return "WD_delimiter_start". $data . "WD_delimiter_end";
    }
    else{
      return $data;
    }
  }

  public static function verify_nonce($page){

    $nonce_verified = false;
    if ( isset( $_GET['wdi_nonce'] ) && wp_verify_nonce( $_GET['wdi_nonce'], $page )) {
      $nonce_verified = true;
    }
    elseif ( isset( $_POST['wdi_nonce'] ) && wp_verify_nonce( $_POST['wdi_nonce'], $page )) {
      $nonce_verified = true;
    }
    elseif ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], $page )) {
      $nonce_verified = true;
    }
    elseif ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], $page )) {
      $nonce_verified = true;
    }
    return $nonce_verified;
  }


  public static function arrayToObject($d) {
    if (is_array($d)) {
    /*
    * Return array converted to object
    * Using __FUNCTION__ (Magic constant)
    * for recursive call
    */
      return (object) array_map(array('WDILibrary','arrayToObject'), $d);
    }
    else {
      // Return object
    return $d;
    }
  }

  public static function objectToArray($d) {
    if (is_object($d)) {
      // Gets the properties of the given object
      // with get_object_vars function
      $d = get_object_vars($d);
    }
   
    if (is_array($d)) {
    /*
    * Return array converted to object
    * Using __FUNCTION__ (Magic constant)
    * for recursive call
    */

    

      return array_map(array('WDILibrary','objectToArray'), $d);
    }
    else {
      // Return array
      return $d;
    }
  }


  /*
  * @return color if valid color, otherwise return false
  *
  */

  public static function regexColor($color, $named) {

    if ($named) {
   
      $named = array('transparent','aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');
      if (in_array(strtolower($color), $named)) {
        /* A color name was entered instead of a Hex Value, so just exit function */
        return $color;
      }
    }
   
    //checking rgb format
    if(self::is_rgb($color) == true){
      return $color; 
    }
    if(substr($color,0,1) == '#' && strlen($color) === 4){
      $color.=substr($color,1,strlen($color));
    }



    //Check for a hex color string '#c1c2b4'
    if(preg_match('/^#[a-f0-9]{6}$/i', $color)) //hex color is valid
    {
      return $color;
    }

    //Check for a hex color string without hash 'c1c2b4'
    if(preg_match('/^[a-f0-9]{6}$/i', $color)) //hex color is valid
    {
      $fix_color = '#' . $color;
      return $fix_color;
    }
    return false;
  }
  /**
   * Check is rgb color value
   * @param   string
   * @return  boolean
   */
  public static function is_rgb($val){
      //checking for rgb
      $rgbFlag = false;
      $rgbaFlag = false;
        if(substr($val,0,4) === 'rgb('){
          $values = explode(',',substr($val,4,strlen($val)-5));
          if(count($values) !== 3){
            return false;
          }
          foreach ($values as $value) {
            if(!is_numeric($value) || $value<0 || $value>255){
              $rgbFlag = true;
            }
          }
          if($rgbFlag === false){
            return true;
          }
        }
        else if(substr($val,0,5) === 'rgba('){
          $values = explode(',',substr($val,5,strlen($val)-6));
          if(count($values) !== 4){
            return false;
          }
          foreach ($values as $value) {
            if(!is_numeric($value) || $value<0 || $value>255){
              $rgbaFlag = true;
            }
          }
          if($rgbaFlag === false){
            return true;
          }
        }else{
          return false;
        }
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}