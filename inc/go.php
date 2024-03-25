<?php

include '../../../../wp-blog-header.php';
//设置标题
pk_set_custom_seo("链接跳转");
//获取网址
$url = $_GET['to'] ?? '';
//获取名称
$name = $_GET['name'] ?? '';
if (!empty($name)) {
    $name = base64_decode(str_replace(' ','+',$name));
}
$error = null;
if (empty($url)) {
    $error = '网址参数有误';
} else {
    //解密url
    $url = htmlentities(pk_authcode($url, 'DECODE', 'puock', 120));
    if ($url === '') {
        $error = '网址参数有误';
    } elseif (strpos($url, "https://") !== 0 && strpos($url, "http://") !== 0) {
        $error = "网址参数有误";
    } else {
        //是本站地址
        if (strpos($url, home_url()) === 0) {
            header("Location:" . $url);
            exit();
        }
    }
}

get_header();


?>

<div id="content" class="mt20 container min-height-container">

    <?php echo pk_breadcrumbs() ?>

    <div class="text-center p-block puock-text">
        <h3 class="mt20">跳转提示</h3>
        <?php if (!empty($error)): ?>
            <p class="mt20"><?php echo $error ?></p>
        <?php else: ?>
            <p class="mt20">
                <span>您即将离开<?php bloginfo('name') ?>跳转至</span><?php echo empty($name) ? $url : $name; ?><span> ，确定进入吗？</span>
            </p>
        <?php endif; ?>
        <div class="text-center mt20">
            <a rel="nofollow" href="<?php echo $url; ?>" class="btn btn-ssm btn-primary"><i
                        class="fa-regular fa-paper-plane"></i>&nbsp;立即进入</a>
            <a href="<?php echo home_url() ?>" class="btn btn-ssm btn-secondary"><i
                        class="fa fa-home"></i>&nbsp;返回首页</a>
        </div>
    </div>
</div>


<?php get_footer() ?>
