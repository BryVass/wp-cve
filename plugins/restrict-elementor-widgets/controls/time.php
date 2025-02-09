<?php
/**
 * Time control class
 *
 * @package codexpert\rew_date_time
 */
namespace Codexpert\Restrict_Elementor_Widgets\Controls;
use Elementor\Base_Data_Control;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Control_Time extends Base_Data_Control {

    /**
     * Get date time control type.
     *
     * Retrieve the control type, in this case `date_time`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function get_type() {
        return 'time';
    }

    /**
     * Get date time control default settings.
     *
     * Retrieve the default settings of the date time control. Used to return the
     * default settings while initializing the date time control.
     *
     * @since 1.8.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function get_default_settings() {
        return [
            'label_block' => false,
            'time_options' => [],
        ];
    }

    /**
     * Render date time control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     */
    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input id="<?php echo $control_uid; ?>" placeholder="{{ data.placeholder }}" class="elementor-time-picker flatpickr" type="time" data-setting="{{ data.name }}">
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
