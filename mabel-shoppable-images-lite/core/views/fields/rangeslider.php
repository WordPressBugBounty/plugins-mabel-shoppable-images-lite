<?php
/** @var \MABEL_SILITE\Core\Models\Range_Option $option */
?>

<input
        style="opacity: 0;"
        name="<?php echo esc_attr( $option->name ); ?>"
        type="range"
        min="<?php echo esc_attr( $option->min ); ?>"
        max="<?php echo esc_attr( $option->max ); ?>"
        step="<?php echo esc_attr( $option->step ); ?>"
        value="<?php echo esc_attr( $option->value ); ?>"
/>

<?php
if ( isset( $option->extra_info ) ) {
    echo '<div class="p-t-1 extra-info">' . esc_html( $option->extra_info ) . '</div>';
}
?>
