<?php $first_option = $args['first_option'];
$second_option      = $args['second_option'];
$third_option       = $args['third_option'];
$selected_option    = $args['selected_option'];
?>
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