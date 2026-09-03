<?php
/**
 * Sunset View Farm — Email Styles override.
 * Based on woocommerce/templates/emails/email-styles.php
 *
 * Outputs plain CSS (no <style> tags) — WooCommerce inlines this into every
 * element via Emogrifier so it renders correctly across email clients.
 * Brand palette matches assets/css/theme.css (:root custom properties),
 * hardcoded here since custom properties are unreliable in email clients.
 *
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

// Brand palette — keep in sync with assets/css/theme.css :root
$green       = '#2F4F2F';
$green_hover = '#3A6B3A';
$brown       = '#8B5E34';
$cream       = '#FAF6EC';
$cream_dark  = '#EDE5CE';
$gold        = '#D99A32';
$gold_dark   = '#B8801E';
$light_green = '#E6EFE0';
$text        = '#2C2C2C';
$text_muted  = '#6B6659';
$white       = '#FFFFFF';
?>
body,
#wrapper {
	background-color: <?php echo esc_attr( $cream ); ?>;
	margin: 0;
	padding: 0;
	color: <?php echo esc_attr( $text ); ?>;
	font-family: Georgia, 'Playfair Display', 'Times New Roman', serif;
	-webkit-font-smoothing: antialiased;
}

#template_container {
	background-color: <?php echo esc_attr( $cream ); ?>;
}

/* Accent ribbon */
#template_ribbon {
	background-color: <?php echo esc_attr( $gold ); ?>;
	background: linear-gradient(90deg, <?php echo esc_attr( $green ); ?> 0%, <?php echo esc_attr( $gold ); ?> 100%);
	line-height: 6px;
	font-size: 6px;
}
#ribbon_cell {
	line-height: 6px;
	font-size: 6px;
}

/* Header */
#template_header {
	background-color: <?php echo esc_attr( $green ); ?>;
}
#header_wrapper {
	padding: 36px 24px 30px;
	text-align: center;
}
#header_logo {
	max-width: 200px;
	height: auto;
	margin: 0 auto 18px;
}
#header_title {
	color: <?php echo esc_attr( $white ); ?>;
	font-family: Georgia, 'Playfair Display', serif;
	font-size: 26px;
	font-weight: 700;
	margin: 0 0 18px;
}
#header_badge {
	display: inline-block;
	background-color: <?php echo esc_attr( $gold ); ?>;
	color: <?php echo esc_attr( $white ); ?>;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 13px;
	font-weight: 700;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	padding: 10px 20px;
	border-radius: 999px;
}
#header_badge_icon {
	display: inline-block;
	margin-right: 6px;
}

/* Body */
#template_body_inner {
	background-color: <?php echo esc_attr( $white ); ?>;
	border-radius: 10px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
}
.body-content-padding {
	padding: 40px 40px 8px;
}
#body_content_inner {
	color: <?php echo esc_attr( $text ); ?>;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 15px;
	line-height: 1.7;
}
#body_content_inner p {
	margin: 0 0 16px;
}
#body_content_inner a {
	color: <?php echo esc_attr( $green ); ?>;
	font-weight: 600;
}

/* Order summary recap box */
.order-recap {
	background-color: <?php echo esc_attr( $light_green ); ?>;
	border: 1px solid <?php echo esc_attr( $cream_dark ); ?>;
	border-radius: 8px;
	padding: 20px 24px;
	margin: 8px 0 28px;
}
.order-recap-row {
	font-family: Helvetica, Arial, sans-serif;
	font-size: 13px;
	color: <?php echo esc_attr( $text_muted ); ?>;
	text-transform: uppercase;
	letter-spacing: 0.03em;
	padding-bottom: 4px;
}
.order-recap-value {
	font-family: Georgia, 'Playfair Display', serif;
	font-size: 20px;
	font-weight: 700;
	color: <?php echo esc_attr( $green ); ?>;
}

/* Order details table (core-generated, dynamic content) */
h2 {
	font-family: Georgia, 'Playfair Display', serif;
	color: <?php echo esc_attr( $green ); ?>;
	font-size: 19px;
	font-weight: 700;
	margin: 28px 0 14px;
	padding-bottom: 10px;
	border-bottom: 2px solid <?php echo esc_attr( $cream_dark ); ?>;
}
h3 {
	font-family: Georgia, 'Playfair Display', serif;
	color: <?php echo esc_attr( $green ); ?>;
	font-size: 15px;
	font-weight: 700;
	margin: 24px 0 8px;
}
.td,
table.td {
	border: 1px solid <?php echo esc_attr( $cream_dark ); ?>;
}
table.td,
#body_content table.td {
	border-collapse: collapse;
}
table thead th,
table.order_details thead th {
	background-color: <?php echo esc_attr( $cream ); ?>;
	color: <?php echo esc_attr( $green ); ?>;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 12px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.03em;
	padding: 12px;
	border-bottom: 2px solid <?php echo esc_attr( $cream_dark ); ?>;
}
table tbody td,
table.order_details tbody td {
	padding: 12px;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 14px;
	color: <?php echo esc_attr( $text ); ?>;
	border-bottom: 1px solid <?php echo esc_attr( $cream_dark ); ?>;
}
table tfoot th,
table tfoot td,
table.order_details tfoot th,
table.order_details tfoot td {
	padding: 12px;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 14px;
	border-top: 1px solid <?php echo esc_attr( $cream_dark ); ?>;
}
table.order_details tfoot tr:last-child th,
table.order_details tfoot tr:last-child td {
	color: <?php echo esc_attr( $green ); ?>;
	font-weight: 700;
	font-size: 16px;
}

/* Customer details (address cards) */
.customer_details {
	background-color: <?php echo esc_attr( $cream ); ?>;
	border-radius: 8px;
	padding: 4px 20px;
	margin: 0 0 20px;
	border: none;
}
.customer_details li,
.wc-customer-details-fields {
	font-family: Helvetica, Arial, sans-serif;
	font-size: 14px;
	color: <?php echo esc_attr( $text ); ?>;
	border-bottom: 1px solid <?php echo esc_attr( $cream_dark ); ?>;
}
.customer_details li strong,
.wc-customer-details-fields strong {
	color: <?php echo esc_attr( $green ); ?>;
}

/* Additional content from WooCommerce email settings */
#body_content_inner .additional-content {
	background-color: <?php echo esc_attr( $cream ); ?>;
	border-left: 3px solid <?php echo esc_attr( $gold ); ?>;
	padding: 14px 18px;
	margin-top: 24px;
	font-size: 14px;
	color: <?php echo esc_attr( $text_muted ); ?>;
}

/* Secondary CTA button */
#template_footer_cta {
	margin-top: 4px;
}
.footer-cta-padding {
	padding: 28px 24px 32px;
}
#footer_cta_button {
	display: inline-block;
	background-color: <?php echo esc_attr( $gold ); ?>;
	color: <?php echo esc_attr( $white ); ?>;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 14px;
	font-weight: 700;
	text-decoration: none;
	letter-spacing: 0.03em;
	text-transform: uppercase;
	padding: 14px 32px;
	border-radius: 999px;
}

/* Footer */
#template_footer {
	background-color: transparent;
}
.footer-padding {
	padding: 8px 24px 40px;
	text-align: center;
}
#credit {
	color: <?php echo esc_attr( $text_muted ); ?>;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 12px;
	line-height: 1.6;
	text-align: center;
}
#credit a {
	color: <?php echo esc_attr( $brown ); ?>;
}

/* Responsive */
@media only screen and (max-width: 620px) {
	#template_body_inner,
	#template_footer {
		width: 100% !important;
	}
	.body-content-padding {
		padding: 28px 20px 4px !important;
	}
	#header_wrapper {
		padding: 28px 16px 24px !important;
	}
}
