<?php

/*
 * Plugin Name: Tell a Friend
 * Plugin URI: https://v-ned.tech
 * Description: Tell a Friend - Shortcode - [tell-a-friend email_button="true" whatsapp_button="true" email_subject="Subject" email_text="Text" whatsapp_text="Text"]
 * Version: 1.0.0
 * Author: v-ned
 * Author URI: https://v-ned.tech
 * Text Domain: tell-friend
 * Requires at least: 5.5
 * Tested up to: 6.1
 * Requires PHP: 7.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 5.5.0
 * WC tested up to: 5.5.2
 */

$tf_option_email_checked = get_option('tf_option_email');
$tf_option_whatsapp_checked = get_option('tf_option_whatsapp');
$tf_option_post_type_checked = get_option('tf_option_post_type');
$tf_option_email_subject = get_option('tf_option_email_subject');
$tf_option_email_text = get_option('tf_option_email_text');
$tf_option_whatsapp_text = get_option('tf_option_whatsapp_text');
$tf_plugin_dir  = plugin_dir_path( __FILE__ );
$tf_plugin_url  = plugins_url().'/tell-friend';


function tf_settings_link( $links_array, $plugin_file_name ) {
    $links_array['settings'] = '<a href="' . esc_url('/wp-admin/options-general.php?page=tf') . '" aria-label="' . esc_attr__( "Settings", "tell-friend" ) . '">' . esc_html__( "Settings", "tell-friend" ) . '</a>';
    return $links_array;
}

add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'tf_settings_link', 10, 2 );


function tf_register_settings() {
   add_option( 'tf_option_email', 1); 
   add_option( 'tf_option_whatsapp', 1); 
   add_option( 'tf_option_post_type', ['post', 'page']);  
   add_option( 'tf_option_email_subject', 'Subject'); 
   add_option( 'tf_option_email_text', 'Text'); 
   add_option( 'tf_option_whatsapp_text', 'Text'); 
    
   register_setting( 'tf_options_group', 'tf_option_email', 'tf_callback' );
   register_setting( 'tf_options_group', 'tf_option_whatsapp', 'tf_callback' );
   register_setting( 'tf_options_group', 'tf_option_post_type', 'tf_callback' );
   register_setting( 'tf_options_group', 'tf_option_email_subject', 'tf_callback' );
   register_setting( 'tf_options_group', 'tf_option_email_text', 'tf_callback' );
   register_setting( 'tf_options_group', 'tf_option_whatsapp_text', 'tf_callback' ); 
}
add_action( 'admin_init', 'tf_register_settings' );


function tf_register_options_page() {
  add_options_page( esc_html__( "Tell a Friend - Settings", "tell-friend" ), esc_html__( "Tell a Friend - Settings", "tell-friend" ), 'manage_options', 'tf', 'tf_options_page');
}
add_action('admin_menu', 'tf_register_options_page');


function tf_options_page(){
    global 
    $post,
    $tf_option_email_checked, 
    $tf_option_whatsapp_checked, 
    $tf_option_post_type_checked, 
    $tf_option_email_subject,        
    $tf_option_email_text, 
    $tf_option_whatsapp_text;
   
    /*$post_types = get_post_types([
        'public' => true
    ]);*/
    
    $post_types = ['post', 'page', 'product'];
    
?>
    <div>
        <h1><?php echo esc_html__( "Tell a Friend - Settings", "tell-friend" ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('tf_options_group'); ?>
            
            <div class="section">
                <h3><?php echo esc_html__( "Select Method:", "tell-friend" ); ?></h3>
                
                <label for="tf_option_email">
                    <input <?php checked( $tf_option_email_checked, 1 ); ?> type="checkbox" id="tf_option_email" name="tf_option_email" value="1" />
                    <span style="font-size: 25px;" class="dashicons dashicons-email-alt"></span>&nbsp;&nbsp;E-Mail
                </label> 
                &nbsp; &nbsp; &nbsp;
                <label for="tf_option_whatsapp">   
                    <input <?php checked( $tf_option_whatsapp_checked, 1 ); ?> type="checkbox" id="tf_option_whatsapp" name="tf_option_whatsapp" value="1" />
                    <span style="font-size: 25px;" class="dashicons dashicons-whatsapp"></span>&nbsp;&nbsp;WhatsApp
                </label> 
            </div>
            
             <div class="section">
                <h3><?php echo esc_html__( "Text Messages:", "tell-friend" ); ?></h3>
                <label for="tf_option_email_subject"> <?php echo esc_html__( "Subject E-Mail:", "tell-friend" ); ?></label> 
                <br> 
                <input type="text" id="tf_option_email_subject" name="tf_option_email_subject" value="<?php echo esc_attr($tf_option_email_subject); ?>">
                <br>
                <label for="tf_option_email_message"> <?php echo esc_html__( "Text E-Mail:", "tell-friend" ); ?></label> 
                <br>
                <textarea cols="50" type="checkbox" id="tf_option_email_message" name="tf_option_email_text"><?php echo esc_attr($tf_option_email_text); ?></textarea>
                <br>
                <br>
                <label for="tf_option_whatsapp_message"> <?php echo esc_html__( "Text WhatsApp:", "tell-friend" ); ?></label> 
                <br>
                <textarea cols="50" type="checkbox" id="tf_option_whatsapp_message" name="tf_option_whatsapp_text"><?php echo esc_attr($tf_option_whatsapp_text); ?></textarea>
            </div>
          
            <div class="section">
                <h3><?php echo esc_html__( "Select Post Type:", "tell-friend" ); ?></h3>
                
                <?php foreach ($post_types as $post_type): $name_post_type = get_post_type_object( $post_type ) ?>
                    <label for="tf_option_post_type_<?php echo $post_type; ?>">   
                        <input <?php echo (in_array($post_type, $tf_option_post_type_checked)) ? 'checked="checked"':''; ?> type="checkbox" id="tf_option_post_type_<?php echo $post_type; ?>" name="tf_option_post_type[]" value="<?php echo $post_type; ?>" />
                        <?php echo $name_post_type->labels->name; ?>
                    </label> 
                    <br>
                <?php endforeach; ?>
            </div>
            
            <?php submit_button(); ?>
        </form>

    </div>
<?php
}


function tf_share_buttons_page_content( $content ) {
    global $post;
    
    $post_type = get_post_type( $post->ID );
    $tf_share_buttons_template = '';
    
    
    
    if($post_type != 'product' && (is_single() || is_page())){
        $tf_share_buttons_template = tf_share_buttons_template();
    }

    return $content.$tf_share_buttons_template;
}
add_filter( 'the_content', 'tf_share_buttons_page_content');


function tf_share_buttons_product_content() {
    $tf_share_buttons_template = tf_share_buttons_template();
    echo $tf_share_buttons_template;
}
add_action('woocommerce_after_add_to_cart_form', 'tf_share_buttons_product_content');


function tf_share_buttons_template(){
    global 
    $post,
    $tf_plugin_dir,
    $tf_plugin_url,        
    $tf_option_email_checked, 
    $tf_option_whatsapp_checked, 
    $tf_option_post_type_checked, 
    $tf_option_email_subject,        
    $tf_option_email_text, 
    $tf_option_whatsapp_text;
    
    $tf_email_button = '';
    $tf_whatsapp_button = '';
    $tf_sharing = '';
    
    $post_type = get_post_type( $post->ID );
    $post_link = get_permalink( $post->ID );
    
    if(in_array($post_type, $tf_option_post_type_checked)){
        if ($tf_option_email_checked){
            $tf_option_email_text = str_replace('<br />', '%break_line%', nl2br($tf_option_email_text)) . '%break_line%';
            $tf_email_button_link = 'mailto:?subject=' . $tf_option_email_subject . '&body=' . $tf_option_email_text . $post_link;
            $tf_email_button = '<a title="' . esc_html__("Tell a Friend via E-Mail", "tell-friend") . '" class="tf tf-email" href="' . esc_url( $tf_email_button_link,  array( 'mailto' ) ) . '">' . esc_html__("Tell a Friend via E-Mail", "tell-friend") . '<i><img src="'. esc_url($tf_plugin_url).'/assets/img/icon-email.png"></i></a>';
            $tf_email_button = str_replace(['%break_line% %break_line%', '%break_line%'], ['%0D', '%0D'], $tf_email_button); 
        }
        
        if ($tf_option_whatsapp_checked){
            $tf_option_whatsapp_text = str_replace('<br />', '%break_line%', nl2br($tf_option_whatsapp_text)) . '%break_line%';
            $tf_whatsapp_button_link = 'whatsapp://send?text=' .  $tf_option_whatsapp_text . $post_link;
            $tf_whatsapp_button = '<a title="' . esc_html__("Tell a Friend via WhatsApp", "tell-friend") . '" class="tf tf-whatsapp" href="' . esc_url( $tf_whatsapp_button_link,  array( 'whatsapp' ) ) . '">' . esc_html__("Tell a Friend via WhatsApp", "tell-friend") . '<i><img src="'. esc_url($tf_plugin_url).'/assets/img/icon-whatsapp.png"></i></a>';
            $tf_whatsapp_button = str_replace(['%break_line% %break_line%', '%break_line%'], ['%0D%0A', '%0D%0A'], $tf_whatsapp_button);
        }
            
        $tf_sharing = '<div class="tf-sharing">' . $tf_email_button . $tf_whatsapp_button . '</div>'; 
    }
   
    return $tf_sharing;
}


function tf_share_buttons_shortcode($atts){
    global 
    $post,
    $tf_plugin_dir,
    $tf_plugin_url,        
    $tf_option_email_checked, 
    $tf_option_whatsapp_checked, 
    $tf_option_post_type_checked, 
    $tf_option_email_subject,            
    $tf_option_email_text,
    $tf_option_whatsapp_text;
    
    $tf_email_button = '';
    $tf_whatsapp_button = '';
    $tf_sharing = '';
    
    $post_type = get_post_type( $post->ID );
    $post_link = get_permalink( $post->ID );
    
    
    $atts = shortcode_atts(array(
        'email_button' => 'true',
        'whatsapp_button' => 'true',
        'email_text' => '',
        'email_subject' => '',
        'whatsapp_text' => '',
    ), $atts);
    
    ob_start(); 
    
    if(!empty($atts['email_text']))
        $tf_option_email_text = $atts['email_text'];
    
    if(!empty($atts['email_subject']))
        $tf_option_email_subject = $atts['email_subject'];
       
    if(!empty($atts['whatsapp_text']))
        $tf_option_whatsapp_text = $atts['whatsapp_text'];
    
    if ($atts['email_button'] == 'true'){
        $tf_option_email_text = str_replace('<br />', '%break_line%', nl2br($tf_option_email_text)) . '%break_line%';
        $tf_email_button_link = 'mailto:?subject=' . $tf_option_email_subject . '&body=' . $tf_option_email_text . $post_link;
        $tf_email_button = '<a title="' . esc_html__("Tell a Friend via E-Mail", "tell-friend") . '" class="tf tf-email" href="' . esc_url( $tf_email_button_link,  array( 'mailto' ) ) . '">' . esc_html__("Tell a Friend via E-Mail", "tell-friend") . '<i><img src="'. esc_url($tf_plugin_url).'/assets/img/icon-email.png"></i></a>';
        $tf_email_button = str_replace(['%break_line% %break_line%', '%break_line%'], ['%0D', '%0D'], $tf_email_button); 
    }    
    
    if ($atts['whatsapp_button'] == 'true'){
        $tf_option_whatsapp_text = str_replace('<br />', '%break_line%', nl2br($tf_option_whatsapp_text)) . '%break_line%';
        $tf_whatsapp_button_link = 'whatsapp://send?text=' .  $tf_option_whatsapp_text . $post_link;
        $tf_whatsapp_button = '<a title="' . esc_html__("Tell a Friend via WhatsApp", "tell-friend") . '" class="tf tf-whatsapp" href="' . esc_url( $tf_whatsapp_button_link, array( 'whatsapp' ) ) . '">' . esc_html__("Tell a Friend via WhatsApp", "tell-friend") . '<i><img src="'. esc_url($tf_plugin_url).'/assets/img/icon-whatsapp.png"></i></a>';
        $tf_whatsapp_button = str_replace(['%break_line% %break_line%', '%break_line%'], ['%0D%0A', '%0D%0A'], $tf_whatsapp_button);
    }
    
    $tf_sharing = '<div class="tf-sharing">' . $tf_email_button . $tf_whatsapp_button . '</div>'; 
    
    echo $tf_sharing;
    
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode( 'tell-a-friend', 'tf_share_buttons_shortcode' );    


function tf_include_style(){
    wp_enqueue_style( 'tf-style', plugins_url('assets/css/tf-style.css', __FILE__), false, '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', "tf_include_style");


function tf_plugin_language() {
    load_plugin_textdomain( 'tell-friend', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'tf_plugin_language' );
