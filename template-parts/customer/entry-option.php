<?php $option = isset( $args['option'] ) ? $args['option'] : false;?>

<div class="option__container">
    <div class="option__wrap <?php echo ( $option ) ? 'option--available' : ''; ?>">
        <div class="top-text__container">
            <span class="top-text"></span>
        </div>
        <a href="<?php echo esc_url( get_post_meta( $option, 'company_option_pdf', true )["url"] ); ?>" target="_blank">
            <div class="option__image">
                <div class="image__wrap">
                    <img src="<?php echo get_the_post_thumbnail_url( $option ); ?>" alt="" />
                </div>
            </div>
            <div class="option__title">
                <h2 class="title"><?php _e( get_the_title( $option ), 'vrchecke' );?></h2>
            </div>
            <div class="option__amount">
                <?php
$currency          = get_theme_mod( 'company_option_amount_symbol', '$' );
$position_currency = get_theme_mod( 'company_option_amount_symbol_position', 'After' );
if ( $position_currency === 'Before' ) {
    ?>
                    <h2 class="amount"><span class="amount__currency">&nbsp;<?php _e( $currency, 'vrchecke' );?></span><span class="amount__value"><?php _e( get_post_meta( $option, 'company_option_amount', true ), 'vrchecke' );?></span></h2>

                <?php } else {?>
                <h2 class="amount"><span class="amount__value"><?php _e( get_post_meta( $option, 'company_option_amount', true ), 'vrchecke' );?><span class="amount__currency"><?php _e( $currency, 'vrchecke' );?>&nbsp;</span></span></h2>
<?php }?>
                <p class="amount__detail"></p>
            </div>
        </a>
        <div class="option__select-btn">
            <div class="select-btn">
                <button type="button" class="select-button <?php echo ( $args['selected'] ) ? 'btn--selected' : ''; ?>"><?php _e( 'Someething', 'vrchecke' );?></button>
            </div>
        </div>
        <input type="radio" name="selected_company" value="<?php echo $option; ?>" style="visibility:hidden;">
    </div>
</div>