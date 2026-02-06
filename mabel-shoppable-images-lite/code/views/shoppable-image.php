<?php
/**
 * @var \MABEL_SILITE\Code\Models\Shoppable_Image_VM $model
 */
?>

<div
        class="mabel-siwc-img-wrapper"
        data-sw-text="<?php echo esc_attr( $model->button_text ); ?>"
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped via wc_esc_json/_wp_specialchars inside thing_to_html_attribute_string()
        echo 'data-sw-tags="' . \MABEL_SILITE\Code\Services\Woocommerce_Service::thing_to_html_attribute_string( $model->tags ). '"';
        ?>
        data-sw-icon="<?php echo esc_attr( $model->icon ); ?>"
        data-sw-size="<?php echo esc_attr( $model->size ); ?>"
>
    <img src="<?php echo esc_attr( $model->image ); ?>" />
</div>
