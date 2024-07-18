/**
 * 消息记录
 * @param {string} msg 消息
 */
export function pkng_web_log(msg = "") {
    console.log("[Puock-NG] " + msg);
}

/**
 * 返回类名称
 * @param {string} str 类名称
 * @returns string
 */
export function pkng_get_class_name(str = "") {
    return intelligent_obj.name + "-" + str;
}

/**
 * 重定向页面
 * @param {string} adr 地址
 */
export function pkng_redirect(adr = "") {
    pkng_web_log("Redirect!");
    if (intelligent_obj.is_pjax) {
        //InstantClick.go(url)
    } else {
        window.location.href = adr;
    }
}

//toast
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

import $ from 'jquery';
var notyf = undefined;
$(function () {
    notyf = new Notyf({
        types: [
            {
                type: 'info',
                background: 'blue',
                icon: {
                    className: 'ift kbk-info',
                    tagName: 'i',
                    text: '',
                    color: '#fff'
                }
            }
        ]
    });
});


/**
 * 显示消息提示
 * @param {string} text 消息内容
 * @param {string} type 消息类型
 */
export function pkng_push_notify(text, type = "info") {
    if (notyf === undefined) {
        pkng_web_log("Notyf Init Err");
        return;
    }
    if (type === 'info') {
        notyf.open({
            type: 'info',
            message: text
        });
    }
    else if (type === 'warning') {
        notyf.open({
            type: 'warning',
            message: text
        });
    }
}