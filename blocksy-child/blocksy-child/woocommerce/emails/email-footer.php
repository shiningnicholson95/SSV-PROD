<?php
/**
 * Sunset View Farm — Email Footer override.
 * Based on woocommerce/templates/emails/email-footer.php
 *
 * The footer text is still pulled dynamically from the
 * "woocommerce_email_footer_text" option / filter, with the same
 * {site_title} / {site_url} placeholder replacement core provides.
 *
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;
?>
													</div>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<!-- Secondary CTA -->
							<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_footer_cta">
								<tr>
									<td align="center" class="footer-cta-padding">
										<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" id="footer_cta_button">
											<?php esc_html_e( 'Continue Shopping', 'sunset-view-farm' ); ?>
										</a>
									</td>
								</tr>
							</table>

						</td>
					</tr>
				</table>

				<!-- Footer -->
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" id="template_footer">
					<tr>
						<td valign="top" class="footer-padding">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td colspan="2" valign="middle" id="credit">
										<?php
										$footer_text = wpautop( wp_kses_post( wptexturize( get_option( 'woocommerce_email_footer_text' ) ) ) );
										$footer_text = str_replace( '{site_title}', get_bloginfo( 'name' ), $footer_text );
										$footer_text = str_replace( '{site_url}', esc_url( home_url( '/' ) ), $footer_text );
										echo apply_filters( 'woocommerce_email_footer_text', $footer_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
</div>
</body>
</html>
