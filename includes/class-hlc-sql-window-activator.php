<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/includes
 * @author     HLC-Software, Tom Trigkas <hlcsoftware.eu@gmail.com>
 */
class Hlc_Sql_Window_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    // load SQL Page template
    $sqlpage = '<div class="wrap">
    [hlc_data]
    </div>';
    $post = array(
      'post_content'   =>  $sqlpage , // The full text of the post.
      'post_name'      => 'hlc-sql-data' , // The name (slug) for your post
      'post_title'     =>  'HLC SQL data Window' ,  // The title of your post.
      'post_status'    => 'private', //|'publish' |  'draft' |  'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
      'post_type'      => 'page',  //|'post' |  'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
      'comment_status' => 'closed'// | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
    );
    $postid0 = wp_insert_post($post);
    unset($post);
    $post = get_post($postid0, 'OBJECT');

    $sqlpage = 
    ' <form id="f1" method="POST">
      <strong>Saved Queries, </strong><select name="savedsql" style="width: 50%">
      <option value="0" selected>Please Select</option>
      [hlc_saved]
      </select>&nbsp;&nbsp;<input type="submit" name="btload" value="to Load"/>
      </form> 
      <form id="frmsql" action="/'.$post->post_name.'" method="POST" target="_blank" >
      Enter an SQL command or a php command. Start with <strong>/*php*/</strong> for php commands.<br/>
      <textarea style="width: 100%;height: 250px;color: green;background: lightgray;font-weight: bolder;font-family: monospace;" name="sql">[hlc_text]</textarea>
      <table>
        <tr>
          <td style="text-align:center"><input type="submit" name="run" value="Run" style="width: 100%"/></td>
        </tr>
      </table>
      </form>
      <div id="hlc_result"></div>
    </div>';
    // Report : <input type="submit" name="report" value="to Page as Report" style="width: 100%"/>
    unset($post);
    //$content = htmlentities( html_entity_decode($sqlpage) );
    $post = array(
      'post_content'   => $sqlpage , // The full text of the post.
      'post_name'      => 'HLC-SQL-Window' , // The name (slug) for your post
      'post_title'     => 'HLC SQL Window' , // The title of your post.
      'post_status'    => 'private' ,//| 'draft' |  'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
      'post_type'      => 'page',  //|'post' |  'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
      'comment_status' => 'closed'// | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
    );
    $postid = wp_insert_post($post);
    // Save post ID.
    delete_option('hlcsqlwindow');
    delete_option('hlcsqldatawindow');
    add_option('hlcsqlwindow',$postid,'','no');
    add_option('hlcsqldatawindow',$postid0,'','no');
    // Create Saved Queries table 
    if(! isset($pwdb)){
      global $wpdb; 
    }
    //$wpdb->query( 'drop table hlc_Queries');      
    $wpdb->query( 'create table hlc_Queries ( id MEDIUMINT NOT NULL AUTO_INCREMENT, myquery varchar(2048), descr varchar(2048), postid int, PRIMARY KEY (id) );');
    // Create the export directory
    if (! file_exists(ABSPATH . 'hlc_export')) {
      mkdir(ABSPATH . 'hlc_export', 0775, true);
    }

	}

}
