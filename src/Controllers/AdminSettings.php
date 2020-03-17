<?php

namespace WilokeEvantoLogin\Controllers;

use WilokeEvantoLogin\Helpers\Render\Form\Form;

/**
 * Class WilokeEvantoLogin
 * @package WilokeEvantoLogin\Controllers
 */
class AdminSettings extends Controller
{
    private $slug = 'wiloke-evanto-login';
    
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('wp_ajax_wil_save_evanto_settings', [$this, 'saveData']);
    }
    
    public function register()
    {
        add_menu_page(
            'Evanto Settings',
            'Evanto Settings',
            'administrator',
            $this->slug,
            [$this, 'settings']
        );
    }
    
    /**
     * @return bool
     */
    public function enqueueScripts()
    {
        if (!is_admin() || !isset($_GET['page']) || $_GET['page'] !== $this->slug) {
            return false;
        }
        
        wp_enqueue_script('semantic-ui', WILOKE_EVANTO_PLUGIN_URL.'assets/semantic/semantic.min.js', ['jquery'], '1
        .0', true);
        wp_enqueue_style('semantic-ui', WILOKE_EVANTO_PLUGIN_URL.'assets/semantic/semantic.min.css');
        wp_enqueue_script('wiloke-evanto-login',
            WILOKE_EVANTO_PLUGIN_URL.'source/js/script.js',
            ['jquery'],
            WILOKE_EVANTO_LOGIN_VERSION,
            true
        );
    }
    
    public function saveData()
    {
        if (!current_user_can('administrator')) {
            wp_send_json_error([
                'msg' => esc_html__('Unfortunately, You do not have permission to access this page',
                    'wiloke-evanto-login'
                )
            ]);
        }
        
        if (!isset($_POST['data']) || empty($_POST['data'])) {
            wp_send_json_error(['msg' => esc_html__('The data is required', 'wiloke-evanto-login')]);
        }
        
        $aData = [];
        foreach ($_POST['data'] as $aField) {
            $aData[sanitize_text_field($aField['name'])] = sanitize_text_field($aField['value']);
        }
        
        update_option($this->optionKey, $aData);
        
        wp_send_json_success([
            'msg' => esc_html__('Congrats! The data has been saved successfully',
                'wiloke-evanto-login'
            )
        ]);
    }
    
    public function settings()
    {
        $this->getConfig();
        $oForm = new Form();
        
        $this->getOption();
        
        if (!empty($this->aOptions)) {
            foreach ($this->aSettings['children'] as $key => $aField) {
                $this->aSettings['children'][$key]['value'] =
                    isset($this->aOptions[$aField['name']]) ? $this->aOptions[$aField['name']] : '';
            }
        }
        
        try {
            echo $oForm->setConfiguration($this->aSettings)
                       ->beforeRenderElements()
                       ->render()
            ;
        } catch (\Exception $e) {
            if (WP_DEBUG) {
                echo $e->getMessage();
                die;
            }
        }
    }
}
