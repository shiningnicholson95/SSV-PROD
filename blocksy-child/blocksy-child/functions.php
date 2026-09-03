<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});
add_filter( 'wc_product_sku_enabled', '__return_false' );

// Modify Gravity Forms webhook request data to use field labels instead of IDs.
// Scoped to the "Send Form to GHL" feed (ID 3) only — this filter is global
// (gform_webhooks_request_data fires for every webhook feed on every form),
// and unscoped it silently overwrote other feeds' request data, including the
// Klaviyo feed's field-key mapping (email/first_name/last_name), with an empty array.
function gf_webhook_send_field_labels( $request_data, $feed, $entry, $form ) {
	if ( (int) rgar( $feed, 'id' ) !== 3 ) {
		return $request_data;
	}

	$new_request_data = array();

	foreach ( $form['fields'] as $field ) {
		$label = $field->label;
		$field_id = $field->id;

		// Handle different field types and their input structure
		if ( isset( $request_data[ $field_id ] ) ) {
			$new_request_data[ $label ] = $request_data[ $field_id ];
		} elseif ( isset( $field->inputs ) && is_array( $field->inputs ) ) {
			foreach ( $field->inputs as $input ) {
				$input_id = $input['id'];
				$input_label = $label;
				if ( ! empty( $input['label'] ) ) {
					$input_label .= ' - ' . $input['label'];
				}
				if ( isset( $request_data[ $input_id ] ) ) {
					$new_request_data[ $input_label ] = $request_data[ $input_id ];
				}
			}
		}
	}

	return $new_request_data;
}
add_filter( 'gform_webhooks_request_data', 'gf_webhook_send_field_labels', 10, 4 );