<?php
/*

Plugin Name: Add To Content
Plugin URI: http://www.trottyzone.com/product/add-to-content/
Description: Place custom content above or below every post and/or page or from a specific category. 
Version: 2.0
Author: Ephrain Marchan
Author URI: http://www.trottyzone.com
License: GPL2 or later
*/

/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

if ( ! defined( 'ABSPATH' ) ) die();

load_plugin_textdomain('atc-menu', false, dirname(plugin_basename(__FILE__)) . '/languages/');

register_activation_hook( __FILE__, 'atc_install' );
register_uninstall_hook( __FILE__, 'atc_uninstall' );
function atc_uninstall() {
    delete_option( 'atc_settings' );
}
function atc_install() {
    $defaults = array( 'top' => array(
            'text'  => __( 'Example Top Text', 'atc-menu' ),
                        'check' => __( '1', 'atc-menu' ),
            'bc' => __( '#fff', 'atc-menu' ),
                        'tc'  => __( '#333', 'atc-menu' ),
            'upi' => __( 'http://www.example.com/picture.jpg', 'atc-menu' )
                            
        
    ),
                           'bottom' => array( 
                        'text'  => __( 'Example Bottom Text', 'atc-menu' ),
                        'check' => __( '1', 'atc-menu' ), 
            'bc' => __( '#fff', 'atc-menu' ),
                        'tc'  => __( '#333', 'atc-menu' ),
            'upi' => __( 'http://www.example.com/picture.jpg', 'atc-menu' )
                             )
          );

    add_option( 'atc_settings', apply_filters( 'atc_defaults', $defaults ) );
}

// Hook for adding admin menus
if ( is_admin() ){ // admin actions

  // Hook for adding admin menu
  add_action( 'admin_menu', 'atc_op_page' );

  add_action( 'admin_init', 'atc_register_setting' );

       // Hook to fire farbtastic includes for using built in WordPress color picker functionality
    add_action('admin_enqueue_scripts', 'atc_farbtastic_script');

// Display the 'Settings' link in the plugin row on the installed plugins list page
    add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'atc_admin_plugin_actions', -10);


} else { // non-admin enqueues, actions, and filters

}

// Include WordPress color picker functionality
function atc_farbtastic_script() {

    // load the style and script for farbtastic
    wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic' );
        wp_enqueue_media();

}


// action function for above hook
function atc_op_page() {

    // Add a new submenu under Settings:
    add_options_page(__('Add To Content','atc-menu'), __('Add To Content','atc-menu'), 'manage_options', __FILE__, 'atc_settings_page');
}
function atc_register_setting() {   
register_setting( 'atc_options', 'atc_settings' );
}

// atc_settings_page() displays the page content 
function atc_settings_page() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $hidden_field_name = 'atc_submit_hidden';
    

// read options values
$options = get_option( 'atc_settings' );

    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

        // Save the posted value in the database
update_option( 'atc_settings', $options );

?>

<div class="updated"><p><strong><?php _e('settings saved. woo hoo', 'atc-menu' ); ?></strong></p></div>

<?php 

    }



    // Now display the settings editing screen

    echo '<div class="wrap">';
    
    // icon for settings
    
     echo '<div id="icon-plugins" class="icon32"></div>';

    // header

    echo "<h2>" . __( 'Add To Content Settings', 'atc-menu' ) . "</h2>";

    // settings form and farbtastic script on click shoot out
    
    ?>

<form name="form" method="post" action="options.php" id="frm1" >


<?php
            settings_fields( 'atc_options' );
            $options = get_option( 'atc_settings' );

//checks to see if empty then populates values

if ($options['top']['bc']== '')
    {
    $options['top']['bc'] = '#fff'; 
    }
if ($options['top']['tc']== '')
    {
    $options['top']['tc'] = '#333'; 
    }
if ($options['bottom']['bc']== '')
    {
    $options['bottom']['bc'] = '#fff'; 
    }
if ($options['bottom']['tc']== '')
    {
    $options['bottom']['tc'] = '#333'; 
    }
?>

        
<script type="text/javascript">

        jQuery(document).ready(function() {
            jQuery('#colorpicker1').hide();
            jQuery('#colorpicker1').farbtastic("#color1");
            jQuery("#color1").click(function(){jQuery('#colorpicker1').slideToggle()});
          
        });
                
                jQuery(document).ready(function() {
            jQuery('#colorpicker2').hide();
            jQuery('#colorpicker2').farbtastic("#color2");
            jQuery("#color2").click(function(){jQuery('#colorpicker2').slideToggle()});
                        
        });

                
        jQuery(document).ready(function() {
            jQuery('#colorpicker3').hide();
            jQuery('#colorpicker3').farbtastic("#color3");
            jQuery("#color3").click(function(){jQuery('#colorpicker3').slideToggle()});
          
        });
                
                jQuery(document).ready(function() {
            jQuery('#colorpicker4').hide();
            jQuery('#colorpicker4').farbtastic("#color4");
            jQuery("#color4").click(function(){jQuery('#colorpicker4').slideToggle()});
                        
        });

jQuery(document).ready(function($){
 
 
    var custom_uploader;
 
 
    $('#upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
});
                  
                
 jQuery(document).ready(function(){
 
 
    var custom_uploader2;
 
 
    jQuery('#upload_image_button2').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader2) {
            custom_uploader2.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader2 = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader2.on('select', function() {
            attachment = custom_uploader2.state().get('selection').first().toJSON();
            jQuery('#upload_image2').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader2.open();
 
    });
});
                  
</script>



<script type="text/javascript">
         function nxs_chAllCats(ch){ 

              jQuery("form input:checkbox[name='post_category[]']").attr('checked', ch==1);}

        (function($) { $(function() {

              $('.button-primary[name="update_NS_SNAutoPoster_settings"]').bind('click', function(e) { 

var str = $('input[name="post_category[]"]').serialize(); 

              $('div.categorydivInd').replaceWith('<input type="hidden" name="pcInd" value="" />');
 
str = str.replace(/post_category/g, "pk"); 

              $('div.categorydiv').replaceWith('<input type="hidden" name="post_category" value="'+str+'" />');  
 
    
}); 
}); 
})
(jQuery); 
    </script>

<style type="text/css">
#upload_image, #upload_image2 {
width: 100%;
}
</style>

<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="posts"><?php _e('Include in Posts', 'atc-menu');  ?></label>
            </th>
            <td>
            <input name="atc_settings[option][one]" type="checkbox" value="1" <?php if (  1 == ($options['option']['one'])) echo "checked='checked'"; ?> />
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="posts"><?php _e('Include on Pages', 'atc-menu');  ?></label>
            </th>
            <td>
            <input name="atc_settings[option][two]" type="checkbox" value="1" <?php if (  1 == ($options['option']['two'])) echo "checked='checked'"; ?> />
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="posts"><?php _e('Categories to Exclude', 'atc-menu');  ?></label>
            </th>
            <td>
            <?php wp_category_checklist(); ?>
            </td>
        </tr>
        
        
    </tbody>
</table>


<table class="form-table" border="0" >


<tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<th width="20%"><?php _e("Top Content: ", 'atc-menu' ); ?> </th>
<td width="80%"><textarea name="atc_settings[top][text]" style="height:100px;width:100%;"><?php echo esc_attr( $options['top']['text'] ); ?></textarea></td>
</tr>

<tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<th width="20%"><?php _e("Tick to Enable Top Content: ", 'atc-menu' ); ?> </th>
<td><input name="atc_settings[top][check]" type="checkbox" value="1" <?php if (  1 == ($options['top']['check'])) echo "checked='checked'"; ?> /></td>
</tr>

        <tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <th scope="row"><?php _e('Background color', 'atc-menu' ); ?></th>
            <td width="20%"><input type="text" maxlength="7" size="6" value="<?php echo esc_attr( $options['top']['bc'] ); ?>" name="atc_settings[top][bc]" id="color1" />
            <div id="colorpicker1"></div></td>
        </tr>

                <tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <th scope="row"><?php _e('Text color', 'atc-menu' ); ?></th>
            <td width="20%"><input type="text" maxlength="7" size="6" value="<?php echo esc_attr( $options['top']['tc'] ); ?>" name="atc_settings[top][tc]" id="color2" />
            <div id="colorpicker2"></div></td>
        </tr>

<tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<th scope="row"><?php _e('Choose Image', 'atc-menu' ); ?></th>
<td><label for="upload_image">
<input id="upload_image" type="text" size="36" name="atc_settings[top][upi]" value="<?php echo $options['top']['upi']; ?>" /> 
<input id="upload_image_button" class="button" type="button" value="Upload Image" />
<br /><?php _e('Enter an URL, upload or select an existing image for the banner.', 'atc-menu' ); ?>
</label></td>
</tr>


<tr valign="top">
            <th scope="row" ></th>
            <td width="20%" ></td>
        </tr>



<tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<th width="20%"><?php _e('Bottom Content: ', 'atc-menu' ); ?>  </th>
<td width="80%"><textarea name="atc_settings[bottom][text]" style="height:100px;width:100%;"><?php echo esc_attr( $options['bottom']['text'] ); ?></textarea>
</td>
</tr>

<tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<th width="20%"><?php _e("Tick to Enable Bottom Content: ", 'atc-menu' ); ?> </th>
<td><input name="atc_settings[bottom][check]" type="checkbox" value="1" <?php if (  1 == ($options['bottom']['check'])) echo "checked='checked'"; ?> /></td>
</tr>

                 <tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <th scope="row"><?php _e('Background color', 'atc-menu' ); ?> </th>
            <td width="20%"><input type="text" maxlength="7" size="6" value="<?php echo esc_attr( $options['bottom']['bc'] ); ?>" name="atc_settings[bottom][bc]" id="color3" />
            <div id="colorpicker3"></div></td>
        </tr>
                
                <tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <th scope="row"><?php _e('Text color', 'atc-menu' ); ?> </th>
            <td width="20%"><input type="text" maxlength="7" size="6" value="<?php echo esc_attr( $options['bottom']['tc'] ); ?>" name="atc_settings[bottom][tc]" id="color4" />
            <div id="colorpicker4"></div></td>
        </tr>


<tr valign="top">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<th scope="row"><?php _e('Choose Image', 'atc-menu' ); ?></th>
<td><label for="upload_image">
<input id="upload_image2" type="text" size="36" name="atc_settings[bottom][upi]" value="<?php echo esc_attr( $options['bottom']['upi'] ); ?>" /> 
<input id="upload_image_button2" class="button" type="button" value="Upload Image" />
<br /><?php _e('Enter an URL, upload or select an existing image for the banner.', 'atc-menu' ); ?>
</label></td>
</tr>

<tr><th scope="row"><div style="float:right;"><?php submit_button(); ?></div></th>
</form>
</tr>
</div>

</table>
<?php }

// Build array of links for rendering in installed plugins list
function atc_admin_plugin_actions($links) {

$atc_plugin_links = array(
          '<a href="options-general.php?page=add-to-content/add-to-content.php">'.__('Settings').'</a>',
           '<a href="http://www.trottyzone.com/forums/forum/website-support/">'.__('Support').'</a>', 
                             );

    return array_merge( $atc_plugin_links, $links );

}

function add_to_content001($content) {

    $options = get_option( 'atc_settings' );

    if (is_page()) {
        if (1 == ($options['option']['two'])) {
            if ( 1 == ($options['top']['check']) ) {
                $content = '<div style="color:'.$options['top']['tc'].';background-color:'.$options['top']['bc'].';background-image: url('.$options['top']['upi'].');">'.$options['top']['text'].'</div>' . $content;
            }
            if ( 1 == ($options['bottom']['check']) ) {
                $content = $content . '<div style="color:'.$options['bottom']['tc'].';background-color:'.$options['bottom']['bc'].';background-image: url('.$options['bottom']['upi'].');">'.$options['bottom']['text'].'</div>';
            }
        }
    } elseif (is_single()) {
        if (1 == ($options['option']['one'])) {

        //look at included and excluded Categories
            
            if (!empty($options['option']['three'])){
                $multiCategoriesInclude= $options['option']['three'];
                // move into usable array
                $multiCategoriesIncludeArray=explode(',',$multiCategoriesInclude);


                if (in_category($multiCategoriesIncludeArray)) {
                    if ( 1 == ($options['top']['check']) ){
                        $content = '<div style="color:'.$options['top']['tc'].';background-color:'.$options['top']['bc'].';background-image: url('.$options['top']['upi'].');">'.$options['top']['text'].'</div>' . $content;
                    }
                    if ( 1 == ($options['bottom']['check']) ){
                        $content = $content . '<div style="color:'.$options['bottom']['tc'].';background-color:'.$options['bottom']['bc'].';background-image: url('.$options['bottom']['upi'].');">'.$options['bottom']['text'].'</div>';
                    }
                }
            } elseif (!empty ($options['option']['four'] )){
                $multiCategoriesExclude= $options['option']['four'];
                // move into usable array
                $multiCategoriesArrayExclude=explode(',',$multiCategoriesExclude);
        
                if (!in_category($multiCategoriesArrayExclude)) {
                    if ( 1 == ($options['top']['check']) ){
                        $content = '<div style="color:'.$options['top']['tc'].';background-color:'.$options['top']['bc'].';background-image: url('.$options['top']['upi'].');">'.$options['top']['text'].'</div>' . $content;
                    }
                    if ( 1 == ($options['bottom']['check']) ){
                        $content = $content . '<div style="color:'.$options['bottom']['tc'].';background-color:'.$options['bottom']['bc'].';background-image: url('.$options['bottom']['upi'].');">'.$options['bottom']['text'].'</div>';
                    }   
                }
            } else {
                if ( 1 == ($options['top']['check']) ){
                    $content = '<div style="color:'.$options['top']['tc'].';background-color:'.$options['top']['bc'].';background-image: url('.$options['top']['upi'].');">'.$options['top']['text'].'</div>' . $content;
                }
                if ( 1 == ($options['bottom']['check']) ){
                    $content = $content . '<div style="color:'.$options['bottom']['tc'].';background-color:'.$options['bottom']['bc'].';background-image: url('.$options['bottom']['upi'].');">'.$options['bottom']['text'].'</div>';
                }       
            }
        }
    }
return $content;
}

add_filter('the_content', 'add_to_content001');


