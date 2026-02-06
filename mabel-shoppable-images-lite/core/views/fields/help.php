<?php
use MABEL_SILITE\Core\Common\Managers\Config_Manager;
/** @var \MABEL_SILITE\Core\Models\Help $help */

?>
<div class="p-t-1">
    <div style="display: none;" id="help-<?php echo esc_attr($help->id); ?>">
        <div style="padding:20px;">
            <?php include Config_Manager::$dir . 'admin/views/' . $help->template; ?>
        </div>
    </div>
    <a title="<?php echo esc_attr($help->title); ?>" href="#TB_inline?width=600&height=550&inlineId=help-<?php echo esc_attr($help->id); ?>" class="primary thickbox">
        <?php echo $help->link_title == null ?
            esc_html__('More info', 'mabel-shoppable-images-lite') :
            esc_html( $help->link_title );
        ?>
    </a>
</div>