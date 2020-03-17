<?php
return [
    'type'           => 'form',
    'wrapperClasses' => 'form ui form-evanto-settings',
    'children'       => [
        [
            'type'  => 'input',
            'label' => esc_html__('Evanto API Token', 'wiloke-evanto-login'),
            'desc'  => '<a href="https://build.envato.com/create-token/" target="_blank">Create Token</a>. List of required permissions: View and search Evanto Sites, Verify purchases the user has made, View your email address, View your account profile details, Verify purchases of your items, View your Envato Account username, View your items\' sales history, List purchases you\'ve made',
            'value' => '',
            'name'  => 'evanto_api_token'
        ],
        [
            'type'  => 'input',
            'label' => esc_html__('Evanto Client ID', 'wiloke-evanto-login'),
            'desc'  => '',
            'value' => '',
            'name'  => 'evanto_client_id'
        ],
        [
            'type'  => 'input',
            'label' => esc_html__('Evanto Client Secret', 'wiloke-evanto-login'),
            'desc'  => '',
            'value' => '',
            'name'  => 'evanto_client_secret'
        ],
        [
            'type'  => 'input',
            'label' => esc_html__('Evanto Redirect URL', 'wiloke-evanto-login'),
            'desc'  => '',
            'value' => '',
            'name'  => 'evanto_redirect_url'
        ],
        [
            'type'  => 'input',
            'label' => esc_html__('Login Redirect To', 'wiloke-evanto-login'),
            'desc'  => '',
            'value' => '',
            'name'  => 'evanto_login_redirect_to'
        ],
        [
            'type'  => 'input',
            'label' => esc_html__('Item Ids', 'wiloke-evanto-login'),
            'desc'  => '',
            'value' => '',
            'name'  => 'evanto_item_ids'
        ],
        [
            'type'  => 'input',
            'label' => esc_html__('Your items are:', 'wiloke-evanto-login'),
            'desc'  => 'wordpress-themes,wordpress-plugins',
            'value' => '',
            'name'  => 'evanto_item_types'
        ],
        [
            'type'  => 'input',
            'label' => esc_html__('MailBox Addresses', 'wiloke-evanto-login'),
            'desc'  => esc_html__('Please provide email addresses where you would like to receive ticket requests. Each email should separately by a comma',
                'wiloke-evanto-login'),
            'value' => '',
            'name'  => 'email_addresses'
        ]
    ],
    'buttons'        => [
        [
            'type'    => 'button',
            'label'   => esc_html__('Save Changes', 'wiloke-evanto-login'),
            'classes' => 'save green ui button'
        ]
    ]
];
