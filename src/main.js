__webpack_public_path__ = intelligent_obj.rooturl + '/dist/';

//导入基础包
import * as common from './common';
import $ from 'jquery';

//引入样式
import './style/footer.less';
//老样式
import './style/style.less';

//涟漪效果
import ripplet from 'ripplet.js';

//黑暗模式
import * as nightmode from './chunk/nightmode';

//侧边栏粘贴
import stickySidebar from 'sticky-sidebar';

//引入bootstrap
import { Tooltip } from 'bootstrap';
import 'bootstrap/scss/bootstrap.scss';

//toast
import toastr from 'toastr';
import 'toastr/build/toastr.css';

//导入智能对象
window.i = intelligent_obj;

//输出一下webpack模式
common.web_log_push('Using Webpack (exp) ' + intelligent_obj.ver);

//判断调试模式
if (intelligent_obj.debug === "1") {
    common.web_log_push('Debug Mode On');
}

//首页路由
if (intelligent_obj.route === 'index') {
    common.web_log_push('Index Route');
}

//文章路由
if (intelligent_obj.route === 'post' || intelligent_obj.route === 'single') {
    common.web_log_push('Post Route');
    import('./chunk/post').then((search) => {

    });
}


$(function () {
    //配置toast
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    common.web_log_push('Toastr Config Done');

    common.web_log_push('Load Func Start');
    //检测并且自动切换黑暗模式
    nightmode.autoToggleMode();
    //黑暗模式切换按钮
    $(document).on("click", ".colorMode", function () {
        nightmode.saveAndToggleMode();
    });

    //搜索按钮
    $(document).on("click", ".search-modal-btn", function () {
        common.web_log_push("Click Search Btn");
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
        common.goUrl(intelligent_obj.homeurl + "/?" + el.serialize())
    })

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
        common.web_log_push('Tooltip Start');
        new Tooltip($(this), {
            placement: 'bottom', trigger: 'hover'
        });
    });
});

