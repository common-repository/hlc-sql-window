<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Hlc-software.eu
 * @since      1.0.0
 *
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/admin
 * @author     HLC-Software, Tom Trigkas <hlcsoftware.eu@gmail.com>
 */
class Hlc_Sql_Window_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hlc_Sql_Window_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hlc_Sql_Window_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hlc-sql-window-admin.css', array(), $this->version, 'all' );

	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hlc_Sql_Window_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hlc_Sql_Window_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hlc-sql-window-admin.js', array( 'jquery' ), $this->version, false );

	}

  public function hlc_adminInit(){
    add_settings_section('hlc_settings_section','HLC settings',array($this,'hlc_settings_section'),'general');
  }

  public function hlc_settings_section(){
    $surl = site_url();
    $content = '';
    echo 'Site name :'.$surl.'<br/>';
    do_action('hlc_genpro_settings', array('content' => $content));
    echo $content;
    if( strlen($content)==0){
      echo 'You are using standard version. Check Pro version <a href="http://hlc-software.eu/sql-window-pro/" target="_blank">here</a>.';
    }
  }

  /**************************************
  * Shortcodes Section
  **************************************/
  
  public function do_hlc_sqldata($atts, $content = null){
    $return_string='';
		ob_start();
		$this->hlc_data($content);
		$newRes = ob_get_contents();
		ob_clean();
		ob_end_flush();
    $return_string .= $newRes;
    return $return_string;
  }
  
  public function do_hlc_saved($atts, $content = null){
    $return_string='';
    ob_start();
    $this->hlc_getSaved($content);
    $newRes = ob_get_contents();
	  ob_clean();
	  ob_end_flush();
    $return_string .= $newRes;
    return $return_string;
  }

  public function do_hlc_text($atts, $content = null){
    $return_string='';
		ob_start();
    $this->hlc_getSQLText($content);
    $newRes = ob_get_contents();
	  ob_clean();
	  ob_end_flush();
    $return_string .= $newRes;
    return $return_string;
  }

/*******************************
* Helper functions
********************************/

	/**
  * Returns sql data results
  * @since 1.0.0
  */
  public function getsqldata($sql){
    if(isset($sql)){
      if(! isset($wpdb)){
        global $wpdb; 
      }
      $results = $wpdb->get_results( $wpdb->prepare( $sql,''), OBJECT );
      if(isset($results)){
        $myTable = new hlc_list();
        $myTable->set_data($results, $wpdb);
        echo '<div class="wrap">'; 
        $myTable->prepare_items(); 
        $myTable->display(); 
        echo '</div>';          
      } 
      return $results; 
    }
    return NULL;
  }

  public function hlc_getSaved($content){
    if(! isset($wpdb)){
      global $wpdb; 
    }
    $sql = 'select id, descr from hlc_Queries order by descr';
    $results = $wpdb->get_results( $wpdb->prepare( $sql,''), OBJECT );      
    if(isset($results)){      
      foreach($results as $res){
        echo '<option value="'.$res->id.'">'.$res->descr.'</option>';
      }
      //$newRes = ob_get_contents();
      unset($results);
    }
  }

  public function hlc_data($content){
    if(empty($content)){
      $hlcsql = $_POST["sql"];
    }
    else {
      $hlcsql = $content;
    }
    echo '<div class="wrap" style="overflow: scroll;">'; 
    if((! empty($hlcsql)) && current_user_can('manage_options')){
      $usr = wp_get_current_user();
      //Evaluate PHP Code
      if($this->startsWith($hlcsql,"/*php*/")){
	      echo("PHP: <br /><hr>" . $hlcsql . '<hr><br />'); 
	      if($_POST["run"]){
  	      $hlcsql = str_replace('\\"','"',$hlcsql);
  	      $hlcsql = str_replace("\\'",'\'',$hlcsql);
	        echo eval($hlcsql);
	      }
			} // Evaluate SQL command
			else {
	      $hlcsql = str_replace('\\"','"',$hlcsql);
	      $hlcsql = str_replace("\\'",'\'',$hlcsql);
	      echo("SQL <br /><hr>" . $hlcsql . '<hr><br />'); 
	      if(! isset($wpdb)){
	        global $wpdb; 
	      }
	      if($_POST["run"]){
          $wpdb->show_errors();
	        $results = $wpdb->get_results( $wpdb->prepare( $hlcsql,''), OBJECT );
	        if(! empty($results)){
	          $mycolumns = $wpdb->get_col_info('name',-1);
	          if($_POST["run"]){
	            $this->hlc_displayResults($results, $mycolumns);
	          }
	        }
	      }
	      else {
	        do_action('hlc_genpro_sql', $hlcsql);
	      }				
			}
    }   
    echo '</div>';
  }

  public function hlc_displayResults($results, $mycolumns){
    //$mycolumns = $wpdb->get_col_info('name',-1);
    if(isset($mycolumns)){
      echo '<table style="width: 100%;">';
      echo '<tr>';
      foreach($mycolumns as $col){
        echo '<th style="padding-left: 10px; padding-right: 10px;">' . $col . '</th>';
      }
      echo '</tr>';
      foreach($results as $res){
        echo '<tr>';
        foreach($res as $fld){
          echo '<td><pre>';//<![CDATA[
          echo $fld;
          echo '</pre></td>'; //]]>
        }
        echo '</tr>';
      }
      echo '</table>';            
    }
    else {
      echo '<br/>No Results.';
    }
  }

  public function hlc_getSQLText($content){
    $id = $_POST['savedsql'];
    if(isset($id)){
      if(! isset($wpdb)){
        global $wpdb; 
      }
      if($id != 0){
        $sql = 'Select myquery from hlc_Queries where id='.$id.' ';
        $results = $wpdb->get_results( $wpdb->prepare( $sql, ''), OBJECT );
        if(isset($results)){      
          foreach($results as $res){
            echo $res->myquery;
          }
          unset($results);
        }
        echo '';
      }
    }
  }
  
  public function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
	
  public function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
  //public function hlc_settings(){
  //  add_options_page('HLC settings','HLC Software','manage_options','hlc_settings','');
  //}
}
