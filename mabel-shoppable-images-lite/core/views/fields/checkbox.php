<?php
/** @var \MABEL_SILITE\Core\Models\Checkbox_Option $option */
?>
    <input type="hidden" name="<?php echo esc_attr($option->name); ?>" value="false" class="skip-dependency" />
    <input
            type="checkbox"
            name="<?php echo esc_attr($option->name); ?>"
            value="true"
        <?php if(in_array($option->value,array('true','1',true),true)) echo ' checked '; ?>
            id="ckb-<?php echo esc_attr($option->id); ?>"
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo isset($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; 
        ?>
    />

    <label for="ckb-<?php echo esc_attr($option->id); ?>">
        <?php echo esc_html($option->label); ?>
    </label>

<?php
if(isset($option->extra_info))
    echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>