<?php

do_action( 'qm/start', 'ajax' );
//将DIR改为绝对路径
require PUOCK_ABS_DIR . '/inc/ajax/page-oauth-login.php';
do_action( 'qm/lap', 'ajax' );
require PUOCK_ABS_DIR . '/inc/ajax/page-front-login.php';
do_action( 'qm/lap', 'ajax' );

require PUOCK_ABS_DIR . '/inc/ajax/page-poster.php';
do_action( 'qm/lap', 'ajax' );

require PUOCK_ABS_DIR . '/inc/ajax/dialog-post-share.php';
do_action( 'qm/lap', 'ajax' );

require PUOCK_ABS_DIR . '/inc/ajax/dialog-smiley.php';
do_action( 'qm/lap', 'ajax' );

require PUOCK_ABS_DIR . '/inc/ajax/dialog-reward.php';
do_action( 'qm/lap', 'ajax' );

if (pk_is_checked('ai_chat_enable')) {
    //ai启用才引入
    require PUOCK_ABS_DIR . '/inc/ajax/ai.php';
}
do_action( 'qm/stop', 'ajax' );
