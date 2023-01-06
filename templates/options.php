<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die;
}

function generate_input($field)
{
    $type = $field['field_type'];
    $option_name = '_wpco_' . $field['field_name'];
    $option_value = get_option($option_name);

    switch ($type) {
        case 'text':
            $html = '<input type="text" name="data[' . esc_attr($option_name) . ']" value="' . esc_attr($option_value) . '">';
            break;
        case 'textarea':
            $html = '<textarea name="data[' . esc_attr($option_name) . ']" id="" rows="5">' . esc_textarea($option_value) . '</textarea>';
            break;

        default:
            $html = '';
            break;
    }

    return $html;
}
?>

<div class="wrap">
    <h2><?php echo __('WP Custom Options', 'wpco'); ?></h2>
    <div class="wpco-wrap">
        <div class="wpco-panel">
            <div class="wpco-panel-left">
                <div class="wpco-logo">
                    <img src="<?php echo WPCO_DIR_URL . 'assets/images/wpco-logo.png' ?>" alt="WPCO">
                </div>
                <div class="wpco-menu">
                    <?php foreach ($options_data as $key => $option) : ?>
                        <div class="wpco-menu-item <?php echo empty($key) ? 'wpco-menu-current' : '' ?>" data-tab="#wpco_menu_option_<?php echo esc_attr($option->id) ?>">
                            <a href="#wpco_menu_option_<?php echo esc_attr($option->id) ?>" class="wpco-menu-goto">
                                <span><?php echo esc_attr($option->option_group) ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="wpco-panel-right">
                <?php foreach ($options_data as $key => $option) : ?>
                    <?php
                    $fields = unserialize($option->option_field);
                    ?>

                    <div id="wpco_menu_option_<?php echo $option->id ?>" class="wpco-panel-main <?php echo empty($key) ? 'wpco-tab-current' : '' ?>">
                        <form id="wpco_settings_option_form_<?php echo $option->id ?>" class="wpco-settings-option-form" method="POST">
                            <?php wp_nonce_field(WPCO_SETTING_KEY_GROUP); ?>

                            <div class="wpco-submit top">
                                <button type="button" class="button button-primary wpco-submit-option-btn" data-form-id="wpco_settings_option_form_<?php echo $option->id ?>"><?php echo __('Save options', 'wpco'); ?></button>
                            </div>

                            <div class="wpco-panel-main-header">
                                <span><?php echo esc_attr($option->option_group) ?></span>
                            </div>
                            <?php if (empty($fields)) : ?>
                                <div class="wpco-empty">
                                    <?php echo __('Empty options', 'wpco'); ?>
                                </div>
                            <?php else : ?>
                                <div class="wpco-panel-section-main">
                                    <div class="wpco-options">
                                        <?php foreach ($fields as $f_key => $field) : ?>
                                            <div class="wpco-option">
                                                <div class="wpco-option-label">
                                                    <?php echo esc_attr($field['field_title']); ?>
                                                    <span>(<?php echo '_wpco_' . esc_attr($field['field_name']); ?>)</span>
                                                </div>
                                                <div class="wpco-option-input">
                                                    <?php echo generate_input($field); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="wpco-submit bottom">
                                <button type="button" class="button button-primary wpco-submit-option-btn" data-form-id="wpco_settings_option_form_<?php echo esc_attr($option->id) ?>"><?php echo __('Save options', 'wpco'); ?></button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>