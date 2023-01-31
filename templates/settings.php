<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>

<div class="wrap">
	<h2><?php echo __( 'WPCO Settings', 'wpco' ); ?></h2>
	<div class="wpco-wrap">
		<div class="wpco-panel">
			<div class="wpco-panel-left">
				<div class="wpco-logo">
					<img src="<?php echo WPCO_DIR_URL . 'assets/images/wpco-logo.png'; ?>" alt="WPCO">
				</div>
				<div class="wpco-menu">
					<div class="wpco-menu-item wpco-menu-current" data-tab="#wpco_menu_setting">
						<a href="#wpco_menu_setting" class="wpco-menu-goto">
							<span><?php echo __( 'Settings', 'wpco' ); ?></span>
						</a>
					</div>
					<div class="wpco-menu-item" data-tab="#wpco_menu_tools">
						<a href="#wpco_menu_tools" class="wpco-menu-goto">
							<span><?php echo __( 'Tools', 'wpco' ); ?></span>
						</a>
					</div>
				</div>
			</div>
			<div class="wpco-panel-right">
				<div id="wpco_menu_setting" class="wpco-panel-main wpco-tab-current">
					<form id="wpco_settings_form" class="wpco-settings-form" method="POST">
						<?php wp_nonce_field( WPCO_SETTING_KEY_GROUP ); ?>

						<div class="wpco-submit top">
							<button type="button" class="button button-primary wpco-submit-btn"><?php echo __( 'Save group', 'wpco' ); ?></button>
						</div>

						<div class="wpco-panel-main-header">
							<span><?php echo __( 'Group Options', 'wpco' ); ?></span>
							<button type="button" class="button wpco-group-add-btn"><?php echo __( 'Add group', 'wpco' ); ?></button>
						</div>
						<?php if ( empty( $settings_data ) ) : ?>
							<div class="wpco-empty">
								<?php echo __( 'Empty group options', 'wpco' ); ?>
							</div>
						<?php endif; ?>
						<div class="wpco-panel-section-main">
							<?php foreach ( $settings_data as $g_key => $group ) : ?>
								<?php
								$fields = unserialize( $group->option_field );
								?>
								<div class="wpco-panel-section" data-group-number="<?php echo esc_attr( $group->id ); ?>">
									<div class="wpco-panel-section-header">
										<input name="group[<?php echo esc_attr( $group->id ); ?>][group_name]" type="text" placeholder="Group name" value="<?php echo esc_attr( $group->option_group ); ?>">
										<div class="wpco-panel-group-action">
											<button type="button" class="button mr-8 wpco-fields-add-btn"><?php echo __( 'Add field', 'wpco' ); ?></button>
											<button type="button" class="button wpco-group-delete-btn"><?php echo __( 'Delete group', 'wpco' ); ?></button>
										</div>
									</div>
									<div class="wpco-controls">
										<?php if ( $fields ) : ?>
											<?php foreach ( $fields as $f_key => $field ) : ?>
												<div class="wpco-fields">
													<div class="field input">
														<input type="text" name="group[<?php echo esc_attr( $group->id ); ?>][fields][<?php echo esc_attr( $f_key ); ?>][field_title]" class="wpco-input" placeholder="<?php echo __( 'Field title', 'wpco' ); ?>" value="<?php echo esc_attr( $field['field_title'] ); ?>">
													</div>
													<div class="field input">
														<input type="text" name="group[<?php echo esc_attr( $group->id ); ?>][fields][<?php echo esc_attr( $f_key ); ?>][field_name]" class="wpco-input" placeholder="<?php echo __( 'Field name', 'wpco' ); ?>" value="<?php echo esc_attr( $field['field_name'] ); ?>">
													</div>
													<div class="field select">
														<select name="group[<?php echo $group->id; ?>][fields][<?php echo $f_key; ?>][field_type]">
															<?php foreach ( WPCO_TYPES as $key => $type ) : ?>
																<option value="<?php echo $key; ?>" <?php echo $field['field_type'] == $key ? 'selected' : ''; ?>><?php echo $type; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="field action">
														<button type="button" class="button wpco-field-delete-btn"><?php echo __( 'Delete field', 'wpco' ); ?></button>
													</div>
												</div>
											<?php endforeach; ?>
										<?php else : ?>
											<div class="wpco-empty-field"><?php echo __( 'Empty field options', 'wpco' ); ?></div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="wpco-submit bottom">
							<button type="button" class="button button-primary wpco-submit-btn"><?php echo __( 'Save group', 'wpco' ); ?></button>
						</div>
					</form>
				</div>
				<div id="wpco_menu_tools" class="wpco-panel-main">
					<div class="wpco-settings-form">
						<div class="wpco-panel-main-header">
							<span><?php echo __( 'WPCO Tools', 'wpco' ); ?></span>
						</div>
						<div class="wpco-panel-section-main">
							<div class="wpco-panel-section">
								<div class="wpco-panel-section-header">
									<?php echo __( 'Import', 'wpco' ); ?>
								</div>
								<div class="wpco-controls">
									<div class="wpco-fields wpco-fields-tool">
										<input type="text" class="input-file mr-8">
										<button type="button" class="button"><?php echo __( 'Import', 'wpco' ); ?></button>
									</div>
								</div>
							</div>
							<div class="wpco-panel-section">
								<div class="wpco-panel-section-header">
									<?php echo __( 'Export', 'wpco' ); ?>
								</div>
								<div class="wpco-controls">
									<div class="wpco-fields wpco-fields-tool">
										<input type="text" class="input-file mr-8">
										<button type="button" class="button"><?php echo __( 'Export', 'wpco' ); ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	let wpco_types = <?php echo json_encode( WPCO_TYPES ); ?>;
</script>
