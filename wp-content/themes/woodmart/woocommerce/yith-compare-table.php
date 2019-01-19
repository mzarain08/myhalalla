<?php
/**
 * Woocommerce Compare page
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

global $product, $yith_woocompare;

?>

<div id="yith-woocompare" class="woocommerce yith-woocompare-premium">

	<?php
        if ( empty( $products ) ):
            echo '<p>' . apply_filters( 'yith_woocompare_empry_compare_message', __( 'No products added in the comparison table.', 'yith-woocommerce-compare' ) ) . '</p>';
        else:
    ?>

    <?php do_action( 'yith_woocompare_before_main_table' ); ?>

	<table id="yith-woocompare-table" class="compare-list <?php if( empty( $products ) ) echo 'empty-list' ?>">
		<thead>
		<tr>
			<th>&nbsp;</th>
			<?php foreach( $products as $product_id => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th>&nbsp;</th>
			<?php foreach( $products as $product_id => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
		</tfoot>

		<tbody>

            <?php if( ! isset( $fields['product_info'] ) && ! $fixed ) : ?>
            <tr class="remove">
                <th>&nbsp;</th>
                <?php
                $index = 0;
                foreach( $products as $product_id => $product ) :
	                $product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product_id
	                ?>
                    <td class="<?php echo $product_class; ?>">
                        <a href="<?php echo $yith_woocompare->obj->remove_product_url( $product_id ) ?>" data-iframe="<?php echo $iframe ?>" data-product_id="<?php echo $product_id; ?>"><span class="remove">x</span><?php _e( 'Remove', 'yith-woocommerce-compare' ) ?></a>
                    </td>
                    <?php
	                ++$index;
                endforeach;
                ?>
            </tr>
            <?php endif; ?>

            <?php foreach ( $fields as $field => $name ) : ?>

                <tr class="<?php echo ! in_array( $field, $different ) ? $field : $field . ' different' ?>">

                    <th>
                        <?php echo $name ?>
                        <?php if ( $field == 'product_info' ) echo '<div class="fixed-th"></div>'; ?>
                    </th>

                    <?php
                    $index = 0;
                    foreach( $products as $product_id => $product ) :
                        // set td class
                        $product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product_id;
                        if( $field == 'stock' ) {
                            $availability = $product->get_availability();
                            $product_class .= ' ' . (  empty( $availability['class'] ) ? 'in-stock' : $availability['class'] );
                        }
                        ?>

                        <td class="<?php echo $product_class; ?>"><?php
                            switch( $field ) {

                                case 'product_info':

                                    if( ! $fixed )
                                        echo '<div class="remove"><a href="'. $yith_woocompare->obj->remove_product_url( $product_id ) . '" data-iframe="'. $iframe . '" data-product_id="'. $product_id . '"><span class="remove">x</span>' .  __( 'Remove', 'yith-woocommerce-compare' ) . '</a></div>';

                                    if( $show_image || $show_title ) {
	                                    echo '<a href="' . $product->get_permalink() .'">';
                                        if( $show_image )
	                                        echo '<div class="image-wrap">' . $product->get_image( 'yith-woocompare-image' ) . '</div>';
	                                    if( $show_title )
		                                    echo '<h4 class="product_title">' . $product->get_title() . '</h4>';
	                                    echo '</a>';

	                                    echo yith_woocompare_get_vendor_name( $product );
                                    }

                                    if( $product->is_type( 'bundle' ) ) {
                                        $bundled_items = $product->get_bundled_items();

                                        if( ! empty( $bundled_items ) ) {

                                            echo '<div class="bundled_product_list">';

                                            foreach ( $bundled_items as $bundled_item ) {
                                                /**
                                                 * wc_bundles_bundled_item_details hook
                                                 */
                                                do_action( 'wc_bundles_bundled_item_details', $bundled_item, $product );
                                            }

                                            echo '</div>';
                                        }
                                    }

                                    if( $show_add_cart ) {
	                                    if( class_exists('YITH_WCCL_Frontend') && defined('YITH_WCCL_PREMIUM') && YITH_WCCL_PREMIUM ) {
		                                    remove_filter( 'woocommerce_loop_add_to_cart_link', array( YITH_WCCL_Frontend(), 'add_select_options' ), 99 );
	                                    }
                                        echo '<div class="add_to_cart_wrap">';
	                                    woocommerce_template_loop_add_to_cart();
	                                    echo '</div>';
                                    }

									if( shortcode_exists( 'yith_ywraq_button_quote' ) && $product->is_type('simple') && $show_request_quote == 'yes' ) {
										echo do_shortcode('[yith_ywraq_button_quote product="'.$product->get_id().'"]');
									}

                                    break;

                                case 'rating':
                                    $rating = function_exists('wc_get_rating_html') ? wc_get_rating_html( $product->get_average_rating() ) : $product->get_rating_html();
                                    echo $rating != '' ? '<div class="woocommerce-product-rating">' . $rating . '</div>' : '-';
                                    break;

                                default:
                                    echo empty( $product->fields[$field] ) ? '-' : $product->fields[$field];
                                    break;
                            }
                            ?>
                        </td>
                        <?php
                        ++$index;
                    endforeach
                    ?>
                </tr>
            <?php endforeach; ?>

            <?php if ( $repeat_price == 'yes' && isset( $fields['price'] )  ) : ?>
                <tr class="price repeated">
                    <th>
	                    <?php echo $fields['price'] ?>
                    </th>

                    <?php
                    $index = 0;
                    foreach( $products as $product_id => $product ) :
	                    $product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product_id ?>
                        <td class="<?php echo $product_class ?>"><?php echo $product->fields['price'] ?></td>
                        <?php
                        ++$index;
                    endforeach; ?>
                </tr>
            <?php endif; ?>

            <?php if ( $repeat_add_to_cart == 'yes'  ) : ?>
                <tr class="add-to-cart repeated">
                    <th>&nbsp;</th>

                    <?php 
                    $index = 0;
                    foreach( $products as $product_id => $product ) :
	                    $product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product_id ?>
                        <td class="<?php echo $product_class ?>">
                            <?php woocommerce_template_loop_add_to_cart(); ?>
                        </td>
                        <?php
                        ++$index;
                    endforeach; ?>
                </tr>
            <?php endif; ?>

		</tbody>
	</table>

	<?php do_action( 'yith_woocompare_after_main_table' ); ?>

	<?php endif; ?>

</div>