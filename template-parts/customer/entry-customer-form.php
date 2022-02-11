<?php $user = $args['user'];
$entry      = $args['entry'];
?>
<div class="portal-container">
    <div class="portal__form">
        <div class="entry-title">
            <h1><?php _e(get_theme_mod('company_form_top_heading', 'Personal Information'), 'vrchecke');?></h1>
            <a href="#" class="edit_icon">
                <span class="material-icons">
                    edit
                </span></a>
        </div>
        <form action="" method="post" id="edit_customer_form">
            <div class="portal__name-wrap  portal__input-wrap">
                <div class="name__first input__wrapper ">
                    <label for="first-name" class="first-name-label"><?php _e(get_theme_mod('company_form_first_name', 'First Name'), 'vrchecke');?></label>
                    <input value="<?php echo esc_attr($user->first_name); ?>" type="text" class="portal__input first-name" name="first-name" disabled />
                    <!-- <div class="portal__icon-wrap">																																																																																																																																																																																	</div> -->
                </div>

                <div class="name__last input__wrapper ">
                    <label for="last-name" class="last-name-label"><?php _e(get_theme_mod('company_form_last_name', 'Last Name'), 'vrchecke');?></label>
                    <input value="<?php echo esc_attr($user->last_name); ?>" type="text" class="portal__input last-name" name="last-name" disabled />
                </div>
            </div>
            <div class="portal__postal-wrap portal__input-wrap">
                <div class="input__wrapper">
                    <label for="postal-code" class="postal-code-label"><?php _e(get_theme_mod('company_form_postal_code', 'Postal Code'), 'vrchecke');?></label>
                    <input value="<?php echo esc_attr($entry->get_postal_code()); ?>" type="text" class="portal__input postal-code" name="postal-code" disabled />
                </div>
            </div>
            <div class="portal__address-wrap portal__input-wrap">
                <div class="input__wrapper">
                    <label for="address" class="address-label"><?php _e(get_theme_mod('company_form_address', 'Address'), 'vrchecke');?></label>
                    <input value="<?php echo esc_attr($user->address); ?>" type="text" class="portal__input address" name="address" disabled />
                </div>
            </div>
            <div class="portal__city-wrap  portal__input-wrap">
                <div class="input__wrapper">
                    <label for="city" class="city-label"><?php _e(get_theme_mod('company_form_city', 'City'), 'vrchecke');?></label>
                    <input value="<?php echo esc_attr($user->city); ?>" type="text" class="portal__input city" name="city" disabled />
                </div>
            </div>
            <div class="portal__phone-wrap portal__input-wrap">
                <div class="input__wrapper">
                    <label for="phone" class="phone-label"><?php _e(get_theme_mod('company_form_phone', 'Phone Number'), 'vrchecke');?></label>
                    <input value="<?php echo esc_attr($user->phone_number); ?>" name="phone" type="tel" id="phone" class="phone" disabled />
                </div>
                <span class="error-msg hidden"></span>
            </div>
            <div class="portal__submit-wrap">
                <input type="hidden" value="<?php echo wp_create_nonce('edit_customer_nonce'); ?>" name="edit_customer_nonce">
                <input type="submit" value="<?php _e(get_theme_mod('company_form_submit_btn', 'Submit'), 'vrchecke');?>" id="submit" />
                <div class="loader"></div>
            </div>
        </form>
    </div>