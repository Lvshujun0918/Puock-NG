<?php

namespace Puock\Theme\setting\options;

class OptionFooter extends BaseOptionItem{

    function get_fields(): array
    {
        return [
            'key' => 'footer',
            'label' => __('页脚设置', PUOCK),
            'icon'=>'dashicons-align-wide',
            'fields' => [
                [
                    'id' => 'footer_copyright',
                    'label' => __('页脚站点版权信息', PUOCK),
                    'sdt' => '所有内容归本站版权所有',
                ],
            ],
        ];
    }
}
