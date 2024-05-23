/**
 * 消息记录
 * @param {string} msg 消息
 */
export function web_log_push(msg = "") {
    console.log("[Puock-NG] " + msg);
}


export function getClassName(str = "") {
    return intelligent_obj.name + "-" + str;
}