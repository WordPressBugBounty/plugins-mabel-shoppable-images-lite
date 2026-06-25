<?php

namespace MABEL_SILITE\Code\Controllers
{

	use MABEL_SILITE\Code\Services\Woocommerce_Service;
	use MABEL_SILITE\Core\Common\Admin;
	use MABEL_SILITE\Core\Common\Linq\Enumerable;
	use MABEL_SILITE\Core\Common\Managers\Config_Manager;
	use MABEL_SILITE\Core\Common\Managers\Options_Manager;
	use MABEL_SILITE\Core\Common\Managers\Settings_Manager;
	use MABEL_SILITE\Core\Models\ColorPicker_Option;
	use MABEL_SILITE\Core\Models\Custom_Option;
	use MABEL_SILITE\Core\Models\Text_Option;

	if( ! defined( 'ABSPATH' ) ) { die; }

	class Admin_Controller extends Admin {

        		private $slug;
        public $capability;

		public function __construct() {

            			parent::__construct(new Options_Manager());
			$this->slug = Config_Manager::$slug;

            $this->capability = apply_filters( 'shoppable_images_capability', 'manage_options' );

            if( isset( $_GET['page'] ) && $_GET['page'] === $this->slug ) {

                $this->add_mediamanager_scripts = true;

                $this->add_script_dependencies('wp-color-picker');
                $this->add_style('wp-color-picker', null);
                $this->add_script_variable('tagsize', Settings_Manager::get_setting('tagsize'));
                $this->add_script_variable('iconsize', Settings_Manager::get_setting('iconsize'));
                $this->add_script_variable('tagicon', Settings_Manager::get_setting('tagicon'));

            }

			$this->add_ajax_function('mb-siwc-get-images', $this,'get_images',false,true);
			$this->add_ajax_function('mb-siwc-get-image', $this,'get_image',false,true);
			$this->add_ajax_function('mb-siwc-add-image', $this,'add_image',false,true);
			$this->add_ajax_function('mb-siwc-update-image', $this,'update_image',false,true);
			$this->add_ajax_function('mb-siwc-delete-image', $this, 'delete_image', false, true);

			$this->add_ajax_function('mb-siwc-get-product-by-id', $this, 'get_wc_product_by_id', false, true);
			$this->add_ajax_function('mb-siwc-get-products-by-ids', $this, 'get_wc_products_by_ids', false, true);
			$this->add_ajax_function('mb-siwc-get-products', $this, 'get_wc_product_by_name', false, true);


        }

		public function render_main_sidebar() {
			include Config_Manager::$dir . 'admin/views/sidebar-main.php';
		}

		public function get_wc_products_by_ids() {

            if(!current_user_can($this->capability) || !isset($_REQUEST['nonce']) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error();
            }

			if( empty( $_GET['ids'] ) ) {
				echo json_encode( [] );
				wp_die();
			}

            $ps = wc_get_products( [
                'limit'     => -1,
                'include'   => array_map( 'intval', explode(',',sanitize_text_field(wp_unslash($_GET['ids']))) )
            ] );

			$products = [];

			foreach ($ps as $product) {

				$products[] = [
					'name'  => $product->get_title(),
					'url'   => $product->get_permalink(),
					'price' => Woocommerce_Service::format_price( wc_get_price_to_display($product) ),
					'id' => $product->get_id()
				];

			}

			echo json_encode( $products );

			wp_die();
		}

		public function get_wc_product_by_id() {
            if(!current_user_can($this->capability) || !isset($_REQUEST['nonce']) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error();
            }

            if ( ! empty( $_GET['id'] ) ) {
                echo json_encode( $this->get_wc_product( sanitize_text_field( wp_unslash( $_GET['id'] ) ) ) );
            }

			wp_die();
		}

		public function get_wc_product_by_name() {

            if(!current_user_can($this->capability) || !isset($_REQUEST['nonce']) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error();
            }

            $term = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';

                        if( empty( $term ) ) wp_send_json( [] );

            $limit      = absint( apply_filters( 'woocommerce_json_search_limit', 10 ) );
            $data_store = \WC_Data_Store::load( 'product' );
            $ids        = $data_store->search_products( $term, '', false, true, $limit );
            $products   = [];

            foreach ( $ids as $id ) {

                if( empty( $id ) ) continue; 

                $product_object = wc_get_product( $id );

                if ( ! wc_products_array_filter_readable( $product_object ) ) continue;

                                $products[] = [
                    'id'    => $product_object->get_id(),
                    'name'  => preg_replace('/\s\(#\d+\)/', '', $product_object->get_formatted_name(), 1),
                    'price' => get_woocommerce_currency_symbol() . wc_get_price_to_display( $product_object ),
                    'url'   => $product_object->get_permalink(),
                ];

            }

            wp_send_json( $products );

            		}

		private function get_wc_product( $pid ) {

			$product = wc_get_product( $pid );

			return array(
				'name'  => $product->get_title(),
				'url'   => $product->get_permalink(),
				'price' => get_woocommerce_currency_symbol() . wc_get_price_to_display($product),
				'id' => $product->get_id()
			);
		}

		public function delete_image() {
            if( ! current_user_can($this->capability) || !isset($_REQUEST['nonce']) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error();
            }

            if ( ! empty( $_REQUEST['imageId'] ) ) {
                wp_delete_post( sanitize_text_field( wp_unslash( $_REQUEST['imageId'] ) ), true );
            }

			wp_die();
		}

		public function get_image() {
            if(!current_user_can($this->capability) || !isset($_REQUEST['nonce']) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error();
            }

			if(!isset($_GET['id'])) wp_die();

            $post = get_post(sanitize_text_field(wp_unslash($_GET['id'])));
			if($post == null) wp_die();

			wp_send_json(array(
				'id' => $post->ID,
				'image'  => json_decode(get_post_meta($post->ID,'image',true))->image,
				'tags' => json_decode(get_post_meta($post->ID,'tags',true))
			));
		}

		public function get_images() {
            if(!current_user_can($this->capability) || !isset($_REQUEST['nonce']) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error();
            }

            $page = isset($_GET['page']) ? intval( wp_unslash( $_GET['page'] ) ) : 1;

			$post_ids = new \WP_Query(array(
				'post_type' => 'mb_siwc_lite_image',
				'fields' => 'ids',
				'posts_per_page' => 12,
				'paged' => $page
			));

			$images = array();

			foreach ($post_ids->posts as $id){
				$thumb = json_decode(get_post_meta($id,'image',true))->thumb;
				$obj = (object) array(
					'id' => $id,
					'image'  => $thumb,
					'tags' => json_decode(get_post_meta($id,'tags',true))
				);
				array_push($images,$obj);
			}
			wp_reset_postdata();

			wp_send_json( array(
				'images' => $images,
				'maxPages' => $post_ids->max_num_pages,
				'currentPage' => $page
			));
		}

		public function update_image() {
            if( empty( $_POST['id'] ) || empty( $_POST['tags'] ) || ! current_user_can($this->capability) || ! isset($_REQUEST['nonce']) || !  wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error();
            }

			if(isset($_POST['id'])) {
				update_post_meta( intval( $_POST['id'] ), 'tags', sanitize_text_field( wp_unslash( $_POST['tags'] ) ) );
			}
			wp_die();
		}

		public function add_image() {

            if( empty( $_POST['tags'] ) || empty( $_POST['image'] ) || empty( $_POST['thumb'] ) || ! current_user_can( $this->capability ) || ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'sinonce' ) ) {
                wp_send_json_error( __( 'Invalid nonce',  'mabel-shoppable-images-lite' ) );
            }

			$id = wp_insert_post( [
				'post_type' => 'mb_siwc_lite_image',
				'post_status' => 'publish'
			], true );

			if( ! is_wp_error( $id ) && $id > 0 ) {

				add_post_meta( $id, 'image', json_encode( [
                    'image' => sanitize_text_field( wp_unslash( $_POST['image'] ) ),
                    'thumb' => sanitize_text_field( wp_unslash( $_POST['thumb'] ) )
				] ) );
                add_post_meta( $id, 'tags', sanitize_text_field( wp_unslash( $_POST['tags'] ) ) );
			}
			wp_die( intval( $id ) );
		}

		public function init_admin_page() {
			add_action(Config_Manager::$slug . '-render-sidebar', array($this,'render_main_sidebar'));

			$this->options_manager->add_section('design', __('Design','mabel-shoppable-images-lite'), 'admin-customizer', true);
			$this->options_manager->add_section('addimage', __('Add image','mabel-shoppable-images-lite'), 'format-image');
			$this->options_manager->add_section('images', __('Images','mabel-shoppable-images-lite'), 'images-alt2');

			$this->options_manager->add_option('design',
				new ColorPicker_Option(
					'tagbgcolor',
					Settings_Manager::get_setting('tagbgcolor'),
					__('Tag background color','mabel-shoppable-images-lite')
				)
			);

			$this->options_manager->add_option('design',
				new ColorPicker_Option(
					'tagfgcolor',
					Settings_Manager::get_setting('tagfgcolor'),
					__('Icon color','mabel-shoppable-images-lite' )
				)
			);

			$this->options_manager->add_option('addimage',
				new Custom_Option(null,'add_image',array(
					'woocommerce_active' => class_exists( 'WooCommerce' )
				))
			);

			$this->options_manager->add_option('design',
				new Text_Option(
					'buttontext',
					__('Button text', 'mabel-shoppable-images-lite'),
					Settings_Manager::get_setting('buttontext'),
					null,
					__('What text should appear on the button linking to the product page?','mabel-shoppable-images-lite')
				)
			);

			$this->options_manager->add_option('images',
				new Custom_Option(null,'all_images')
			);

		}

	}
}