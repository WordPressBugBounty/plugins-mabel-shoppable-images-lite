<?php
use \MABEL_SILITE\Core\Common\Managers\Config_Manager;
use \MABEL_SILITE\Core\Common\Html;
/** @var \MABEL_SILITE\Core\Models\Start_VM $model */

add_thickbox();
?>

<div class="padding-t">
	<div class="mabel-container">
		<div class="mabel-row">
			<div class="mabel-eight mabel-columns">
				<h2 class="nav-tab-wrapper">
					<?php
						foreach( $model->sections as $section )
						{
							echo
								'<a data-tab="options-' . esc_attr( $section->id ) . '" href="#" class="nav-tab'.($section->active === true? '  nav-tab-active':'').'">
										<i class="dashicons dashicons-'. esc_attr( $section->icon ) .'"></i>
										<span>'. esc_html( $section->title ).'</span>
									</a>';
						}
						do_action( $model->slug . '-add-tabs' );
					?>
				</h2>
				<form action="options.php" id="<?php echo esc_attr( $model->slug ) ?>-form" method="POST">
					<?php
					settings_fields( $model->slug );
					foreach($model->sections as $section)
					{
						echo '<div class="tab tab-options-'. esc_attr( $section->id ) .'" '.($section->active === true? '':'style="display:none;"').'>';
						if($section->has_options())
						{
							echo '<table class="form-table">';
							foreach($section->get_options() as $o)
							{
								echo '<tr>';
								if(!empty($o->title))
									echo '<th scope="row">' . esc_html( $o->title ) . '</th>';
								echo '<td>';
								Html::option($o);
								echo '</td></tr>';
							}
							echo '</table>';
						}

						do_action( $model->slug . '-add-section-content-' . $section->id );

						echo '<div class="p-t-2">
                                <span class="all-settings-saved"><i class="icon-check icon-15"></i> ' . esc_html__( 'All settings saved', 'mabel-shoppable-images-lite' ) . '</span>
                                <span style="display:none;" class="saving-settings">' . esc_html__( 'Saving settings...', 'mabel-shoppable-images-lite' ) . '</span>
                                </div>';
						echo '</div>';

					}

					do_action( $model->slug . '-add-panels' );

					foreach($model->hidden_settings as $option)
					{
						include Config_Manager::$dir . 'core/views/fields/hidden.php';
					}
					?>
				</form>
			</div>

			<div class="mabel-four mabel-columns">
				<div style="display: none;" class="mabel-sidebar sidebar-main" data-sidebar-for="main">
					<?php
					do_action( $model->slug . '-render-sidebar' );
					?>
				</div>
				<?php
				foreach( $model->sections as $section )
				{
					echo '<div style="display: none;" class="mabel-sidebar sidebar-' . esc_attr( $section->id ) . '" data-sidebar-for="options-' . esc_attr( $section->id ) . '">';
					do_action( $model->slug . '-render-sidebar-' . $section->id );
					echo '</div>';
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php
do_action( $model->slug . '-add-content' );
?>
<div
	data-context
	data-settings-key="<?php echo esc_attr( $model->settings_key ) ?>"
	data-slug="<?php echo esc_attr( $model->slug ) ?>"
	data-admin-ajax-url="<?php echo esc_attr( admin_url('admin-ajax.php') ) ?>">
</div>