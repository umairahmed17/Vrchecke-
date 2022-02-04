<?php ?>
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