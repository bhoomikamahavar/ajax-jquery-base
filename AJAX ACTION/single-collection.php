<?php

/**
 *  ACF Custom Field Meta In Single-Collection.php [ For Select Multiple Products ]
 *  This code is for get product ids and with this one single button click this all products add to cart [ All Add To Cart ] 
 */

if( have_rows('products') ):
	while( have_rows('products') ) : the_row();

		// Get parent value.
		$parent_title = get_sub_field('product_selection');

		// Loop over sub repeater rows.
		if( have_rows('product_selection') ):
			while( have_rows('product_selection') ) : the_row();

				// Get sub value.
				//$single_product_arr = array();
				$single_product_id = get_sub_field('products');

				$all_single_product_ids = implode(',', $single_product_id); ?>
				
				<div class="btn-all-add-to-cart">
					<a href="Javascript:void(0)" id="all_product_ids_in_ajax" data-product-id="<?php echo $all_single_product_ids; ?>" class="button" >Add All To Cart</a>
				</div><?php

			endwhile;
		endif;

	endwhile;
endif; 