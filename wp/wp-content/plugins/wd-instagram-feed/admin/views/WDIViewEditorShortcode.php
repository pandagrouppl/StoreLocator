<?php

class WDIViewEditorShortcode {
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
    $rows = WDILibrary::objectToArray($this->model->get_row_data());
    wp_print_scripts('jquery');

    ?>
    <style type="text/css">
    .editor_popup_container{
      font-family:'Open Sans', sans-serif;
    }
    .styled-select select {
     background: transparent;
     width: 200px;
     padding: 5px;
     font-size: 14px;
     color:rgb(68, 68, 68);
     border-radius: 0;
     height: 34px;
     -webkit-appearance: none;
     }
     .wdi_editor_label{
      color:rgb(68, 68, 68);
      font-size:14px;
      font-weight:600;
      font-family:'Open Sans', sans-serif;
     }
     .wdi_feed_info{
      color:rgb(68, 68, 68);
      font-size:14px;
      font-weight:600;
      text-align: center;
      font-family:'Open Sans', sans-serif;
      width: 30%;
      margin-top: 10px;
      margin:0 auto;
     }
     .wdi_feed_thumb img{
      max-height: 50px;
      max-width: 50px;
      width: auto;
      height: auto;
     }
     .table-cell{
      display: table-cell;
      vertical-align: middle;
     }
     .table-row{
      display: table-row;
     }
     .wdi_feed_thumb,
     .wdi_feed_name{
      padding: 5px;
     }
     .wdi_editor_insert{
        background: #00a0d2;
        border-color: #0073aa;
        -webkit-box-shadow: inset 0 1px 0 rgba( 120, 200, 230, 0.5), 0 1px 0 rgba( 0, 0, 0, 0.15 );
        box-shadow: inset 0 1px 0 rgba( 120, 200, 230, 0.5 ), 0 1px 0 rgba( 0, 0, 0, 0.15 );
        color: #fff;
        text-decoration: none;
        display: inline-block;
        text-align: right;
        height: 30px;
        line-height: 28px;
        padding: 0 12px 2px;
        border-radius: 5px;
        border: 1px solid #948888;
     }
     .wdi_editor_insert:hover{
        cursor: pointer;
     }
    </style>
    <div id="wdi_editor_popup">
      <div class="editor_popup_container styled-select">
        <div class="wdi_feed_select table-row">
          <label for="wdi_feed_select" class="wdi_editor_label  table-cell"><?php _e('Select Feed:', 'wd-instagram-feed'); ?></label>
          <span class="wdi_feed_thumb table-cell"></span>
          <span class="table-cell"><select name="wdi_feed_select" onchange="wdi_selectChange()" id="wdi_feed_select">
            <?php foreach ($rows as $row) {
              ?>
                <option value="<?php echo $row['id']?>"><?php echo $row["feed_name"]?></option>
              <?php
            }?>
          </select></span>
        </div>
        <div style="text-align:right">
            <div id="wdi_editor_insert_btn" class="wdi_editor_insert">Insert</div>
        </div>
      </div>
    </div>
    <script>
    <?php echo 'var wdi_feed_rows =' . json_encode($rows);?>;
    jQuery(document).ready(function(){
      wdi_selectChange(jQuery('#wdi_feed_select'));
      jQuery('#wdi_editor_insert_btn').on('click',wdi_insert_shortcode);
    });
    function wdi_selectChange(){
      var current = jQuery("#wdi_feed_select").val();
      for(var i = 0; i < wdi_feed_rows.length; i++){
        if(wdi_feed_rows[i]['id'] == current){
          var currentRow = wdi_feed_rows[i];
          break;
        }
      }
      var thumbHtml = '<img src="'+currentRow['feed_thumb']+'">';
      //var nameHtml = currentRow['feed_name'];
      var id_elem = jQuery('<input id="wdi_id" name="wdi_id" type="hidden"/>');
      id_elem.val(currentRow['id']);
      jQuery('.wdi_feed_thumb').html(thumbHtml);
      jQuery('.wdi_feed_thumb').append(id_elem);
      //jQuery('.wdi_feed_info .wdi_feed_name').html(nameHtml);
    }
    function wdi_insert_shortcode() {
        if (document.getElementById("wdi_id").value) {
          window.parent.send_to_editor('[wdi_feed id="' + document.getElementById('wdi_id').value + '"]');
        }
        window.parent.tb_remove();
      }
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