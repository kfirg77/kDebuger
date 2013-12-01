<?php
/* 
Plugin Name: kDebug
Plugin URI: https://www.kfirgershon.com/
Description: Show information about the current page
Author: Kfir Gershon
Version: 1.0.0
Domain Path: /languages/
Text Domain: kDebuger 
*/

/**
 * Usage: 
 * 
 *   You can use this plugin to manually log data. 
 *      
 *   $tmp=array('Arr A'=>1);
 *   kDebuger::log($tmp);
 *   
 *   $tmp=array('Arr B'=>2);
 *   kDebuger::log($tmp);    
 *   
 *   kDebuger::log('A');
 *   kDebuger::log('B');
 *   kDebuger::log('C');
 * 
 *   kDebuger plugin print array in slider ander
 *   title call Filter
 */

if(is_admin())
    return;

if ( !defined('ABSPATH') )
	die('-1');

define('KRUMO_TRUNCATE_LENGTH', 20);

error_reporting(E_ALL^E_NOTICE);
ini_set('display_errors', 0);

define( 'kDebugerVersion' , fileatime(__FILE__)); 

if(strpos($_SERVER["HTTP_HOST"], 'staging') === false ){
    class kDebuger{
        public static function log($data){}
    }
    return;
}else{
    require_once plugin_dir_path(__FILE__).'/class/kDebuger.php'; 
}

function kDebuger_get_header(){
    if(isset($_GET["security"])){
        $nonce = $_REQUEST['security'];

        if ( ! wp_verify_nonce( $nonce, 'kDebugerSecurityForAjax' ) ) 
                die(-1);
    }

    
    wp_enqueue_script('jquery');
        
    wp_register_script('jquery-cookie', plugins_url('/js/jquery.cookie.js', __FILE__),array('jquery'), WP_TRIM_VERSION, false);
    wp_enqueue_script('jquery-cookie'); 
    
    wp_register_style('perfect-scrollbar-css', plugins_url('/plugin/perfect-scrollbar/perfect-scrollbar-0.4.5.min.css', __FILE__),array(),kDebugerVersion);
    wp_enqueue_style('perfect-scrollbar-css');
    
    wp_register_script('perfect-scrollbar-js', plugins_url('/plugin/perfect-scrollbar/perfect-scrollbar-0.4.5.with-mousewheel.min.js', __FILE__),array('jquery'), WP_TRIM_VERSION, false);
    wp_enqueue_script('perfect-scrollbar-js'); 
    
    wp_register_script('krumo-js', plugins_url('/plugin/krumo/krumo.js', __FILE__));
    wp_enqueue_script('krumo-js'); 
    
    wp_register_style('kDebuger-css', plugins_url('/css/kDebuger.css', __FILE__),array(),kDebugerVersion);
    wp_enqueue_style('kDebuger-css');
    
    wp_register_script('kDebugerJs',  plugins_url('/js/kDebuger.js', __FILE__));       
    /*wp_localize_script( 'kDebugerJs', 'kDebugerObj', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'loadingmessage' => __('<img src="'.plugins_url('/images/ajax-loader.gif', __FILE__).'" />', 'kDebuger')
    ));*/
    wp_enqueue_script('kDebugerJs');    
}
add_action('get_header', 'kDebuger_get_header');
add_action('login_head', 'kDebuger_get_header');
 
 
add_filter('template_include','kDebuger_include',1);
function kDebuger_include($template) {
	ob_start();
	return $template;
}


add_filter('shutdown','kDebuger_shutdown',0);
function kDebuger_shutdown() {
    global $kDebuger,$kDebugerMsg,$wp_query,$post,$bp,$wp_styles;
    
    ob_start(); 
                            
    require_once plugin_dir_path(__FILE__).'/plugin/krumo/class.krumo.php'; 
    
    /////////////DATA//////////////////////////////////
        
    $nonce = wp_create_nonce("kDebugerSecurityForAjax");

    $dataVar=array(
        'pageName'=>(empty( $post->post_parent ) ? get_the_title( $post->ID ) : get_the_title( $post->post_parent )),
        'editLink'=>get_edit_post_link()
    );

    extract($dataVar);

    $data=array();                                

    $data[]=array('title'=>'Filter',              'data'=>  kDebuger::getLog() );

    $dataArr['Type']=  get_post_type();
    $dataArr['ID']=  get_the_ID();
    $dataArr['Stylesheet DIR']=  get_stylesheet_directory();
    $dataArr['Theme DIR']=get_template_directory();
    $dataArr['Theme URL']=get_template_directory_uri();

    $dir=wp_upload_dir(); 

    $dataArr['Upload URL']=$dir["url"];
    $dataArr['Upload BASE']=$dir["baseurl"];

    $data[]=array('title'=>'Data',                'data'=>$dataArr);
    $data[]=array('title'=>'Body Class',          'data'=>get_body_class());
    $data[]=array('title'=>'Wp Styles',           'data'=>kDebuger::getWpStyles());
    $data[]=array('title'=>'Wp Scripts',          'data'=>kDebuger::getWpScripts());
    $data[]=array('title'=>'WP Included Files',   'data'=>kDebuger::getIncludedFiles() );
    $data[]=array('title'=>'WP Post',             'data'=>$post);
    $data[]=array('title'=>'WP Post Meta',        'data'=>get_post_meta(get_the_ID()));
    $data[]=array('title'=>'WP Upload OBJ',       'data'=>wp_upload_dir());
    $data[]=array('title'=>'WP Query',            'data'=>$wp_query);
    $data[]=array('title'=>'Buddypress',          'data'=>$bp);

    $data[]=array('title'=>'Server',              'data'=>$_SERVER);    
    $data[]=array('title'=>'Environment',         'data'=>$_ENV);    
    $data[]=array('title'=>'Session',             'data'=>$_SESSION); 
    $data[]=array('title'=>'Post Method',         'data'=>$_POST);
    $data[]=array('title'=>'Get Method',          'data'=>$_GET); 
    $data[]=array('title'=>'Request',             'data'=>$_REQUEST);
    $data[]=array('title'=>'Cookie',              'data'=>$_COOKIE);
    
    
    $memory = number_format_i18n( ceil( memory_get_usage(true) / 1048576 ) ); // Mb

    /////////////DATA//////////////////////////////////
    
    require_once plugin_dir_path(__FILE__).'/tpl/default.tpl.php';  
	
    $insert = ob_get_contents();	
	
    ob_end_clean();    
	
    $content = ob_get_clean();
	
    $content = preg_replace('#<body([^>]*)>#i',"<body$1>{$insert}",$content);
	
    echo $content;
}
?>
