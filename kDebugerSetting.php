<?php
function kDebuger_add_menu_items(){
    add_menu_page(
        'kDebuger', 
        'kDebuger', 
        'activate_plugins',
        'kdebuger',
        'kDebuger_render_page'
    );
} 
add_action('admin_menu', 'kDebuger_add_menu_items');

function kDebuger_render_page(){
    
    echo 'kdebuger Menu!<hr>';  
                    
    return;
}

class kDebugerSettingsPage{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    
    public function __construct(){
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
    
    public function add_plugin_page(){         
        add_submenu_page( 
              'kdebuger'
            , 'Settings Admin' 
            , 'kDebuger Settings'
            , 'activate_plugins'
            , 'kdebuger-setting-admin'
            , array( $this, 'create_admin_page' )
        );        
    }
    
    public function create_admin_page(){
        // Set class property
        $this->options = get_option( 'kdebuger_option' );
        ?>
        <div class="wrap">
            <?php screen_icon('users'); ?>
            <h2>kDebuger Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'kdebuger_option_group' );   
                do_settings_sections( 'kdebuger-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }
    
    public function page_init(){        
        register_setting(
            'kdebuger_option_group', // Option group
            'kdebuger_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'kdebuger-setting-admin' // Page
        );

        add_settings_field(
            'trigger_api_name', // ID
            'Trigger name <br>Prefix (default: "_".WPLANG)', // Title 
            array( $this, 'trigger_api_name_callback' ), // Callback
            'kdebuger-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'active_trigger_api_name', 
            'Disable Trigger', 
            array( $this, 'trigger_api_callback' ), 
            'kdebuger-setting-admin', 
            'setting_section_id'
        );      
    }
    
    public function sanitize( $input ){
        $new_input = array();
        if( isset( $input['trigger_api_name'] ) )
            $new_input['trigger_api_name'] = sanitize_text_field( $input['trigger_api_name'] );

        if( isset( $input['trigger_api'] ) )
            $new_input['trigger_api'] = sanitize_text_field( $input['trigger_api'] );

        return $new_input;
    }

    public function print_section_info(){
        print 'Enter your settings below:';
    }

    public function trigger_api_name_callback(){
        printf(
            '<input type="text" id="trigger_api_name" name="kdebuger_option[trigger_api_name]" value="%s" />',
            isset( $this->options['trigger_api_name'] ) ? esc_attr( $this->options['trigger_api_name']) : ''
        );
    }
    
    public function trigger_api_callback(){
        printf(
            '<input type="checkbox" id="trigger_api" name="kdebuger_option[trigger_api]" value="1" %s />',
            isset( $this->options['trigger_api'] ) ? 'checked="checked"' : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new kDebugerSettingsPage();
?>
