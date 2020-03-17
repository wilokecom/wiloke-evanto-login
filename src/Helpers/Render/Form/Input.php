<?php

namespace WilokeEvantoLogin\Helpers\Render\Form;

use WilokeEvantoLogin\Helpers\Render\RenderAbleAbstract;
use WilokeEvantoLogin\Helpers\Render\RenderAbleInterface;

/**
 * Class Input
 * @package WilokeEvantoLogin\Helpers\Form
 */
class Input extends RenderAbleAbstract implements RenderAbleInterface
{
    /**
     * @return string
     */
    public function render(): string
    {
        $id = $this->getAttribute('id', uniqid('input'));
        ob_start();
        ?>
        <div class="<?php echo esc_attr($this->getAttribute('wrapperClasses', 'field')); ?>">
            <?php if ($this->getAttribute('label')) : ?>
                <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($this->getAttribute('label')); ?></label>
            <?php endif; ?>
            <input id="<?php echo esc_attr($id); ?>"
                   type="<?php echo esc_attr($this->getAttribute('fieldType', 'text')); ?>"
                   value="<?php echo esc_attr($this->getAttribute('value')); ?>"
                   name="<?php echo esc_attr($this->getAttribute('name')); ?>"
                   class="<?php echo esc_attr($this->getAttribute('classes')); ?>"
                   placeholder="<?php echo esc_attr($this->getAttribute('placeholder')); ?>"
            />
            
            <?php $this->renderDesc(); ?>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
