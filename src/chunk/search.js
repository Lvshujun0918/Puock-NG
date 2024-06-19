import $ from 'jquery';
import * as common from '../common';

export function searchToggle() {
    common.web_log_push('Toggle Search');
    //找到搜索框
    const search = $("#search");
    //看一下数据, 判断是否开启了
    const open = search.attr("data-open") === "true";
    //通过开启状态来判断动画
    let tag = open ? 'Out' : 'In';
    //加动画类
    search.attr("class", "animated fade" + tag + "Left");
    $("#search-backdrop").attr("class", "modal-backdrop animated fade" + tag + "Right");
    //切换状态
    search.attr("data-open", !open);
    if (!open) {
        //聚焦一下搜索框
        search.find("input").trigger("focus");
    }
}