//导入基础包
import * as common from '../common';
import $ from 'jquery';

//高亮
import hljs from 'highlight.js/lib/common';
import * as hljsln from '../inc/hljsline-number';
//引入主题
import 'highlight.js/styles/base16/humanoid-dark.css';

//toastr
import toastr from 'toastr';

$(function () {
    common.web_log_push('Post Chunk Loaded');

    //高亮时避免转义
    hljs.configure({ ignoreUnescapedHTML: true });
    $('#post').find("pre").each((index, block) => {
        const el = $(block);
        const codeChildClass = el.children("code") ? el.children("code").attr("class") : undefined;
        if (codeChildClass) {
            if (codeChildClass.indexOf("katex") !== -1 || codeChildClass.indexOf("latex") !== -1 || codeChildClass.indexOf("flowchart") !== -1
                || codeChildClass.indexOf("flow") !== -1 || codeChildClass.indexOf("seq") !== -1 || codeChildClass.indexOf("math") !== -1) {
                return;
            }
        }

        el.before("<div class='pk-code-tools' data-pre-id='hljs-item-" + index + "'><div class='dot'>" +
            "<i></i><i></i><i></i></div><div class='actions'><div><i class='ift kbk-copy cp-code' data-clipboard-target='#hljs-item-" + index + "'></i></div></div></div>")
        //执行高亮
        hljs.highlightElement(block);
        //行号
        hljsln.lineNumbersBlock(block);
    });

    //复制提示
    document.body.oncopy = function() {
        toastr.success("复制成功，转载请保留原文链接哦！");
    };
});