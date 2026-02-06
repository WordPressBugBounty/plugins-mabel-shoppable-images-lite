<?php
/** @var \MABEL_SILITE\Core\Models\Dropdown_Option $option */

$has_pre_text = false;

if(isset($option->pre_text)) {
	echo '<span>' . esc_html($option->pre_text) . '</span>';
	$has_pre_text = true;
}
?>

    <select
        class="widefat"
        <?php echo $has_pre_text ? 'style="padding:0 10px;width:auto;"' : ''; ?>
        name="<?php echo esc_attr( $option->name ); ?>"
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped via wp_json_encode + esc_attr
        echo isset( $option->dependency )
        ? 'data-dependency="' . esc_attr( wp_json_encode( $option->dependency ) ) . '"'
        : '';
        ?>
    >
    <?php foreach ( $option->options as $key => $value ) : ?>
        <option
            value="<?php echo esc_attr( $key ) ?>"
            <?php selected( $key, $option->value ) ?>
        >
            <?php echo esc_html( $value ) ?>
        </option>
    <?php endforeach; ?>
</select>

<?php
if(isset($option->post_text))
    echo '<span>' . esc_html( $option->post_text ) . '</span>';
?>

<?php

if(isset($option->extra_info))
    echo '<div class="p-t-1 extra-info">' . esc_html( $option->extra_info ) . '</div>';
