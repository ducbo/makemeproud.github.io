<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_CONTACT_SHORTCODE' ) ) {
	/**
	 * BESTBUG_FB_CONTACT_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_CONTACT_SHORTCODE {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}
		
		public function init() {
			add_action('wpcf7_init',array( $this, 'get_post_contact' ));
			add_shortcode( BESTBUG_FB_CONTACT_SHORTCODE, array( $this, 'shortcode' ) );
			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
				$this->vc_shortcode();
			}

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
        }

		public function adminEnqueueScripts() {
			// wp_enqueue_style( 'css', BESTBUG_RPPRO_URL . '/assets/admin/css/style.css' );
			// wp_enqueue_script( 'js', BESTBUG_RPPRO_URL . '/assets/admin/js/script.js', array( 'jquery' ), '1.0', true );
		}

		public function enqueueScripts() {
			// wp_enqueue_style( 'css', BESTBUG_RPPRO_URL . '/assets/css/style.css' );
			// wp_enqueue_script( 'js', BESTBUG_RPPRO_URL . '/assets/js/script.js', array( 'jquery' ), '1.0', true );
		}
        public function get_post_contact(){
			
		}
		public function vc_shortcode() {
			$posts = get_posts(array(
				'post_type'     => 'wpcf7_contact_form',
				'numberposts'   => -1
			));

			$title_contact = [];

			foreach($posts as $key => $value){
				$title_contact[$value->post_title] = $value->ID;
			}

			vc_map( array(
			    "name" => esc_html__( "Contact", 'bestbug' ),
			    "base" => BESTBUG_FB_CONTACT_SHORTCODE,
			    "as_parent" => array('except' => BESTBUG_FB_CONTACT_SHORTCODE),
			    "content_element" => true,
				"icon" => 'icon-' . BESTBUG_FB_CONTACT_SHORTCODE,
				"description" => esc_html__( "Contact form for page", 'bestbug' ),
				'category' => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BBFOOTER_DESIGNER_CATEGORY ) ),
			    "params" => array(
					array(
						'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Select contact form', 'bestbug' ),
				        'value'       =>$title_contact,
				        'param_name'  => 'id',
						'admin_label' => true,
						'save_always' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'bestbug' ),
						'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'bestbug'),
						'param_name' => 'el_class',
					),
					array(
						'type' => 'css_editor',
						'heading' => 'CSS box',
						'param_name' => 'css',
						'group' => 'Design Options',
					),
			    ),
			) );
        }
		public function settings($attr = BESTBUG_FB_CONTACT_SHORTCODE) {
			return BESTBUG_FB_CONTACT_SHORTCODE;
		}
		
		public function shortcode( $atts ){

			extract( shortcode_atts( array(
				'id' => '',
				'css' => '',
				'el_class' => '',
			), $atts ) );
			
			$class_array = array();
			if(isset($css) && !empty($css)) {
				array_push($class_array, BESTBUG_HELPER::vc_shortcode_custom_css_class($css));
			}
			if(isset($el_class) && !empty($el_class)) {
				array_push($class_array, $el_class);
			}
			if(isset($id) && !empty($id)) {
				array_push($class_array, $id);
			}
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_FB_CONTACT_SHORTCODE, $atts );
			
			return  '<div class="'.$el_class.'">'.do_shortcode('[contact-form-7 id="'.$id.'"]').'</div>';
		}
		
    }
	
	new BESTBUG_FB_CONTACT_SHORTCODE();
}
