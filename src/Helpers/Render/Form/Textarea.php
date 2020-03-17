<?php

namespace WilokeEvantoLogin\Helpers\Render\Form;

use WilokeEvantoLogin\Helpers\Render\RenderAbleAbstract;
use WilokeEvantoLogin\Helpers\Render\RenderAbleInterface;

/**
 * Class Input
 * @package WilokeEvantoLogin\Helpers\Form
 */
class Textarea extends RenderAbleAbstract implements RenderAbleInterface
{
    /**
     * @return string
     */
    public function render(): string
    {
        $id = $this->getAttribute('id', uniqid('textarea'));
        ?>
        <div class="<?php echo esc_attr($this->getAttribute('wrapperClasses', 'field')); ?>">
            <?php if ($this->getAttribute('label')) : ?>
                <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($this->getAttribute('label')); ?></label>
            <?php endif; ?>
            <textarea id="<?php echo esc_attr($id); ?>"
                      type="<?php echo esc_attr($this->getAttribute('inputType', 'text')); ?>"
                      name="<?php echo esc_attr($this->getAttribute('name')); ?>"
                      class="<?php echo esc_attr($this->getAttribute('classes')); ?>"
                      placeholder="<?php echo esc_attr($this->getAttribute('placeholder')); ?>"
            ><?php echo esc_attr($this->getAttribute('value')); ?></textarea>
    
            <?php $this->renderDesc(); ?>
        </div>
        <?php
    }
}
