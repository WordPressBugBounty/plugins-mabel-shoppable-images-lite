<?php
/** @var \MABEL_SILITE\Core\Models\Text_Option $option */

$has_pre_text = false;

if(isset($option->pre_text)) {
	echo '<span>' . esc_html($option->pre_text) . '</span>';
	$has_pre_text = true;
}
?>

<input
	class="<?php echo $has_pre_text ? '' : 'widefat'; ?>"
	style="<?php echo $has_pre_text? 'width:100px;' : ''; ?>"
	type="number"
	name="<?php echo esc_attr( $option->name ) ?>"
	value="<?php echo esc_attr( $option->value ) ?>"
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped via wp_json_encode + esc_attr
    echo isset( $option->dependency )
        ? 'data-dependency="' . esc_attr( wp_json_encode( $option->dependency ) ) . '"'
        : '';
    ?>
/>
<?php

if(isset($option->post_text))
	echo '<span>' . esc_html($option->post_text) . '</span>';

if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . esc_html( $option->extra_info ) .'</div>';

?>
