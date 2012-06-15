<?php
if( !class_exists( Wp_Aweber_Admin ) ){
  class Wp_Aweber_Admin{
    function __construct(){
      $plugin_prefix_root = plugin_dir_path( __FILE__ );
      $plugin_prefix_filename = "{$plugin_prefix_root}wp_aweber.php";
      register_activation_hook( $plugin_prefix_filename, array( $this, 'create_table' ) );
      add_action( 'admin_menu', array( $this, 'aweber_add_page' ) );
      add_action( 'admin_init', array( $this, 'aweber_admin_init' ) );
    }
        
    public function aweber_add_page(){
      if($_GET['aweber_auth'] == 'true'){
        $auth = new Wp_Aweber();
        $auth->authorize();
      }
      add_options_page(
        'Wp Aweber Settings', 
        'Wp Aweber', 
        'manage_options', 
        'wp-aweber', 
        array( $this, 'wp_aweber_settings' )
      );
    }
    
    public function create_table(){
      global $wpdb;
      $table_name = $wpdb->prefix . "aweber_subscribers";
      $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name VARCHAR(50),
        last_name VARCHAR(50),
        phone VARCHAR(15),
        email VARCHAR(50) NOT NULL UNIQUE,
        province TEXT(15),
        amount INT(7),
        UNIQUE KEY id (id)
      );";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }

    public function wp_aweber_settings(){
      ?>
      <div>
      <h2>Wp Aweber Settings</h2>
      <form action="options.php" method="post">
      <?php settings_fields('wp_aweber_options'); ?>
      <?php do_settings_sections('wp_aweber'); ?>
      <br />
      <a href="?page=wp-aweber&aweber_auth=true">Connect Account</a>
      <br /><br />
      <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
      
      </form>
      </div>

      <?php
    }
    
    public function aweber_admin_init(){
      register_setting( 
        'wp_aweber_options', 
        'wp_aweber_options'
      );
      
      add_settings_section(
        'wp_aweber_main', 
        'Authentication Settings', 
        array( $this, "auth_section_text" ), 
        'wp_aweber'
      );
      
      add_settings_field(
        'wp_aweber_auth_key', 
        'Consumer Key', 
        array( $this, 'wp_aweber_auth_key' ), 
        'wp_aweber', 
        'wp_aweber_main'
      );
      
      add_settings_field(
        'wp_aweber_auth_secret', 
        'Consumer Secret', 
        array( $this, 'wp_aweber_auth_secret' ), 
        'wp_aweber', 
        'wp_aweber_main'
      );
      
      add_settings_field(
        'wp_aweber_auth_conn', 
        'Account Status', 
        array( $this, 'wp_aweber_auth_conn' ), 
        'wp_aweber', 
        'wp_aweber_main'
      );
    }
    
    public function auth_section_text(){
      echo "";
    }
    
    public function wp_aweber_auth_key(){
      $options = get_option( 'wp_aweber_options' );
      echo '<input type="text" size="40" name="wp_aweber_options[consumer_key]" value="' . $options['consumer_key'] . '" />';
    }
    
    public function wp_aweber_auth_secret(){
      $options = get_option( 'wp_aweber_options' );
       echo '<input type="text" size="40" name="wp_aweber_options[consumer_secret]" value="' . $options['consumer_secret'] . '" />';
    }
    
    public function wp_aweber_auth_conn(){
      $options = get_option( 'wp_aweber_options' );
      if(!empty($options['access_key']) && !empty($options['access_secret'])){
       echo "<p style='color:green;'>Connected</p>";
      }else{
        echo "<p style='color:red;'>Not Connected</p>";
      }
    }
  } 
}
?>
