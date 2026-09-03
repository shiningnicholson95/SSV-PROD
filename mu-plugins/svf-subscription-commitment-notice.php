<?php
/**
 * Plugin Name: SVF - Subscription Commitment Notice (UI)
 * Description: Shows a "One-Time Purchase" / "Subscribe & Save" option
 * structure - each with its own price and, for subscription options, a
 * "3-delivery minimum" note - on both the single product page and the
 * cart page, plus a one-time explanatory note on each page. The product
 * page is forced to the "flat" WCS-ATT layout (a plain list of priced
 * options) instead of the default dropdown, so it matches the cart's
 * layout. Purely cosmetic - the actual enforcement lives in
 * svf-subscription-cancellation-lock.php and is based on successfully
 * paid orders (WC_Subscription::get_payment_count()), not calendar time,
 * so this text intentionally does not vary by billing frequency.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Force the "flat" product-page layout (a plain, always-visible list of
 * priced options) instead of WCS-ATT's default "grouped" layout (which
 * hides all but the active option behind a dropdown). This is the same
 * flat-list structure the cart page already uses, so both pages present
 * subscription plans the same way.
 */
add_filter( 'wcsatt_subscription_options_layout', 'svf_force_flat_subscription_layout' );

function svf_force_flat_subscription_layout() {
	return 'flat';
}

/**
 * The "flat" layout auto-generates a generic "Choose a purchase plan:"
 * label above the options list. We suppress it because our own
 * "One-Time Purchase" / "Subscribe & Save" headings (added below) already
 * describe each option.
 */
add_filter( 'wcsatt_default_prompt_text', 'svf_suppress_default_prompt_text' );

function svf_suppress_default_prompt_text() {
	return '';
}

/**
 * Single product page - one-time option: show it as "One-Time Purchase"
 * plus the item's normal one-time price.
 */
add_filter( 'wcsatt_single_product_one_time_option_description', 'svf_product_one_time_option_description', 10, 2 );

function svf_product_one_time_option_description( $description, $product ) {
	$price_html = WCS_ATT_Product_Prices::get_price_html( $product, false, array() );

	return '<span class="svf-option-heading">' . esc_html__( 'One-Time Purchase', 'sunset-view' ) . '</span>' . $price_html;
}

/**
 * Single product page - subscription options: prefix the first
 * (base) plan with a "Subscribe & Save X%" heading, and append a
 * "3-delivery minimum" note to every plan.
 */
add_filter( 'wcsatt_single_product_subscription_option_description', 'svf_product_subscription_option_description', 10, 6 );

function svf_product_subscription_option_description( $description, $sub_price_html, $has_price_filter, $allow_one_time, $product, $subscription_scheme ) {
	$heading     = '';
	$base_scheme = WCS_ATT_Product_Schemes::get_base_subscription_scheme( $product );

	if ( $base_scheme && $subscription_scheme->get_key() === $base_scheme->get_key() ) {
		$heading = '<span class="svf-option-heading">' . esc_html( svf_subscribe_and_save_heading_text( $subscription_scheme->get_discount() ) ) . '</span>';
	}

	return $heading . $description . '<span class="svf-commitment-note">' . esc_html__( '— 3-delivery minimum', 'sunset-view' ) . '</span>';
}

/**
 * Cart page - prefix the one-time option with "One-Time Purchase" and the
 * first subscription option with "Subscribe & Save X%", then append a
 * "3-delivery minimum" note to every subscription option.
 */
add_filter( 'wcsatt_cart_item_options', 'svf_append_commitment_notice_cart', 10, 2 );

function svf_append_commitment_notice_cart( $options, $subscription_schemes ) {
	$discount = null;

	if ( ! empty( $subscription_schemes ) ) {
		$first_scheme = reset( $subscription_schemes );
		$discount     = $first_scheme->get_discount();
	}

	$is_first_subscription_option = true;

	foreach ( $options as $key => $option ) {

		if ( 'one-time-option' === $option['class'] ) {

			$options[ $key ]['description'] = '<span class="svf-option-heading">' . esc_html__( 'One-Time Purchase', 'sunset-view' ) . '</span>' . $option['description'];

		} elseif ( 'subscription-option' === $option['class'] ) {

			$suffix = '<span class="svf-commitment-note">' . esc_html__( '— 3-delivery minimum', 'sunset-view' ) . '</span>';

			if ( $is_first_subscription_option ) {
				$heading = '<span class="svf-option-heading">' . esc_html( svf_subscribe_and_save_heading_text( $discount ) ) . '</span>';
				$options[ $key ]['description'] = $heading . $option['description'] . $suffix;
				$is_first_subscription_option    = false;
			} else {
				$options[ $key ]['description'] .= $suffix;
			}
		}
	}

	return $options;
}

/**
 * Shared "Subscribe & Save X%" heading text, used on both pages.
 *
 * @param  int|string|null $discount
 * @return string
 */
function svf_subscribe_and_save_heading_text( $discount ) {
	if ( $discount ) {
		/* translators: %s: subscription discount percentage */
		return sprintf( __( 'Subscribe & Save %s%%', 'sunset-view' ), $discount );
	}

	return __( 'Subscribe & Save', 'sunset-view' );
}

/**
 * Single product page - one-time note below the whole plan-options block.
 * 'wcsatt_after_product_subscription_options' only fires inside the
 * options wrapper template, which WCS-ATT only renders for products that
 * actually have subscription schemes - so this never shows up on a
 * one-time-only product.
 */
add_action( 'wcsatt_after_product_subscription_options', 'svf_product_page_terms_note' );

function svf_product_page_terms_note() {
	echo '<p class="svf-subscription-terms-note">' . esc_html__( 'You may change products or quantities anytime. Cancellation is available after 3 successful subscription deliveries.', 'sunset-view' ) . '</p>';
}

/**
 * Cart page - one-time "Subscription Terms" note below the whole plan
 * options list. Runs right after WCS-ATT's own late (priority 1000)
 * filter that builds the options HTML for a cart item, and only appends
 * when that HTML is actually present - so it never touches a cart item
 * that isn't showing subscription options at all.
 */
add_filter( 'woocommerce_cart_item_price', 'svf_cart_page_terms_note', 1001 );

function svf_cart_page_terms_note( $price ) {
	if ( false === strpos( $price, 'svf-commitment-note' ) ) {
		return $price;
	}

	return $price . '<div class="svf-subscription-terms-note">' . esc_html__( 'Subscription Terms: Save 5% on every recurring order. A minimum of 3 successful subscription deliveries is required before cancellation.', 'sunset-view' ) . '</div>';
}

/**
 * Small, unobtrusive styling so none of these notes compete with price
 * text or force the option layout to wrap oddly.
 */
add_action( 'wp_head', 'svf_commitment_notice_styles' );

function svf_commitment_notice_styles() {
	?>
	<style id="svf-commitment-note-style">
		/* Strip the browser's default list indent - it was pushing every
		   radio + price row to the right and forcing the "3-delivery
		   minimum" text to wrap outside the option box. */
		ul.wcsatt-options-product,
		ul.wcsatt-options {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		ul.wcsatt-options-product > li,
		ul.wcsatt-options > li {
			margin: 0 0 12px;
		}
		ul.wcsatt-options-product > li:last-child,
		ul.wcsatt-options > li:last-child {
			margin-bottom: 0;
		}
		ul.wcsatt-options-product label,
		ul.wcsatt-options label {
			display: flex;
			align-items: flex-start;
			gap: 6px;
			cursor: pointer;
			margin: 0;
		}
		ul.wcsatt-options-product label > input,
		ul.wcsatt-options label > input {
			margin-top: 4px;
			flex-shrink: 0;
		}
		ul.wcsatt-options-product .one-time-option-details,
		ul.wcsatt-options-product .subscription-option-details,
		ul.wcsatt-options .one-time-option-details,
		ul.wcsatt-options .subscription-option-details {
			flex: 1;
			min-width: 0;
		}
		/* Normalize price/period text to one consistent size - the product
		   page was inheriting the large single-product price styling,
		   making the subscription rows much bigger than "One-Time Purchase". */
		ul.wcsatt-options-product .price,
		ul.wcsatt-options-product .one-time-price,
		ul.wcsatt-options-product .subscription-price,
		ul.wcsatt-options-product .subscription-details,
		ul.wcsatt-options-product .wcsatt-sub-discount,
		ul.wcsatt-options .price,
		ul.wcsatt-options .one-time-price,
		ul.wcsatt-options .subscription-price,
		ul.wcsatt-options .subscription-details,
		ul.wcsatt-options .wcsatt-sub-discount {
			font-size: 16px !important;
			line-height: 1.4 !important;
		}
		.svf-option-heading {
			display: inline-block;
			font-weight: 600;
			font-size: 15px;
			margin-right: 6px;
		}
		.svf-options-heading-row {
			display: block;
			list-style: none;
			font-weight: 600;
			font-size: 17px;
			margin: 56px 0 12px;
			padding: 0;
		}
		li.svf-options-heading-row:first-child {
			margin-top: 0;
		}
		.svf-commitment-note {
			font-size: 13px;
			color: #8a8a8a;
			font-weight: 400;
			margin-left: 6px;
			white-space: nowrap;
		}
		.svf-subscription-terms-note {
			font-size: 13px;
			line-height: 1.4;
			color: #8a8a8a;
			margin: 8px 0 0;
		}
	</style>
	<?php
}

/**
 * Two bits of DOM cleanup that only make sense client-side, run every
 * time a "wcsatt-options" / "wcsatt-options-product" list appears or is
 * re-rendered (initial load, a variable product's variation change, or a
 * cart/checkout AJAX refresh):
 *
 * 1. The "One-Time Purchase" and "Subscribe & Save X%" headings are
 *    attached (server-side) to their option's own description, inside
 *    that option's radio label - otherwise WCS-ATT's template gives us
 *    no way to place a heading between list items. Move each one out
 *    into its own plain (radio-less) row above its option, so every
 *    price row - one-time and every subscription plan alike - stays a
 *    single-line "radio + price + 3-delivery minimum" row, all aligned
 *    the same way.
 *
 * 2. Hide the one-time "terms" note (a sibling of the whole option list -
 *    on the cart page directly, on the product page one level further up,
 *    inside the shared ".wcsatt-options-wrapper" container) unless a
 *    subscription option is selected, mirroring WCS-ATT's own toggle
 *    behaviour rather than fighting it.
 */
add_action( 'wp_footer', 'svf_commitment_notice_toggle_script' );

function svf_commitment_notice_toggle_script() {
	?>
	<script id="svf-commitment-note-toggle">
	( function () {

		function processList( list ) {

			if ( ! list.dataset.svfHeadingMoved ) {
				list.querySelectorAll( 'li' ).forEach( function ( li ) {
					var heading = li.querySelector( '.svf-option-heading' );

					if ( ! heading ) {
						return;
					}

					var headingRow = document.createElement( 'li' );
					headingRow.className = 'svf-options-heading-row';
					headingRow.textContent = heading.textContent;
					li.parentNode.insertBefore( headingRow, li );
					heading.remove();
				} );

				list.dataset.svfHeadingMoved = '1';
			}

			var container = list.closest( '.wcsatt-options-wrapper' ) || list.parentElement;
			var note = container ? container.querySelector( '.svf-subscription-terms-note' ) : null;

			if ( ! note || list.dataset.svfToggleBound ) {
				return;
			}

			var update = function () {
				var checked = list.querySelector( 'input:checked' );
				var isOneTime = checked && checked.closest( 'li' ).classList.contains( 'one-time-option' );
				note.style.display = isOneTime ? 'none' : '';
			};

			list.addEventListener( 'change', update );
			update();
			list.dataset.svfToggleBound = '1';
		}

		function processAllLists() {
			document.querySelectorAll( 'ul.wcsatt-options, ul.wcsatt-options-product' ).forEach( processList );
		}

		document.addEventListener( 'DOMContentLoaded', processAllLists );

		// Re-run after a variation change or a cart/checkout AJAX refresh replaces the list markup.
		if ( window.jQuery ) {
			jQuery( document.body ).on( 'show_variation reset_data wc_fragments_refreshed wc_fragments_loaded updated_cart_totals updated_checkout', function () {
				setTimeout( processAllLists, 50 );
			} );
		}
	} )();
	</script>
	<?php
}
