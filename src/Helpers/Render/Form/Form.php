<?php

namespace WilokeEvantoLogin\Helpers\Render\Form;

use WilokeEvantoLogin\Helpers\Render\RenderAbleAbstract;
use WilokeEvantoLogin\Helpers\Render\RenderAbleInterface;

/**
 * Class Form
 * @package WilokeEvantoLogin\Helpers\Form
 */
class Form extends RenderAbleAbstract implements RenderAbleInterface
{
    /**
     * @return string
     */
    public function render(): string
    {
        $form = '<form class="'.esc_attr($this->getAttribute('wrapperClasses', 'form ui')).'" method="'
                .$this->getAttribute('formMethod', 'POST').'" action="'.$this->getAttribute('formAction', '').'">';
        
        $form .= '<div class="form-message ui message hidden"></div>';
        
        foreach ($this->aElements as $oElement) {
            $form .= $oElement->render();
        }
        
        $aButtons = $this->getAttribute('buttons');
        if (is_array($aButtons)) {
            $oButton = new Button();
            foreach ($aButtons as $aButton) {
                $form .= $oButton->setConfiguration($aButton)->render();
            }
        }
        
        $form .= '</form>';
        
        return $form;
    }
}
