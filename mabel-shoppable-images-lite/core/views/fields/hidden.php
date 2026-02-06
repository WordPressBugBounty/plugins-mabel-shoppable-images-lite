<?php
/** @var \MABEL_SILITE\Core\Models\Hidden_Option $option */
?>

<input type="hidden" name="<?php echo esc_attr( $option->name ) ?>" value="<?php echo esc_attr( $option->value ) ?>" />