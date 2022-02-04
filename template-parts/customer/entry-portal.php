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
$retval      = '';
$invoice_msg = '';
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
        get_template_part( 'template-parts/customer/entry', 'customer-form', array( 'user' => $user, 'entry' => $entry ) );
        get_template_part( 'template-parts/customer/entry', 'invoice' );
        get_template_part( 'template-parts/customer/entry', 'selection-form', array( 'first_option' => $first_option, 'second_option' => $second_option, 'third_option' => $third_option, 'selected_option' => $selected_option ) );
        ?>
				</div>

			<?php

    endif; // <!-- Entry Check -->

    if ( ! $entry ) {?>
			<h3>You do not have an entry added.</h3>
			</div>

	<?php }
endif;?>