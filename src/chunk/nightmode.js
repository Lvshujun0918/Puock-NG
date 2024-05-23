import * as common from '../common';
import $ from 'jquery';
import storage from 'simplestorage.js';

export function toggleDayAndNight() {
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
            target.toggleClass('fa-sun');
            target.toggleClass('fa-moon');
        }
    });

    //切换body上的样式
    $('body').toggleClass(common.getClassName('light'));
    $('body').toggleClass(common.getClassName('dark'));

    //储存下来
    if (storage.hasKey('puock-ng-mode')) {
        //获取当前的状态
        let mode = storage.get('puock-ng-mode');
        //反转状态
        mode = (mode === 'light' ? 'night' : 'light');
        //存回去
        if (storage.set('puock-ng-mode', mode) === false) {
            //储存失败
            common.web_log_push('Mode Storage Failed!');
        }
    }
    else {
        //没存过
        storage.set('puock-ng-mode', 'night');
    }
}