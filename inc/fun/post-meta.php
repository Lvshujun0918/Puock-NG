<?php

use Puock\Theme\classes\meta\PuockAbsMeta;

function pk_toolbar_link(WP_Admin_Bar $bar)
{
    $menu_id = 'theme-quick-start';
    $bar->add_node(array(
        'id' => $menu_id,
        'title' => '<i class="czs-paper-plane"></i>&nbsp;Puock Theme 快捷入口',
        'href' => '#'
    ));
    $bar->add_node(array(
        'id' => 'theme-setting',
        'parent' => $menu_id,
        'title' => '<i class="czs-setting" style="color:#9627e3"></i>&nbsp;主题设置',
        'href' => pk_get_theme_option_url()
    ));
    $bar->add_node(array(
        'id' => 'theme-docs',
        'parent' => $menu_id,
        'title' => '<i class="czs-doc-file" style="color:#496cf9"></i>&nbsp;主题文档',
        'href' => 'https://licoy.cn/puock-doc.html',
        'meta' => array(
            'target' => 'blank'
        )
    ));
    $bar->add_node(array(
        'id' => 'theme-sponsor',
        'parent' => $menu_id,
        'title' => '<i class="czs-heart" style="color:#f54747"></i>&nbsp;赞助主题',
        'href' => 'https://licoy.cn/puock-theme-sponsor.html',
        'meta' => array(
            'target' => 'blank'
        )
    ));
    $bar->add_node(array(
        'id' => 'theme-group',
        'parent' => $menu_id,
        'title' => '<i class="czs-weixin" style="color:#177b17"></i>&nbsp;主题交流群',
        'href' => 'https://licoy.cn/go/puock-update.php?r=qq_qun',
        'meta' => array(
            'target' => 'blank'
        )
    ));
    $bar->add_node(array(
        'id' => 'theme-github',
        'parent' => $menu_id,
        'title' => '<i class="czs-github-logo"></i>&nbsp;Github 开源主页',
        'href' => 'https://github.com/Licoy/wordpress-theme-puock',
        'meta' => array(
            'target' => 'blank'
        )
    ));
}

if (is_user_logged_in() && current_user_can('manage_options')) {
    add_action('admin_bar_menu', 'pk_toolbar_link', 999);
}

function pk_admin_scripts()
{
    wp_enqueue_script('puock-admin', get_stylesheet_directory_uri() . '/assets/dist/admin.min.js',
        array(), PUOCK_CUR_VER_STR, true);
}

add_action('admin_enqueue_scripts', 'pk_admin_scripts');
function pk_admin_print_scripts()
{
    $settings = json_encode(array(
        'compatible' => [
            'githubermd' => defined('GITHUBER_PLUGIN_NAME')
        ]
    ));
    echo "<script type='text/javascript'>var puock_admin_setting = $settings</script>";
}

add_action('admin_print_footer_scripts', 'pk_admin_print_scripts', 1);

PuockAbsMeta::newPostMeta('pk-post-seo', [
    'title' => 'SEO设置',
    'options' => [
        array(
            "id" => "seo_keywords",
            "title" => "自定义SEO关键词",
            'desc' => '多个关键词之间使用", "分隔，默认为设置的标签',
            "type" => "text"
        ),
        array(
            "id" => "seo_desc",
            "title" => "自定义SEO描述",
            'desc' => '默认为文章前150个字符（推荐不超过150个字符）',
            "type" => "text"
        )
    ]
]);

PuockAbsMeta::newPostMeta('pk-post-basic', [
    'title' => '基本设置',
    'options' => [
        array(
            "id" => "hide_side",
            "title" => "隐藏侧边栏",
            "type" => "checkbox"
        ),
        array(
            "id" => "author_cat_comment",
            "title" => "评论仅对作者可见",
            "type" => "checkbox"
        ),
        array(
            "id" => "origin_author",
            "title" => "文章出处名称",
            "desc" => "若非原创则填写此值，包括其下一栏",
            "type" => "text"
        ),
        array(
            "id" => "origin_url",
            "title" => "文章出处链接",
            "type" => "text"
        )
    ]
]);

function pk_page_meta_basic()
{
    $link_cats = get_all_category_id_row('link_category');
    PuockAbsMeta::newPostMeta('pk-page-basic', [
        'title' => '基本设置',
        'post_type' => 'page',
        'options' => [
            array(
                "id" => "hide_side",
                "title" => "隐藏侧边栏",
                "type" => "checkbox"
            ),
            array(
                "id" => "author_cat_comment",
                "title" => "评论仅对作者可见",
                "type" => "checkbox"
            ),
            array(
                "id" => "use_theme_link_forward",
                "std" => "0",
                "title" => "内部链接使用主题链接跳转页",
                "type" => "checkbox"
            ),
            array(
                "id" => "page_links_id",
                "std" => "",
                "title" => "链接显示分类目录ID列表",
                'desc' => "（仅为<b>友情链接</b>及<b>网址导航</b>模板时有效，为空则不显示，可多选）",
                "type" => "select",
                'multiple'=>true,
                "options" => $link_cats
            ),
            array(
                "id" => "page_books_id",
                "std" => "",
                "title" => "书籍显示分类目录ID列表",
                "desc" => "（仅为<b>书籍推荐</b>模板时有效，为空则不显示，可多选）",
                "type" => "select",
                'multiple'=>true,
                "options" => $link_cats
            )
        ]
    ]);
}

pk_page_meta_basic();
