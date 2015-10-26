<?php
   /*
   Plugin Name: Fullscreen Menu
   Plugin URI: http://najih.net/fullscreen-menu-wordpress-plugin
   Description: a free plugin to create custom fullscreen menu quickly in your wordpress website (user-friendly, highly customizable, responsive) 
   Version: 1.0
   Author: hamza najih
   Author URI: http://najih.net
   */
?>
<?php
	
	require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

	function my_plugin_register_required_plugins() {

		$plugins = array(
			array(
				'name'     => 'Redux Framework', 
				'slug'     => 'redux-framework', 
				'source'   => 'https://downloads.wordpress.org/plugin/redux-framework.3.4.1.zip', 
				'required' => true,
				'force_activation' => true,
				'force_deactivation' => true
			),

		);

		$theme_text_domain = 'tgmpa';

		$config = array(
			'dismissable'  => false, 
			'is_automatic' => true,
			'strings'      	 => array(
				'notice_can_install_required' => _n_noop( 'This plugin requires the following plugin: %1$s.', 'This plugin requires the following plugins: %1$s.' ),
			),
		);

		tgmpa( $plugins, $config );

	}

	if ( !class_exists( 'ReduxFramework' ) && file_exists( ABSPATH . 'wp-content/plugins/redux-framework/ReduxCore/framework.php' ) ) {
   		 require_once( ABSPATH . 'wp-content/plugins/redux-framework/ReduxCore/framework.php' );
	}else{
		add_action( 'tgmpa_register', 'my_plugin_register_required_plugins' );
	}
	if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/config.php' ) ) {
	   	 require_once( dirname( __FILE__ ) . '/config.php' );
	}

	

	register_nav_menus( array(
		'Fullscreen-Menu-Plugin' => 'Fullscreen Menu Plugin'
	) );

	function head_scripts(){
		global $snp;
		if($snp['smp-enable']==1){
			wp_enqueue_style('smp_style',plugins_url( 'css/style.css', __FILE__ ));
			wp_enqueue_style('smp_style'.$snp['smp-animation'],plugins_url( 'css/style'.$snp['smp-animation'].'.css', __FILE__ ));
			wp_enqueue_script('smp_modernizr',plugins_url( 'js/modernizr.custom.js', __FILE__ ));

			echo '<style>'.$snp['smp-css'].'</style>';
			
			/*if(!wp_script_is('jquery')){
				wp_enqueue_script('jquery');		
			}*/
		}
	}


	add_action('wp_head','head_scripts',0);
	
	function footer_scripts(){

		global $snp;
		if($snp['smp-enable']==1){

			$animation_num='1';

			if($snp['smp-animation']>5 && $snp['smp-animation']<8){
				$animation_num=6;
			}

			if($snp['smp-animation']>=8 && $snp['smp-animation']<10 || $snp['smp-animation']==11 ){
				$animation_num=$snp['smp-animation'];
			}

			wp_enqueue_script('smp_script',plugins_url( 'js/script.js', __FILE__ ));
			wp_enqueue_script('smp_classie',plugins_url( 'js/classie.js', __FILE__ ));
			wp_enqueue_script('smp_demo'.$animation_num,plugins_url( 'js/script'.$animation_num.'.js', __FILE__ ));

			$open='';
			
			if($snp['smp-open-type']==true){
				$open='icon';
			}else{
				$open='text';
			}

			/*
			echo '
				<section>
					<button style="'.$snp['smp-open-location'].':40px" id="trigger-overlay" type="button">'.$open.'</button></p>
				</section>';
			*/
			if($snp['smp-open-type']=='2'){
				echo '<div style="'.$snp['smp-open-location'].':40px" id="trigger-overlay" class="menu_img"><img src="'.$snp['smp-open-image']['url'].'" /></div>';
			}elseif($snp['smp-open-type']=='3'){
				echo '<div style="'.$snp['smp-open-location'].':40px" id="trigger-overlay" class="menu_text">'.$snp['smp-open-text'].'</div>';
			}else{
				echo '<div style="'.$snp['smp-open-location'].':40px" id="trigger-overlay" class="menu-icon"><span></span></div>';
			}
						
			echo '<div id="smp_menu" class="overlay overlay-'.$snp['smp-animation'].'" >';

			echo '
				<nav>
					';
			
					
			wp_nav_menu( array('theme_location' => 'Fullscreen-Menu-Plugin','container' => false ));
			
			echo	'
				</nav>
			</div>';

		}
	}

	
	add_action('wp_footer','footer_scripts');
	
	

?>