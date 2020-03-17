<?php

namespace WilokeEvantoLogin\Helpers\Render\Form;

use WilokeEvantoLogin\Helpers\Render\RenderableAbstract;
use WilokeEvantoLogin\Helpers\Render\RenderAbleInterface;

/**
 * Class Button
 * @package WilokeEvantoLogin\Helpers\Render\Form
 */
class Button extends RenderableAbstract implements RenderAbleInterface
{
    /**
     * @return string
     */
    public function render(): string
    {
        ob_start();
        ?>
        <button type="<?php echo esc_attr($this->getAttribute('fieldType', 'submit')); ?>"
                class="<?php echo esc_attr($this->getAttribute('classes', 'ui button'));
                ?>">
            <?php echo esc_html($this->getAttribute('label', 'Submit')); ?>
        </button>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
}
