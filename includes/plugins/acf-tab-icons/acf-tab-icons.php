<?php
/*
Plugin Name: ACF Tab & Accordion Title Icons
Plugin URI: https://wordpress.org/plugins/acf-tab-accordion-title-icons/
Description: Add icons to the titles of ACF Tabs and Accordions.
Version: 1.0.1
Author: Thomas Meyer
Author URI: https://dreihochzwo.de
Text Domain: acf_icon_title
Domain Path: /languages
License: GPLv2 or later
Copyright: Thomas Meyer
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;
	// check if class already exists

if( !class_exists('dhz_acf_plugin_title_icons') ) :

	class dhz_acf_plugin_title_icons {
	
		function __construct() {

			if ( ! defined( 'DHZ_SHOW_DONATION_LINK' ) )
				define( 'DHZ_SHOW_DONATION_LINK', true );
			
			
			// vars
			$this->settings = array(
				'plugin'			=> __('ACF Tab & Accordion Title Icons', 'acf_icon_title'),
				'this_acf_version'	=> 0,
				'min_acf_version'	=> '5.6.7',
				'version'			=> '1.0.1',
				'url'				=> plugin_dir_url( __FILE__ ),
				'path'				=> plugin_dir_path( __FILE__ ),
				'plugin_path'		=> 'https://wordpress.org/plugins/acf-tab-accordion-title-icons/'
			);
			
			// set text domain
			load_plugin_textdomain( 'acf_icon_title', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );

			add_action( 'admin_init', array($this, 'acf_or_die'), 11);
					
			// include field
			add_action('acf/input/admin_enqueue_scripts', array($this, 'acf_title_icon_admin_enqueue_scripts'), 11);

			add_action('acf/render_field_settings/type=accordion', array($this, 'dhz_title_icon_render_field_settings'), 11);
			add_action('acf/render_field_settings/type=tab', array($this, 'dhz_title_icon_render_field_settings'), 11);

			add_filter('acf/prepare_field/type=accordion', array($this, 'dhz_acf_title_icon_prepare_field'), 11);
			add_filter('acf/prepare_field/type=tab', array($this, 'dhz_acf_title_icon_prepare_field'), 11);
			
			if ( DHZ_SHOW_DONATION_LINK == true ) {

				// add plugin to $plugins array for the metabox
				add_filter( '_dhz_plugins_list', array($this, '_dhz_meta_box_data') );

				// metabox callback for plugins list and donation link
				add_action( 'add_meta_boxes_acf-field-group', array($this, '_dhz_plugins_list_meta_box') );

			}
			
		}

		/**
		 * Let's make sure ACF Pro is installed & activated
		 * If not, we give notice and kill the activation of ACF RGBA Color Picker.
		 * Also works if ACF Pro is deactivated.
		 */
		function acf_or_die() {

			if ( !class_exists('acf') ) {
				$this->kill_plugin();
			} else {
				$this->settings['this_acf_version'] = acf()->settings['version'];
				if ( version_compare( $this->settings['this_acf_version'], $this->settings['min_acf_version'], '<' ) ) {
					$this->kill_plugin();
				}
			}
		}

		function kill_plugin() {
			deactivate_plugins( plugin_basename( __FILE__ ) );   
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			add_action( 'admin_notices', array($this, 'acf_dependent_plugin_notice') );
		}

		function acf_dependent_plugin_notice() {
			echo '<div class="error"><p>' . sprintf( __('%1$s requires ACF PRO v%2$s or higher to be installed and activated.', 'acf_icon_title'), $this->settings['plugin'], $this->settings['min_acf_version']) . '</p></div>';
		}
	
		/*
		*  Load the javascript and CSS files on the ACF admin pages
		*/
		function acf_title_icon_admin_enqueue_scripts() {
			
			// globals
			global $wp_scripts, $wp_styles;

			$url = $this->settings['url'];
			$version = $this->settings['version'];

			if ( file_exists(get_theme_file_path() . '/acf-title-icons/style.css') ) {
				// register ACF Title Icons CSS from theme folder
				wp_register_style( 'acf-title-icons', get_theme_file_uri() . '/acf-title-icons/style.css', array(), $version);
			} else {
				// register ACF Title Icons CSS from plugin folder
				wp_register_style( 'acf-title-icons', "{$url}assets/icons/style.css", array(), $version);
			}			

			// register ACF Title Icons
			wp_register_style( 'acf-title-icon', "{$url}assets/css/acf-title-icon.css", array(), $version);
		
			// enqueue styles & scripts
			wp_enqueue_style('acf-title-icons');
			wp_enqueue_style('acf-title-icon');

		}

		function dhz_title_icon_render_field_settings( $field ) {

			$url = $this->settings['url'];

			// $json_file = wp_remote_get( get_theme_file_uri( '/acf-title-icons/' ) . $iconname . '/selection.json');

			// $response_code = wp_remote_retrieve_response_code( $json_file );

			// if ( $response_code != 200 ) {
				// $json_file = wp_remote_get( $icondir . 'selection.json');
			// }
			// $json_file = wp_remote_retrieve_body( $json_file );

			if ( file_exists(get_theme_file_path() . "/acf-title-icons/selection.json") ) {
				$json_file = file_get_contents( get_theme_file_uri( "/acf-title-icons/selection.json") );
			} else {
				$json_file = file_get_contents( "{$url}assets/icons/selection.json");
			}				

			$json_content = json_decode( $json_file, true );
			
			if ( !isset( $json_content['icons'] ) ) {
				
				acf_render_field_setting( $field, array(
					'label'			=> __('Icon',"acf_icon_title"),
					'instructions'	=> '',
					'type'			=> 'message',
					'message'		=> __('No icons found', "acf_icon_title"),
					'new_lines'		=> ''
				));
				return;
			}

			$prefix = $json_content['preferences']['fontPref']['prefix'];
			$iconname = $json_content['preferences']['fontPref']['metadata']['fontFamily'];

			$icons = array();

			foreach ( $json_content['icons'] as $icon ) {
				$class = $icon['properties']['name'];
				$name = implode(" ",$icon['icon']['tags']);
				$icons[$iconname . '-' . $class] = esc_html('<span class="acf-icon-title"><i class="' . $iconname . ' ' . $prefix . $class . '"></i>' . $name . '</span>');
			}
			
			// icon select
			acf_render_field_setting( $field, array(
				'label'			=> __("Icon", "acf_icon_title"),
				'instructions'	=> __("Select an icon you want to show before the title.", "acf_icon_title"),
				'type'			=> 'select',
				'id'			=> $field['ID'] . 'accordion-select',
				'name'			=> 'icon_class',
				'choices'		=> $icons,
				'allow_null'	=> 1,
				'multiple'		=> 0,
				'ui'			=> 1,
				'ajax'			=> 0,
			));
		
			// icon only
			acf_render_field_setting( $field, array(
				'label'			=> __("Show icon only", "acf_icon_title"),
				'instructions'	=> __("If set to <em>Yes</em>, you will see only the icon and no title.", "acf_icon_title"),
				'name'			=> 'show_icon_only',
				'type'			=> 'true_false',
				'ui'			=> 1,
			));
		}

		function dhz_acf_title_icon_prepare_field( $field ) {

			if ( array_key_exists('icon_class', $field) && !$field['icon_class'] == '') {
				if ( array_key_exists('show_icon_only', $field) && $field['show_icon_only'] == 1 ) {
					$field['label'] = '<span class="acf-title-icon ' . esc_attr( $field['icon_class']) . '"></span>';
				} else {
					$field['label'] = '<span class="acf-title-icon ' . esc_attr( $field['icon_class']) . '"></span><span class="acf-title-text">' . esc_attr( $field["label"] ) . '</span>';
				}
				$field['wrapper']['class'] .= ' acf-title-with-icon';
			}			

		    return $field;
		}

		/*
		*  Add plugin to $plugins array for the metabox
		*/
		function _dhz_meta_box_data($plugins=array()) {
			
			$plugins[] = array(
				'title' => $this->settings['plugin'],
				'screens' => array('acf-field-group'),
				'doc' => $this->settings['plugin_path']
			);
			return $plugins;
			
		} // end function meta_box

		/*
		*  Add metabox for plugins list and donation link
		*/
		function _dhz_plugins_list_meta_box() {

			$plugins = apply_filters('_dhz_plugins_list', array());
				
			$id = 'plugins-by-dreihochzwo';
			$title = '<a style="text-decoration: none; font-size: 1em;" href="https://profiles.wordpress.org/tmconnect/#content-plugins" target="_blank">'.__("Plugins by dreihochzwo", "acf_icon_title").'</a>';
			$callback = array($this, 'show_dhz_plugins_list_meta_box');
			$screens = array();
			foreach ($plugins as $plugin) {
				$screens = array_merge($screens, $plugin['screens']);
			}
			$context = 'side';
			$priority = 'default';
			add_meta_box($id, $title, $callback, $screens, $context, $priority);
			
			
		} // end function _dhz_plugins_list_meta_box

		/*
		*  Metabox callback for plugins list and donation link
		*/
		function show_dhz_plugins_list_meta_box() {

			$plugins = apply_filters('_dhz_plugins_list', array());
			?>
				<p style="margin-bottom: 10px; font-weight:500"><?php _e("Thank you for using my plugins!", "acf_icon_title") ?></p>
				<ul style="margin-top: 0; margin-left: 5px;">
					<?php 
						foreach ($plugins as $plugin) {
							?>
								<li style="list-style-type: disc; list-style-position:inside; text-indent:-13px; margin-left:13px">
									<?php 
										echo $plugin['title']."<br/>";
										if ($plugin['doc']) {
											?> <a style="font-size:12px" href="<?php echo $plugin['doc']; ?>" target="_blank"><?php _e("Documentation", "acf_icon_title") ?></a><?php 
										}
									?>
								</li>
							<?php 
						}
					?>
				</ul>
				<div style="margin-left:-12px; margin-right:-12px; margin-bottom: -12px; background: #2a9bd9; padding:14px 12px">
					<p style="margin:0; text-align:center"><a style="color: #fff;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=XMLKD8H84HXB4&lc=US&item_name=Donation%20for%20WordPress%20Plugins&no_note=0&cn=Add%20a%20message%3a&no_shipping=1&currency_code=EUR" target="_blank"><?php _e("Please consider making a small donation!", "acf_icon_title") ?></a></p>
				</div>
			<?php
		}
		

	}
// initialize
new dhz_acf_plugin_title_icons();

// class_exists check
endif;

?>