<?php

namespace Puock\Theme\setting\options;

class OptionScript extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'script',
            'label' => __('脚本及样式', PUOCK),
            'icon' => 'dashicons-shortcode',
            'fields' => [
                [
                    'id' => 'style_color_primary',
                    'label' => __('站点主色调', PUOCK),
                    'type' => 'color',
                    'sdt' => '#1c60f3',
                ],
                [
                    'id' => 'block_not_tran',
                    'label' => __('全站区块不透明度', PUOCK),
                    'type' => 'slider',
                    'sdt' => 100,
                    'step' => 1,
                    'max' => 100,
                    'tips' => "func:(function(args){
                        return args.h('div',[
                            args.h('span',null,'".__('小于100则会进行透明显示，部分浏览器可能不兼容', PUOCK)." '),
                            args.h(args.el.nTag,{type:'primary',size:'small',round:true},'".__("当前不透明度", PUOCK)."：'+args.data.block_not_tran+'%')
                        ])
                    })(args)"
                ],
                [
                    'id' => 'tj_code_header',
                    'label' => __('头部流量统计代码', PUOCK),
                    'type' => 'textarea',
                    'sdt' => '',
                    'tips' => __("用于在页头添加统计代码（PS：若开启无刷新加载，请在标签上加上<code>data-instant</code>属性）", PUOCK)
                ],
                [
                    'id' => 'css_code_header',
                    'label' => __('头部自定义全局CSS样式', PUOCK),
                    'type' => 'textarea',
                    'placeholder' => __('例如', PUOCK).'：#header{background-color:red !important}',
                    'sdt' => '',
                    'tips' => __('用于在页头添加统自定义CSS样式', PUOCK)
                ],
                [
                    'id' => 'tj_code_footer',
                    'label' => __('底部流量统计代码', PUOCK),
                    'type' => 'textarea',
                    'sdt' => '',
                ],
            ],
        ];
    }
}
