<?php
	/**
	 * Plugin Name: CB Dashboard
	 * Plugin URI: http://tronnet.me
	 * Version: 1.0.2
	 * Author: Tronnet DevOps
	 * Author URI: http://tronnet.me
	 */
	
	
	class CBDashboard {
		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;
		/**
		 * The array of templates that this plugin tracks.
		 */
		protected $templates;
		/**
		 * Returns an instance of this class. 
		 */
		public static function get_instance() {
			if( null == self::$instance ) {
				self::$instance = new CBDashboard();
			} 
			return self::$instance;
		} 
		
		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct() {
			$this->templates = array();
			// Add a filter to the attributes metabox to inject template into the cache.
			add_filter(
						'page_attributes_dropdown_pages_args',
						 array( $this, 'register_project_templates' ) 
					);
			// Add a filter to the save post to inject out template into the page cache
			add_filter(
						'wp_insert_post_data', 
						array( $this, 'register_project_templates' ) 
					);
			// Add a filter to the template include to determine if the page has our 
					// template assigned and return it's path
			add_filter(
						'template_include', 
						array( $this, 'view_project_template') 
					);
			// Add your templates to this array.
			$this->templates = array(
				'main-template.php'     => __( 'CB Dashboard' )
			);
			

		}
		
		/**
		 * 
		 */
		function load_scripts() {
			if (is_singular('cb_dashboard')){
				wp_enqueue_style( 'foundation', 'http://cdnjs.cloudflare.com/ajax/libs/foundation/6.0.1/css/foundation.min.css' );
				wp_enqueue_style( 'foundation-icons', 'http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css' );
				wp_enqueue_style( 'font-awesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );
				wp_enqueue_style( 'foundation-datepicker', 'http://cdn.jsdelivr.net/foundation.datepicker/0.1/stylesheets/foundation-datepicker.css' );
				wp_enqueue_style( 'cbdashboard-main-css', plugins_url( '/styles/main.css' , __FILE__ ) );
			
				wp_enqueue_script( 'foundation', 'http://cdnjs.cloudflare.com/ajax/libs/foundation/6.0.1/js/foundation.min.js', array('jquery'), '6.0.1', true );
				wp_enqueue_script( 'dataTables', 'http://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js', array('jquery'), '1.10.7', true );
				wp_enqueue_script( 'foundation-dataTables', 'http://cdn.datatables.net/plug-ins/1.10.7/integration/foundation/dataTables.foundation.js', array('foundation', 'dataTables'), '1.10.7', true );
				wp_enqueue_script( 'foundation-datepicker', 'http://cdn.jsdelivr.net/foundation.datepicker/0.1/js/foundation-datepicker.js', array('foundation'), '0.1', true );
				wp_enqueue_script( 'cbdashboard-main-js', plugins_url( '/js/main.js' , __FILE__ ) , array('jquery', 'foundation'), '1.0.0', true );
			}
		}
		
		public function save_data($key, $data){
			if ( get_option( $key ) !== false ) {
			    update_option( $key, $data );
			} else {
			    add_option( $key, $data );
			}
			
			return get_option( $key );
		}
		
		public function get_data($key){
			return get_option( $key );
		}
		
		public function create_posttype(){
		    register_post_type( 'cb_dashboard',
				array(
					'labels' => array(
						'name' => __( 'Dashboards' ),
						'singular_name' => __( 'Dashboard' )
					),
					'public' => true,
					// 'has_archive' => true,
					'rewrite' => array('slug' => 'dashboards'),
				)
		    );
		}
		
		public function myplugin_activate() {
			
			$post = array(
				'post_type'		=> 'cb_dashboard',
				'post_content'	=> '',
				'post_title'	=> __( 'Primary Dashboard' ),
				'post_name'		=> 'primary-dashboard',
				'post_status'	=> 'publish'
			);
			
			$new_post_id = wp_insert_post( $post ); // creates page
			
			update_post_meta( $new_post_id, '_wp_page_template', 'main-template.php' );
		}
		
		public function myplugin_deactivate() {
			$Posts = get_posts(array(
				'post_type' => 'cb_dashboard'
			));
			
			foreach($Posts as $Post){
				wp_delete_post($Post->ID);
			}
		}
		
		/**
		 * Adds our template to the pages cache in order to trick WordPress
		 * into thinking the template file exists where it doens't really exist.
		 *
		 */
		public function register_project_templates( $atts ) {
			// Create the key used for the themes cache
			$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
			// Retrieve the cache list. 
					// If it doesn't exist, or it's empty prepare an array
					$templates = wp_get_theme()->get_page_templates();
			if ( empty( $templates ) ) {
				$templates = array();
			} 
			// New cache, therefore remove the old one
			wp_cache_delete( $cache_key , 'themes');
			// Now add our template to the list of templates by merging our templates
			// with the existing templates array from the cache.
			$templates = array_merge( $templates, $this->templates );
			// Add the modified cache to allow WordPress to pick it up for listing
			// available templates
			wp_cache_add( $cache_key, $templates, 'themes', 1800 );
			return $atts;
		} 
		
		/**
		 * Checks if the template is assigned to the page
		 */
		public function view_project_template( $template ) {
			global $post;
			if (!isset($this->templates[get_post_meta( 
						$post->ID, '_wp_page_template', true 
					)] ) ) {
					
				return $template;
						
			} 
			$file = plugin_dir_path(__FILE__). get_post_meta( 
				$post->ID, '_wp_page_template', true 
			);
				
			// Just to be safe, we check if the file exist first
			if( file_exists( $file ) ) {
				return $file;
			} else { 
				echo $file; 
			}
			
			return $template;
		}
	} 
	
	
	
    register_activation_hook( __FILE__, array( 'CBDashboard', 'myplugin_activate' ) );
    register_deactivation_hook( __FILE__, array( 'CBDashboard', 'myplugin_deactivate' ) );
	
	add_action( 'plugins_loaded', array( 'CBDashboard', 'get_instance' ) );
	add_action( 'init', array( 'CBDashboard', 'create_posttype' ) );
	add_action( 'wp_enqueue_scripts', array( 'CBDashboard', 'load_scripts' ), 999 );
	
