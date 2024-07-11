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
    document.body.oncopy = function () {
        toastr.success("复制成功，转载请保留原文链接哦！");
    };

    //github短代码
    $.each($(".github-card"), (_index, _el) => {
        const el = $(_el);
        const repo = el.attr("data-repo");
        if (repo) {
            common.web_log_push("Get A Github Card. Start Convert.");
            $.get(`https://api.github.com/repos/${repo}`, (res) => {
                const link_html = `class="hide-hover" href="${res.html_url}" target="_blank" rel="noreferrer"`;
                el.html(`<div class="card-header"><i class="ift kbk-github"></i><a ${link_html}>${res.full_name}</a></div>
                    <div class="card-body">${res.description}</div>
                    <div class="card-footer">
                    <div class="row">
                    <div class="col-4"><i class="ift kbk-like"></i><a ${link_html}>${res.stargazers_count}</a></div>
                    <div class="col-4"><i class="fa-solid fa-code-fork"></i><a ${link_html}>${res.forks}</a></div>
                    <div class="col-4"><i class="ift kbk-postview"></i><a ${link_html}>${res.subscribers_count}</a></div>
                    </div>
                    </div>
                `);
                el.addClass("loaded");
            }, 'json').fail((err) => {
                el.html(`<div class="alert alert-danger"><i class="fa fa-error"></i>&nbsp;请求Github项目详情异常：${repo}</div>`)
            });
        }
    })
});