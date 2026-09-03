<?php
/**
 * Sunset View Farm — Email Header override.
 * Based on woocommerce/templates/emails/email-header.php
 *
 * All dynamic email data ($email_heading, header image filter, site name,
 * text direction) is preserved exactly as core passes it in — only the
 * surrounding markup/branding has been enhanced.
 *
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo esc_html( $email_heading ); ?></title>
</head>
<body>
<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
	<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="template_container">
		<tr>
			<td align="center" valign="top">

				<!-- Accent ribbon -->
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="template_ribbon">
					<tr>
						<td height="6" id="ribbon_cell">&nbsp;</td>
					</tr>
				</table>

				<!-- Header -->
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header">
					<tr>
						<td id="header_wrapper" align="center">
							<?php
							$header_image = apply_filters( 'woocommerce_email_header_image', get_option( 'woocommerce_email_header_image' ) );

							// Fall back to the theme logo when no header image is set in WooCommerce settings.
							if ( ! $header_image ) {
								$header_image = get_stylesheet_directory_uri() . '/assets/images/sunset-view-farm-logo.png';
							}

							if ( $header_image ) :
								?>
								<img src="<?php echo esc_url( $header_image ); ?>" alt="<?php bloginfo( 'name' ); ?>" id="header_logo" />
							<?php else : ?>
								<h1 id="header_title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
							<?php endif; ?>

							<div id="header_badge">
								<span id="header_badge_icon" aria-hidden="true">&#10003;</span>
								<span id="header_badge_text"><?php echo esc_html( $email_heading ); ?></span>
							</div>
						</td>
					</tr>
				</table>

				<!-- Body -->
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
					<tr>
						<td align="center" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body_inner">
								<tr>
									<td valign="top" id="body_content">
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td valign="top" class="body-content-padding">
													<div id="body_content_inner">
