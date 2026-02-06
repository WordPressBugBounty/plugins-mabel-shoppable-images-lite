<?php
/** @var \MABEL_SILITE\Core\Models\Text_Option $option */
?>

<input
        class="widefat"
        type="text"
        name="<?php echo esc_attr( $option->name ); ?>"
        value="<?php echo esc_attr( $option->value ); ?>"
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped via wp_json_encode + esc_attr
    echo isset( $option->dependency )
        ? 'data-dependency="' . esc_attr( wp_json_encode( $option->dependency ) ) . '"'
        : '';
    ?>
/>

<?php
$option->display_help();

if ( isset( $option->extra_info ) ) {
    echo '<div class="p-t-1 extra-info">' . esc_html( $option->extra_info ) . '</div>';
}
?>
