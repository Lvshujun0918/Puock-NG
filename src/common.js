/**
 * 消息记录
 * @param {string} msg 消息
 */
export function web_log_push(msg = "") {
    console.log("[Puock-NG] " + msg);
}

/**
 * 返回类名称
 * @param {string} str 类名称
 * @returns string
 */
export function getClassName(str = "") {
    return intelligent_obj.name + "-" + str;
}

/**
 * 重定向页面
 * @param {string} adr 地址
 */
export function goUrl(adr=""){
    web_log_push("Redirect!");
    if (intelligent_obj.is_pjax) {
        //InstantClick.go(url)
    } else {
        window.location.href = url;
    }
}