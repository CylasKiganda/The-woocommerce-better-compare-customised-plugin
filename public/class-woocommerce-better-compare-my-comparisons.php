<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://welaunch.io/plugins/woocommerce-reward-points/
 * @since      1.0.0
 *
 * @package    WooCommerce_delivery
 * @subpackage WooCommerce_delivery/public
 */
class WooCommerce_Better_Compare_My_Comparisons extends WooCommerce_Better_Compare {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $options
	 */
	protected $options;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) 
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->data = array();
	}

	/**
	 * Inits the My Account
	 *
	 * @since    1.0.0
	 */
    public function init()
    {
		global $woocommerce_better_compare_options;

		$this->options = $woocommerce_better_compare_options;
		$this->userId = get_current_user_id();

		$endpoint =  'my-comparisons';

		if(!$this->get_option('myComparisons')) {
			return false;
		}

		if(!is_user_logged_in()) {
			return false;
		}

		add_action( 'woocommerce_account_' . $endpoint . '_endpoint', array($this, 'endpoint_content') );
		add_action( 'query_vars', array($this, 'query_vars'), 0);
		add_rewrite_endpoint( $endpoint, EP_ROOT | EP_PAGES );

		add_filter( 'woocommerce_account_menu_items', array($this, 'menu_items'));
	}

	/*
	 * Change endpoint title.
	 *	Deactivated. Overrides global post title
	 * @param string $title
	 * @return string
	 */
	public function endpoint_title( $title ) 
	{
		global $wp_query;

		$endpoint =  'my-comparisons';
		$is_endpoint = isset( $wp_query->query_vars[$endpoint] );

		if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
			$title =  $this->get_option('myComparisonsMenuTitle');
			remove_filter( 'the_title', 'endpoint_title' );
		}

		return $title;
	}

	/**
	 *  Add new query var.
	 * @author Daniel Barenkamp
	 * @version 1.0.0
	 * @since   1.0.0
	 * @link    https://welaunch.io/plugins/
	 * @param   [type]                       $vars [description]
	 * @return  [type]                             [description]
	 */
	public function query_vars( $vars )
	{
		$endpoint =  'my-comparisons';
		$vars[] = $endpoint;
		return $vars;
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 * @author Daniel Barenkamp
	 * @version 1.0.0
	 * @since   1.0.0
	 * @link    https://welaunch.io/plugins/
	 * @param   [type]                       $items [description]
	 * @return  [type]                              [description]
	 */
	public function menu_items( $items ) 
	{
		$customerLogoutItem = false;
		if(isset($items['customer-logout'])) {
			$customerLogoutItem = $items['customer-logout'];
			unset($items['customer-logout']);
		}

		$items['my-comparisons'] = $this->get_option('myComparisonsMenuTitle');

		if($customerLogoutItem && $this->get_option('myComparisonsReorderLogout')) {
			$items[] = $customerLogoutItem;
		}

		return $items;
	}

	/**
	 * Endpoint contents
	 * @author Daniel Barenkamp
	 * @version 1.0.0
	 * @since   1.0.0
	 * @link    https://welaunch.io/plugins/
	 * @return  [type]                       [description]
	 */
	public function endpoint_content() 
	{
		if(!$this->userId) {
			return;
		}

		echo '<div class="woocommerce-better-compare-my-comparisons-container">';

			echo '<div class="woocommerce-better-compare-my-comparisons-intro">';
				echo do_shortcode( wpautop( $this->get_option('myComparisonsIntro') ) );
			echo '</div>';

			$userComparisons = get_user_meta($this->userId, 'woocommerce_better_compare_comparisons', true);
			$comparePage = get_permalink($this->get_option('myComparisonsPage'));
			
			echo '<div class="woocommerce-better-compare-my-comparisons-sidebar">';

				if(!empty($userComparisons) && !empty($comparePage)) {

					echo '<h3>' . esc_html__('Your Comparisons', 'woocommerce-better-compare') . '</h3>';

					echo '<ul>';

					foreach($userComparisons as $userComparisonId => $userComparisonData) {

						echo 
						'<li class="comparison-id-' . $userComparisonId . '">
							<a class="woocommerce-better-compare-my-comparisons-view-comparison" href="#" data-id="' . $userComparisonId . '">' . $userComparisonData['name'] . '</a>
						</li>';
					}
					
					echo '</ul>';

				} else {
					esc_html_e('No Compare page set or no comparisons saved yet.', 'woocommerce-better-compare');
				}

			echo '</div>';
			echo '<div class="woocommerce-better-compare-my-comparisons-single">';

			echo '</div>';

			echo '<div class="woocommerce-better-compare-my-comparisons-outro">';
				echo do_shortcode( wpautop( $this->get_option('myComparisonsOutro') ) );
			echo '</div>';

		echo '</div>';
	}

	public function save()
	{
		if (!is_user_logged_in()) {
        	header('HTTP/1.1 400 Not logged In', true, 400);
            die();
        }

		if (!defined('DOING_AJAX') || !DOING_AJAX) {
        	header('HTTP/1.1 400 No AJAX call', true, 400);
            die();
        }

        if (!isset($_POST['name']) || empty($_POST['name'])) {
            header('HTTP/1.1 400 No name ID', true, 400);
            die();
        }

		if(!isset($_COOKIE['compare_products_products']) || empty($_COOKIE['compare_products_products'])) {
            header('HTTP/1.1 400 Products in Comparison', true, 400);
            die();
		}

        $listName = esc_attr($_POST['name']);

		$productsInCompareBar = json_decode(stripslashes($_COOKIE['compare_products_products']), true);

		$existingComparisons = get_user_meta($this->userId, 'woocommerce_better_compare_comparisons', true);
		if(empty($existingComparisons)) {
			$existingComparisons = array();
		}
		
		$existingComparisons[] = array(
			'name' => $listName,
			'products' => $productsInCompareBar,
		);

		update_user_meta($this->userId, 'woocommerce_better_compare_comparisons', $existingComparisons);
	}

	public function view()
	{
		if (!is_user_logged_in()) {
        	header('HTTP/1.1 400 Not logged In', true, 400);
            die();
        }

		if (!defined('DOING_AJAX') || !DOING_AJAX) {
        	header('HTTP/1.1 400 No AJAX call', true, 400);
            die();
        }

        if (!isset($_POST['id']) || empty($_POST['id']) && $_POST['id'] != 0) {
            header('HTTP/1.1 400 No ID', true, 400);
            die();
        }

        $userComparisons = get_user_meta($this->userId, 'woocommerce_better_compare_comparisons', true);
        if(empty($userComparisons)) {
            header('HTTP/1.1 400 Comparisons', true, 400);
            die();
        }

        $comparisonId = intval( $_POST['id'] );
        if(!isset($userComparisons[$comparisonId])) {
            header('HTTP/1.1 400 Comparison not Found', true, 400);
            die();
    	}

    	$userComparison = $userComparisons[$comparisonId];
		wc_get_template( 'woocommerce-my-comparisons-single.php', array('id' => $comparisonId, 'comparison' => $userComparison ), '', plugin_dir_path(__FILE__) . 'templates/' );
		die();		
	}

	public function remove_product()
	{
		if (!is_user_logged_in()) {
        	header('HTTP/1.1 400 Not logged In', true, 400);
            die();
        }

		if (!defined('DOING_AJAX') || !DOING_AJAX) {
        	header('HTTP/1.1 400 No AJAX call', true, 400);
            die();
        }

        if (!isset($_POST['id']) || empty($_POST['id']) && $_POST['id'] != 0) {
            header('HTTP/1.1 400 No ID', true, 400);
            die();
        }

        if (!isset($_POST['product']) || empty($_POST['product'])) {
            header('HTTP/1.1 400 No ID', true, 400);
            die();
        }

        $userComparisons = get_user_meta($this->userId, 'woocommerce_better_compare_comparisons', true);
        if(empty($userComparisons)) {
            header('HTTP/1.1 400 No Comparisons', true, 400);
            die();
        }

        $comparisonId = intval( $_POST['id'] );
        $productId = intval( $_POST['product'] );
        if(!isset($userComparisons[$comparisonId])) {
            header('HTTP/1.1 400 Comparison not Found', true, 400);
            die();
    	}

        unset($userComparisons[$comparisonId]['products'][$productId]);
        update_user_meta($this->userId, 'woocommerce_better_compare_comparisons', $userComparisons);

        $response = array(
        	'id' => $comparisonId,
        	'product' => $productId,
        );
		echo json_encode($response);
		die();		
	}

	public function edit()
	{
		if (!is_user_logged_in()) {
        	header('HTTP/1.1 400 Not logged In', true, 400);
            die();
        }

		if (!defined('DOING_AJAX') || !DOING_AJAX) {
        	header('HTTP/1.1 400 No AJAX call', true, 400);
            die();
        }

        if (!isset($_POST['id']) || empty($_POST['id']) && $_POST['id'] != 0) {
            header('HTTP/1.1 400 No ID', true, 400);
            die();
        }

        if (!isset($_POST['name']) || empty($_POST['name'])) {
            header('HTTP/1.1 400 No ID', true, 400);
            die();
        }

        $userComparisons = get_user_meta($this->userId, 'woocommerce_better_compare_comparisons', true);
        if(empty($userComparisons)) {
            header('HTTP/1.1 400 No Comparisons', true, 400);
            die();
        }

        $comparisonId = intval( $_POST['id'] );
        if(!isset($userComparisons[$comparisonId])) {
            header('HTTP/1.1 400 Comparison not Found', true, 400);
            die();
    	}

    	$name = esc_html($_POST['name']);
        $userComparisons[$comparisonId]['name'] = $name;

        update_user_meta($this->userId, 'woocommerce_better_compare_comparisons', $userComparisons);

        $response = array(
        	'id' => $comparisonId,
        	'name' => $name,
        );
		echo json_encode($response);
		die();
	}

	public function delete()
	{
		if (!is_user_logged_in()) {
        	header('HTTP/1.1 400 Not logged In', true, 400);
            die();
        }

		if (!defined('DOING_AJAX') || !DOING_AJAX) {
        	header('HTTP/1.1 400 No AJAX call', true, 400);
            die();
        }

        if (!isset($_POST['id']) || empty($_POST['id']) && $_POST['id'] != 0) {
            header('HTTP/1.1 400 No ID', true, 400);
            die();
        }

        $userComparisons = get_user_meta($this->userId, 'woocommerce_better_compare_comparisons', true);
        if(empty($userComparisons)) {
            header('HTTP/1.1 400 Comparisons', true, 400);
            die();
        }

        $comparisonId = intval( $_POST['id'] );
        if(!isset($userComparisons[$comparisonId])) {
            header('HTTP/1.1 400 Comparison not Found', true, 400);
            die();
    	}

    	unset($userComparisons[$comparisonId]);
    	update_user_meta($this->userId, 'woocommerce_better_compare_comparisons', $userComparisons);

        $response = array(
        	'id' => $comparisonId,
        );
		echo json_encode($response);
		die();

	}




}