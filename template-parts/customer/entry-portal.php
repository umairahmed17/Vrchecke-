<?php

/**
 * Template part for displaying a post
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

use function VRCHECKE\VRCHECKE\Customer_Portal\insert_attachment;
use function VRCHECKE\VRCHECKE\Customer_Portal\update_selected_company;

vrchecke()->print_styles( 'vrchecke-portal' );
vrchecke()->print_styles( 'intlTelInput' );

/**
 * Processing Form
 */
if ( isset( $_POST['selected_company'] ) && isset( $_POST['selected_company_nonce'] ) ) {

    $retval = update_selected_company( get_current_user_id(), $_POST['selected_company'], $_POST['selected_company_nonce'] );
}

/**
 * Uploading Invoice
 */
if ( isset( $_FILES['invoice'] ) && isset( $_POST['invoice_nonce'] ) ) {
    $invoice_msg = insert_attachment( 'invoice', get_current_user_id(), $_POST['invoice_nonce'] );
}

if ( ! is_user_logged_in() ): ;
    ?>
		<p>Please Log in.</p>
		<?php
endif;
if ( is_user_logged_in() ):
    $user_id = get_current_user_id();
    $user    = get_userdata( $user_id );
    $entry   = vrchecke_get_entry_by_user_id( $user_id );
    if ( $entry && $entry instanceof \VRCHECKE_Form\Form_Entry ): // <!-- Entry Check -->
        $first_option    = ( $entry->get_first_option() || $entry->get_first_option() !== null ) ? (int) $entry->get_first_option() : false;
        $second_option   = ( $entry->get_second_option() && $entry->get_second_option() !== null ) ? (int) $entry->get_second_option() : false;
        $third_option    = ( $entry->get_third_option() && $entry->get_third_option() !== null ) ? (int) $entry->get_third_option() : false;
        $selected_option = ( $entry->get_selected_option() && $entry->get_selected_option() !== null ) ? (int) $entry->get_selected_option() : false;
        ?>


				<div id="post-<?php the_ID();?>" <?php post_class( 'entry-portal' );?>>

					<?php
        /**
         * Displaying form process result
         */
        echo '<div class="return-msg">' . __( $retval, 'vrchecke' ) . '</div>';
        echo '<div class="return-msg">' . __( $invoice_msg, 'vrchecke' ) . '</div>';
        ?>

					<div class="portal-container">
						<div class="portal__form">
							<div class="entry-title">
								<h1>Personal Information</h1>
								<a href="#" class="edit_icon">
									<span class="material-icons">
										edit
									</span></a>
							</div>
							<form action="" method="post" id="edit_customer_form">
								<div class="portal__name-wrap  portal__input-wrap">
									<div class="name__first input__wrapper ">
										<label for="first-name" class="first-name-label">First Name</label>
										<input value="<?php echo esc_attr( $user->first_name ); ?>" type="text" class="portal__input first-name" name="first-name" disabled />
										<!-- <div class="portal__icon-wrap">																																																																																																																																																																																	</div> -->
									</div>

									<div class="name__last input__wrapper ">
										<label for="last-name" class="last-name-label">Last Name</label>
										<input value="<?php echo esc_attr( $user->last_name ); ?>" type="text" class="portal__input last-name" name="last-name" disabled />
									</div>
								</div>
								<div class="portal__postal-wrap portal__input-wrap">
									<div class="input__wrapper">
										<label for="postal-code" class="postal-code-label">Postal Code</label>
										<input value="<?php echo esc_attr( $entry->get_postal_code() ); ?>" type="text" class="portal__input postal-code" name="postal-code" disabled />
									</div>
								</div>
								<div class="portal__address-wrap portal__input-wrap">
									<div class="input__wrapper">
										<label for="address" class="address-label">Address</label>
										<input value="<?php echo esc_attr( $user->address ); ?>" type="text" class="portal__input address" name="address" disabled />
									</div>
								</div>
								<div class="portal__city-wrap  portal__input-wrap">
									<div class="input__wrapper">
										<label for="city" class="city-label">City</label>
										<input value="<?php echo esc_attr( $user->city ); ?>" type="text" class="portal__input city" name="city" disabled />
									</div>
								</div>
								<div class="portal__phone-wrap portal__input-wrap">
									<div class="input__wrapper">
										<label for="phone" class="phone-label">Phone Number</label>
										<input value="<?php echo esc_attr( $user->phone_number ); ?>" name="phone" type="tel" id="phone" class="phone" disabled />
									</div>
									<span class="error-msg hidden"></span>
								</div>
								<div class="portal__submit-wrap">
									<input type="hidden" value="<?php echo wp_create_nonce( 'edit_customer_nonce' ); ?>" name="edit_customer_nonce">
									<input type="submit" value="Submit" id="submit" />
									<div class="loader"></div>
								</div>
							</form>
						</div>

						<div class="invoice__upload-form">
							<h3><?php _e( 'Upload your invoice', 'vrchecke' );?></h3>
							<form action="" method="post" enctype="multipart/form-data">
								<div class="portal__invoice-wrap portal__input-wrap">
									<input type="file" class="invoice portal__input" name="invoice" />
									<input type="submit" value="Submit">
									<input type="hidden" name="invoice_nonce" value="<?php echo wp_create_nonce( 'invoice_nonce_action' ); ?>">
								</div>
							</form>
						</div>

						<div class="company-select__form">
							<?php if ( ! $first_option && ! $second_option && ! $third_option ) {
            echo '<h3>' . __( 'No options available at the moment', 'vrchecke' ) . '</h3>';
        } else {?>
								<h3>Select your preferred company</h3>
								<form action="" method="post" class="company-select">
									<div class="company-options__container">
										<?php get_template_part( 'template-parts/customer/entry', 'option', array( 'option' => $first_option, 'selected' => $selected_option === $first_option ) )?>
										<?php get_template_part( 'template-parts/customer/entry', 'option', array( 'option' => $second_option, 'selected' => $second_option === $selected_option ) )?>
										<?php get_template_part( 'template-parts/customer/entry', 'option', array( 'option' => $third_option, 'selected' => $third_option === $selected_option ) )?>
									</div>

						</div>
						<div class="button-wrap">
							<input type="submit" value="<?php _e( 'Submit your selection', 'vrchecke' );?>" />
							<input type="hidden" name="selected_company_nonce" value="<?php echo wp_create_nonce( 'selected_company_nonce_action' ); ?>">
						</div>

						</form>
					<?php }?>
					</div>


				</div>
			<?php

    endif; // <!-- Entry Check -->

    if ( ! $entry ) {?>
			<h3>You do not have an entry added.</h3>
			</div>

	<?php }
endif;?>