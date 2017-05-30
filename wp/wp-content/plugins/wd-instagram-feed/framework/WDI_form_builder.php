<?php

class WDI_form_builder  {





public function __construct() {

}
/*




 * $element['name'] 
 * $element['id'] //default is WDI_.$element_name
 * $element['type'] //text,number ...
 * $element['attr'] //custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * $element['input_size']
 * $element['label'] // array('text','place') 
 * $element['defaults'] //array of default vlaues
 * $element['CONST'] //variable to store data in array
 * $element['disabled'] //text to be displayed if option is disabled
 */
  public function input($element,$feed_row=''){
      $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET') . ']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      $type = isset($element['input_type']) ? $element['input_type'] : 'text';
      //$attrText = $element['attr']['name'] . '=' . $element['attr']['value'];
      $disabled_text = (isset($element['disabled']) && isset($element['disabled']['text'])) ? $element['disabled']['text'] : '';
      $switched = (isset($element['switched']) && $element['switched'] === 'off') ? 'disabled' : '';
      $attrs = ' ';
      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      }
      $attr = $attrs;

      $input_size= isset($element['input_size']) ? $element['input_size'] : '20';
      $label = isset($element['label']) ? $element['label'] : '';
      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }
     
      
      ?>
      <div class="wdwt_param" id="WDI_wrap_<?php echo $element['name']; ?>">
        <div class="block">
          <div class="optioninput" style="text-align:center;">

              <?php 
                if($label!='' && $label['place']=='before'){
                  ?>
                    <label for="<?php echo $id; ?>"><?php echo $label['text']; ?></label>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                  <?php
                }
              ?>

              <input type="<?php echo $type; ?>" id="<?php echo $id; ?>" <?php echo $switched; ?> name="<?php echo $name; ?>" value="<?php echo esc_attr($value); ?>"
              <?php echo $attr; ?> size="<?php echo $input_size; ?>">
              <?php 
                if($label!='' && $label['place']=='after'){
                  ?>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                    <label for="<?php echo $id; ?>"><?php echo $label['text']; ?></label>
                  <?php
                }
                if($disabled_text != ''){
                  ?><span class="wdi_pro_only"><?php echo $disabled_text?></span> <?php
                }
              ?>
          </div>
        </div>
      </div> 
      <?php
  }

/*
 * $element['name'] 
 * $element['id'] //default is WDI_.$element_name
 * $element['type'] //multiple
 * $element['attr'] //custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * $element['label'] // array('text','place') 
 * $element['valid_options'] //array('option_value1'=>'option_name1','option_value2'=>'option_name2');
 * $element['width'] 
 * $element['selected'] //one of valid options
 * $element['defaults'] //array of default vlaues
 * $element['CONST'] //variable to store data in array
 * $element['switched'] //if set to 'off' will make field disabled
 * $element['disabled'] //disabled text label
 */
  public function select($element,$feed_row=''){
      $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET') . ']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      $type = isset($element['type']) ? $element['type'] : '';
      //$attr = isset($element['attr']) ? $element['attr']['name'] . '="' . $element['attr']['value'].'"' : '';
      $label = isset($element['label']) ? $element['label'] : '';
      $width = isset($element['width']) ? ($element['width']) : '';
      $options = isset($element['valid_options']) ? $element['valid_options'] : ''; 
      $switched = (isset($element['switched']) && $element['switched'] === 'off') ? 'disabled' : '';
      //$disabled_text = (isset($element['disabled']) && isset($element['disabled']['text'])) ? $element['disabled']['text'] : '';
      $disabled_options = isset($element['disabled_options']) ? $element['disabled_options'] : array();
      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $opt_value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }


      $attrs = ' ';
      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      }
      $attr = $attrs;




     ?> 

      <div class="wdwt_param" id="WDI_wrap_<?php echo $element['name']; ?>">
      <div class="block">   
        <div class="optioninput"> 

           <?php 
                if($label!='' && $label['place']=='before'){
                  ?>
                    <label class="<?php echo isset($label['class']) ? $label['class'] : '';?>" for="<?php echo $id; ?>"><?php echo $label['text']; ?></label>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                  <?php
                }
                ?>

          <select  name="<?php echo $name; ?>" id="<?php echo $id; ?>" <?php echo $type; ?> <?php echo $switched; ?> style="<?php if($width!='') echo 'width:' .$width . ';';?> resize:vertical;" <?php echo $attr; ?>>
          <?php foreach($options as $key => $value){ ?>
            <option <?php echo (in_array($key, $disabled_options)) ? 'disabled' : '' ?> value="<?php echo esc_attr($key) ?>" <?php if($key==$opt_value){echo 'selected';}?>>
              <?php echo esc_html($value); ?>
            </option>
          <?php } ?>
          </select>

           <?php 
                if($label!='' && $label['place']=='after'){
                  ?>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                    <label class="<?php echo isset($label['class']) ? $label['class'] : '';?>" for="<?php echo $id; ?>"><?php echo $label['text']; ?></label>
                  <?php
                }
                
                //echo disable text if need label & disable text

              ?>

        </div>
      </div>
    </div>
    <?php
  }


/*
 * $element['name'] 
 * $element['id'] //default is WDI_.$element_name
 * $element['attr'] //custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * $element['label'] // array('text','place') 
 * $element['valid_options'] //array('option_value1'=>'option_name1','option_value2'=>'option_name2');
 * $element['width'] 
 * $element['selected'] //one of valid options
 * $element['defaults'] //array of default vlaues
 * $element['CONST'] //variable to store data in array
 */
  public function radio($element,$feed_row=''){
      $name = $element['CONST'] . '[' . (isset($element['name']) ? $element['name'] : 'NOT_SET') . ']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      //$attr = isset($element['attr']) ? $element['attr']['name'] . '="' . $element['attr']['value'].'"' : '';
      $label = isset($element['label']) ? $element['label'] : '';
      $width = isset($element['width']) ? ($element['width']) : '';
      $options = isset($element['valid_options']) ? $element['valid_options'] : ''; 
      $break = isset($element['break']) ? '<br/>' : ''; 
      $hide_ids = isset($element['hide_ids']) ? $element['hide_ids'] : '';
      $show_ids = isset($element['show_ids']) ? $element['show_ids'] : '';
      $disabled_options = isset($element['disabled_options']) ? $element['disabled_options'] : array(); 
      $attrs = ' ';

      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      }
      $attr = $attrs;


      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $opt_value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }
      
      ?>
        <div class="wdwt_param" id="WDI_wrap_<?php echo $element['name'];?>">
        <div class="block">
        <div class="optioninput">
        <?php 

                if($label!='' && $label['place']=='before'){
                  ?>
                    <label class="<?php echo isset($label['class']) ? $label['class'] : '';?>" for="<?php $id; ?>"><?php echo $label['text']; ?></label>
                    <?php echo isset($label['br']) ? '<br/>' : ''?>
                  <?php
                }
        foreach ( $options as $key => $option ) {
          $disable = '';
          $disable_text = '';
          foreach ($disabled_options as $disabled_option => $disable_lable) {
            if($disabled_option == $key){
              $disable = 'disabled';
              $disable_text = $disable_lable;
            }
          }
        ?>
          <input <?php echo $disable;?> style="margin:2px;" type="radio" name="<?php echo $name ?>" value="<?php echo esc_attr($key); ?>" <?php checked($key,$opt_value); ?> <?php echo $attr; ?> /> <?php echo esc_html($option); ?>
          
          <?php if($disable_text != '') : ?>
            <?php if(isset($disabled_options['br'])) : ?>
              <br>
            <?php endif; ?>
            <span class="wdi_pro_only"><?php echo $disable_text?></span>
          <?php endif;?>

          <?php echo $break;?>
        <?php
      }
      if($label!='' && $label['place']=='after'){
                  ?>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                    <label for="<?php $id; ?>" class="<?php echo isset($label['class']) ? $label['class'] : '';?>"><?php echo $label['text']; ?></label>
                  <?php
                }
      echo '</div></div></div>';

      if($hide_ids != ''){
      ?>
      <style>
      .<?php echo $id.'_hide_ids_hidden';?>{
        display:none !important;
      }
      </style>
      <script>
      jQuery(document).ready(function(){
        var <?php echo $id.'_hide_ids';?> = <?php echo json_encode($hide_ids);?>;
        jQuery("#WDI_wrap_<?php echo $element['name'];?> input").on('click',function(){
          jQuery('.<?php echo $id.'_hide_ids_hidden';?>').each(function(){
            jQuery(this).removeClass('<?php echo $id.'_hide_ids_hidden';?>');
          });
          var selected = jQuery(this).val();
          for (var opt in <?php echo $id.'_hide_ids'?>){
            if(opt == selected){
              var ids = <?php echo $id.'_hide_ids'?>[opt].split(',');
              for (var i in ids){
                
                jQuery('#WDI_wrap_'+ids[i]).parent().parent().addClass("<?php echo $id.'_hide_ids_hidden';?>");
              }
            }
            
          }
        });
        jQuery("#WDI_wrap_<?php echo $element['name'];?> input").each(function(){
          var currentOption = "<?php echo $opt_value?>";
          if(jQuery(this).val() == currentOption){
            jQuery(this).trigger('click');
          }
        });
      });
      </script>
    <?php }
    if($show_ids != ''){
      ?>
      <style>
    .<?php echo $id.'_show_ids_show';?>{
      display:block !important;
    }
      </style>
      <script>
      jQuery(document).ready(function(){
        var <?php echo $id.'_show_ids';?> = <?php echo json_encode($show_ids);?>;
        jQuery("#WDI_wrap_<?php echo $element['name'];?> input").on('click',function(){
          jQuery('.<?php echo $id.'_show_ids_show';?>').each(function(){
            jQuery(this).removeClass('<?php echo $id.'_show_ids_show';?>');
          });
          var selected = jQuery(this).val();
          for (var opt in <?php echo $id.'_show_ids'?>){
            if(opt == selected){
              var ids = <?php echo $id.'_show_ids'?>[opt].split(',');
              for (var i in ids){
                jQuery('#WDI_wrap_'+ids[i]).parent().parent().addClass("<?php echo $id.'_show_ids_show';?>");
              }
            }
            
          }
        });
        jQuery("#WDI_wrap_<?php echo $element['name'];?> input").each(function(){
          var currentOption = "<?php echo $opt_value?>";
          if(jQuery(this).val() == currentOption){
            jQuery(this).trigger('click');
          }
        });
      });
      </script>
    <?php }

  }


/*
 * $element['name'] 
 * $element['id'] //default is WDI_.$element_name
 * $element['attr'] //custom attribute array(array('name'=>'attr_name','value'=>'attr_value'),...)
 * $element['label'] // array('text','place') 
 * $element['width'] 
 * $element['selected'] //one of valid options
 * $element['defaults'] //array of default vlaues
 * $element['CONST'] //variable to store data in array 
 */
  public function checkbox($element,$feed_row=''){
      $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET').']';
      $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
      //$attr = isset($element['attr']) ? $element['attr']['name'] . '="' . $element['attr']['value'].'"' : '';
      $label = isset($element['label']) ? $element['label'] : '';
      $width = isset($element['width']) ? ($element['width']) : ''; 
      $break = isset($element['break']) ? '<br/>' : ''; 
      $disable = (isset($element['switched']) && $element['switched']=='off') ? 'disabled' : '' ;
      $attrs = ' ';
      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
      }
      $attr = $attrs;

      $defaults = $element['defaults'];
      $current_settings = isset($feed_row) ? $feed_row : '';
      $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
        $opt_value = isset($current_settings[$element['name']]) ? $current_settings[$element['name']] : '';
      }
      ?>  
      <div class="wdwt_param" id="WDI_wrap_<?php echo $element['name'];?>">

     

        <div class="block margin">
        <div class="optioninput checkbox">
         <?php
                if($label!='' && $label['place']=='before'){
                  ?>
                    <label class="<?php echo isset($label['class']) ? $label['class'] : '' ?>" for="<?php $id; ?>"><?php echo $label['text']; ?></label>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                  <?php
                }
      ?>
          <input <?php echo $disable; ?> type="checkbox" class="checkbox" name="<?php echo $name; ?>" id="<?php echo $id ?>" <?php echo $attr;?> <?php checked(1,$opt_value)?>  value="1">
          <?php
          if($label!='' && $label['place']=='after'){
                  ?>
                    <?php echo isset($label['br'])? '<br/>' : ''?>
                    <label class="<?php echo isset($label['class']) ? $label['class'] : '' ?>" for="<?php $id; ?>"><?php echo $label['text']; ?></label>
                  <?php
                }
          ?>
        </div>
       </div>
      </div>
      
      <script>
      
      jQuery(document).ready(function(){
          if(jQuery('#<?php echo ''.$id?>').attr('checked') != 'checked'){
             jQuery('#<?php echo ''.$id?>').after('<input type=\"hidden\" name=\"' + jQuery("#<?php echo ''.$id?>").attr("name") + '\" value="0">');
          }
          jQuery('#<?php echo ''.$id?>').on('click',function(){
            if (jQuery(this).attr("checked") != 'checked') {
                       jQuery(this).after("<input type=\"hidden\" name=\"" + jQuery(this).attr("name") + "\" value=0>");
                    } else {
                       jQuery(this).next().remove();
                    }
          });
      });
      </script>
               
        <?php
  }


   /**
  * Displays a single color control
  * $element['name']
  * $element['CONST'] //variable to store data in array
  */

   public function color($element,$feed_row=''){
    $name = $element['CONST'].'['.(isset($element['name']) ? $element['name'] : 'NOT_SET').']';
    $id = isset($element['id']) ? $element['id'] : 'WDI_'.$element['name'];
    $defaults = $element['defaults'];
    
    $attrs = ' ';
      foreach ($element['attr'] as $attr) {
        $attrs .=  $attr['name'] . '="' . $attr['value'] . '" ';
        if($attr['name'] === 'tab'){
          $tab = $attr['value'];
        }
        if($attr['name'] === 'section'){
          $section = $attr['value'];
        }
      }
      $attr = $attrs;

    $current_settings = isset($feed_row) ? $feed_row : '';

    $opt_value = isset($defaults[$element['name']]) ? $defaults[$element['name']] : '';
      if($current_settings !=''){
         if($current_settings[$element['name']] != '')
          {
            $opt_value = $current_settings[$element['name']];
          } 
      }
     ?>
     <div class="wdwt_param" id="WDI_wrap_<?php echo $element['name'];?>">
         <div class='wdwt_float' >  
           <div>
              <input type="text" class='color_input' id="<?php echo $id ?>" <?php echo $attr;?> name="<?php echo $name; ?>"   value="<?php echo esc_attr($opt_value); ?>" data-default-color="<?php echo $defaults[$element['name']]; ?>" style="background-color:<?php echo esc_attr($opt_value); ?>;">
           </div>
         </div>
     </div>
     <script  type="text/javascript">
     jQuery(document).ready(function() {
       jQuery('.color_input').wpColorPicker();
       jQuery('#WDI_wrap_<?php echo $element['name'];?> .wp-picker-container').attr('tab','<?php echo $tab;?>');
       jQuery('#WDI_wrap_<?php echo $element['name'];?> .wp-picker-container').attr('section','<?php echo $section;?>');
     });
     </script>
     <?php
   }

}



