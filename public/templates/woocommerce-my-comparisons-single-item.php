<?php

global $post, $product, $woocommerce_better_compare_options;

$elements = $woocommerce_better_compare_options['myComparisonsDataToShow']['enabled'];

if(!$comparisonProduct) {
	return false;
}

$parentProduct = false;
if($comparisonProduct->is_type('variation')) {
	$parentProduct = wc_get_product( $comparisonProduct->get_parent_id() );
}

$product_id = $comparisonProduct->get_id();
$product_url = get_permalink($product_id);

do_action( 'woocommerce_better_compare_item_start' );

echo '<div class="woocommerce-better-compare-item">';

		echo '<a href="#" data-product="' . $product_id . '" class="woocommerce-better-compare-remove-product"><i class="fa fa-times"></i></a>';


	if(isset($elements['im'])) {

		if ( has_post_thumbnail($product_id)) { 
			$product_image_id = get_post_thumbnail_id($comparisonProduct->get_id());
			$thumbnail = wp_get_attachment_image_src( $product_image_id, 'full' ); 
			$product_image_src = $thumbnail[0];
		} else { 
			$product_image_src = wc_placeholder_img_src();
		}
		echo '<a href="' . $product_url . '" class="woocommerce-better-compare-item-image">';
			echo sprintf('<img src="%s" alt="%s" class="woocommerce-better-compare-image-src">', $product_image_src, $comparisonProduct->get_title());
		echo '</a>';
	}
	?>

	<div class="woocommerce-better-compare-item-content">
	
		<?php
		do_action( 'woocommerce_better_compare_item_content_start' );

		// Title
		if(isset($elements['ti'])) {
			$title =  apply_filters( 'the_title', $comparisonProduct->get_title(), $comparisonProduct->get_id() );
			if(!empty($title)) {
				echo 
				'<a href="' . $product_url . '">
					<h4 class="woocommerce-better-compare-title">
						' . apply_filters( 'woocommerce_better_compare_title', $title ) . '
					</h4>
				</a>';
			}
		}

		// Rating
		if(isset($elements['re'])) {

			if($parentProduct) {
				$rating =  wc_get_rating_html( $parentProduct->get_average_rating() );
			} else {
				$rating =  wc_get_rating_html( $comparisonProduct->get_average_rating() );	
			}

			if(!empty($rating)) {
				echo 
				'<div class="woocommerce-better-compare-rating">
					' . apply_filters( 'woocommerce_better_compare_rating', $rating ) . '
				</div>';
			}
		}

		// Price
		if(isset($elements['pr'])) {
			$price =  $comparisonProduct->get_price_html();
			if(!empty($price)) {
				echo 
				'<div class="woocommerce-better-compare-price">
					' . apply_filters( 'woocommerce_better_compare_price', $price ) . '
				</div>';
			}
		}

		// Short Description
		if(isset($elements['sd'])) {

			$short_description =  $comparisonProduct->get_short_description();
			if($parentProduct && empty($short_description)) {
				$short_description = $parentProduct->get_short_description();
			}

			
			if(!empty($short_description)) {
				echo 
				'<div class="woocommerce-better-compare-short-description">
					' . apply_filters( 'woocommerce_better_compare_short_description', $short_description ) . '
				</div>';
			}
		}

		// Description
		if(isset($elements['de'])) {

			$description =  $comparisonProduct->get_description();
			if($parentProduct && empty($description)) {
				$description = $parentProduct->get_description();
			}

			if(!empty($description)) {
				echo 
				'<div class="woocommerce-better-compare-short-description">
					' . apply_filters( 'woocommerce_better_compare_description', $description ) . '
				</div>';
			}
		}
		?>

		<div class="woocommerce-better-compare-meta">
			<?php
			// Stock Status
			if(isset($elements['st'])) {

				$stock_status = wc_get_stock_html( $comparisonProduct );
				if(!empty($stock_status)) {
					echo 
					'<div class="woocommerce-better-compare-stock">' .
						apply_filters( 'woocommerce_better_compare_sku', $stock_status) .
					'</div>';
				}
			}

			// SKU
			if(isset($elements['sk'])) {

				$sku = $comparisonProduct->get_sku();
				if(!empty($sku)) {
					echo 
					'<div class="woocommerce-better-compare-sku">' .
						__('SKU: ', 'woocommerce-wishlist') . 
						apply_filters( 'woocommerce_better_compare_sku', $sku) .
					'</div>';
				}
			}

			// Tags
			if(isset($elements['tg'])) { 

				if($parentProduct) {
					$tags = wc_get_product_tag_list( 
							$parentProduct->get_id(), 
							', ', 
							'<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $parentProduct->get_tag_ids() ), 'woocommerce' ) . ' '
							, '</span>' );
				} else {
					$tags = wc_get_product_tag_list( 
							$comparisonProduct->get_id(), 
							', ', 
							'<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $comparisonProduct->get_tag_ids() ), 'woocommerce' ) . ' '
							, '</span>' );
				}

				if(!empty($tags)) {
					echo 
					'<div class="woocommerce-better-compare-tags">' .
						apply_filters( 'woocommerce_better_compare_tags', $tags) .
					'</div>';
				}
			}

			// Categories
			if(isset($elements['ct'])) { 

				if($parentProduct) {
					$categories = wc_get_product_category_list( 
								$parentProduct->get_id(), 
								', ', 
								'<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $parentProduct->get_category_ids() ), 'woocommerce' ) . ' ', 
								'</span>' 
							);
				} else {
					$categories = wc_get_product_category_list( 
								$comparisonProduct->get_id(), 
								', ', 
								'<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $comparisonProduct->get_category_ids() ), 'woocommerce' ) . ' ', 
								'</span>' 
							);
				}

				if(!empty($categories)) {
					echo 
					'<div class="woocommerce-better-compare-categories">' .
						apply_filters( 'woocommerce_better_compare_categories', $categories) .
					'</div>';
				}
			}
			?>
		</div>

		<?php if(isset($elements['ca'])) { ?> 
			<div class="woocommerce-better-compare-add-to-cart">
				<?php 
				if($comparisonProduct->is_type('simple')) {
					// do_action( 'woocommerce_' . $comparisonProduct->get_type() . '_add_to_cart' ); 
				} else {
					// echo '<a class="single_add_to_cart_button button btn btn-primary alt" href="' . $comparisonProduct->add_to_cart_url() . '">' . $comparisonProduct->add_to_cart_text() . '</a>';
				}
				?>
			</div>
		<?php } ?>

		<?php if(isset($elements['rm'])) { ?> 
			<div class="woocommerce-better-compare-read-more">
				<?php 
					printf('<a href="%s" class="woocommerce-better-compare-read-more-btn btn button">%s</a>', $comparisonProduct->get_permalink(), __('Read More', 'woocommerce-wishlist') );
				?>
			</div>
		<?php } ?>

		<?php 
		if(isset($elements['ac'])) { 
			
			$addToCompare = $woocommerce_better_compare_options['addToCompareText'];

			$url = "#";
			$pageId = $woocommerce_better_compare_options['displayButtonPage'];
			if(!empty($pageId)) {
				$url = get_permalink($pageId) . '?compare=' . $comparisonProduct->get_id();
			}

			echo '<a href="' .  $url . '" class="button add-to-compare-button btn button btn-default theme-button theme-btn" data-product-id="' . $comparisonProduct->get_id() . '" rel="nofollow">' . $addToCompare . '</a>';
		} 
		?>



		<?php if(isset($elements['at'])) { ?> 
			<div class="woocommerce-better-compare-attributes">
				<?php do_action( 'woocommerce_product_additional_information', $comparisonProduct ); ?>
			</div>
		<?php } ?>

		<?php do_action( 'woocommerce_better_compare_item_content_end' ); ?>

	</div>
	<hr>
</div>

<?php 
do_action( 'woocommerce_better_compare_item_end' ); 