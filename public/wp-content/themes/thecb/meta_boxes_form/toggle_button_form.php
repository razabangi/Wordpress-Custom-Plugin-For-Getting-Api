<div class="hcf_box">
    <style>
        .switch {
          position: relative;
          display: inline-block;
          width: 60px;
          height: 34px;
        }

        .switch input { 
          opacity: 0;
          width: 0;
          height: 0;
        }

        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          -webkit-transition: .4s;
          transition: .4s;
        }

        .slider:before {
          position: absolute;
          content: "";
          height: 26px;
          width: 26px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }

        input:checked + .slider {
          background-color: #2196F3;
        }

        input:focus + .slider {
          box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
          -webkit-transform: translateX(26px);
          -ms-transform: translateX(26px);
          transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
          border-radius: 34px;
        }

        .slider.round:before {
          border-radius: 50%;
        }
    </style>

    <?php 
      global $post;

      @$custom_checkbox = get_post_meta($post->ID,'toggle_resource_link_btn', true);
      if($custom_checkbox == 1) $custom_checkbox_val = 'checked="checked"';
      wp_nonce_field( 'mytheme_control_meta_box', 'mytheme_control_meta_box_nonce' ); // Always add nonce to your meta boxes!
     ?>
    
    <label class="switch">
      <input type="checkbox" name="toggle_resource_link_btn" id="toggle_resource_link_btn" onclick="togglelink()"  value="<?php echo ($custom_checkbox) ? $custom_checkbox : 0 ; ?>" <?php echo (isset($custom_checkbox_val)) ? $custom_checkbox_val : '' ;  ?> >
      <span class="slider round"></span>
    </label>   


    <script>

         function togglelink() {
             var change = document.getElementById("toggle_resource_link_btn");
            if (change.value == 0)
            {
                change.value = 1;
            }
            else {
                change.value = 0;
            }
        }

    </script>

</div>

