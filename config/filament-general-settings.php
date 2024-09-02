<?php

use App\HandlesSettings;
use Joaopaulolndev\FilamentGeneralSettings\Enums\TypeFieldEnum;

return [
    'show_application_tab' => true,
    'show_analytics_tab' => false,
    'show_seo_tab' => false,
    'show_email_tab' => false,
    'show_social_networks_tab' => false,
    'expiration_cache_config_time' => 60,
    'show_logo_and_favicon' => true,
    'show_custom_tabs' => true,
    'custom_tabs' => [
        'more_configs' => [
            'label' => 'More Configs',
            'icon' => 'heroicon-o-plus-circle',
            'columns' => 1,
            'fields' => [
                'Address' => [
                    'type' => TypeFieldEnum::Text->value,
                    'label' => 'Address',
                    'placeholder' => '123 New York Street',
                    'required' => false,
                    'rules' => 'string|max:255',
                ],
                'Header' => [
                    'type' => TypeFieldEnum::RichEditor->value,
                    'label' => 'Header',
                    'placeholder' => 'Enter',
                    'required' => false,
                    'rules' => 'string',
                    // 'default' =>  setting(),
                ],
            ]
        ],
    ]
];
