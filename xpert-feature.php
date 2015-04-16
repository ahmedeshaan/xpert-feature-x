<?php
/*
Plugin Name: Xpert Feature
Plugin URI: http://themexpert.com/wordpress-plugins/xpert-team
Version: 1.0
Author: ThemeXpert
Authro URI : http://www.themexpert.com
Description: Supercharge your WordPress team plugin
License: GPLv2 or later
Text Domain: xf
*/


add_action( 'init', 'xpert_feature_init' );
/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function xpert_feature_init() {
    $labels = array(
        'name'               => _x( 'TX Features', 'xpert-feature' ),
        'singular_name'      => _x( 'TX Features', 'xpert-feature' ),
        'menu_name'          => _x( 'TX Features', 'xpert-feature' ),
        'name_admin_bar'     => _x( 'Feature', 'xpert-feature' ),
        'add_new'            => _x( 'Add New', 'feature', 'xpert-feature' ),
        'add_new_item'       => __( 'Add New Feature', 'xpert-feature' ),
        'new_item'           => __( 'New Feature', 'xpert-feature' ),
        'edit_item'          => __( 'Edit Feature', 'xpert-feature' ),
        'view_item'          => __( 'View Feature', 'xpert-feature' ),
        'all_items'          => __( 'Edit Features', 'xpert-feature' ),
        'search_items'       => __( 'Search Features', 'xpert-feature' ),
        'parent_item_colon'  => __( 'Parent Features:', 'xpert-feature' ),
        'not_found'          => __( 'No features found.', 'xpert-feature' ),
        'not_found_in_trash' => __( 'No features found in Trash.', 'xpert-feature' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'feature' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor','thumbnail' )
    );

    register_post_type( 'feature', $args );
}


    function metaboxes() {
        add_action('add_meta_boxes', function() {
            add_meta_box('tx_feature', 'Additional Settings', 'tx_settings', 'feature');
        });

        function tx_settings($post) {
            $id = $post->ID;
            $tx_title         = get_post_meta($id, 'tx_title', true);
            $tx_url           = get_post_meta($id, 'tx_url', true);
            $tx_position      = get_post_meta($id, 'tx_position', true);

            ?>
            <style>
            #tx_feature table { width: 100%; }
            #tx_feature td.custom-title { width: 30%; height: 28px; }
            </style>
            <table class="custom-table">
                <tr>
                    <td class="custom-title"><label for="tx_title">Call To Action Title</label></td>
                    <td class="custom-input">
                        <input type="text" class="widefat" id="tx_title" name="tx_title" value="<?php echo esc_attr($tx_title); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="custom-title"><label for="tx_url">Call To Action URL</label></td>
                    <td class="custom-input">
                        <input type="text" class="widefat" id="tx_url" name="tx_url" value="<?php echo esc_attr($tx_url); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="custom-title"><label for="tx_position"></label></td>
                    <td class="custom-input">
                        <select id="tx_position" class="image-picker show-html" name="tx_position">
                                  <option data-img-src=" <?php echo plugins_url('assets/image/layoutOne.jpg', __FILE__) ?>" value="layoutOne"<?php if($tx_position == 'layoutOne') echo 'selected="selected"'; ?>></option>
                                  <option data-img-src=" <?php echo plugins_url('assets/image/layoutTwo.jpg', __FILE__) ?>" value="layoutTwo"<?php if($tx_position == 'layoutTwo') echo 'selected="selected"'; ?>></option>
                                  <option data-img-src=" <?php echo plugins_url('assets/image/layoutThree.jpg', __FILE__) ?>" value="layoutThree"<?php if($tx_position == 'layoutThree') echo 'selected="selected"'; ?>></option>
                                  <option data-img-src=" <?php echo plugins_url('assets/image/layoutFour.jpg', __FILE__) ?>" value="layoutFour"<?php if($tx_position == 'layoutFour') echo 'selected="selected"'; ?>></option>                 
                        </select>
                    </td>
                </tr>
            </table>

            <?php
        }

        add_action('save_post', 'tx_save');

        function tx_save($id) {
            if(!empty($_POST['tx_title']))
                update_post_meta($id, 'tx_title', $_POST['tx_title']) || add_post_meta($id, 'tx_title', $_POST['tx_title']);
            if(!empty($_POST['tx_url']))
                update_post_meta($id, 'tx_url', $_POST['tx_url']) || add_post_meta($id, 'tx_url', $_POST['tx_url']);
            if(!empty($_POST['tx_position']))
                update_post_meta($id, 'tx_position', $_POST['tx_position']) || add_post_meta($id, 'tx_position', $_POST['tx_position']);
        }


    }


add_action( 'init', 'metaboxes' );

//__ Create Shortcode__//

add_shortcode('xpert-feature','feature_placement_shortcode');

function feature_placement_shortcode($atts, $content){


    
    $args = array(
            'post_type'   => 'feature',
            'layout'      => 'center',
            'post_id'     => '',
    
        );
    $data = shortcode_atts($args, $atts);   

    $feature =  get_posts($args);
    

    foreach ($feature as $post) {
        setup_postdata( $post );
        $okey = $post->ID;

        if($post->ID == $data['post_id']){

        $call_to_action_title    = get_post_meta( $post->ID, 'tx_title', true );
        $call_to_action_url      = get_post_meta( $post->ID, 'tx_url', true );
        $call_to_action_position = get_post_meta( $post->ID, 'tx_position', true );
        $xpert_feature_title     = get_the_title($post->ID);
        $xpert_feature_image     = get_the_post_thumbnail($post->ID);
        $xpert_feature_content   = get_the_content($post->ID);
        //echo $xpert_feature_image;

         $layout = get_post_meta( $post->ID, 'tx_position', TRUE );
         //echo $layout;

         switch ($layout) {
        case 'layoutOne':
            # code...
                    echo '<section id="feature-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="img" data-wow-duration="800ms" data-wow-delay="800ms" href="#" class="wow fadeInRight" >
                                '.$xpert_feature_image.'                       
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-content">
                                    <h1 class="wow fadeInLeft section-title" data-wow-duration="300ms" data-wow-delay="100ms"><a href="'.$call_to_action_url.'">'.$call_to_action_title.'</a></h1>
                                    
                                    <p class="wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="300ms">
                                       '.$call_to_action_title.'
                                    </p>
                                     

                                <a data-wow-duration="800ms" data-wow-delay="800ms" href="'.$call_to_action_url.'" class="btn btn-default btn-download wow fadeInUp"><i class="ion-ios-download-outline"></i> Download Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>';             
            break;

             case 'layoutTwo':
                    echo '<section id="feature-3">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="feature-content">
                                            <h1 class="wow fadeInLeft section-title" data-wow-duration="300ms" data-wow-delay="100ms">'.$call_to_action_title.'</h1>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="block wow fadeInLeft" data-wow-duration="300ms" data-wow-delay="300ms">
                                                        <h4>Lorem ipsum dolor sit.</h4>
                                                        <p>'.$xpert_feature_content.'</p>        
                                                    </div>
                                                </div>                                             
                                            </div>
                                            <a data-wow-duration="800ms" data-wow-delay="800ms" href="'.$call_to_action_url.'" class="btn btn-default btn-download wow fadeInUp"><i class="ion-ios-download-outline"></i> Download Now</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="slider">
                                            <div id="owl-example" class="owl-carousel">
                                                <div>'.$xpert_feature_image.'</div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>';
                break;

            case 'layoutThree':
                # code...
                    echo '<section id="feature">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="block">
                                            <div class="section-header text-center">
                                                <h1 class="wow fadeInUp section-title" data-wow-duration="500ms" data-wow-delay="100ms">'.$call_to_action_title.'</h1>
                                                <p class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms">
                                                    '.$xpert_feature_content.'
                                                </p>    
                                            </div>
                                            <div class="showcase-1 text-center">
                                                <div class="wow fadeInUp" data-wow-duration="500ms" data-wow-delay="600ms">
                                                    '.$xpert_feature_image.'
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>';
                break;

                case 'layoutFour':
                    # code...
                            echo '<section id="feature">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="block">
                                            <div class="section-header text-center">
                                                <h1 class="wow fadeInUp section-title" data-wow-duration="500ms" data-wow-delay="100ms">'.$call_to_action_title.'</h1>
                                                <p class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms">
                                                    '.$xpert_feature_content.'
                                                </p>    
                                            </div>                                            
                                    </div>
                                </div>
                            </div>
                        </section>';

                    break;

        
        default:
            # code...
            break;
    }

        wp_reset_postdata();
            //return $output;
        }

    }
}

//__End Shortcode__//

//__Tinymce Buttion Load__//

add_action('admin_head', 'tx_add_my_tc_button');


function tx_add_my_tc_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'page' ) ) )
        return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "tx_add_tinymce_plugin");
        add_filter('mce_buttons', 'tx_register_my_tc_button');
        
    }
}


function tx_add_tinymce_plugin($plugin_array) {
    $plugin_array['tx_tc_button'] = plugins_url( '/text-button.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
    return $plugin_array;
}

function tx_register_my_tc_button($buttons) {
   array_push($buttons, "tx_tc_button");
   return $buttons;
}

//__End Tinymc load__//

//__Current post load__//
add_action('admin_head','tx_current_post');
function tx_current_post() {

    global $current_screen;
    $type = $current_screen->post_type;

    if (is_admin() && $type == 'post' || $type == 'page') {
        ?>
        <script type="text/javascript">
        var post_id = '<?php global $post; echo $post->ID; ?>';
        </script>
        <?php
    }

}

//__End Post Load__//


//__Enqueue script and style Load__//
add_action( 'admin_enqueue_scripts', 'FeatureBackendScripts' );
function FeatureBackendScripts(){
 
wp_enqueue_script('image-picker-js', plugins_url('assets/vendor/image-picker/js/image-picker.min.js',__FILE__));
wp_enqueue_script('xpert-picker-app-js', plugins_url('assets/js/app.min.js',__FILE__));
wp_enqueue_style('image-picker-css', plugins_url('assets/vendor/image-picker/css/image-picker.css', __FILE__));

wp_enqueue_script('tx_bootstrap_feature-modal-js', plugins_url('assets/vendor/bootstrap/js/modal.js',__FILE__));
wp_enqueue_script('tx_bootstrap_transision-js', plugins_url('assets/vendor/bootstrap/js/transition.js',__FILE__));
wp_enqueue_style('tx_feature-css', plugins_url('assets/css/app.min.css',__FILE__));
 
wp_enqueue_style('tx-tc', plugins_url('assets/css/style.min.css', __FILE__));
}

//__ End Script and Style Load__//


//__ Modal Load__//
add_action('admin_footer', function(){
      $query = new WP_Query(array('post_type' => 'feature'));
      $posts = $query->get_posts();
    ?>
 
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tx_modal_title">Xpert Feature Shortcode</h4>
              </div>
              <div class="modal-body">
                <form>
                  <div class="form-group">
                    <label for="select-post" class="control-label">Select a Post:</label>
                    <select id="shotcode_selector" class="form-control tx-feature-value">
                       <option value="">Select Your feature post</option>';
                         <?php 
                               foreach($posts as $post) {
                               echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
                                }
                         ?>
                    </select>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary action-insert-shortcode">Enter Shortcode</button>
              </div>
            </div>
          </div>
        </div>
            <?php
        });

//__End Modal Load__//