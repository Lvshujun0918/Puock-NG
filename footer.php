<?php if (pk_is_checked('use_post_menu')) get_template_part('templates/module', 'menus') ?>
<!--返回顶部和底部-->
<div id="rb-float-actions">
    <?php echo apply_filters('pk_rb_float_actions','') ?>
    <div data-to="top" class="p-block"><i class="ift kbk-top puock-text"></i></div>
    <div data-to="bottom" class="p-block"><i class="ift kbk-down-arrow puock-text"></i></div>
</div>
<footer id="footer">
    <div class="container">
        <div class="row row-cols-md-1">
            <?php if (pk_is_checked('footer_about_me_open')): ?>
                <div class="col-md-6">
                    <div><span class="t-md pb-2 d-inline-block border-bottom border-primary"><i
                                    class="fa-regular fa-bell"></i> <?php echo pk_get_option('footer_about_me_title', '') ?></span>
                    </div>
                    <p class="mt20 t-md"><?php echo pk_get_option('footer_about_me', '') ?></p>
                </div>
            <?php endif; ?>
            <?php if (pk_is_checked('footer_copyright_open')): ?>
                <div class="col-md-6">
                    <div><span class="t-md pb-2 d-inline-block border-bottom border-primary"><i
                                    class="fa-regular fa-copyright"></i> <?php echo pk_get_option('footer_copyright_title', '') ?></span>
                    </div>
                    <p class="mt20 t-md"><?php echo pk_get_option('footer_copyright', '') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="text-center t-md">
        <div class="container">
            <div class="footer-bar">
                <div class="footer-title"><?php bloginfo('name'); ?></div>
                <div class="footer-social-button">
                    <a class="btn btn-secondary" href="https://github.com/Lvshujun0918/Puock-NG"><i class="ift kbk-github"></i></a>
                </div>
            </div>
            <hr>
            <div class="footer-bar c-sub">
                <div class="footer-bar-left">
                    <div id="footer-bar-tips">
                        <div class="copyright"><?php echo pk_get_option('footer_copyright_left');?></div>
                    </div>
                </div>
                <div class="footer-bar-right">
                    <i class="fa-brands fa-wordpress"></i>&nbsp;Theme by&nbsp;<a target="_blank" class="c-sub" href="https://github.com/Lvshujun0918/Puock-NG">Puock-NG</a>
                    <?php echo pk_get_option('footer_copyright_right');?>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<div id="gt-validate-box"></div>
<?php get_template_part('inc/metas') ?>
<?php wp_footer(); ?>
<?php if (!empty(pk_get_option('tj_code_footer', ''))): ?>
    <?php echo pk_get_option('tj_code_footer', ''); ?>
<?php endif; ?>
<?php pk_debug_print_sql_list(); ?>
</body>
</html>
