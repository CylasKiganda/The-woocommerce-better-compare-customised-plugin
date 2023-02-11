<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://woocommerce.db-dzine.de
 * @since      1.0.0
 *
 * @package    WooCommerce_Better_Compare
 * @subpackage WooCommerce_Better_Compare/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce_Better_Compare
 * @subpackage WooCommerce_Better_Compare/public
 * @author     Daniel Barenkamp <support@db-dzine.com>
 */
class WooCommerce_Better_Compare_Public extends WooCommerce_Better_Compare {

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
	}

	/**
	 * Enqueue Styles
	 * @author Daniel Barenkamp
	 * @version 1.0.0
	 * @since   1.0.0
	 * @link    http://woocommerce.db-dzine.de
	 * @return  boolean
	 */
	public function enqueue_styles()
	{
		global $woocommerce_better_compare_options;

		$this->options = $woocommerce_better_compare_options;

		if (!$this->get_option('enable')) {
			return false;
		}

		if($this->get_option('performanceOnlyWooPages') && (!is_shop() && !is_product() && !is_product_category() && !is_product_tag() && !is_product_taxonomy()) ){
			return;
		}

		wp_enqueue_style($this->plugin_name.'-public', plugin_dir_url(__FILE__) . 'css/woocommerce-better-compare-public.css', array(), $this->version, 'all');

		if(!$this->get_option('doNotLoadFontAwesome')) {
			wp_enqueue_style('font-awesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome-free-5.15.3-web/css/all.min.css', array(), '5.15.3', 'all');
		}
		
		wp_enqueue_style('slick', plugin_dir_url(__FILE__).'vendor/slick/slick.css', array(), $this->version, 'all');

		$css = "";
		$compareBarPosition = $this->get_option('compareBarPosition');
		$compareBarBackgroundColor = $this->get_option('compareBarBackgroundColor');
		$compareBarBackgroundColor = isset($compareBarBackgroundColor['rgba']) ? $compareBarBackgroundColor['rgba'] : $compareBarBackgroundColor['color'];

		$compareBarHeight = $this->get_option('compareBarHeight');
		$compareBarItemWidth = $this->get_option('compareBarItemWidth');
		$compareBarItemHeight = $this->get_option('compareBarItemHeight');
		$compareBarTextColor = $this->get_option('compareBarTextColor');

		$css .= '.woocommerce-compare-bar {
			' . $compareBarPosition . ': 0;
			color: ' . $compareBarTextColor . ';
		}';

		$css .= '.woocommerce-compare-bar, .woocommerce-compare-bar-item a, .woocommerce-compare-bar-item a:hover {
			color: ' . $compareBarTextColor . ';
		}';

		if($compareBarPosition == "top") {
		    $css .= '.woocommerce-compare-bar-open-close-container {
				bottom: -32px;
			}';
			$css .= '.woocommerce-compare-bar-open-close {
				    border-radius: 0 0 15px 15px;
				}';
		} elseif($compareBarPosition == "bottom") {
		    $css .= '.woocommerce-compare-bar-open-close-container {
				top: -32px;
			}';
			$css .= '.woocommerce-compare-bar-open-close {
				    border-radius: 15px 15px 0 0;
				}';
		}

		$css .= 'a.woocommerce-compare-bar-action-clear, a.woocommerce-compare-bar-action-clear:hover {
			color: ' . $compareBarTextColor . ';
		}';
		

		$css .= '.woocommerce-compare-bar-items {
			height: ' . $compareBarHeight . 'px;
		}';

		$css .= '.woocommerce-compare-bar-item {
			max-width: ' . $compareBarItemWidth . 'px;
			width: ' . $compareBarItemWidth . 'px;
			height: ' . $compareBarItemHeight . 'px;
		}';

		$css .= '.woocommerce-compare-bar-open-close, .woocommerce-compare-bar-items {
			background-color: ' . $compareBarBackgroundColor . ';
			color: ' . $compareBarTextColor . ';
		}';


		$compareTableTextColor = $this->get_option('compareTableTextColor');

		$compareTableBackgroundColor = $this->get_option('compareTableBackgroundColor');
		$compareTableBackgroundColor = isset($compareTableBackgroundColor['rgba']) ? $compareTableBackgroundColor['rgba'] : $compareTableBackgroundColor['color'];

		$compareTableOddBackgroundColor = $this->get_option('compareTableOddBackgroundColor');
		$compareTableOddBackgroundColor = isset($compareTableOddBackgroundColor['rgba']) ? $compareTableOddBackgroundColor['rgba'] : $compareTableOddBackgroundColor['color'];

		$compareTableEvenBackgroundColor = $this->get_option('compareTableEvenBackgroundColor');
		$compareTableEvenBackgroundColor = isset($compareTableEvenBackgroundColor['rgba']) ? $compareTableEvenBackgroundColor['rgba'] : $compareTableEvenBackgroundColor['color'];

		$compareTableHighlightBackgroundColor = $this->get_option('compareTableHighlightBackgroundColor');
		$compareTableHighlightBackgroundColor = isset($compareTableHighlightBackgroundColor['rgba']) ? $compareTableHighlightBackgroundColor['rgba'] : $compareTableHighlightBackgroundColor['color'];


		$css .= '.woocommerce-compare-table-container {
			color: ' . $compareTableTextColor . ';
			background-color: ' . $compareTableBackgroundColor . ';
		}';

		$css .= '.woocommerce-compare-table-container .compare-table-row:nth-child(even) {
			background-color: ' . $compareTableEvenBackgroundColor . ';
		}';

		$css .= '.woocommerce-compare-table-container .compare-table-row:nth-child(odd) {
			background-color: ' . $compareTableOddBackgroundColor . ';
		}';

		$css .= '.woocommerce-compare-table-container .compare-table-row .compare-table-highlight  {
			background-color: ' . $compareTableHighlightBackgroundColor . ';
		}';

		$css .= '.woocommerce-compare-table-close {
			color: ' . $compareTableTextColor . ';
		}';

		$compareSingleTableTextColor = $this->get_option('compareSingleTableTextColor');

		$compareSingleTableOddBackgroundColor = $this->get_option('compareSingleTableOddBackgroundColor');
		$compareSingleTableOddBackgroundColor = isset($compareSingleTableOddBackgroundColor['rgba']) ? $compareSingleTableOddBackgroundColor['rgba'] : $compareSingleTableOddBackgroundColor['color'];

		$compareSingleTableEvenBackgroundColor = $this->get_option('compareSingleTableEvenBackgroundColor');
		$compareSingleTableEvenBackgroundColor = isset($compareSingleTableEvenBackgroundColor['rgba']) ? $compareSingleTableEvenBackgroundColor['rgba'] : $compareSingleTableEvenBackgroundColor['color'];

		$compareSingleTableHighlightBackgroundColor = $this->get_option('compareSingleTableHighlightBackgroundColor');
		$compareSingleTableHighlightBackgroundColor = isset($compareSingleTableHighlightBackgroundColor['rgba']) ? $compareSingleTableHighlightBackgroundColor['rgba'] : $compareSingleTableHighlightBackgroundColor['color'];


		$css .= '.woocommerce-single-compare-table-container {
			color: ' . $compareSingleTableTextColor . ';
		}';

		$css .= '.woocommerce-single-compare-table-container .single-product-compare-value.even, 
				.woocommerce-single-compare-table-container .single-product-compare-key-column.even {
			background-color: ' . $compareSingleTableOddBackgroundColor . ';
		}';

		$css .= '.woocommerce-single-compare-table-container .single-product-compare-value.oddd, 
				.woocommerce-single-compare-table-container .single-product-compare-key-column.oddd {
			background-color: ' . $compareSingleTableEvenBackgroundColor . ';
		}';

		$css .= '.woocommerce-single-compare-table-container .single-product-compare-value.compare-table-highlight, 
				.woocommerce-single-compare-table-container .single-product-compare-key-column.compare-table-highlight  {
			background-color: ' . $compareSingleTableHighlightBackgroundColor . ';
		}';

		$customCSS = $this->get_option('customCSS');
		$css = $css . $customCSS;

		file_put_contents( dirname(__FILE__)  . '/css/woocommerce-better-compare-custom.css', $css);
		wp_enqueue_style( $this->plugin_name.'-custom', plugin_dir_url( __FILE__ ) . 'css/woocommerce-better-compare-custom.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 * @author Daniel Barenkamp
	 * @version 1.0.0
	 * @since   1.0.0
	 * @link    http://#
	 * @return  boolean
	 */
	public function enqueue_scripts()
	{
		global $woocommerce_better_compare_options;

		$this->options = $woocommerce_better_compare_options;

		if (!$this->get_option('enable')) {
			return false;
		}

		if($this->get_option('performanceOnlyWooPages') && (!is_shop() && !is_product() && !is_product_category() && !is_product_tag() && !is_product_taxonomy()) ){
			return;
		}

		wp_enqueue_script('slick', plugin_dir_url(__FILE__).'vendor/slick/slick.min.js', array('jquery'), $this->version, true);
		wp_enqueue_script('matchHeight', plugin_dir_url(__FILE__).'vendor/jquery-match-height/jquery.matchHeight.js', array('jquery'), '0.7.2', true);
		wp_enqueue_script($this->plugin_name.'-public', plugin_dir_url(__FILE__).'js/woocommerce-better-compare-public.js', array('jquery', 'slick', 'matchHeight'), '1.121', true);
		

        $forJS['ajax_url'] = admin_url('admin-ajax.php');
        $forJS['shop_url'] = get_site_url();
        $forJS['trans'] = $this->get_translations();
        $forJS['maxProducts'] = $woocommerce_better_compare_options['maxProducts'];
        $forJS['maxProductsMobile'] = $woocommerce_better_compare_options['maxProductsMobile'];
        $forJS['disableReplaceState'] = $woocommerce_better_compare_options['disableReplaceState'];
        
		$forJS['textsSaveComparisonPrompt'] = $woocommerce_better_compare_options['textsSaveComparisonPrompt'];
		$forJS['textsSaveComparisonSaved'] = $woocommerce_better_compare_options['textsSaveComparisonSaved'];
		$forJS['compareBarHideUntilProductAdded'] = $woocommerce_better_compare_options['compareBarHideUntilProductAdded'];

        $forJS['singleCompareTableShowAttrNameInColumn'] = $woocommerce_better_compare_options['singleCompareTableShowAttrNameInColumn'];
        // $forJS['enableDraggable'] = $woocommerce_better_compare_options['enableDraggable'];
        wp_localize_script($this->plugin_name . '-public', 'woocommerce_better_compare_options', $forJS);
	}

    /**
     * Init the Bought together
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://#
     * @return  [type]                       [description]
     */
    public function init()
    {
        global $woocommerce_better_compare_options;
        $this->options = $woocommerce_better_compare_options;

		if (!$this->get_option('enable')) {
			return false;
		}

		$shopLoopCompareButtonPosition = $this->get_option('shopLoopCompareButtonPosition');
		!empty($shopLoopCompareButtonPosition) ? $shopLoopCompareButtonPosition = $shopLoopCompareButtonPosition : $shopLoopCompareButtonPosition = 'woocommerce_after_shop_loop_item';

		$shopLoopCompareButtonPriority = $this->get_option('shopLoopCompareButtonPriority');

		add_action($shopLoopCompareButtonPosition, array($this, 'compare_button'), $shopLoopCompareButtonPriority);

		add_shortcode( 'woocommerce_better_compare', array($this, 'compare_products_shortcode'));
		add_shortcode( 'woocommerce_better_compare_button', array($this, 'compare_button_shortcode'));
		add_shortcode( 'woocommerce_better_compare_autocomplete', array($this, 'autocomplete_shortcode'));
		add_shortcode( 'woocommerce_better_compare_share', array($this, 'get_share_icons'));
    }

	public function compare_button()
	{
		global $product;

		if(!is_object($product)) {
			return false;
		}

		$showAddToCompare = apply_filters('woocommerce_better_compare_show_add_to_compare_button', true, $product);
		if(!$showAddToCompare) {
			return false;
		}

		$productId = $product->get_id();

		$excludeProductCategories = $this->get_option('excludeProductCategories');
		$excludeProductCategoriesRevert = $this->get_option('excludeProductCategoriesRevert');
		$terms = get_the_terms( $productId, 'product_cat' );
		if(!empty($terms) && !empty($excludeProductCategories)) {
			foreach ($terms as $term) {
				if($excludeProductCategoriesRevert) {
					if(!in_array($term->term_id, $excludeProductCategories)) {
						return TRUE;
					}
				} else {
					if(in_array($term->term_id, $excludeProductCategories)) {
						return TRUE;
					}
				}
			}
		}

		$excludeProductsAuthor = $this->get_option('excludeProductsAuthor');
		$excludeProductsAuthorRevert = $this->get_option('excludeProductsAuthorRevert');
		if(!empty($excludeProductsAuthor) && !empty($excludeProductsAuthor)) {
			$post = get_post($productId);
			if($excludeProductsAuthorRevert) {
				if(!in_array($post->post_author, $excludeProductsAuthor)) {
					return TRUE;
				}
			} else {
				if(in_array($post->post_author, $excludeProductsAuthor)) {
					return TRUE;
				}
			}
		}

		$addToCompare = $this->get_option('addToCompareText');

		$url = "#";
		$pageId = $this->get_option('displayButtonPage');
		if(!empty($pageId)) {
			$url = get_permalink($pageId) . '?compare=' . $product->get_id();
		}

		$html = '<div id="belo_baba" style="display:flex;justify-content: center;order:4;border-top: solid 1px black;margin-top: 10px;margin-bottom: 10px;padding-top: 10px;position: relative;"><label class="container-checkbox"> <input style=" margin-right: 10px; min-width: 19px; min-height: 19px; " type="checkbox" href="' .  $url . '" class="button add-to-compare-button btn button btn-default theme-button theme-btn" data-product-id="' . $product->get_id() . '" rel="nofollow"><span class="add-to-compare-text">' . $addToCompare . '</span></input><span class="checkmark"></span> </label></div>';
		
		echo $html;
	}

	public function compare_button_shortcode($atts)
	{
		$args = shortcode_atts( array(
	        'product' => '',
	    ), $atts );

		$productId = absint($args['product']);

		if(empty($productId)) {
			global $product;
		} else {
			 $product = wc_get_product($productId);
		}	   

		if(!is_object($product)) {
			return false;
		}

		$excludeProductCategories = $this->get_option('excludeProductCategories');
		$excludeProductCategoriesRevert = $this->get_option('excludeProductCategoriesRevert');
		$terms = get_the_terms( $productId, 'product_cat' );
		if(!empty($terms)) {
			foreach ($terms as $term) {
				if($excludeProductCategoriesRevert) {
					if(!in_array($term->term_id, $excludeProductCategories)) {
						return TRUE;
					}
				} else {
					if(in_array($term->term_id, $excludeProductCategories)) {
						return TRUE;
					}
				}
			}
		}

		$excludeProductsAuthor = $this->get_option('excludeProductsAuthor');
		$excludeProductsAuthorRevert = $this->get_option('excludeProductsAuthorRevert');
		if(!empty($excludeProductsAuthor)) {
			$post = get_post($productId);
			if($excludeProductsAuthorRevert) {
				if(!in_array($post->post_author, $excludeProductsAuthor)) {
					return TRUE;
				}
			} else {
				if(in_array($post->post_author, $excludeProductsAuthor)) {
					return TRUE;
				}
			}
		}

		$addToCompare = $this->get_option('addToCompareText');

		$url = "#";
		$pageId = $this->get_option('displayButtonPage');
		if(!empty($pageId)) {
			$url = get_permalink($pageId) . '?compare=' . $product->get_id();
		}

		$html = '<a href="' .  $url . '" class="button add-to-compare-button btn button btn-default theme-button theme-btn" data-product-id="' . $product->get_id() . '" rel="nofollow">' . $addToCompare . '</a>';
		
		return $html;
	}

	/**
	 * Compare Btn on single product page
	 * @author Daniel Barenkamp
	 * @version 1.0.0
	 * @since   1.0.0
	 * @link    https://#
	 * @return  [type]                       [description]
	 */
	public function maybe_show_compare_button_on_single_product()
	{
		if (!$this->get_option('enable')) {
			return false;
		}

		global $product;

		if(!is_product()) {
			return;
		}

		// Custom Button
		if(!$this->get_option('displayButtonOnProductPage')) {
			return false;
		}

		$showAddToCompare = apply_filters('woocommerce_better_compare_show_add_to_compare_button', true, $product);
		if(!$showAddToCompare) {
			return false;
		}

        $buttonPosition = $this->get_option('displayButtonOnProductPagePosition');
        !empty($buttonPosition) ? $buttonPosition = $buttonPosition : $buttonPosition = 'woocommerce_single_product_summary';
        $displayButtonOnProductPagePriority = $this->get_option('displayButtonOnProductPagePriority');

		add_action( $buttonPosition, array($this,'show_compare_button_on_single_product'), $displayButtonOnProductPagePriority );
	}

	public function show_compare_button_on_single_product()
	{
		global $product;

		if(!$product) {
			return;
		}

		$productId = $product->get_id();
		$addToCompare = $this->get_option('addToCompareText');

		$url = "#";
		$pageId = $this->get_option('displayButtonPage');
		if(!empty($pageId)) {
			$url = get_permalink($pageId) . '?compare=' . $product->get_id();
		}

		$excludeProductCategories = $this->get_option('excludeProductCategories');
		$excludeProductCategoriesRevert = $this->get_option('excludeProductCategoriesRevert');
		
		if(!empty($excludeProductCategories)) {
			$terms = get_the_terms( $productId, 'product_cat' );
			if(!empty($terms)) {

				foreach ($terms as $term) {
					if($excludeProductCategoriesRevert) {
						if(!in_array($term->term_id, $excludeProductCategories)) {
							return TRUE;
						}
					} else {
						if(in_array($term->term_id, $excludeProductCategories)) {
							return TRUE;
						}
					}
				}
			}
		}

		$excludeProductsAuthor = $this->get_option('excludeProductsAuthor');
		$excludeProductsAuthorRevert = $this->get_option('excludeProductsAuthorRevert');
		if(!empty($excludeProductsAuthor)) {
			$post = get_post($productId);
			if($excludeProductsAuthorRevert) {
				if(!in_array($post->post_author, $excludeProductsAuthor)) {
					return TRUE;
				}
			} else {
				if(in_array($post->post_author, $excludeProductsAuthor)) {
					return TRUE;
				}
			}
		}

		$html = '<a href="' . $url . '" class="button add-to-compare-button btn button btn-default theme-button theme-btn" data-product-id="' . $productId . '" rel="nofollow">' . $addToCompare . '</a>';
		
		echo $html;
	}

	public function compare_products_shortcode($atts)
	{
		$args = shortcode_atts( array(
	        'products' => '',
	        'only_first' => 'no',
	        'slidestoshow' => $this->get_option('singleCompareTableSliderSlidesToShow'),
	    ), $atts );

	    $products = $args['products'];
	    $only_first = $args['only_first'];
	    $sliderSlidesToShow = intval($args['slidestoshow']);

	    if(empty($products)) {
	    	
	    	if(isset($_GET['compare']) && !empty($_GET['compare'])) {
	    		$products = explode(',', $_GET['compare']);
	    	} elseif(isset($_COOKIE['compare_products_products']) && !empty($_COOKIE['compare_products_products'])) {
	    		$products = json_decode(stripslashes($_COOKIE['compare_products_products']), true);
    		}
	    } else {
	    	$products = explode(',', $products);
	    }

	    if(!isset($products)|| empty($products)) {
	    	return __('No Products defined.', 'woocommerce-better-compare');
	    }

	    if($only_first == "yes") {
	    	$products = array(
    			end($products)
			);
	    }

		$sliderSlidesToScroll = $this->get_option('singleCompareTableSliderSlidesToScroll');
		$sliderDots = $this->get_option('singleCompareTableSliderDots');
		$sliderArrows = $this->get_option('singleCompareTableSliderArrows');		$sliderInfinite = $this->get_option('singleCompareTableSliderInfinite');

		$slickData = array(
			'slidesToShow' => $sliderSlidesToShow,
			'slidesToScroll' => intval($sliderSlidesToScroll),
			'dots' => $sliderDots == "1" ? true : false,
			'arrows' => $sliderArrows == "1" ? true : false,
			'infinite' => $sliderInfinite == "1" ? true : false,
			'responsive' => array(
				array(
					'breakpoint' => 600,
					'settings' => array(
						'slidesToShow' => 2,
						'slidesToScroll' => 1
					)
				),
				array(
					'breakpoint' => 480,
					'settings' => array(
						'slidesToShow' => 1,
						'slidesToScroll' => 1
					)
				),
			),
		);

		$data_to_compare = $this->get_data_to_compare($products);

	 	return $this->get_shortcode_compare_table($data_to_compare, $slickData);
	}

	public function compare_bar()
	{
		if (!$this->get_option('enable')) {
			return false;
		}

		if($this->get_option('compareBarOnlyProductCategory') && !is_product() && !is_product_category() && !is_shop() && !is_account_page()) {
			return false;
		}

		$html = "";
		$html .= $this->get_compare_table();

		if (!$this->get_option('compareBar')) {
			echo $html;
			return false;
		}

		$compareNowText = $this->get_option('textsCompareNow');
		$clearAllText = $this->get_option('textsClearAll');
		$compareProductsText = $this->get_option('textsCompareProducts');
		$saveComparisonText = $this->get_option('textsSaveComparison');
		$shareComparisonText = $this->get_option('textsShareComparison');

		$maxProducts = $this->get_option('maxProducts');
		$maxProductsMobile = $this->get_option('maxProductsMobile');
		if($maxProductsMobile > $maxProducts) {
			$maxProducts = $maxProductsMobile;
		}

		$compareBarPage = $this->get_option('compareBarPage');
		$compareBarLayout = $this->get_option('compareBarLayout');
		$compareBarHidePlaceholder = $this->get_option('compareBarHidePlaceholder');
		if(!empty($compareBarPage)) {
			$compareBarPage = get_permalink($compareBarPage);
		} else {
			$compareBarPage = '#';
		}

		if($this->get_option('compareBarHideUntilProductAdded')) {
			$compareBarLayout .= ' woocommerce-compare-bar-hidden';
		}

		$html .= '<div id="woocommerce-compare-bar" class="woocommerce-compare-bar woocommerce-compare-bar-layout-' . $compareBarLayout . ' woocommerce-compare-bar-hide-placeholder-' . $compareBarHidePlaceholder . '">';

			$html .= '<div id="woocommerce-compare-bar-open-close-container" class="woocommerce-compare-bar-open-close-container">';
				$html .= '<a href="#" id="woocommerce-compare-bar-open-close" class="woocommerce-compare-bar-open-close">' . $compareProductsText . ' <i class="fa fa-angle-double-up"></i></a>';
			$html .= '</div>';

			$html .= '<div id="woocommerce-compare-bar-items" class="woocommerce-compare-bar-items" style="display: none;">';

				for ($i=0; $i < $maxProducts; $i++) { 
					$html .= $this->get_single_item();
				}

				// Compare Bar Actions
				$html .= '<div id="woocommerce-compare-bar-actions" class="woocommerce-compare-bar-actions">';

					$html .= '<a href="' . $compareBarPage . '" id="woocommerce-compare-bar-action-compare" class="woocommerce-compare-table-action-compare">' . $compareNowText . ' <i class="fa fa-chevron-right"></i></a>';

					if($this->get_option('myComparisons') && is_user_logged_in()) {
						$html .= '<a href="#" id="woocommerce-compare-bar-action-save-comparison" class="woocommerce-compare-table-action-save-comparison"><i class="fa fa-save"></i> ' . $saveComparisonText . '</a>';
					}

					$html .= '<a href="#" id="woocommerce-compare-bar-action-clear" class="woocommerce-compare-bar-action-clear clear-all-compared-products">' . $clearAllText . ' <i class="fa fa-times"></i></a>';

					if($this->get_option('shareComparison')) {
						$html .= $this->get_share_icons();
						// $html .= '<a href="#" id="woocommerce-compare-bar-action-share-comparison" class="woocommerce-compare-table-action-share-comparison">' . $shareComparisonText . ' <i class="fa fa-share"></i></a>';
					}


				$html .= '</div>';

			$html .= '</div>';

		$html .= '</div>';

		echo $html;
	}

	protected function get_single_item()
	{
		$html = "";
		$html .= '<div class="woocommerce-compare-bar-item-container woocommerce-compare-bar-item-placeholder">';
			$html .= '<div class="woocommerce-compare-bar-item">';

			$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	protected function get_shortcode_compare_table($productData = array(), $slickData)
	{
		global $woocommerce_group_attributes_options;
		$translations = $this->get_translations();

		$hide_similarities = __('Hide Similarities', 'woocommerce-better-compare');
		$highlight_differences = __('Highlight Differences', 'woocommerce-better-compare');
		$notAvailableText = $this->get_option('notAvailableText');

		$showAllColumns = $this->get_option('singleCompareTableAlwaysShowAllColumns');

		$showAttrNameInColumn = $this->get_option('singleCompareTableShowAttrNameInColumn');
		$showAttrNameInColumnCSS = $showAttrNameInColumn ? ' has-keys-column' : '';

		$class3 = "";

		$html = "";
		$html .= '<div id="woocommerce-single-compare-table-container" class="woocommerce-single-compare-table-container ' . $showAttrNameInColumnCSS . '">';


			if($this->get_option('singleCompareTableHideSimilarities')) {
				$html .= '<label><input type="checkbox" class="woocommerce-compare-table-hide-similarities" name="hide_similarities" value="1">' . $hide_similarities . '</label>';
			}
			if($this->get_option('singleCompareTableHighlightDifferences')) {
				$html .= '<label><input type="checkbox" class="woocommerce-compare-table-highlight-differences" name="highlight_differences" value="1">' . $highlight_differences . '</label> ';
			}		
			
			$first = true;
			$firstProduct = true;
			$count = 0;
			$totalProducts = count($productData);
			if($totalProducts <= $slickData['slidesToShow']) {
				$neededProducts = $slickData['slidesToShow'] - $totalProducts;
			}

			foreach ($productData as $productId => $single_product_data) {

				$product = wc_get_product($productId);

				// Attribute in First Column
				if($showAttrNameInColumn && $first) {
					$html .= '<div class="single-product-compare-keys">';

					foreach ($single_product_data as $dataKey => $dataValue) {

						$accordion = "";
						$attributesToHideString = "";
						$class = 'single-product-compare-key-column single-product-compare-value-' . $dataKey . ' single-product-compare-value-th';

						$attribute_group_keys = '';

						if($this->get_option('enableGroupedAttributes')) {

							if(isset($woocommerce_group_attributes_options['enableAccordion']) && $woocommerce_group_attributes_options['enableAccordion'] = "1" && substr($dataKey, 0, 5) == "group") {

								if($this->get_option('enableGroupedAttributesResetCounts')) {
									$count = 0;
								}
								$attributesToHide = array();
								$attributeGroupId =  substr($dataKey, 6);

								// WPML Support
								if(function_exists('icl_object_id')) {
									$attributeGroupId = icl_object_id($attributeGroupId, 'attribute_group', false, ICL_LANGUAGE_CODE);
								}

								$attributesInGroup = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_attributes');
								if(is_array($attributesInGroup[0])) {
									$attributesInGroup = $attributesInGroup[0];
								}
								
								if(!empty($attributesInGroup)) {

									$count2 = 1;
									foreach ($attributesInGroup as $attributeInGroup) {
										$attributeInGroup = wc_get_attribute($attributeInGroup);
										$attributeInGroupSlug = substr($attributeInGroup->slug, 3);
										
										$attributesToHide[] = $attributeInGroupSlug;

									}
								}

								if(!empty($attributesToHide)) {
									$attributesToHideString = "data-hide-attributes='" . json_encode($attributesToHide) . "'";
									
									$class .= ' woocommerce-better-compare-accordion-title';
									$accordion_open = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_accordion_compare_open', true);
									if($accordion_open == "1"){ 
										$class .= ' woocommerce-better-compare-accordion-title-open';
										$accordion = '<i class="fa fa-minus woocommerce-group-attributes-icon"></i>';
									} else {
										$accordion = '<i class="fa fa-plus woocommerce-group-attributes-icon"></i>';	
									}
								}
							}
						}

						$class .= ' ' . (++$count%2 ? "oddd" : "even");

						$html .= '<div class="' . $class . '" ' . $attributesToHideString . ' data-group-id="' . $attributeGroupId .'"><b>' . $accordion . $translations[$dataKey] . '</b></div>';	

						$html .= $attribute_group_keys;

					}
					$html .= '</div>';
					
				}

				if($first) {
					$html .= '<div id="woocommerce-single-compare-table" class="woocommerce-single-compare-table woocommerce-single-compare-table-slick" data-slick=' . json_encode($slickData) . '>';
					$first = false;
				}

				$html .= '<div class="single-product-compare-column single-product-compare-column-' . $productId . '">';
				$count = 0;
				foreach ($single_product_data as $dataKey => $dataValue) {

					if($showAttrNameInColumn) {
						if($this->get_option('enableGroupedAttributesResetCounts')) {
							if(substr($dataKey, 0, 5) == "group") {
								$count = 0;
							}
						}
						$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '">' . $dataValue . '</div>';	
					} else {

						if(isset($woocommerce_group_attributes_options['enableAccordion']) && $woocommerce_group_attributes_options['enableAccordion'] = "1" && substr($dataKey, 0, 5) == "group") {

							if($this->get_option('enableGroupedAttributesResetCounts')) {
								$count = 0;
							}

							$attributesToHide = array();
							$attributeGroupId =  substr($dataKey, 6);
							$attributesInGroup = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_attributes');
							if(is_array($attributesInGroup[0])) {
								$attributesInGroup = $attributesInGroup[0];
							}
							
							if(!empty($attributesInGroup)) {
								foreach ($attributesInGroup as $attributeInGroup) {
									$attributeInGroup = wc_get_attribute($attributeInGroup);
									$attributeInGroupSlug = substr($attributeInGroup->slug, 3);
									
									$attributesToHide[] = $attributeInGroupSlug;

								}
							}

							if(!empty($attributesToHide)) {
								$attributesToHideString = "data-hide-attributes='" . json_encode($attributesToHide) . "'";
								
								$class3 .= ' woocommerce-better-compare-accordion-title';
								$accordion_open = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_accordion_compare_open', true);
								if($accordion_open == "1"){ 
									$class3 .= ' woocommerce-better-compare-accordion-title-open';
									$accordion = '<i class="fa fa-minus woocommerce-group-attributes-icon"></i>';
								} else {
									$accordion = '<i class="fa fa-plus woocommerce-group-attributes-icon"></i>';	
								}
							}

							if($firstProduct) { 
								$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey .  $class3 . '" ' . $attributesToHideString . ' data-group-id="' . $attributeGroupId .'">' . $accordion . $translations[$dataKey] . '</div>';	
							} else {
								$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey .  $class3 . '" ' . $attributesToHideString . ' data-group-id="' . $attributeGroupId .'">&nbsp;</div>';	
							}

						} else {
							$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '"><span class="single-product-compare-key">' . $translations[$dataKey] . '</span>' . $dataValue . '</div>';	
						}
					}										
				}

				$html .= '</div>';
				$firstProduct = false;
			}
			
			if($neededProducts > 0 && $showAllColumns) {
				for ($i=0; $i < $neededProducts; $i++) { 
					$html .= '<div class="single-product-compare-column single-product-compare-column-live">';
						$html .= '<div class="single-product-compare-value single-product-compare-value-ac">';
							if(!$showAttrNameInColumn) {
								$html .= '<span class="single-product-compare-key">' . __('Compare with', 'woocommerce-better-compare') . '</span>';
							} 	
							$html .= $this->autocomplete_shortcode(true);
						$html .= '</div>';
					$html .= '</div>';
				}
			}

			$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	protected function get_compare_table()
	{
		$hide_similarities = __('Hide Similarities', 'woocommerce-better-compare');
		$highlight_differences = __('Highlight Differences', 'woocommerce-better-compare');

		$html = "";
		$html .= '<div id="woocommerce-compare-table-container" class="woocommerce-compare-table-container compare-table-grid" style="display: none;">';

			if($this->get_option('hideSimilarities')) {
				$html .= '<label><input type="checkbox" class="woocommerce-compare-table-hide-similarities" name="hide_similarities" value="1">' . $hide_similarities . '</label>';
			}
			if($this->get_option('highlightDifferences')) {
				$html .= '<label><input type="checkbox" class="woocommerce-compare-table-highlight-differences" name="highlight_differences" value="1">' . $highlight_differences . '</label> ';
			}

			$html .= '<a href="#" id="woocommerce-compare-table-close" class="woocommerce-compare-table-close"><i class="fa fa-times"></i></a>';
			$html .= '<div id="woocommerce-compare-table" class="woocommerce-compare-table">';

			$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

    public function get_single_product()
    {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
        	header('HTTP/1.1 400 No AJAX call', true, 400);
            die();
        }

        if (!isset($_POST['product'])) {
            header('HTTP/1.1 400 No product ID', true, 400);
            die();
        }

        $productId = intval($_POST['product']);
        $originalProduct = wc_get_product($productId);
        if(!$originalProduct) {
        	header('HTTP/1.1 400 No Product found', true, 400);
        	die();
        }

        if($this->get_option('enableCategoryRestriction') && isset($_COOKIE['compare_products_products']) && !empty($_COOKIE['compare_products_products'])) {

        	$productCategoriesInCompareBar = array();
    		$productsInCompareBar = json_decode(stripslashes($_COOKIE['compare_products_products']), true);

    		foreach ($productsInCompareBar as $productInCompareBar) {

    			if($productInCompareBar == $productId) {
    				continue;
    			}

		        $productInCompareBar = wc_get_product($productInCompareBar);
        		if(!$productInCompareBar) {
        			continue;
    			}
    			$productCategoriesInCompareBar = array_merge($productCategoriesInCompareBar, $this->getProductCategories($productInCompareBar));
    		}

    		if(!empty($productCategoriesInCompareBar)) {

	        	$productCategories = $this->getProductCategories($originalProduct);
				$matchingCategories = array_intersect($productCategoriesInCompareBar, $productCategories);
				if(empty($matchingCategories)) {
					$return = array(
						'status' => 'error',
						'message' => $this->get_option('enableCategoryRestrictionText'),
					);
					echo json_encode($return, JSON_FORCE_OBJECT);
					die();
				}
			}
        }

        $imageSize = $this->get_option('compareBarImageSize');
        if(!empty($imageSize)) {
        	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $productId ), $imageSize );
        } else {
        	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $productId ), 'full' );
        }

        $product 		= new stdClass();
        $product->status = 'success';
        $product->img	= (isset($img[0]) && !empty($img[0])) ?  $img[0] : wc_placeholder_img_src();
        $product->title	= get_the_title($productId);
        $product->ID	= $productId;
        $product->url	= get_permalink($productId);

        echo json_encode($product, JSON_FORCE_OBJECT);
        die();
	}

	public function get_all_products()
	{
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
        	header('HTTP/1.1 400 No AJAX call', true, 400);
            die();
        }

        if (!isset($_POST['products'])) {
            header('HTTP/1.1 400 No products found', true, 400);
            die();
        }

		$products = array_filter($_POST['products']);

		$productData = array();
		if(empty($products)) {
			echo json_encode($productData, JSON_FORCE_OBJECT);
			die();
		}

		$dataToCompare = $this->get_option('dataToCompare');
		$notAvailableText = $this->get_option('notAvailableText');
		$dataToCompare = $dataToCompare['enabled'];
		unset($dataToCompare['placebo']);

		if(isset($dataToCompare['ac'])) {
			unset($dataToCompare['ac']);
		}
		
		$foundOneAttributeInGroupValue = array();
    	// $current_lang = apply_filters('wpml_current_language', null);

		foreach ($dataToCompare as $key => $value) {
			foreach ($products as $productId) {

				$outputKey = $key;
				if(strpos($key, 'attr') === false && strpos($key, 'tx') === false && strpos($key, 'group') === false && strpos($key, 'mt') === false) {
					$outputKey = substr($key, 0, 2);
				}

				$productId = intval($productId);
				$product = wc_get_product($productId);
				if(!$product) {
					continue;
				}


				$data = $this->get_product_data($product, $key);
				if(substr($outputKey, 0, 5) == "group" && $this->get_option('enableGroupedAttributes') && empty($data)) {
					$productData[$outputKey][$productId] = '';
				} else {
					$data = apply_filters( 'woocommerce_better_compare_single_product_data', $data, $product, $key );
					$productData[$outputKey][$productId] = !empty($data) ? $data : $notAvailableText;
				}

				if($this->get_option('enableGroupedAttributes') && $this->get_option('enableGroupedAttributesGetAttributeAutomatically') && substr($outputKey, 0, 5) == "group") {

					$attributesToHide = array();
					$attributeGroupId =  substr($outputKey, 6);

					// WPML Support
					if(function_exists('icl_object_id')) {
						$attributeGroupId = icl_object_id($attributeGroupId, 'attribute_group', false, ICL_LANGUAGE_CODE);
					}

					$attributesInGroup = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_attributes');
					if(is_array($attributesInGroup[0])) {
						$attributesInGroup = $attributesInGroup[0];
					}
					
					if(!empty($attributesInGroup)) {
						
						foreach ($attributesInGroup as $attributeInGroup) {
							$attributeInGroup = wc_get_attribute($attributeInGroup);
							$attributeInGroupSlug = substr($attributeInGroup->slug, 3);

							$attributeInGroupSlugToGetData = 'attr-' . $attributeInGroupSlug;
							
							$attributeInGroupData = $this->get_product_data($product, $attributeInGroupSlugToGetData);
							if(!empty($attributeInGroupData)) {
								$foundOneAttributeInGroupValue[$attributeGroupId] = true;;
							}

							$productData[$attributeInGroupSlugToGetData][$productId] = !empty($attributeInGroupData) ? $attributeInGroupData : $notAvailableText;
						}

						if(!isset($foundOneAttributeInGroupValue[$attributeGroupId])) {
							unset($productData[$outputKey]);	
						}

					} else {
						unset($productData[$outputKey]);
					}
				}

			}
		}

	 	foreach ($productData as $key => $value) {
	 		
	 		$errorCheck = reset($value);
	 		if(is_wp_error($errorCheck)) {
	 			continue;
	 		}

	 		if (count(array_unique($value)) === 1 && end($value) === $notAvailableText) {
	 			unset($productData[$key]);
	 		}
	 	}


		echo json_encode($productData, JSON_FORCE_OBJECT);
		die();
	}

	private function filter_array(&$array) 
	{
	    foreach ( $array as $key => $item ) {
	        is_array ( $item ) && $array [$key] = $this->filter_array ( $item );
	        if (empty ( $array [$key] ))
	            unset ( $array [$key] );
	    }
	    return $array;
	}

	protected function get_product_data($prod, $key)
	{
		global $product;

		if(!is_object($prod)) {
			return false;
		}

		$product = $prod;

		$data = "";
		$productId = $product->get_id();

		// Image
		if($key == "im") {
	        $imageSize = $this->get_option('dataToCompareImageSize');
	        $link = get_permalink($productId);
	        if(!empty($imageSize)) {
	        	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $productId ), $imageSize );
	        } else {
	        	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $productId ), 'full' );
	        }
			$data =  (isset($img[0]) && !empty($img[0])) ?  $img[0] : wc_placeholder_img_src();
			$data = '<a href="' . $link . '"><img class="compare-table-responsive-image" src="' . $data . '" /></a>';

		// Title
		} elseif($key == "ti") {

			$data = get_the_title($productId);

			if($this->get_option('titleLinkToProduct')) {
				$link = get_permalink($productId);
				$data = '<a class="compare-table-title-link" href="' . $link . '">' . $data . '</a>';
			}

			if($this->get_option('titleShowRemove')) {
				$data .= ' <a href="#" data-product-id="' . $productId . '" class="woocommerce-compare-single-item-remove"><i class="fa fa-times"></i></a>';
			}

		// Ratings
		} elseif($key == "re") {
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();
			$data = wc_get_rating_html($average, $rating_count);

		// Variations
		} elseif($key == "va") {
			if($product->is_type( 'variable' )) {
				$aval_variations = $product->get_available_variations();
				$variation_html = "";
				if(!empty($aval_variations)) {
					foreach ($aval_variations as $variation) {
						$variation_attributes = $variation['attributes'];
						foreach ($variation_attributes as $variation_attribute_key => $variation_attribute) {;
	                        if (strpos($variation_attribute_key, '_pa_')){ // variation is a pre-definted attribute
	                            $variation_attribute_key = substr($variation_attribute_key, 10);
	                            $attr = get_term_by('slug', $variation_attribute, $variation_attribute_key);
	                            $variation_attribute = $attr->name;
	                        } else { // variation is a custom attribute
	                            $attr = maybe_unserialize( get_post_meta( $post->ID, '_product_attributes' ) );
	                            
	                            $attr = get_term_by('slug', $variation_attribute, $variation_attribute_key);
	                            $variation_attribute = $attr->name;
	                        }
	                        $attr_label = wc_attribute_label($attr->taxonomy);
	                        $variation_html .= $attr_label . ': ' . $variation_attribute . '<br>';
                        }
					}
					$data = $variation_html;
				}
			}

		// Price
		} elseif($key == "pr") {
			$data = $product->get_price_html();

		// SKU
		} elseif($key == "sk") {
			$data = $product->get_sku();

		// Excerpt (Short Description)
		} elseif($key == "ex") {
			$data = $product->get_short_description();
			if($this->get_option('excerptStripShortcodes')) {
				$data = preg_replace("/\[[^\]]+\]/", '', $data);
			} else {
				$data = do_shortcode($data);
			}
		// Description
		} elseif($key == "de") {
			$data = $product->get_description();
			if($this->get_option('excerptStripShortcodes')) {
				$data = preg_replace("/\[[^\]]+\]/", '', $data);
			} else {
				$data = do_shortcode($data);
			}
		// Description
		} elseif($key == "ac") {
			$data = $this->autocomplete_shortcode(true);
		// Dimensions
		} elseif($key == "di") {
			$data = wc_format_dimensions($product->get_dimensions(false));

		// Weight
		} elseif($key == "we") {
			if(!empty($product->get_weight())) {
				$data = $product->get_weight() . get_option('woocommerce_weight_unit');
			}

		// Add to Cart
		} elseif($key == "ca") {
 			ob_start();
			do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
	        $output_string = ob_get_contents();
	        ob_end_clean();
			$data = $output_string;

		// Tags
		} elseif($key == "ta") {
			$data = $product->get_tags();

		// Categories
		} elseif($key == "ct") {
			$data = $product->get_categories();


		// Read More
		} elseif($key == "rm") {
			$url = get_permalink($productId);
			$data = '<a href="' . $url . '" class="woocommerce-better-compare-read-more btn button btn-default theme-button theme-btn">' . __('Read More', 'woocommerce-better-compare') . '</a>';
		
		// Get Stock Status
		} elseif($key == "st") {
			$data = $product->get_stock_status();

		// Get Attributes
		} elseif(strpos($key, 'attr') !== false) {

			$attribute_slug = substr($key, 5);
			$attribute_value = $product->get_attribute($attribute_slug);
			if(!empty($attribute_value)) {
				$data = $attribute_value;
			}
		// Get Attribute Group Name
		} elseif(strpos($key, 'group') !== false) {

			$enableGroupedAttributes = $this->get_option('enableGroupedAttributes');

			$group_id = substr($key, 6);
			$attr_found = array();

			$attributesInGroup = get_post_meta($group_id, 'woocommerce_group_attributes_attributes');

			if(is_array($attributesInGroup[0])) {
				$attributesInGroup = $attributesInGroup[0];
			}

			global $woocommerce_group_attributes_options;	
			if(isset($woocommerce_group_attributes_options['enableAttributeGroupCategories']) && $woocommerce_group_attributes_options['enableAttributeGroupCategories'] == "1" ) {

				$terms = get_the_terms( $product->get_id(), 'product_cat' );
				$attributeGroupsProductCategories = get_the_terms( $group_id, 'product_cat' );

				if(!empty($attributeGroupsProductCategories) && !empty($terms)) {
					
					$attributeGroupsProductCategoriesFlat = array();
					foreach ($attributeGroupsProductCategories as $attributeGroupsProductCategory) {
					    $attributeGroupsProductCategoriesFlat[] = $attributeGroupsProductCategory->term_id;
					}

					$productCategories = array();
					foreach ($terms as $term) {
					    $productCategories[] = $term->term_id;
					}
					$check = array_intersect($productCategories, $attributeGroupsProductCategoriesFlat);
					if(empty($check)) {
						return;
					}
				}
			}

			$product_attributes = $product->get_attributes();
			if(!empty($product_attributes) && !empty($attributesInGroup) && $enableGroupedAttributes) {
				foreach ($product_attributes as $product_attribute) {
					$attr_id = $product_attribute->get_id();
					if(in_array($attr_id, $attributesInGroup)){
						$data = '&nbsp;';
						break;	
					}
				}
			}
			
		// Taxonomies
		} elseif(strpos($key, 'tx') !== false) {
			$tax_id = substr($key, -1);
			$taxonomy_name = $this->get_option('dataToCompareTaxonomy' . $tax_id);
			if($taxonomy_name) {
				$data = get_the_term_list($productId, $taxonomy_name, '', ', ');

				if($this->get_option('dataToCompareTaxonomyNoLinks'. $tax_id)) {
					$data = strip_tags($data);
				}
			}
		// Custom Meta fields
		} elseif(strpos($key, 'mt') !== false) {
			$tax_id = substr($key, -1);
			$meta_key = $this->get_option('dataToCompareMeta' . $tax_id);
			if($meta_key) {
				$data = get_post_meta($productId, $meta_key, true);
			}
		}
		wp_reset_postdata();
		return $data;
	}

	protected function get_translations()
	{
		$translations = array(
			'im' => __('', 'woocommerce-better-compare'),
			'ti' => __('Title', 'woocommerce-better-compare'),
			're' => __('Reviews', 'woocommerce-better-compare'),
			'va' => __('Variations', 'woocommerce-better-compare'),
			'pr' => __('Price', 'woocommerce-better-compare'),
			'sk' => __('Sku', 'woocommerce-better-compare'),
			'ac' => __('Compare with', 'woocommerce-better-compare'),
			'ex' => __('Excerpt', 'woocommerce-better-compare'),
			'de' => __('Description', 'woocommerce-better-compare'),
			'di' => __('Dimensions', 'woocommerce-better-compare'),
			'we' => __('Weight', 'woocommerce-better-compare'),
			'st' => __('Stock', 'woocommerce-better-compare'),
			'ta' => __('Tags', 'woocommerce-better-compare'),
			'ct' => __('Categories', 'woocommerce-better-compare'),
			'ca' => __('', 'woocommerce-better-compare'),
			'rm' => __('', 'woocommerce-better-compare'),
            'tx1' => $this->get_option('dataToCompareTaxonomyName1'),
            'tx2' => $this->get_option('dataToCompareTaxonomyName2'),
            'tx3' => $this->get_option('dataToCompareTaxonomyName3'),
            'tx4' => $this->get_option('dataToCompareTaxonomyName4'),
            'tx5' => $this->get_option('dataToCompareTaxonomyName5'),
            'tx6' => $this->get_option('dataToCompareTaxonomyName6'),
            'tx7' => $this->get_option('dataToCompareTaxonomyName7'),
            'tx8' => $this->get_option('dataToCompareTaxonomyName8'),
            'tx9' => $this->get_option('dataToCompareTaxonomyName9'),
            'tx10' => $this->get_option('dataToCompareTaxonomyName10'),
            'mt1' => $this->get_option('dataToCompareMetaName1'),
            'mt2' => $this->get_option('dataToCompareMetaName2'),
            'mt3' => $this->get_option('dataToCompareMetaName3'),
            'mt4' => $this->get_option('dataToCompareMetaName4'),
            'mt5' => $this->get_option('dataToCompareMetaName5'),
            'mt6' => $this->get_option('dataToCompareMetaName6'),
            'mt7' => $this->get_option('dataToCompareMetaName7'),
            'mt8' => $this->get_option('dataToCompareMetaName8'),
            'mt9' => $this->get_option('dataToCompareMetaName9'),
            'mt10' => $this->get_option('dataToCompareMetaName10'),
			'add' => '<span class="add-to-compare-text">' . $this->get_option('addToCompareText') . '</span>',
			'max' => __('Max products reached', 'woocommerce-better-compare'),
			'remove' => '<span class="remove-from-compare-text">' . $this->get_option('removeFromCompareText') . '</span>',
			'difference' => __('Show differences only', 'woocommerce-better-compare'),
		);

		$atts = wc_get_attribute_taxonomies();
	    if(!empty($atts)) {
	        foreach ($atts as $value) {

	        	$attributeName = $value->attribute_name;
	        	$attributeValue = $value->attribute_label;

				if(function_exists('icl_object_id')) {
					$attributeValue = wc_attribute_label( 'pa_' . $value->attribute_name);
				}	        	

	            $translations['attr-' . $attributeName] = __($attributeValue);
	        }
	    }

	    // Attribute Groups
	    $args = array( 'posts_per_page' => -1, 'post_type' => 'attribute_group', 'post_status' => 'publish', 'orderby' => 'menu_order');
	    $attribute_groups = get_posts( $args );

	    if(!empty($attribute_groups)) {
	        foreach ($attribute_groups as $attribute_group) {

	        	$attribute_group_title = $attribute_group->post_title;

				// WPML Support
				if(function_exists('icl_object_id')) {
					$attributeGroupIdTranslated = icl_object_id($attribute_group->ID, 'attribute_group', false, ICL_LANGUAGE_CODE);
					if($attributeGroupIdTranslated != $attribute_group->ID) {
						$attribute_group_title = get_post($attributeGroupIdTranslated)->post_title;
					}
				}

	            $translations['group-' . $attribute_group->ID] = __($attribute_group_title);

	        }
	    }

		return $translations;
	}

	public function single_product_page() 
	{

		if (!$this->get_option('enable')) {
			return false;
		}

		$productPage = $this->get_option('displayProductPage');
		if($productPage) {
			$productPagePosition = $this->get_option('displayProductPagePosition');
			$productPagePriority = $this->get_option('displayProductPagePriority');
			add_action($productPagePosition, array($this, 'display_compare_products'), $productPagePriority);
		}
	}

	public function display_compare_products() 
	{
		global $product;
		
		if(!is_object($product)) {
			return;
		}

		$product_categories = $this->getProductCategories($product);

		if(empty($product_categories)) {
			// fallback to all categories ?? makes no sense.
			// $product_categories = $product->get_category_ids();
			// if(empty($product_categories)) {
				return;
			// }
		}

		$maxProducts = $this->get_option('displayProductPageMaxProducts');

	    $args = array(
		    'post_type'             => 'product',
		    'post_status'           => 'publish',
		    'ignore_sticky_posts'   => 1,
		    'posts_per_page'        => $maxProducts,
		    'post__not_in'          => array( $product->get_id() ),
		    'tax_query'             => array(
		        array(
		            'taxonomy'      => 'product_cat',
		            'field' 		=> 'term_id',
		            'terms'         =>  $product_categories, // 26,
		            'operator'      => 'IN'
		        ),
		        array(
		            'taxonomy'      => 'product_visibility',
		            'field'         => 'slug',
		            'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
		            'operator'      => 'NOT IN'
		        )
		    )
		);

	    $args = apply_filters('woocommerce_better_compare_single_product_compare_products_query_args', $args, $product);

		$products = new WP_Query($args);

		if(!isset($products->posts) || empty($products->posts)) {
			return;
		}
		
		$products = $products->posts;
		array_unshift($products, $product->get_id());

		$html = "";

		$title = $this->get_option('displayProductPageTitle');
		if(!empty($title)) {
			$html .= '<h2 class="woocommerce-compare-single-product-title">' . $title . '</h2>';
		}

		$text = $this->get_option('displayProductPageText');
		if(!empty($text)) {
			$html .= '<p class="woocommerce-compare-single-product-desc">' . $text . '</p>';
		}

		$sliderSlidesToShow = $this->get_option('displayProductPageSliderSlidesToShow');
		$sliderSlidesToScroll = $this->get_option('displayProductPageSliderSlidesToScroll');
		$sliderDots = $this->get_option('displayProductPageSliderDots');
		$sliderArrows = $this->get_option('displayProductPageSliderArrows');
		$sliderInfinite = $this->get_option('displayProductPageSliderInfinite');


		$slickData = array(
			'slidesToShow' => intval($sliderSlidesToShow),
			'slidesToScroll' => intval($sliderSlidesToScroll),
			'dots' => $sliderDots == "1" ? true : false,
			'arrows' => $sliderArrows == "1" ? true : false,
			'infinite' => $sliderInfinite == "1" ? true : false,
			'responsive' => array(
				array(
					'breakpoint' => 600,
					'settings' => array(
						'slidesToShow' => 2,
						'slidesToScroll' => 1
					)
				),
				array(
					'breakpoint' => 480,
					'settings' => array(
						'slidesToShow' => 2,
						'slidesToScroll' => 1
					)
				),
			),
		);

		$productData = $this->get_data_to_compare($products);

		$showAttrNameInColumn = $this->get_option('displayProductPageShowAttrNameInColumn');
		$showAttrNameInColumnCSS = $showAttrNameInColumn ? ' has-keys-column' : '';

		$html .= '<div class="single-product-compare ' . $showAttrNameInColumnCSS . '">';

		if(!empty($productData)) {

			$translations = $this->get_translations();
			
			$first = true;
			foreach ($productData as $productId => $single_product_data) {

				// Attribute in First Column
				if($showAttrNameInColumn && $first) {
					$html .= '<div class="single-product-compare-keys">';
					foreach ($single_product_data as $dataKey => $dataValue) {
						$html .= '<div class="single-product-compare-key-column ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '"><b>' . $translations[$dataKey] . '</b></div>';	
					}
					$html .= '</div>';
					
				}

				if($first == true) {
					$html .= '<div id="single-product-compare-products-slick" class="single-product-compare-products-slick" data-slick=' . json_encode($slickData) . '>';
					$html .= '<div class="single-product-compare-column single-product-compare-column-this-product single-product-compare-column-' . $productId . '">';
				} else {
					$html .= '<div class="single-product-compare-column single-product-compare-column-' . $productId . '">';
				}

				$count = 0;
				foreach ($single_product_data as $dataKey => $dataValue) {

					if($dataKey == "im" || $dataKey == "ti") {
						if($first == true && $dataKey == "ti") {
							$dataValue = '<b class="single-product-compare-current-product"> ' . __('Current Product: ') . '</b>' . $dataValue;
						}
						$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '">' . $dataValue . '</div>';
					} else {
						if($showAttrNameInColumn) {
							$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '">' . $dataValue . '</div>';	
						} else {
							$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '"><span class="single-product-compare-key">' . $translations[$dataKey] . '</span>' . $dataValue . '</div>';	
						}
					}
					
				}
				$html .= '</div>';
				$first = false;
			}
			$html .= '</div>';
		}

		$html .= '</div>';
		
		echo $html;
	}

	public function get_data_to_compare($products)
	{
		$productData = array();

		$translations = $this->get_translations();
		$dataToCompare = $this->get_option('displayProductPageDataToCompare');
		$notAvailableText = $this->get_option('notAvailableText');
		$dataToCompare = $dataToCompare['enabled'];
		unset($dataToCompare['placebo']);

		foreach ($products as $product) {

			if(is_object($product)) {
				$productId = $product->ID;
			} else {
				$productId = $product;
			}

			$productId = intval($productId);
			$product = wc_get_product($productId);

			foreach ($dataToCompare as $key => $value) {

				$outputKey = $key;
				if(strpos($key, 'attr') === false && strpos($key, 'tx') === false && strpos($key, 'group') === false && strpos($key, 'mt') === false) {
					$outputKey = substr($key, 0, 2);
				}
				
				$data = $this->get_product_data($product, $key);
				$data = apply_filters( 'woocommerce_better_compare_single_product_data', $data, $product, $key );

				$productData[$productId][$outputKey] = !empty($data) ? $data : $notAvailableText;

				if(substr($outputKey, 0, 5) == "group") {

					$attributeGroupId =  substr($outputKey, 6);

					// WPML Support
					if(function_exists('icl_object_id')) {
						$attributeGroupId = icl_object_id($attributeGroupId, 'attribute_group', false, ICL_LANGUAGE_CODE);
					}

					$terms = get_the_terms( $productId, 'product_cat' );

					if(!empty($terms)) {

						$productCategories = array();
						foreach ($terms as $term) {
						    $productCategories[] = $term->term_id;
						}

						$attributeGroupsProductCategories = get_the_terms( $attributeGroupId, 'product_cat' );
						if(!empty($attributeGroupsProductCategories)) {

							$attributeGroupsProductCategoriesFlat = array();
							foreach ($attributeGroupsProductCategories as $attributeGroupsProductCategory) {
							    $attributeGroupsProductCategoriesFlat[] = $attributeGroupsProductCategory->term_id;
							}

							$check = array_intersect($productCategories, $attributeGroupsProductCategoriesFlat);
							if(empty($check)) {
								unset($productData[$productId][$outputKey]);
								continue;
							}
						}
					}
				}

				if($this->get_option('enableGroupedAttributes') && $this->get_option('enableGroupedAttributesGetAttributeAutomatically') && substr($outputKey, 0, 5) == "group") {

					$attributeGroupId =  substr($outputKey, 6);

					// WPML Support
					if(function_exists('icl_object_id')) {
						$attributeGroupId = icl_object_id($attributeGroupId, 'attribute_group', false, ICL_LANGUAGE_CODE);
					}

					$attributesInGroup = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_attributes');
					if(is_array($attributesInGroup[0])) {
						$attributesInGroup = $attributesInGroup[0];
					}
					
					if(!empty($attributesInGroup)) {
						foreach ($attributesInGroup as $attributeInGroup) {
							$attributeInGroup = wc_get_attribute($attributeInGroup);
							$attributeInGroupSlug = substr($attributeInGroup->slug, 3);

							$attributeInGroupSlugToGetData = 'attr-' . $attributeInGroupSlug;
							
							$attributeInGroupData = $this->get_product_data($product, $attributeInGroupSlugToGetData);

							$productData[$productId][$attributeInGroupSlugToGetData] = !empty($attributeInGroupData) ? $attributeInGroupData : $notAvailableText;
							$dataToCompare[$attributeInGroupSlugToGetData] = $translations[$attributeInGroupSlugToGetData];
						}
					}
				}
			}
		}

// var_dump($productData);
// die();

		foreach ($dataToCompare as $key => $value) {
			$temp = array();
			foreach ($productData as $productId => $single_product_data_temp) {
				$temp[] = $single_product_data_temp[$key];
			}

	 		if (count(array_unique($temp)) === 1 && end($temp) === $notAvailableText) {
				foreach ($productData as $productId => $single_product_data_temp) {
					unset($productData[$productId][$key]);
				}
	 			
	 		}
		}


		return $productData;
	}

	public function autocomplete_shortcode($isColumn = false)
	{
		if($isColumn) {
			$class = "woocommerce-compare-autocomplete-results-is-column";
		}  else {
			$class = "";
		}

        $html = 
        '<div class="woocommerce-compare-autocomplete">' .
        	'<div class="woocommerce-compare-autocomplete-icon">' .
        		'<i class="fa fa-search"></i>' .
        	'</div>' .
    		'<div class="woocommerce-compare-autocomplete-input">' .
				'<input type="text" name="woocommerce-compare-autocomplete-field" class="woocommerce-compare-autocomplete-field" placeholder="' . __('Search by Product Name or SKU', 'woocommerce-better-compare') . '">' .
			'</div>' .
        
	        '<div class="woocommerce-compare-autocomplete-results ' . $class . '">' .

	        '</div>' .
	        '<div class="woocommerce-compare-autocomplete-message">' .
	        	__('Search for product or sku', 'woocommerce-better-compare') . 
	        '</div>' .
        '</div>';

        return $html;
	}


   	public function check_product()
   	{
   		$response = array(
   			'message' => __('No Product found ...', 'woocommerce-better-compare'),
   			'products' => array(),
   		);

   		if(!isset($_POST['skuOrProduct']) || empty($_POST['skuOrProduct'])) {
   			die(json_encode($response));
   		}

   		$skuOrProduct = $_POST['skuOrProduct'];

   		if(empty($skuOrProduct)) {
   			die(json_encode($response));
   		}

   		$bySKU = wc_get_product_id_by_sku($skuOrProduct);

   		if(!empty($bySKU)) {
   			$response['message'] = __('Product found!', 'woocommerce-better-compare');
   			$response['product'] = $bySKU;
   		} else {
   			if($this->get_option('popupUseSimpleSearch')) {
	   			$skuOrProduct = sanitize_title_for_query( $skuOrProduct );
		   		$byName = get_page_by_path($skuOrProduct, OBJECT, 'product' );

		   		if(!empty($byName)) {
		   			$response['message'] = __('Product found!', 'woocommerce-better-compare');
		   			$response['product'] = $byName->ID;
		   		}

	   		} else {
	   			$products = $this->search_product_by_name($skuOrProduct);

		   		if(!empty($products)) {
		   			$response['message'] = sprintf( __('%d products found!', 'woocommerce-better-compare'), count($products) );

		   			$temp = array();
		   			foreach ($products as $product) {

		   				$product = wc_get_product($product->ID);
		   				if(!$product) {
		   					continue;
		   				}

		   				$temp[] = array(
		   					'id' => $product->get_id(),
		   					'img' => wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' )[0],
		   					'name' => $product->get_name(),
		   				);
		   			}
		   			$response['products'] = $temp;
		   		}
	   		}
   		}
   		
   		die(json_encode($response));
   	}

   	protected function search_product_by_name($title)
   	{
	    global $wpdb;
	    $title = esc_sql($title);

	    if(!$title) return;

	    $title = str_replace( array('-', ' '), '', $title);
	  	
	  	// Name search
	    $products = $wpdb->get_results("
	        SELECT * 
	        FROM $wpdb->posts
	        WHERE REPLACE( REPLACE(post_title, '-', ''), ' ', '') LIKE '%$title%'
	        AND post_type = 'product' 
	        AND post_status = 'publish'
	    ");

	    // Tag search
		$params = array(
	        'post_type' => 'product',
	        'post_status' => 'publish',
	        'tax_query' => array( 
	        	'relation' => 'OR',
	        	array(
             	   'taxonomy' => 'product_tag',
                	'field' => 'name',
                	'terms' => array( $title )
            	),
	        	array(
             	   'taxonomy' => 'product_tag',
                	'field' => 'slug',
                	'terms' => array( $title )
            	)
        	)
        );

        $tagQuery = new WP_Query($params);
        
        if(isset($tagQuery->posts) && !empty($tagQuery->posts)) {
        	$products = array_merge($products, $tagQuery->posts);

			$temp_array = array();
			foreach ($products as &$v) {
			    if (!isset($temp_array[$v->ID]))
			        $temp_array[$v->ID] =& $v;
			}
			$products = $temp_array;
        }

	    if(!empty($products)) {
	    	return $products;
	    } else {

	    	return false;
	    }
   	}

	public function getProductCategories($product) 
	{
		$productChildCategories = array();

		$yoastPrimaryCategoryID = get_post_meta($product->get_id(), '_yoast_wpseo_primary_product_cat', true);
		if($this->get_option('enableCategoryRestrictionYoast') && $yoastPrimaryCategoryID){
		   $productChildCategories[] = $yoastPrimaryCategoryID;
		} else {
			// Only subcategories with no children
			$productCategories = get_the_terms( $product->get_id(), 'product_cat' );
			$productCategoryName = "";
			if(!empty($productCategories)) {
				foreach ($productCategories as $productCategory) {

					$children = get_term_children($productCategory->term_id, 'product_cat');
					if( empty( $children ) ) {
					    $productChildCategories[] = $productCategory->term_id;
					}
				}
			}
		}

		return $productChildCategories;
	}

	public function compare_products_get_single_column()
	{
		global $woocommerce_group_attributes_options;

		$translations = $this->get_translations();

   		$response = array(
   			'message' => __('No Product found ...', 'woocommerce-better-compare'),
   			'html' => '',
   		);

  		if(!isset($_POST['product']) || empty($_POST['product'])) {
   			die(json_encode($response));
   		}

		$showAttrNameInColumn = $this->get_option('singleCompareTableShowAttrNameInColumn');
		$showAttrNameInColumnCSS = $showAttrNameInColumn ? ' has-keys-column' : '';

		$product = intval($_POST['product']);

		$products = array(
		 	$product
		);

		$productData = $this->get_data_to_compare($products);

		foreach ($productData as $productId => $single_product_data) {

			$html .= '<div class="single-product-compare-column single-product-compare-column-' . $productId . '">';

			$count = 0;
			foreach ($single_product_data as $dataKey => $dataValue) {

				if($showAttrNameInColumn) {
					if($this->get_option('enableGroupedAttributesResetCounts')) {
						if(substr($dataKey, 0, 5) == "group") {
							$count = 0;
						}
					}
					$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '">' . $dataValue . '</div>';	
				} else {

					if(isset($woocommerce_group_attributes_options['enableAccordion']) && $woocommerce_group_attributes_options['enableAccordion'] = "1" && substr($dataKey, 0, 5) == "group") {

						if($this->get_option('enableGroupedAttributesResetCounts')) {
							$count = 0;
						}

						$attributesToHide = array();
						$attributeGroupId =  substr($dataKey, 6);
						$attributesInGroup = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_attributes');
						if(is_array($attributesInGroup[0])) {
							$attributesInGroup = $attributesInGroup[0];
						}
						
						if(!empty($attributesInGroup)) {
							foreach ($attributesInGroup as $attributeInGroup) {
								$attributeInGroup = wc_get_attribute($attributeInGroup);
								$attributeInGroupSlug = substr($attributeInGroup->slug, 3);
								
								$attributesToHide[] = $attributeInGroupSlug;

							}
						}

						if(!empty($attributesToHide)) {
							$attributesToHideString = "data-hide-attributes='" . json_encode($attributesToHide) . "'";
							
							$class3 .= ' woocommerce-better-compare-accordion-title';
							$accordion_open = get_post_meta($attributeGroupId, 'woocommerce_group_attributes_accordion_compare_open', true);
							if($accordion_open == "1"){ 
								$class3 .= ' woocommerce-better-compare-accordion-title-open';
								$accordion = '<i class="fa fa-minus woocommerce-group-attributes-icon"></i>';
							} else {
								$accordion = '<i class="fa fa-plus woocommerce-group-attributes-icon"></i>';	
							}
						}

						if($firstProduct) { 
							$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey .  $class3 . '" ' . $attributesToHideString . ' data-group-id="' . $attributeGroupId .'">' . $accordion . $translations[$dataKey] . '</div>';	
						} else {
							$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey .  $class3 . '" ' . $attributesToHideString . ' data-group-id="' . $attributeGroupId .'">&nbsp;</div>';	
						}

					} else {
						$html .= '<div class="single-product-compare-value ' . (++$count%2 ? "oddd" : "even") . ' single-product-compare-value-' . $dataKey . '"><span class="single-product-compare-key">' . $translations[$dataKey] . '</span>' . $dataValue . '</div>';	
					}
				}										
			}

			$html .= '</div>';
		}

		$response['html'] = $html;
		die(json_encode($response));
	}


	public function get_share_icons()
	{
		$services = $this->get_option('shareComparisonServices');
		$shareShowTitle = $this->get_option('shareComparisonShowTitle');

		$html = '<div class="woocommerce-better-compare-share-buttons">';

		foreach($services as $service) {

			if($service == 'facebook') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="facebook" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-facebook" target="_blank"><i class="fab fa-facebook"></i>Facebook</a>';
				} else {
					$html .= '<a href="#" data-service="facebook" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-facebook" target="_blank"><i class="fab fa-facebook"></i></a>';
				}
			}

			if($service == 'twitter') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="twitter" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-twitter" target="_blank"><i class="fab fa-twitter"></i>Twitter</a>';
				} else {
					$html .= '<a href="#" data-service="twitter" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-twitter" target="_blank"><i class="fab fa-twitter"></i></a>';
				}
			}

			if($service == 'pinterest') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="pinterest" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-pinterest" target="_blank"><i class="fab fa-pinterest"></i>Pinterest</a>';
				} else {
					$html .= '<a href="#" data-service="pinterest" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-pinterest" target="_blank"><i class="fab fa-pinterest"></i></a>';
				}
			}

			if($service == 'linkedin') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="linkedin" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-linkedin" target="_blank"><i class="fab fa-linkedin"></i>LinkedIn</a>';
				} else {
					$html .= '<a href="#" data-service="linkedin" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-linkedin" target="_blank"><i class="fab fa-linkedin"></i></a>';
				}
			}

			if($service == 'buffer') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="buffer" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-buffer" target="_blank"><i class="fab fa-buffer"></i>Buffer</a>';
				} else {
					$html .= '<a href="#" data-service="buffer" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-buffer" target="_blank"><i class="fab fa-buffer"></i></a>';
				}
			}

			if($service == 'tumblr') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="tumblr" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-tumblr" target="_blank"><i class="fab fa-tumblr"></i>Tumblr</a>';
				} else {
					$html .= '<a href="#" data-service="tumblr" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-tumblr" target="_blank"><i class="fab fa-tumblr"></i></a>';
				}
			}

			if($service == 'reddit') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="reddit" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-reddit" target="_blank"><i class="fab fa-reddit"></i>Reddit</a>';
				} else {
					$html .= '<a href="#" data-service="reddit" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-reddit" target="_blank"><i class="fab fa-reddit"></i></a>';
				}
			}

			if($service == 'wordpress') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="wordpress" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-wordpress" target="_blank"><i class="fab fa-wordpress"></i>WordPress</a>';
				} else {
					$html .= '<a href="#" data-service="wordpress" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-wordpress" target="_blank"><i class="fab fa-wordpress"></i></a>';
				}
			}

			if($service == 'pocket') {
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="pocket" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-pocket" target="_blank"><i class="fab fa-pocket"></i>Pocket</a>';
				} else {
					$html .= '<a href="#" data-service="pocket" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-pocket" target="_blank"><i class="fab fa-pocket"></i></a>';
				}
			}

			if($service == 'vk') {		
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="vk" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-vk" target="_blank"><i class="fab fa-vk"></i> VK</a>';
				} else {
					$html .= '<a href="#" data-service="vk" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-vk" target="_blank"><i class="fab fa-vk"></i> </a>';
				}
			}

			if($service == 'email') {				
				if($shareShowTitle) {
					$html .= '<a href="#" data-service="email" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-envelope" target="_blank"><i class="fa fa-envelope"></i>Email</a>';
				} else {
					$html .= '<a href="#" data-service="email" class="woocommerce-better-compare-share-button woocommerce-better-compare-share-envelope" target="_blank"><i class="fa fa-envelope"></i></a>';
				}
			}
		}

		$html .= '</div>';

		return $html;
	}


	public function get_share()
	{

		global $woocommerce_better_compare_options;
		$this->options = $woocommerce_better_compare_options;

		if(!isset($_COOKIE['compare_products_products']) || empty($_COOKIE['compare_products_products'])) {
			die('No products to share.');
		}

		$products = implode(',', json_decode(stripslashes($_COOKIE['compare_products_products']), true) );

		$service = esc_html( $_GET['woocommerce-better-compare-share'] );

		$title = $this->get_option('shareComparisonSubject');
		$message = $this->get_option('shareComparisonMessage');
		$url = get_permalink($this->get_option('shareComparisonPage')) . '?compare=' . $products;

		switch ($service) {
			case 'facebook':
				$data = array(
					'title' => $title,
					'u' => $url,
				);
				$share_url = '//www.facebook.com/sharer.php?' . http_build_query($data);
				break;
			case 'twitter':
				$data = array('url' => $url);
				$share_url = '//twitter.com/share?' . http_build_query($data);
				break;
			case 'pinterest':
				$data = array('url' => $url);
				$share_url = '//pinterest.com/pin/create/?' . http_build_query($data);
				break;
			case 'linkedin':
				$data = array(
					'url' => $url,
					'title' => $title
				);
				$share_url = '//www.linkedin.com/shareArticle?' . http_build_query($data);
				break;
			case 'buffer':
				$data = array(
					'url' => $url,
					'text' => $title
				);
				$share_url = 'https://bufferapp.com/add?' . http_build_query($data);
				break;
			case 'tumblr':
				$data = array(
					'url' => $url,
					'name' => $title
				);
				$share_url = '//www.tumblr.com/share/link?' . http_build_query($data);
				break;
			case 'reddit':

				$data = array(
					'url' => $url,
					'title' => $title
				);
				$share_url = '//reddit.com/submit?' . http_build_query($data);
				break;
			case 'wordpress':
				$data = array(
					'u' => $url,
					't' => $title
				);
				$share_url = get_site_url() . '/press-this.php?' . http_build_query($data);
				break;
			case 'pocket':
				$data = array(
					'url' => $url,
					'title' => $title
				);
				$share_url = '//getpocket.com/save?' . http_build_query($data);
				break;
			case 'vk':
				$data = array(
					'url' => $url,
					'title' => $title
				);
				$share_url = '//vk.com/share.php?' . http_build_query($data);
				break;
			case 'email':
				$data = array(
					'subject' => $title,
					'body' => $message,
				);
				$share_url = 'mailto:?' . str_replace('+', ' ', http_build_query($data));
				break;
		}

		header('Location: ' . $share_url);
		exit();
	}
}