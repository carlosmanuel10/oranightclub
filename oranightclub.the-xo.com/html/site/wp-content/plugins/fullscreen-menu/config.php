<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux_Framework_sample_config' ) ) {

        class Redux_Framework_sample_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                // If Redux is running as a plugin, this will remove the demo notice and links
                //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

                // Function to test the compiler hook and demo CSS output.
                // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
                //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);

                // Change the arguments after they've been declared, but before the panel is created
                //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

                // Change the default value of a field after it's been set, but before it's been useds
                //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

                // Dynamically add a section. Can be also used to modify sections/fields
                //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
                //print_r($options); //Option values
                //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

                /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'fullscreen-menu-plugin' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'fullscreen-menu-plugin' ),
                    'icon'   => 'el-icon-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array(
                        ReduxFrameworkPlugin::instance(),
                        'plugin_metalinks'
                    ), null, 2 );

                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'fullscreen-menu-plugin' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'fullscreen-menu-plugin' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'fullscreen-menu-plugin' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'fullscreen-menu-plugin' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'fullscreen-menu-plugin' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'fullscreen-menu-plugin' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'fullscreen-menu-plugin' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'fullscreen-menu-plugin' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    Redux_Functions::initWpFilesystem();

                    global $wp_filesystem;

                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                // ACTUAL DECLARATION OF SECTIONS
                $this->sections[] = array(
                    'title'  => __( 'General settings', 'fullscreen-Menu-Plugin' ),
                    'icon'   => 'el-icon-home',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(
                        array(
                            'id'       => 'smp-enable',
                            'type'     => 'checkbox',
                            'title'    => __( 'Enable Menu', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'check if you want to enable the menu', 'fullscreen-menu-plugin' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'smp-animation',
                            'type'     => 'select',
                            'title'    => __( 'Select animation', 'fullscreen-menu-plugin' ),
                            'options'  => array(
                                '1' => 'Huge Inc',
                                '2' => 'Corner',
                                '3' => 'Slide Down',
                                '4' => 'Scale',
                                '5' => 'Door',
                                '7' => 'Content Scale'
                            ),
                            'default'  => '1'
                        ),
                    )
 
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-adjust-alt',
                    'title'  => __( 'Modal Style', 'fullscreen-Menu-Plugin' ),
                    'fields' => array(
                        array(
                            'id'          => 'smp-typography',
                            'type'        => 'typography',
                            'title'       => __( 'Typography', 'fullscreen-menu-plugin' ),
                            //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                            'google'      => true,
                            // Disable google fonts. Won't work if you haven't defined your google api key
                            'font-backup' => true,
                            // Select a backup non-google font in addition to a google font
                            //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                            //'subsets'       => false, // Only appears if google is true and subsets not set to false
                            //'font-size'     => false,
                            //'line-height'   => false,
                            //'word-spacing'  => true,  // Defaults to false
                            //'letter-spacing'=> true,  // Defaults to false
                            //'color'         => false,
                            //'preview'       => false, // Disable the previewer
                            'all_styles'  => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'      => array( 'h2.site-description, .entry-title' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'    => array( 'h2.site-description-compiler' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'       => 'px',
                            // Defaults to px
                            'subtitle'    => __( 'Typography option with each property can be called individually.', 'fullscreen-menu-plugin' ),
                            'output' => array('#smp_menu nav ul li a')
                        ),
                        array(
                            'id'       => 'smp-background-color',
                            'type'     => 'color_rgba',
                            'title'    => __( 'Background Color', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'Gives you the RGBA color.', 'fullscreen-menu-plugin' ),
                            'default'  => array( 
                                'color' => '#000', 
                                'alpha' => '.8' 
                            ),
                            'output' => array('background-color' => '#smp_menu'),
                            'mode'     => 'background',
                            'validate' => 'colorrgba',
                        ),
                        array(
                            'id'       => 'smp-background-image',
                            'type'     => 'background',
                            'output'   => array( '#smp_menu' ),
                            'title'    => __( 'Background Image', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'background with image, color, etc.', 'fullscreen-menu-plugin' ),
                            'background-color' => false
                        ),
                    )
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-lines',
                    'title'  => __( 'Open Button', 'fullscreen-Menu-Plugin' ),
                    'fields' => array(
                        array(
                            'id'       => 'smp-open-location',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => __( 'Open Button Location', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'Select Open Button location.', 'fullscreen-menu-plugin' ),
                            'options'  => array(
                                'left' => array(
                                    'alt' => 'Left',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                                ),
                                'right' => array(
                                    'alt' => 'Right',
                                    'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                                ),
                            ),
                            'default'  => 'right',
                        ),
                        array(
                            'id'       => 'smp-open-type',
                            'type'     => 'button_set',
                            'title'    => __( 'Button Type', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( '', 'fullscreen-menu-plugin' ),
                            'desc'     => __( '', 'fullscreen-menu-plugin' ),
                            //Must provide key => value pairs for radio options
                            'options'  => array(
                                '1' => 'default icon',
                                '2' => 'image',
                                '3' => 'text'
                            ),
                            'default'  => '1'
                        ),
                        array(
                            'id'                => 'smp-open-text',
                            'type'              => 'text',
                            'title'             => __( 'Open Button Text', 'fullscreen-menu-plugin' ),
                            'subtitle'          => __( 'You decide.', 'fullscreen-menu-plugin' ),
                            'desc'              => __( 'This is the description field, again good for additional info.', 'fullscreen-menu-plugin' ),
                            'required' => array( 'smp-open-type', "=", '3' ),
                        ),
                        array(
                            'id'       => 'smp-open-image',
                            'type'     => 'media',
                            'title'    => __( 'Media w/o URL', 'fullscreen-menu-plugin' ),
                            'desc'     => __( ' ', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'Upload any image to show in the menu open button', 'fullscreen-menu-plugin' ),
                            'required' => array( 'smp-open-type', "=", '2' ),
                        ),
                        array(
                            'id'       => 'smp-open-color',
                            'type'     => 'color_rgba',
                            'title'    => __( 'Color', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'Gives you the RGBA color.', 'fullscreen-menu-plugin' ),
                            'default'  => array( 
                                'color' => '#000', 
                                'alpha' => '1' 
                            ),
                            'mode'     => 'background',
                            'validate' => 'colorrgba',
                            'output' => array('color'=>'.menu_text','background-color'=>'.menu_text , .menu-icon > span , .menu-icon > span:before, .menu-icon > span:after')
                        ),
                       array(
                            'id'       => 'smp-open-background',
                            'type'     => 'color_rgba',
                            'title'    => __( 'Background', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'Gives you the RGBA color.', 'fullscreen-menu-plugin' ),
                            'default'  => array( 
                                'color' => '#fff', 
                                'alpha' => '.0' 
                            ),
                            'output' => array('background-color' => '#trigger-overlay'),
                            'mode'     => 'background',
                            'validate' => 'colorrgba',
                            'output' => array('background-color'=>'.menu-icon,.menu_text,.menu_img')
                        ),
                    )
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-cogs',
                    'title'  => __( 'Custom css', 'fullscreen-Menu-Plugin' ),
                    'fields' => array(
                        array(
                            'id'       => 'smp-css',
                            'type'     => 'ace_editor',
                            'title'    => __( 'Custom CSS', 'fullscreen-menu-plugin' ),
                            'subtitle' => __( 'Paste your CSS code here.', 'fullscreen-menu-plugin' ),
                            'mode'     => 'css',
                            'theme'    => 'monokai',
                            'desc'     => '',
                            'default'  => ""
                        ),
                    )
                );

 
            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'fullscreen-menu-plugin' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'fullscreen-menu-plugin' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'fullscreen-menu-plugin' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'fullscreen-menu-plugin' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'fullscreen-menu-plugin' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'snp',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => 'Fullscreen Menu Plugin',
                    // Name that appears at the top of your panel
                    'display_version'      => '1.0',
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'fullscreen Menu Plugin', 'fullscreen-menu-plugin' ),
                    'page_title'           => __( 'fullscreen Menu Plugin', 'fullscreen-menu-plugin' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => false,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => '_options',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => false,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );


                

                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $this->args['share_icons'][] = array(
                    'url'   => 'http://najih.net',
                    'title' => 'Visit my website',
                    'icon'  => 'el-icon-website'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://twitter.com/dakchi',
                    'title' => 'Follow me on Twitter',
                    'icon'  => 'el-icon-twitter'
                );

                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    $this->args['intro_text'] = sprintf( __( '<p>fullscreen Menu Plugin Options</p>', 'fullscreen-menu-plugin' ), $v );
                } else {
                    $this->args['intro_text'] = __( '', 'fullscreen-menu-plugin' );
                }

                // Add content after the form.
                $this->args['footer_text'] = __( '', 'fullscreen-menu-plugin' );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_sample_config();
    } else {
        echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
