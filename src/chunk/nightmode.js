import * as common from '../common';
import $ from 'jquery';
import storage from 'simplestorage.js';

function toggleMode() {
    common.pkng_web_log('Toggle Mode!');

    //处理白天黑夜模式的图标问题
    let dn = 'd-none';
    $('#logo-light').toggleClass(dn);
    $('#logo-dark').toggleClass(dn);

    //修改
    $('.colorMode').each((_, e) => {
        //找到按钮的目标元素
        const el = $(e);
        let target;
        if (el.prop('localName') === 'i') {
            target = el;
        } else {
            target = $(el).find('i');
        }
        if (target) {
            target.toggleClass('kbk-light');
            target.toggleClass('kbk-nightmode');
        }
    });

    //切换body上的样式
    $('body').toggleClass(common.pkng_get_class_name('light'));
    $('body').toggleClass(common.pkng_get_class_name('dark'));
}

export function autoToggleMode() {
    //用户自己系统是黑暗模式
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        common.pkng_web_log('Dark Media Mode Detect.');
        if (!storage.hasKey('puock-ng-mode') || (storage.get('puock-ng-mode') != 'night')) {
            //不是夜晚模式，不符合系统设置值
            common.pkng_web_log('Exec Auto Dark Mode');
            toggleMode();
            common.pkng_push_notify("检测到系统级黑暗设置，已为您自动切换模式！");
        }
    }
}

export function saveAndToggleMode() {
    //手动切换
    common.pkng_web_log('Manual Mode Toggle');
    common.pkng_push_notify("切换成功！");

    //切换
    toggleMode();

    common.pkng_web_log('Chunk Loaded!');
    //储存下来
    if (storage.hasKey('puock-ng-mode')) {
        //获取当前的状态
        let mode = storage.get('puock-ng-mode');
        //反转状态
        mode = (mode === 'light' ? 'night' : 'light');
        document.cookie = "puock-ng-mode=" + mode;
        //存回去
        if (storage.set('puock-ng-mode', mode) === false) {
            //储存失败
            common.pkng_web_log('Mode Storage Failed!');
        }
    }
    else {
        //没存过
        storage.set('puock-ng-mode', 'night');
        document.cookie = "puock-ng-mode=night";
    }
}