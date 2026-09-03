<?php
/**
 * Plugin Name: SVF - Subscription Minimum Commitment (3 Deliveries)
 * Description: Blocks a customer from self-cancelling a subscription from
 * My Account until 3 successful (paid) deliveries - the initial order plus
 * renewals - have gone through, regardless of billing frequency. Only
 * intercepts the customer-initiated "Cancel" link/action - admin
 * cancellation from wp-admin is completely unaffected, since that goes
 * through a different code path entirely.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Number of successfully paid orders (initial + renewals) required before
 * a subscriber can cancel their own subscription.
 */
const SVF_SUBSCRIPTION_MIN_DELIVERIES = 3;

/**
 * Runs on 'wp_loaded' at priority 99 - one tick before WooCommerce
 * Subscriptions' own customer status-change handler (registered at
 * priority 100 in WCS_User_Change_Status_Handler::init()). If this blocks
 * the request, it exits before that handler ever runs, so the subscription
 * status is never touched.
 */
add_action( 'wp_loaded', 'svf_block_early_subscription_cancellation', 99 );

function svf_block_early_subscription_cancellation() {
	if ( ! isset( $_GET['change_subscription_to'], $_GET['subscription_id'], $_GET['_wpnonce'] ) ) {
		return;
	}

	if ( 'cancelled' !== wc_clean( wp_unslash( $_GET['change_subscription_to'] ) ) ) {
		return;
	}

	$subscription = wcs_get_subscription( absint( $_GET['subscription_id'] ) );

	if ( ! $subscription ) {
		return;
	}

	// WC_Subscription::get_payment_count() counts successfully paid orders
	// (parent + renewals) - i.e. deliveries actually fulfilled and charged.
	$successful_deliveries = $subscription->get_payment_count();

	if ( $successful_deliveries < SVF_SUBSCRIPTION_MIN_DELIVERIES ) {
		$remaining = SVF_SUBSCRIPTION_MIN_DELIVERIES - $successful_deliveries;

		wc_add_notice(
			sprintf(
				/* translators: 1: total deliveries required, 2: deliveries still remaining */
				__( 'This subscription requires %1$d successful deliveries before it can be cancelled - %2$d to go. Please contact us if you need help.', 'sunset-view' ),
				SVF_SUBSCRIPTION_MIN_DELIVERIES,
				$remaining
			),
			'error'
		);

		wp_safe_redirect( $subscription->get_view_order_url() );
		exit;
	}
}
