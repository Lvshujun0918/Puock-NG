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