<?php
//验证码
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
/**
 * puock_post_like
 * 处理文章点赞
 * @return void
 */
function puock_post_like()
{
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    $nounce = $_POST["action_nounce"];
    if(wp_verify_nonce($nounce, 'like_nounce_'.$id)!=1)
    {
        echo json_encode(array('e' => 1, 't' => '参数错误'));
        die;
    }
    if ($action == 'like') {
        $cookie_key = 'puock_like_' . $id;
        if (!empty($_COOKIE[$cookie_key])) {
            echo json_encode(array('e' => 1, 't' => '你已经点过赞了'));
            die;
        }
        $like_num = get_post_meta($id, 'puock_like', true);
        $expire = time() + (86400);
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie($cookie_key, $id, $expire, '/', $domain, false);
        if (!$like_num || !is_numeric($like_num)) {
            update_post_meta($id, 'puock_like', 1);
        } else {
            update_post_meta($id, 'puock_like', ($like_num + 1));
        }
        echo json_encode(array('e' => 0, 't' => '点赞成功', 'd' => get_post_meta($id, 'puock_like', true)));
    }
    else{
        echo json_encode(array('e' => 1, 't' => '参数错误'));
        die;
    }
    die;
}

add_action('wp_ajax_nopriv_puock_like', 'puock_post_like');
add_action('wp_ajax_puock_like', 'puock_post_like');

// 获取当前访问cookie是否点赞
function puock_post_is_like()
{
    global $post;
    return !empty($_COOKIE['puock_like_' . $post->ID]);
}

//移除wp自带的widget
function init_unregister_widgets()
{
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Nav_Menu_Widget');
//    unregister_widget( 'WP_Widget_Archives' );
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Media_Gallery');
    unregister_widget('WP_Widget_Media_Video');
    unregister_widget('WP_Widget_Media_Audio');
}

add_action('widgets_init', 'init_unregister_widgets');

// 页面添加html后缀
function html_page_permalink()
{
    global $wp_rewrite;
    if (!strpos($wp_rewrite->get_page_permastruct(), '.html')) {
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
    }
}

// 添加斜杠
function add_init_trailingslashit($string, $type_of_url)
{
    if ($type_of_url != 'single' && $type_of_url != 'page')
        $string = trailingslashit($string);
    return $string;
}

//解析get参数
function get_path_query($query)
{
    $querys = explode('&', $query);
    $params = array();
    foreach ($querys as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}

function pk_theme_footer_copyright($content)
{
    global $pk_right_slug;
    $content .= pk_get_option('footer_info');
    if(!pk_is_checked('ext_dont_show_copyright')){
        $content .= str_replace('{PUOCK_VERSION}', PUOCK_CUR_VER_STR, base64_decode($pk_right_slug));
    }
    return $content;
}

add_filter('pk_footer_info', 'pk_theme_footer_copyright', 10, 1);

//GrAvatar头像源
$gravatar_urls = array('www.gravatar.com', '0.gravatar.com', '1.gravatar.com', '2.gravatar.com', 'secure.gravatar.com', 'cn.gravatar.com');
function cn_avatar($avatar)
{
    global $gravatar_urls;
    return str_replace($gravatar_urls, 'cn.gravatar.com', $avatar);
}

function cr_avatar($avatar)
{
    global $gravatar_urls;
    return str_replace($gravatar_urls, 'cravatar.cn', $avatar);
}

function loli_avatar($avatar)
{
    global $gravatar_urls;
    return str_replace($gravatar_urls, 'gravatar.loli.net', $avatar);
}

function cn_ssl_avatar($avatar)
{
    global $gravatar_urls;
    return str_replace("http://", "https://", str_replace($gravatar_urls, 'cn.gravatar.com', $avatar));
}

function loli_ssl_avatar($avatar)
{
    global $gravatar_urls;
    return str_replace("http://", "https://", str_replace($gravatar_urls, 'gravatar.loli.net', $avatar));
}

function v2ex_ssl_avatar($avatar)
{
    global $gravatar_urls;
    return str_replace("http://", "https://", str_replace("/avatar", "/gravatar", str_replace($gravatar_urls, 'cdn.v2ex.com', $avatar)));
}

function pk_custom_avatar($avatar){
    global $gravatar_urls;
    return str_replace($gravatar_urls, pk_get_option('gravatar_custom_url'), $avatar);
}

//评论回复邮件通知
function comment_mail_notify($comment_id)
{
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    $spam_confirmed = $comment->comment_approved;
    if ($spam_confirmed != 1) {
        return;
    }
    if ($parent_id != '' && $spam_confirmed != 'spam') {
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $to = trim(get_comment($parent_id)->comment_author_email);
        if (pk_check_email_is_sysgen($to)) { // 默认生成的第三方注册邮箱，未进行修改
            return;
        }
        if ($to == $comment->comment_author_email) {
            return;
        }
        $subject = '您在 [' . get_option("blogname") . '] 的文章评论有了新的回复';
        $message = get_comment_notify_template($comment, $parent_id);
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}

//评论添加@
function pk_comment_add_at($text, $comment = '')
{
    if ($comment->comment_parent > 0) {
        $text = "<a class='c-sub' href='#comment-{$comment->comment_parent}'>@" . get_comment_author($comment->comment_parent) . "&nbsp;</a>" . $text;
    }
    return $text;
}

add_action('media_buttons', 'pk_shortcode_box_init', 99);
function pk_shortcode_box_init()
{
    $shortcodes = pk_shortcode_register();
    $output = "";
    foreach ($shortcodes as $key => $item) {
        $attr = '';
        $content = $item['content'] ?? '';
        if (isset($item['attr']) && count($item) > 0) {
            $attr = 'data-attr=\'' . json_encode($item['attr']) . '\'';
        }
        $output .= "<a href='javascript:void(0)' class='add-shortcode button button-small' data-key='{$key}' {$attr} data-content='{$content}'>{$item['name']}</a>";
    }
    echo '<a id="insert-shortcode-button" style="position:relative" class="button" 
        title="' . __('添加短代码', PUOCK) . '" data-editor="content" href="javascript:;">  
        <span>' . __('添加短代码', PUOCK) . '</span>
        </a><div id="insert-shortcode-wrap" class="pk-media-wrap" style="display: none">' . $output . '</div>';
}

//压缩HTML
function wp_compress_html()
{

    //禁止pre标签压缩
    function pre_no_compress($content)
    {
        if (preg_match_all('/<\/pre>/i', $content, $matches)) {
            $content = '<!--wp-compress-html--><!--wp-compress-html no compression-->' . $content;
            $content .= '<!--wp-compress-html no compression--><!--wp-compress-html-->';
        }
        return $content;
    }

    add_filter("the_content", "pre_no_compress");

    function wp_compress_html_main($buffer)
    {
        $initial = strlen($buffer);
        $buffer = explode("<!--wp-compress-html-->", $buffer);
        $count = count($buffer);
        $out = "";
        for ($i = 0; $i <= $count; $i++) {
            if(!($buffer[$i] ?? null)){
                continue;
            }
            if (stristr($buffer[$i], '<!--wp-compress-html no compression-->')) {
                $buffer[$i] = (str_replace("<!--wp-compress-html no compression-->", " ", $buffer[$i]));
            } else {
                $buffer[$i] = (str_replace("\t", " ", $buffer[$i]));
                $buffer[$i] = (str_replace("\n\n", "\n", $buffer[$i]));
                $buffer[$i] = (str_replace("\n", "", $buffer[$i]));
                $buffer[$i] = (str_replace("\r", "", $buffer[$i]));
                while (stristr($buffer[$i], '  ')) {
                    $buffer[$i] = (str_replace("  ", " ", $buffer[$i]));
                }
            }
            $out .= $buffer[$i];
        }
        $final = strlen($out);
        $savings = ($initial - $final) / $initial * 100;
        $savings = round($savings, 2);
        $info = "<!--压缩前为:{$initial}bytes;压缩后为:{$final}bytes;节约:{$savings}%-->";
        return $out . $info;
    }

    ob_start("wp_compress_html_main");
}

/**
 * 内页跳转链接
 *
 * @param string $content 修改前的内容
 * @return string 修改后的内容
 * @author lvshujun
 * @date 2024-03-19
 */
function pk_content_addlink($content) {
    //匹配链接
	preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/',$content,$matches);
	if ($matches) {
		foreach ($matches[2] as $val) {
			if (strpos($val,'://') !== false 
                && pk_is_cur_site($val) === false 
                && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val)) {
				$content = str_replace('href="'.$val.'"', 'href="'.pk_go_link($val).'"', $content);
			}
		}
	}
	return $content;
}
add_filter('the_content', 'pk_content_addlink');

/**
 * 获取跳转链接
 *
 * @param string $url 原链接
 * @param string $name 链接名称
 * @return string 跳转链接
 * @author lvshujun
 * @date 2024-03-19
 */
function pk_go_link($url, $name = '')
{
    //链接属于本站
    if (pk_is_cur_site($url)) {
        return $url;
    }
    //创建密码
    $url = pk_authcode($url, 'ENCODE', 'puock', 0);
    //拼合url
    $url = PUOCK_ABS_URI . '/inc/go.php?to=' . $url;
    if (!empty($name)) {
        $url .= '&name=' . base64_encode($name);
    }
    return $url;
}

/**
 * 加密函数
 * 
 * @param $string    要加密/解密的字符串
 * @param string $operation   类型，ENCODE 加密；DECODE 解密
 * @param string $key    密匙
 * @param int $expiry    有效期
 * @return string
 */
function pk_authcode($string, $operation = 'DECODE', $key = 'puock', $expiry = 0)
{
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;
    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? pk_safe_base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)
        ) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc . str_replace('=', '', pk_safe_base64_encode($result));
    }
}

//检测链接是否属于本站
function pk_is_cur_site($url)
{
    if (str_starts_with($url, home_url())) {
        return true;
    }
    return false;
}

if (pk_is_checked('use_post_menu')) {
    //生成目录锚点
    function pk_post_menu_id($content)
    {
        if (preg_match_all("/<h[1234]>(.*?)<\/h[1234]>/im", $content, $ms)) {
            foreach ($ms[1] as $i => $title) {
                $start = stripos($content, $ms[0][$i]);
                $end = strlen($ms[0][$i]);
                $level = substr($ms[0][$i], 1, 2);
                $content = substr_replace($content, "<$level id='pk-menu-{$i}'>{$title}</{$level}>", $start, $end);
            }
        }
        return $content;
    }

    add_filter("the_content", "pk_post_menu_id");
}
//兼容处理
function pk_compatible()
{
    wp_scripts()->remove("Editormd_Front");
}

add_action('wp_enqueue_scripts', 'pk_compatible', 999);

if (pk_is_checked("upload_webp")) {
    add_filter('plupload_default_settings', function ($defaults) {
        $defaults['webp_upload_error'] = false;
        return $defaults;
    }, 10, 1);

    add_filter('plupload_init', function ($plupload_init) {
        $plupload_init['webp_upload_error'] = false;
        return $plupload_init;
    }, 10, 1);
}

/**
 * 解析评论UA函数
 *
 * @param string $ua 评论UA
 * @return string 返回的网页字符串
 * @author lvshujun
 * @date 2024-04-24
 */
function pk_ua_parser($ua) {
    if($ua == null) {
        //没有UA
        return '';
    }
    //解析ua
    $result = new WhichBrowser\Parser($ua);
    //浏览器和版本号
    $pk_ua_browser = $result->browser->name . ' ' . $result->browser->version->toString();
    //操作系统
    $pk_ua_system = $result->os->toString();
    //操作系统图标
    $pk_ua_system_icon = pk_get_comment_ua_os_icon($result);
    //拼合字符串
    $output = '<span class="pk-ua-badget mt10" title="根据评论UA解析得出"><span class="pk-ua-system"><i class="'.$pk_ua_system_icon.'"></i>'.$pk_ua_system.'</span>&nbsp;<span class="pk-ua-browser">'.$pk_ua_browser.'&nbsp;</span></span>';
    return $output;
}

/**
 * 获取操作系统对应图标
 *
 * @param string $result 解析用对象
 * @return string 图标代码
 * @author lvshujun
 * @date 2024-04-28
 */
function pk_get_comment_ua_os_icon($result)
{
    $prefix = 'fa-brands ';
    $res_class = 'fa-tablet';
    if ($result -> isOs('OS X') || $result -> isOs('iOS')) {
        $res_class = 'fa-apple';
    } elseif ($result -> isOs('Android')) {
        $res_class = 'fa-android';
    } elseif ($result -> isOs('Linux')) {
        $res_class = 'fa-linux';
    } elseif ($result -> isOs('Windows')) {
        $res_class = 'fa-windows';
    } elseif ($result -> isOS('Windows Mobile')) {
        $res_class = 'fa-microsoft';
    } elseif ($result -> isOS('UIQ')) {
        //诺基亚
        $prefix = 'fa-solid ';
        $res_class = 'fa-mobile';
    }
    if ($res_class == 'fa-tablet') {
        //未匹配到
        $prefix = 'fa';
    }
    return $prefix . $res_class;
}

// 二维码生成
function pk_post_qrcode($url, $base_dir = '/cache/qrcode')
{
    $file = $base_dir . '/qr-' . md5($url) . '.png';
    if (!is_dir(PUOCK_ABS_DIR . $base_dir)) {
        if (!mkdir(PUOCK_ABS_DIR . $base_dir, 0775, true)) {
            return false;
        }
    }
    $filepath = PUOCK_ABS_DIR . $file;
    if (!file_exists($filepath)) {
        QRcode::png($url, $filepath, QR_ECLEVEL_L, 7, 1);
    }
    return $file;
}

// 评论验证码
// request url: {host}/wp-admin/admin-ajax.php?action=puock_comment_captcha
function pk_captcha()
{
    if (pk_get_option('vd_type') === 'gt') {
        return; 
    }
    $type = $_GET['type'] ?? '';
    if (!in_array($type, ['comment', 'login', 'register', 'forget-password'])) {
        wp_die();
    }
    //获取宽和高
    $width = $_GET['w'];
    $height = $_GET['h'];
    //需要四个字符
    $phraseBuilder = new PhraseBuilder(4);
    //生成验证码
    $builder = new CaptchaBuilder(null, $phraseBuilder);
    $builder->build($width, $height);
    //输出验证码
    header('Content-type: image/jpeg');
    $builder->output();
    $text = $builder->getPhrase();
    pk_session_call(function () use ($text, $type) {
        $_SESSION[$type . '_vd'] = $text;
    });
    // $result->output();
    wp_die();
}

pk_ajax_register('pk_captcha', 'pk_captcha', true);

function pk_captcha_url($type, $width = 100, $height = 40)
{
    return admin_url() . 'admin-ajax.php?action=pk_captcha&type=' . $type . '&w=' . $width . '&h=' . $height;
}

function pk_captcha_validate($type, $val, $success_clear = true)
{
    $res = false;
    pk_session_call(function () use ($type, $val, $success_clear, &$res) {
        if (isset($_SESSION[$type . '_vd']) && $_SESSION[$type . '_vd'] == $val) {
            $res = true;
            if ($success_clear) {
                unset($_SESSION[$type . '_vd']);
            }
        }
    });
    return $res;
}

function pk_get_favicon_url($url)
{
    return PUOCK_ABS_URI . '/inc/favicon.php?url=' . $url;
}

function pk_post_comment_is_closed()
{
    return pk_is_checked('close_post_comment', false);
}

//add_filter('clean_url', 'pk_compatible_githuber_md_katex', 10, 3);
function pk_compatible_githuber_md_katex($good_protocol_url, $original_url, $_context)
{
    if (false !== strpos($original_url, '/assets/vendor/katex/katex.min.js')) {
        remove_filter('clean_url', 'unclean_url');
        $url_parts = parse_url($good_protocol_url);
        return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . "' data-instant nil='";
    }
    return $good_protocol_url;
}

//获取网站标题
function pk_get_web_title()
{
    $title = pk_get_option('web_title');
    if (empty($title)) {
        $title = get_bloginfo('name');
    }
    return $title;
}

// 获取链接的target属性
function pk_link_target($echo = true)
{
    // $target = "";
    // if (!pk_is_checked('page_ajax_load') && pk_is_checked('link_blank_content')) {
    //     $target = "target=\"_blank\"";
    // }
    // if ($echo) {
    //     echo $target;
    // }
    return "";
}

// 头部样式定义
function pk_head_style_var()
{
    $vars = [
        "--puock-block-not-tran:" . pk_get_option('block_not_tran', 100) . "%",
    ];
    return ":root{" . join(";", $vars) . "}";
}

// 加载文件媒体文件
function pk_load_media_files()
{
    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'pk_load_media_files');

// debug sql
function pk_debug_print_sql_list()
{
    global $wpdb;
    $show_sql_count = pk_get_option('debug_sql_count');
    $show_sql_detail = pk_get_option('debug_sql_detail');
    $out = "<script>";
    if ($show_sql_count) {
        $out .= "console.log('共计查询SQL：" . get_num_queries() . "次，耗时：" . timer_stop() . "秒');";
    }
    if ($show_sql_detail) {
        $out .= "console.log(" . json_encode($wpdb->queries) . ");";
    }
    echo $out . "</script>";
}

function pk_get_excerpt_more_filter()
{
    return '...';
}

add_filter('excerpt_more', 'pk_get_excerpt_more_filter');


function pk_get_client_ip()
{
    return preg_replace('/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR']);
}

function pk_skeleton($type = 'default', $line = 1)
{
    switch ($type) {
        case 'comment':
        {
            $out = '<div class="pk-skeleton _comment"><div class="_h"><div class="_avatar"></div>
                <div class="_info"><div class="_name"></div><div class="_date"></div></div></div><div class="_text"><div></div><div></div><div></div></div></div>';
            break;
        }
        default:
        {
            $out = '<div class="pk-skeleton _default"><div></div><div></div><div></div><div></div></div>';
        }
    }
    if ($line > 1) {
        $out = str_repeat($out, $line);
    }
    return $out;
}

function get_entry_content_class($echo = true)
{
    $class = 'entry-content';
    if (pk_is_checked('page_link_before_icon')) {
        $class .= ' show-link-icon';
    }
    if ($echo) {
        echo $class;
    }
    return $class;
}

function pk_disable_not_admin_user_profile()
{
    if (is_admin() && !current_user_can('administrator')) {
        wp_die('您无权访问');
    }
}

if (pk_is_checked('disable_not_admin_user_profile')) {
    add_action('load-profile.php', 'pk_disable_not_admin_user_profile');
}


function pk_read_time_tip(): string
{
    $words_count = count_words();
    $read_time = ceil($words_count / 400);
    return sprintf(__('共计 %d 个字符，预计需要花费 %d 分钟才能阅读完成。'), $words_count, $read_time);
}

function pk_set_custom_seo($title, $keywords = '', $description = '')
{
    $GLOBALS['pk-seo'] = array(
        'title' => $title,
        'keywords' => $keywords,
        'description' => $description
    );
}

function pk_get_custom_seo()
{
    return $GLOBALS['pk-seo'] ?? array();
}

function pk_safe_base64_encode($string)
{
    $data = base64_encode($string);
    return str_replace(array('+', '/', '='), array('-', '_', ''), $data);
}

function pk_safe_base64_decode($string){
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}


//添加文章修改时间提示
function pk_post_expire_tips_open($content)
{
    $u_time = get_the_time('U');
    $u_modified_time = get_the_modified_time('U');
    $custom_content = '';
    if ($u_modified_time >= $u_time + (86400*pk_get_option('post_expire_tips_day',100))) {
        $updated_date = get_the_modified_time('Y-m-d H:i');
        $tips = str_replace('{date}', $updated_date, pk_get_option('post_expire_tips',''));
        $custom_content .= '<p class="fs12 c-sub">'.$tips.'</p>';
    }
    $custom_content .= $content;
    return $custom_content;
}

if (pk_is_checked('post_expire_tips_open')) {
    add_filter('the_content', 'pk_post_expire_tips_open');
}
