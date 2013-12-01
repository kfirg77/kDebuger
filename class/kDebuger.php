<?php
class kDebuger{
    private static $container=NULL;

    public static function getWpStyles(){
        global $wp_styles;
        
        return $wp_styles;
    }
    
    public static function getWpScripts(){
        global $wp_scripts;
        return $wp_scripts;
    }    
    
    public static function getIncludedFiles(){
        $included_files = get_included_files();
        
        $stylesheet_dir = str_replace( '\\', '/', get_stylesheet_directory() );
        
        $template_dir   = str_replace( '\\', '/', get_template_directory() );  
        
        foreach ( $included_files as $key => $path ) {

            $path   = str_replace( '\\', '/', $path );

            if ( false === strpos( $path, $stylesheet_dir ) && false === strpos( $path, $template_dir ) )
                unset( $included_files[$key] );
        }        
        return $included_files;
    }
    
    private static function get_caller_info() {
        $c = '';
        $file = '';
        $func = '';
        $class = '';
        $line = '';
        $trace = debug_backtrace();
        
        //self::log($trace);
        
        if (isset($trace[2])) {
            $file = $trace[1]['file'];
            $line = $trace[1]['line'];
            $func = $trace[2]['function'];
            if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
                $func = '';
            }
            
        } else if (isset($trace[1])) {
            $file = $trace[1]['file'];
            $func = '';
            $line = $trace[1]['line'];
        }
        if (isset($trace[3]['class'])) {
            $class = $trace[3]['class'];
            $func = $trace[3]['function'];
            $file = $trace[2]['file'];
            $line = $trace[2]['line'];
        } else if (isset($trace[2]['class'])) {
            $class = $trace[2]['class'];
            $func = $trace[2]['function'];
            $file = $trace[1]['file'];
            $line = $trace[1]['line'];
        }
        if ($file != '') $file = basename($file);
        $c = $file . ": ";
        $c .= ($class != '') ? ":" . $class . "->" : "";
        $c .= ($func != '') ? $func . "(): " : "";
        $c .= ($line != '') ? " line: " . $line : "";
        return($c);
    }

    public static function log($data){
        $obj=array(
            'Caller Info'=>self::get_caller_info(), 
            'Message'=>$data
        );
                
        self::$container[]=$obj;
    }
    
    public static function getLog(){
        return self::$container;
    }
    
    public static function isLogExist(){
        return (isset(self::$container) || count(self::$container)>0 || self::$container != '');
    }
}