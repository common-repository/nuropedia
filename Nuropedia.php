<?php

/*

 * Plugin Name: Nuropedia
 * Plugin URI: https://staging2.nuronics.com/home-2/
 * Description: Nuropedia allows you to engage your WordPress site visitors with instant conversations,responding to queries, scheduling meetings, generating leads and a lot more  to your website.
 * Version: 0.8
 * Requires at least: 4.6
 * Author: nuronicscorp
 * Author URI:  https://nuronics.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html

*/


/* Block for authorization credentials of users for Nuropedia */


$WP_TEMP_DIR = plugin_dir_url(__file__);

function Nuro_Status(){
  global $wpdb;
  $table_name = $wpdb->prefix . "nuropedia_status";
  $user_activated = $wpdb->get_var( "SELECT plugin_status FROM $table_name where id=1 " );
  return $user_activated;  
}

function Nuro_Token(){
  global $wpdb;
  $table_name = $wpdb->prefix . "nuropedia_status";
  $user_activated = $wpdb->get_var( "SELECT token FROM $table_name where id=1 " );
  return $user_activated;  
}


/* Plugin initialization of Nuropedia */ 

if ( Nuro_Status()  == "true"){
  new NuroPlugin;
}


/* Main plugin class of Nuropedia */

class NuroPlugin{

    public function __construct(){
        
        add_action('wp_enqueue_scripts', array($this,'Nuro_load_assets'));

        add_shortcode('nuro', array($this,'Nuro_load_shortcode'));

        // add_filter( 'the_content', array($this,'Nuro_content_filter'));

        add_action('rest_api_init',array($this,'Nuro_register_rest_api'));

        add_action('wp', array($this,'Nuro_automation'));

    }


/* Inserting shortcode to other pages and posts of Nuropedia */

    // public function Nuro_content_filter( $content ) {
    //   global $post;
    //   if( ! $post instanceof WP_Post ) return $content;
    
    //   switch( $post->post_type ) {
    //     case 'page':
    //       return $content . '[nuro]';
    //     default:
    //       return $content;
    //   }
    // }


/* Automation of shortcode for Nuropedia */

    public function Nuro_automation() {
      if (is_admin()) {
        return; // Exit early if in the WordPress admin area
    }
      $nuro = do_shortcode('[nuro]');
      return $nuro ;
    }


/* Initializing css and js files for Nuropedia */

    public function Nuro_load_assets(){

        wp_enqueue_style(
            'nuro_plugin',
            plugin_dir_url(__file__) . 'css/nuro.css',
            array(),
            1,
            'all'
        );
        wp_enqueue_script( 
          'nuro_plugin_time_picker',
          plugin_dir_url(__file__).'js/nuroTimePicker.js',
          array('jquery'),
          '1.0.0',
          'true'
        );
        wp_enqueue_script( 
            'nuro_plugin_script',
            plugin_dir_url(__file__).'js/main.js',
            array('jquery'),
            '1.0.0',
            'true'
        );

        wp_enqueue_script( 'bootstrap-fontawesome-js', 'https://www.bootstrapcdn.com/fontawesome/' );
        wp_enqueue_style('google-icons-css','https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css');
        wp_enqueue_style('google-font-css','https://fonts.googleapis.com/css?family=Poppins');

        wp_enqueue_style( 'bootstrap-cdn-css', plugin_dir_url(__file__) . 'css/bootstrap.min.css' );
        wp_enqueue_script( 'bootstrap-cdn-js', plugin_dir_url(__file__) . 'js/bootstrap.min.js' );
      }


/* Inserting html code to home page in the form of shortcode */

    public function Nuro_load_shortcode()
    {
        include_once('Nuro_plugin.php');

    }


/*  Register rest api routing of Nuropedia */

    public function Nuro_register_rest_api(){
      register_rest_route('nuro','send-chat',array(
        'methods'=>'POST',
        'callback'=>array($this,'Nuro_route')
      ));
      register_rest_route('nuro','get-chat',array(
        'methods'=>'POST',
        'callback'=>array($this,'Nuro_get_chat')
      ));
      register_rest_route('nuro','send_calendar',array(
        'methods'=>'POST',
        'callback'=>array($this,'Nuro_send_calendar')
      ));
      register_rest_route('nuro','send_cancel',array(
        'methods'=>'POST',
        'callback'=>array($this,'Nuro_send_cancel')
      ));
      register_rest_route('nuro','send_reschedule',array(
        'methods'=>'POST',
        'callback'=>array($this,'Nuro_send_reschedule')
      ));
      register_rest_route('nuro','has_email',array(
        'methods'=>'POST',
        'callback'=>array($this,'Nuro_has_email')
      ));
      register_rest_route('nuro','set_email',array(
        'methods'=>'POST',
        'callback'=>array($this,'Nuro_set_email')
      ));
    }


/*  Routing of messages from Nuropedia */ 

    public function Nuro_route($data){
      $params = $data->get_params();
      $ans = isset($_POST['msg']);

      $session_id = $_POST['session_id'];
      $ans = sanitize_text_field($_POST['msg']); /* Sanitization of ans */
 
      $url = 'https://nuropedia.nuronics.com/api/bot';

      $body = [
	      'query' => $ans,
        'session_id' => $session_id,
        'hook_url' => get_home_url(),
      ];

      $token = Nuro_Token();
      $headers = array(
          'Authorization' => 'token '.$token
      );

      $options = [
        'methods' => 'POST',
        'headers'   => $headers,
        'body'     =>  $body,
        'timeout'     => 360,
      ];

      $response = wp_remote_post( $url, $options);
      $body = wp_remote_retrieve_body( $response );
      wp_send_json_success($body);
    }

    public function Nuro_get_chat($data){
      $params = $data->get_params();
      $session_id = $_POST['session_id'];
      $url = 'https://nuropedia.nuronics.com/api/chat_history';

      $body = [
        'session_id' => $session_id
      ];
      $token = Nuro_Token();
      $headers = array(
          'Authorization' => 'token '.$token
      );

      $options = [
        'methods' => 'POST',
        'headers'   => $headers,
        'body'     =>  $body,
        'timeout'     => 360,
      ];

      $response = wp_remote_post( $url, $options);
      $body = wp_remote_retrieve_body( $response );
      wp_send_json_success($body);
    }

    public function Nuro_send_calendar($data){
      $params = $data->get_params();

      $url = 'https://nuropedia.nuronics.com/api/calender';

      $body = [
        'date_time' => $_POST['msg'],
        'token' => $_POST['token'],
        'hook_url' => get_home_url(),
      ];
      $token = Nuro_Token();
      $headers = array(
          'Authorization' => 'token '.$token
      );

      $options = [
        'methods' => 'POST',
        'headers'   => $headers,
        'body'     =>  $body,
        'timeout'     => 360,
      ];

      $response = wp_remote_post( $url, $options);
      $body = wp_remote_retrieve_body( $response );
      wp_send_json_success($body);

    }

    public function Nuro_send_cancel($data){
      $params = $data->get_params();

      $url = 'https://nuropedia.nuronics.com/api/calender_cancel';

      $body = [
        'date_time' => $_POST['msg'],
        'token' => $_POST['token'],
        'hook_url' => get_home_url(),
      ];
      $token = Nuro_Token();
      $headers = array(
          'Authorization' => 'token '.$token
      );

      $options = [
        'methods' => 'POST',
        'headers'   => $headers,
        'body'     =>  $body,
        'timeout'     => 360,
      ];

      $response = wp_remote_post( $url, $options);
      $body = wp_remote_retrieve_body( $response );
      wp_send_json_success($body);

    }

    public function Nuro_send_reschedule($data){
      $params = $data->get_params();

      $url = 'https://nuropedia.nuronics.com/api/calender_reschedule';

      $body = [
        'oldDate' => $_POST['oldDate'],
        'newDate' => $_POST['newDate'],
        'session_token' => $_POST['token'],
        'hook_url' => get_home_url(),
      ];
      $token = Nuro_Token();
      $headers = array(
          'Authorization' => 'token '.$token
      );

      $options = [
        'methods' => 'POST',
        'headers'   => $headers,
        'body'     =>  $body,
        'timeout'     => 360,
      ];

      $response = wp_remote_post( $url, $options);
      $body = wp_remote_retrieve_body( $response );
      wp_send_json_success($body);
    }


    public function Nuro_has_email($data){
      $params = $data->get_params();
      $url = 'https://nuropedia.nuronics.com/api/has_email';

      $body = [
        'session_id' => $_POST['session_id'],
      ];
      $token = Nuro_Token();
      $headers = array(
          'Authorization' => 'token '.$token
      );

      $options = [
        'methods' => 'POST',
        'headers'   => $headers,
        'body' => $body,
        'timeout'  => 360,
      ];

      $response = wp_remote_post( $url, $options);
      $body = wp_remote_retrieve_body( $response );
      wp_send_json_success($body);
    }

    public function Nuro_set_email($data){
      $params = $data->get_params();
      $session_id = $_POST['session_id'];
      $end_email = $_POST['end_email'];
      $end_username = $_POST['end_user'];
      $url = 'https://nuropedia.nuronics.com/api/set_email';

      $body = [
        'session_id' => $session_id,
        'end_email' => $end_email,
        'end_username' => $end_username,
      ];
      $token = Nuro_Token();
      $headers = array(
          'Authorization' => 'token '.$token
      );

      $options = [
        'methods' => 'POST',
        'headers'   => $headers,
        'body'     =>  $body,
        'timeout'     => 360,
      ];

      $response = wp_remote_post( $url, $options);
      $body = wp_remote_retrieve_body( $response );
      wp_send_json_success($body);
    }

}


/* Adding admin menu content for Nuropedia */

add_action( 'admin_menu', 'Nuro_admin_menu');

function Nuro_admin_menu() {

  $token = Nuro_Token();
  if ($token == ""){
    $target_ui = "Nuro_admin_page";
    add_action( 'admin_enqueue_scripts', 'Nuro_login_style' );
  }
  else{
    $target_ui = "Nuro_menu_page";
    add_action( 'admin_enqueue_scripts', 'Nuro_dashboard_style' );
  }
  add_menu_page(
      __( 'Sample page', 'textdomain' ),
      __( 'Nuropedia', 'textdomain' ),
      'manage_options',
      'sample-page',
      $target_ui,
      'dashicons-schedule',
      66
    );
}


/* Adding dashboard ui page in wp-dashboard */

function Nuro_menu_page(){
  include_once('Nuro_dashboard.php');
}


/* Adding login ui page in wp-dashboard */

function Nuro_admin_page() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient pilchards to access this page.'));
  }
  $token = Nuro_Token();
  if ($token != ""){
    Nuro_admin_menu();
  }
  else{
      include_once('Nuro_login.php');
  }
}


/* Enqueueing css & js files for login page */

function Nuro_login_style( ) {

  wp_enqueue_style( 'bootstrap-cdn-css', plugin_dir_url(__file__) . 'css/bootstrap.min.css' );
  wp_enqueue_script( 'bootstrap-cdn-js', plugin_dir_url(__file__) . 'js/bootstrap.min.js' );
  wp_enqueue_script( 'bootstrap-cdn-js', 'https://www.bootstrapcdn.com/fontawesome/' );
  wp_enqueue_style('googlefont', 'https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap' );
  wp_enqueue_script( 
      'admin_custom_script', 
      plugin_dir_url(__file__) . 'js/login.js', array(),
      array('jquery'),
      '1.0' 
  );
  wp_enqueue_style(
    'admin_custom_style',
    plugin_dir_url(__file__) . 'css/login.css',
    array(),
    1,
    'all'
);
}


/* Enqueueing css & js files for dashboard page */
 
function Nuro_dashboard_style( ) {

  wp_enqueue_style( 'bootstrap-cdn-css', plugin_dir_url(__file__) . 'css/bootstrap.min.css' );
  wp_enqueue_script( 'bootstrap-cdn-js', plugin_dir_url(__file__) . 'js/bootstrap.min.js' );
  wp_enqueue_script( 'bootstrap-cdn-js', 'https://www.bootstrapcdn.com/fontawesome/' );
  wp_enqueue_style('googlefont', 'https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap' );

  wp_enqueue_script( 
      'admin_custom_script', 
      plugin_dir_url(__file__) . 'js/dashboard.js', array(),
      array('jquery'),
      '1.0' 
  );
  wp_enqueue_style(
    'admin_custom_style',
    plugin_dir_url(__file__) . 'css/dashboard.css',
    array(),
    1,
    'all'
);
}


/* Creating a database table for Nuropedia */

function Nuro_create(){

    global $wpdb;
    $table_name = $wpdb->prefix . "nuropedia_status";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      plugin_status text,
      token text,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    $wpdb->insert( 
      $table_name, 
      array( 
        'plugin_status' => "false", 
      ) 
    );
}

register_activation_hook(__FILE__,'Nuro_create');


/* Function to check if already table exists in database */

function Nuro_data_drop(){
     global $wpdb;
     $table_name = $wpdb->prefix . "nuropedia_status";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}
register_deactivation_hook(__file__,'Nuro_data_drop');


/* Ajax call for logging in for Nuropedia */

add_action('wp_ajax_contact_us','Nuro_ajax_login');

function Nuro_ajax_login(){
  $email = is_email($_POST['email']);
  $clean_email = filter_var($email,FILTER_SANITIZE_EMAIL); /* Sanitization of email */

  $password = $_POST["password"];    /* Validation and sanitization of password has been done through API */
  
  if ($email == $clean_email && filter_var($email,FILTER_VALIDATE_EMAIL)){  /* Validation of email */

  $endpoint = 'https://nuropedia.nuronics.com/api/login';

  $body = [
	  'email' => $email,
    'password' => $password,
  ];


  $options = [
	  'body'        => $body,
  ];

  $response = wp_remote_post( $endpoint, $options );

  $data = wp_remote_retrieve_body( $response );
  $token = json_decode($data)->{"token"};
  $status = json_decode($data)->{"status"};
  
  if($status == "success"){
    $action=0;

    global $wpdb;
    $table_name = $wpdb->prefix . "nuropedia_status";
    $wpdb->update( 
      $table_name, 
      [
        'plugin_status' => 'false',
        'token' => $token
      ],
      [
        'id' => 1
      ]
    );
  
  }
  else{
    $action=1;
  }

  wp_send_json_success($action);
}
}

// Function to retrieve content and titles
function Nuro_extract_content() {
  $args = array(
      'post_type' => array( 'post', 'page' ),
      'posts_per_page' => -1,
  );

  $posts = get_posts( $args );

  $data = '';

  foreach ( $posts as $post ) {
      $title = sanitize_text_field( $post->post_title );
      $content = wp_kses_post($post->post_content);

      // Remove HTML tags
      $content = wp_strip_all_tags( $content );

      // Remove &nbsp; entities
      $content = str_replace( '&nbsp;', ' ', $content );

      // Remove links
      $content = preg_replace( '/<a\b[^>]*>(.*?)<\/a>/i', '', $content );

      // Trim leading and trailing spaces
      $content = trim( $content );

      $data .= "\n". $title . "\n";
      $data .=  "\n". $content . "\n\n";
  }

  return $data;
}

/* Ajax call for activation of Nuropedia  */

add_action('wp_ajax_activate','Nuro_ajax_activate');
function Nuro_ajax_activate(){

    $url = 'https://nuropedia.nuronics.com/api/setwebsite';
    $content = Nuro_extract_content();
    #$content = "";
    $body = [
         'url' => get_home_url(),
         'content' => wp_kses_post($content),
    ];
    $token = Nuro_Token();
    $token = 'token'.' '.$token;
    $headers = array(
        'Authorization' => $token
        );
    
    $options = [
        'headers'      => $headers,
        'body'        => $body,
        'timeout'     => 360,
        
    ];
    
    $response = wp_remote_post( $url, $options );


   $body = wp_remote_retrieve_body( $response );
   $status= json_decode($body)->{"status"};
   $activated = 0;
   if($status == 'success'){

    global $wpdb;
    $activated=1;
    $table_name = $wpdb->prefix . "nuropedia_status";
    $wpdb->update( 
      $table_name, 
      [
        'plugin_status' => 'true',
      ],
      [
        'id' => 1
      ]
    );

   }
  

   wp_send_json_success($status);
}

/* Ajax call for deactivation of Nuropedia */

add_action('wp_ajax_deactivate','Nuro_ajax_deactivate');

function Nuro_ajax_deactivate(){
  global $wpdb;
  $activated=1;
  $table_name = $wpdb->prefix . "nuropedia_status";
  $wpdb->update( 
    $table_name, 
    [
      'plugin_status' => 'false',
    ],
    [
      'id' => 1
    ]
  );
  wp_send_json_success("success");
}


/* Ajax call for logging out from Nuropedia  */

add_action('wp_ajax_logout','Nuro_ajax_logout');

function Nuro_ajax_logout(){
  global $wpdb;
  $table_name = $wpdb->prefix . "nuropedia_status";
  $wpdb->update( 
    $table_name, 
    [
      'plugin_status' => 'false',
      'token' => NULL
    ],
    [
      'id' => 1
    ]
  );
  wp_send_json_success("success");
}


/*  Validation for hex color code of Nuropedia */

function Nuro_check_color($hex_color){

  if( preg_match('/^#[a-f0-9]{6}$/i', $hex_color) )
    return 1;
else
   return 0;
}

/* Gradient color changing of Nuropedia */

add_action('wp_ajax_change_color','Nuro_gradient_color');
function Nuro_gradient_color(){

  /* Sanitization of color1  */

  $color1 = sanitize_hex_color($_POST['color1']);  /* Validation for the color is done in check_color function */

  /* Sanitization of color2  */

  $color2 = sanitize_hex_color($_POST['color2']);  /* Validation for the color is done in check_color function */

  if(Nuro_check_color($color1)==1 and Nuro_check_color($color2)==1){
    $url = 'https://nuropedia.nuronics.com/api/set_color';

    $body = [
        'status' => 1,
        'left_color' => $color1,
        'right_color' => $color2
    ];
    $token = Nuro_Token();
    $token = 'token'.' '.$token;
    $headers = array(
        'Authorization' => $token
        );
    
    $options = [
        'headers'      => $headers,
        'body'        => $body,
        
    ];
    
    $response = wp_remote_post( $url, $options );
  
  
    $body = wp_remote_retrieve_body( $response );

    $ans = [
      'status' => 1
    ];
    wp_send_json_success($ans);
  
  }
  else {
    $ans = [
      'status' => 0
    ];
  wp_send_json_success($ans);

  }
}
