__webpack_public_path__ = intelligent_obj.rooturl + '/dist/';

//导入基础包
import * as common from './common';
import $ from 'jquery';

//引入样式
import './style/footer.less';
//老样式
import './style/style.less';

//引入动画
import 'animate.css/animate.compat.css';

//涟漪效果
import ripplet from 'ripplet.js';

//黑暗模式
import * as nightmode from './chunk/nightmode';

//侧边栏粘贴
import stickySidebar from 'sticky-sidebar';

//引入bootstrap
import { Tooltip } from 'bootstrap';
import 'bootstrap/scss/bootstrap.scss';

//导入智能对象
window.i = intelligent_obj;

//输出一下webpack模式
common.pkng_web_log('Using Webpack (exp) ' + intelligent_obj.ver);

//判断调试模式
if (intelligent_obj.debug === "1") {
    common.pkng_web_log('Debug Mode On');
}

//首页路由
if (intelligent_obj.route === 'index') {
    common.pkng_web_log('Index Route');
}

//文章路由
if (intelligent_obj.route === 'post' || intelligent_obj.route === 'single') {
    common.pkng_web_log('Post Route');
    import('./chunk/post');
}


$(function () {
    common.pkng_web_log('Load Func Start');
    //检测并且自动切换黑暗模式
    nightmode.autoToggleMode();
    //黑暗模式切换按钮
    $(document).on("click", ".colorMode", function () {
        nightmode.saveAndToggleMode();
    });

    //搜索按钮
    $(document).on("click", ".search-modal-btn", function () {
        common.pkng_web_log("Click Search Btn");
        import('./chunk/search').then((search) => {
            search.searchToggle();
        });
    });
    $(document).on("click", "#search-backdrop", function () {
        import('./chunk/search').then((search) => {
            search.searchToggle();
        });
    });
    //搜索表单提交
    $(document).on("submit", ".global-search-form", (e) => {
        //阻断默认提交事件
        e.preventDefault();
        const el = $(e.currentTarget);
        //跳转地址
        common.pkng_redirect(intelligent_obj.homeurl + "/?" + el.serialize())
    })

    //初始化涟漪效果
    const args = {
        opacity: 0.4,
        spreadingDuration: ".6s",
    }
    //设置涟漪
    $('.btn,.ww').on('mousedown', function () {
        common.pkng_web_log('Ripple Start');
        ripplet(arguments[0], args);
    });

    //侧边栏粘滞
    new stickySidebar('#sidebar', {
        topSpacing: 20,
        bottomSpacing: 20,
        containerSelector: '.pk-scroll-wrap',
        innerWrapperSelector: '.sidebar-main'
    });

    //提示初始化
    let el = $("[data-bs-toggle=\"tooltip\"]");
    el.each(function (e) {
        common.pkng_web_log('Tooltip Start');
        new Tooltip($(this), {
            placement: 'bottom', trigger: 'hover'
        });
    });
});

