<?php
global $post, $product, $woocommerce_better_compare_options;

$current_user_id = get_current_user_id();
$comparisonProducts = $comparison['products'];
$comparePage = get_permalink($woocommerce_better_compare_options['myComparisonsPage']);
if(!$comparePage) {
	wp_die( __('You need to set a compare page in my comparison settings.', 'woocommerce-better-compare') );
	return false;
}
$url = $comparePage . '?compare=' . implode(',', $comparisonProducts);

do_action( 'woocommerce_better_compare_before_wishlist' );
?>

<div class="woocommerce-better-compare-header">
	<h3 class="woocommerce-better-compare-header-title"><?php echo $comparison['name'] ?></h3>
	<div class="woocommerce-better-compare-header-actions">
		<a href="<?php echo $url ?>" class="btn button primary btn-primary woocommerce-better-compare-view"><?php echo __('View', 'woocommerce-better-compare') ?></a>
		<a href="#" data-id="<?php echo $id ?>" class="btn button secondary btn-secondary woocommerce-better-compare-edit"><?php echo __('Edit', 'woocommerce-better-compare') ?></a>
		<a href="#" data-id="<?php echo $id ?>" class="btn button secondary btn-secondary woocommerce-better-compare-delete"><?php echo __('Delete', 'woocommerce-better-compare') ?></a>
	</div>
</div>

<?php

do_action( 'woocommerce_better_compare_before_products' );



if(!$comparisonProducts || empty($comparisonProducts)) {
	echo $woocommerce_better_compare_options['myComparisonsNoProducts'];
	return false;
}

foreach ($comparisonProducts as $comparisonProduct) {
	
	$comparisonProduct = wc_get_product($comparisonProduct);

	wc_get_template( 'woocommerce-my-comparisons-single-item.php', array('comparisonProduct' => $comparisonProduct), '', plugin_dir_path(__FILE__));

	wp_reset_postdata();
}

do_action( 'woocommerce_better_compare_after_products' );

if($woocommerce_better_compare_options['myComparisonsShareEnabled'] == "1") {

	echo '<div class="woocommerce-better-compare-share">';

		echo '<div class="woocommerce-better-compare-share-title">'. __('Share this Comparison ...', 'woocommerce-better-compare') . '</div>';

		$title = $woocommerce_better_compare_options['myComparisonsShareTitle'];

		if($woocommerce_better_compare_options['myComparisonsSharePrint'] == "1") {
			echo '<a href="javascript:window.print();" class="woocommerce-better-compare-share-print"><i class="fa fa-print"></i></a>';
		}

		if($woocommerce_better_compare_options['myComparisonsShareFacebook'] == "1") {
			$data = array(
				'title' => $title,
				'u' => $url,
			);
			$share_url = '//www.facebook.com/sharer.php?' . http_build_query($data);

			echo '<a href="' . $share_url . '" class="woocommerce-better-compare-share-facebook" target="_blank"><i class="fab fa-facebook"></i></a>';
			
		}

		if($woocommerce_better_compare_options['myComparisonsShareTwitter'] == "1") {
			$data = array('url' => $url);
			$share_url = '//twitter.com/share?' . http_build_query($data);
			echo '<a href="' . $share_url . '" class="woocommerce-better-compare-share-twitter" target="_blank"><i class="fab fa-twitter"></i></a>';

		}

		if($woocommerce_better_compare_options['myComparisonsSharePinterest'] == "1") {
			$data = array('url' => $url);
			$share_url = '//pinterest.com/pin/create/?' . http_build_query($data);
			echo '<a href="' . $share_url . '" class="woocommerce-better-compare-share-pinterest" target="_blank"><i class="fab fa-pinterest"></i></a>';

		}

		if($woocommerce_better_compare_options['myComparisonsShareEmail'] == "1") {
			$data = array(
				'subject' => $title,
				'body' => $url,
			);
			$share_url = 'mailto:?' . http_build_query($data);
			echo '<a href="' . $share_url . '" class="woocommerce-better-compare-share-envelope" target="_blank"><i class="fa fa-envelope"></i></a>';
		}


	echo '</div>';
}

do_action( 'woocommerce_better_compare_after_wishlist' );
?>