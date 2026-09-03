<?php
/**
 * Sunset View Farm — Customer Completed Order email override.
 * Based on woocommerce/templates/emails/customer-completed-order.php
 *
 * All order data is still pulled live from the $order object and the same
 * core hooks used by the default template — nothing dynamic was removed,
 * only the surrounding markup was restyled.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @var WC_Order $order
 * @var bool $sent_to_admin
 * @var bool $plain_text
 * @var WC_Email $email
 * @var string $email_heading
 * @var string $additional_content
 *
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<?php /* translators: %s: Customer first name */ ?>
<p style="font-size:17px;margin:0 0 6px;">
	<?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?>
</p>

<p>
	<?php esc_html_e( 'Great news — your order from the farm is complete and on its way! Here are your order details for your reference:', 'sunset-view-farm' ); ?>
</p>

<div class="order-recap">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="left" valign="top">
				<div class="order-recap-row"><?php esc_html_e( 'Order Number', 'woocommerce' ); ?></div>
				<div class="order-recap-value">#<?php echo esc_html( $order->get_order_number() ); ?></div>
			</td>
			<td align="left" valign="top">
				<div class="order-recap-row"><?php esc_html_e( 'Order Date', 'woocommerce' ); ?></div>
				<div class="order-recap-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></div>
			</td>
			<td align="left" valign="top">
				<div class="order-recap-row"><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
				<div class="order-recap-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></div>
			</td>
		</tr>
	</table>
</div>

<?php
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo '<div class="additional-content">';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo '</div>';
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
