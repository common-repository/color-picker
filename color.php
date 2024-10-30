<?php
/*
Plugin Name: Color Picker 
Description: A plugin to create dynamic color selection.
Version: 0.1
Author: ifourtechnolab
Author URI: http://www.ifourtechnolab.com/
*/
 
class Colorpicker {
  
    
    private static $instance = null;
    
    public $options;
  
    public static function get_instance() {
  
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
  
        return self::$instance;
  
    } 
    private function __construct() { 
		
	$this->name = 'colorpicker';
    $this->section = 'findSection';
    $this->option = 'findOptions';	
 
    add_action( 'admin_menu', array( &$this, 'add_page' ) );
     
    add_action( 'admin_init', array( &$this, 'register_page_options') );
    add_action('wp_footer',array(&$this, 'custom_content_after_body_open_tag'));
    wp_enqueue_style( 'wp-color-picker' );
     
    add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );
    
    add_action( 'wp_enqueue_scripts', array($this,'slider_front_script' ));
    
    //add_action( 'admin_notices', array( $this, 'plugin_activation' ) ) ;
             
    //$this->options = get_option( 'cpa_settings_options' );
}
  
    public function add_page() {
		
		add_options_page( 'Theme Options', 'Color Options', 'manage_options', __FILE__, array( $this, 'display_page' ) );
 }
      
    public function display_page() { 
    ?>
    <div class="wrap">
     
        <form method="post" action="options.php">     
        <?php
                settings_fields($this->section);
                do_settings_sections($this->option);
                submit_button();
         ?>
        </form>
    </div> 
    <?php    
}
       
    public function register_page_options() { 
    add_settings_section($this->section, $this->name . " Settings", null, $this->option);
    
    add_settings_field("color_id", "Colorpicker", array($this, "bg_settings_field"), $this->option, $this->section);
    

    register_setting($this->section, "color_id");
}

 
	public function bg_settings_field() { 
     
    $getOption = get_option('color_id');
   
    echo '<input type="text" name="color_id[background]" value="' . ((isset($getOption['background'])) ? $getOption['background']: '') . '" class="cpa-color-picker" >';
     
}
    public function display_section() { } 
    
    public function validate_options( $fields ) { 
     
    $valid_fields = array();
     
    
    $title = trim( $fields['title'] );
    $valid_fields['title'] = strip_tags( stripslashes( $title ) );
     
    
    $background = trim( $fields['background'] );
    $background = strip_tags( stripslashes( $background ) );
     
    
    if( FALSE === $this->check_color( $background ) ) {
     
        
        add_settings_error( 'cpa_settings_options', 'cpa_bg_error', 'Insert a valid color for Background', 'error' ); 
         
       $getOption = get_option('color_id');
       
        $valid_fields['background'] = $getOption['background'];
     
    } else {
     
        $valid_fields['background'] = $background;  
     
    }
     
    return apply_filters( 'validate_options', $valid_fields, $fields);
}
 

public function check_color( $value ) { 
      
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { 
		 return true;
    }
     
    return false;
}
  public function slider_front_script() {
     wp_enqueue_script('my_amazing_script', plugins_url('slider.front.js', __FILE__), array('jquery'),'1.1', true);
     
     
     $getOption =  get_option('color_id');
     $scriptData = array('width' => $getOption['background'],);
     $orginaldata = maybe_unserialize( $scriptData );
     /*echo "<pre>";
     print_r($orginaldata);
     echo "</pre>";*/

    wp_localize_script('my_amazing_script', 'my_options', $scriptData);


}   
 public function enqueue_admin_js() { 
     
     wp_enqueue_script( 'cpa_custom_js', plugins_url( 'slider.custom.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  );
}  
/*public function plugin_activation() {
			
			
			
			$html = '<div class="notice notice-success is-dismissible">';
				$html .= '<p>';
					$html .= __( 'Color Picker <a href="http://www.ifourtechnolab.com/" target="">Develop by iFour Technolab</a>.');
				$html .= '</p>';
			$html .= '</div><!-- /.updated -->';

			echo $html;

	}*/ // end plugin_activation
	
public function custom_content_after_body_open_tag() {
?>
		 <a href="http://www.ifourtechnolab.com/">iFour Technolab Pvt.Ltd</a>
<?php
}

}

Colorpicker::get_instance();







