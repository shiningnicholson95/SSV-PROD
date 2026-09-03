<?php
/**
 * Display single product reviews (comments)
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<h2 class="woocommerce-Reviews-title">
			<?php
			$count = $product->get_review_count();
			if ( $count && wc_review_ratings_enabled() ) {
				$reviews_title = sprintf(
					esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ),
					esc_html( $count ),
					'<span>' . get_the_title() . '</span>'
				);
				echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product );
			} else {
				esc_html_e( 'Reviews', 'woocommerce' );
			}
			?>
		</h2>

		<?php if ( have_comments() ) : ?>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters(
					'woocommerce_product_review_list_args',
					array( 'callback' => 'woocommerce_comments' )
				) ); ?>
			</ol>

		<?php else : ?>

			<p class="woocommerce-noreviews">
				<?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?>
			</p>

		<?php endif; ?>

	</div>


	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no'
		|| wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">

				<?php
				$commenter = wp_get_current_commenter();

				$comment_form = array(
					'title_reply'  => have_comments()
						? esc_html__( 'Add a review', 'woocommerce' )
						: sprintf(
							esc_html__( 'Be the first to review "%s"', 'woocommerce' ),
							get_the_title()
						),
					'label_submit' => esc_html__( 'Submit', 'woocommerce' ),
					'logged_in_as' => '',
					'comment_field' => '',
				);

				if ( wc_review_ratings_enabled() ) {

					$comment_form['comment_field'] .=
						'<p class="comment-form-rating">
						<label for="rating">Your rating</label>
						<select name="rating" id="rating" required>
							<option value="">Rate…</option>
							<option value="5">Perfect</option>
							<option value="4">Good</option>
							<option value="3">Average</option>
							<option value="2">Not that bad</option>
							<option value="1">Very poor</option>
						</select>
						</p>';
				}

				$comment_form['comment_field'] .=
					'<p class="comment-form-comment">
						<label for="comment">Your review</label>
						<textarea id="comment" name="comment" required></textarea>
					</p>';

				comment_form(
					apply_filters(
						'woocommerce_product_review_comment_form_args',
						$comment_form
					)
				);
				?>

			</div>
		</div>


	<?php else : ?>

		<?php
		$login_url = add_query_arg(
			'redirect_to',
			get_permalink() . '#reviews',
			wc_get_page_permalink( 'myaccount' )
		);
		?>

		<p class="woocommerce-verification-required">
			<?php esc_html_e(
				'Only logged in customers who have purchased this product may leave a review.',
				'woocommerce'
			); ?>

			<br><br>

			<a class="button review-login-button"
			   href="<?php echo esc_url( $login_url ); ?>">
				Login to leave a review
			</a>

		</p>

	<?php endif; ?>


	<div class="clear"></div>

</div>