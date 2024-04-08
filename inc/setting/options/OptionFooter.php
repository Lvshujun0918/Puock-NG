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
                    'id' => 'footer_copyright_left',
                    'label' => __('页脚站点左侧显示内容', PUOCK),
                    'sdt' => '所有内容归本站版权所有',
                ],
                [
                    'id' => 'footer_copyright_right',
                    'label' => __('页脚站点右侧侧显示内容', PUOCK),
                    'tips' => __('可在此放置ICP备案号', PUOCK),
                    'sdt' => '',
                ],
            ],
        ];
    }
}
