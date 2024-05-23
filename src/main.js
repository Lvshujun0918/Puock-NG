__webpack_public_path__ = intelligent_obj.rooturl + '/dist/';

//导入基础包
import * as common from './common';
import $ from 'jquery';

//涟漪效果
import ripplet from 'ripplet.js';

//侧边栏粘贴
import stickySidebar from 'sticky-sidebar';

//导入智能对象
window.i = intelligent_obj;

//输出一下webpack模式
common.web_log_push('Using Webpack (exp) ' + intelligent_obj.ver);

//判断调试模式
if (intelligent_obj.debug === "1") {
    common.web_log_push('Debug Mode On');
}

//首页路由
if (intelligent_obj.isindex === "1") {
    common.web_log_push('Index Route');
}

$(function () {
    common.web_log_push('Load Func Start');

    //黑暗模式切换按钮
    $(document).on("click", ".colorMode", function () {
        import("./chunk/nightmode").then((nightmode) => {
            common.web_log_push('Chunk Loaded!');
            nightmode.toggleDayAndNight();
        });
    });

    //初始化涟漪效果
    const args = {
        opacity: 0.4,
        spreadingDuration: ".6s",
    }
    //设置涟漪
    $('.btn,.ww').on('mousedown', function () {
        common.web_log_push('Ripple Start');
        ripplet(arguments[0], args);
    });

    new stickySidebar('#sidebar', {
        topSpacing: 20,
        bottomSpacing: 20,
        containerSelector: '.pk-scroll-wrap',
        innerWrapperSelector: '.sidebar-main'
    })
});

