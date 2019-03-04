define([], function () {
    require.config({
    paths: {
        'async': '../addons/example/js/async',
        'BMap': ['//api.map.baidu.com/api?v=2.0&ak=mXijumfojHnAaN2VxpBGoqHM'],
    },
    shim: {
        'BMap': {
            deps: ['jquery'],
            exports: 'BMap'
        }
    }
});

require.config({
    paths: {
        'geetest': '../addons/geetest/js/geetest.min'
    }
});

require(['geetest'], function (Geet) {
    var geetInit = false;
    window.renderGeetest = function () {
        $("input[name='captcha']:visible").each(function () {
            var obj = $(this);
            var form = obj.closest('form');
            obj.parent()
                .removeClass('input-group')
                .html('<div class="embed-captcha"><input type="hidden" name="captcha" class="form-control" data-msg-required="请完成验证码验证" data-rule="required" /> </div> <p class="wait show" style="min-height:44px;line-height:44px;">正在加载验证码...</p>');

            Fast.api.ajax("/addons/geetest/index/start", function (data) {
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    https: true,
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    product: Config.geetest.product, // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    width: '100%',
                    offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                }, function (captchaObj) {
                    // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
                    geetInit = captchaObj;
                    captchaObj.appendTo($(".embed-captcha", form));
                    captchaObj.onReady(function () {
                        $(".wait", form).remove();
                    });
                    captchaObj.onSuccess(function () {
                        var result = captchaObj.getValidate();
                        if (result) {
                            $('input[name="captcha"]', form).val('ok');
                        }
                    });
                    captchaObj.onError(function () {
                        geetInit.reset();
                    });
                });
                // 监听表单错误事件
                form.on("error.form", function (e, data) {
                    geetInit.reset();
                });
                return false;
            });
        });
    };
    renderGeetest();
});

require.config({
    paths: {
        'bootstrap-markdown': '../addons/markdown/js/bootstrap-markdown.min',
        'hyperdown': '../addons/markdown/js/hyperdown.min',
        'pasteupload': '../addons/markdown/js/jquery.pasteupload'
    },
    shim: {
        'bootstrap-markdown': {
            deps: [
                'jquery',
                'css!../addons/markdown/css/bootstrap-markdown.css'
            ],
            exports: '$.fn.markdown'
        },
        'pasteupload': {
            deps: [
                'jquery',
            ],
            exports: '$.fn.pasteUploadImage'
        }
    }
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            if ($(".editor", form).size() > 0) {
                require(['bootstrap-markdown', 'hyperdown', 'pasteupload'], function (undefined, undefined, undefined) {
                    $.fn.markdown.messages.zh = {
                        Bold: "粗体",
                        Italic: "斜体",
                        Heading: "标题",
                        "URL/Link": "链接",
                        Image: "图片",
                        List: "列表",
                        "Unordered List": "无序列表",
                        "Ordered List": "有序列表",
                        Code: "代码",
                        Quote: "引用",
                        Preview: "预览",
                        "strong text": "粗体",
                        "emphasized text": "强调",
                        "heading text": "标题",
                        "enter link description here": "输入链接说明",
                        "Insert Hyperlink": "URL地址",
                        "enter image description here": "输入图片说明",
                        "Insert Image Hyperlink": "图片URL地址",
                        "enter image title here": "在这里输入图片标题",
                        "list text here": "这里是列表文本",
                        "code text here": "这里输入代码",
                        "quote here": "这里输入引用文本"
                    };
                    var parser = new HyperDown();
                    window.marked = function (text) {
                        return parser.makeHtml(text);
                    };
                    //粘贴上传图片
                    $.fn.pasteUploadImage.defaults = $.extend(true, $.fn.pasteUploadImage.defaults, {
                        fileName: "file",
                        appendMimetype: false,
                        ajaxOptions: {
                            url: Fast.api.fixurl(Config.upload.uploadurl),
                            beforeSend: function (jqXHR, settings) {
                                $.each(Config.upload.multipart, function (i, j) {
                                    settings.data.append(i, j);
                                });
                                return true;
                            }
                        },
                        success: function (data, filename, file) {
                            var ret = Upload.events.onUploadResponse(data);
                            $(this).insertToTextArea(filename, Config.upload.cdnurl + data.data.url);
                            return false;
                        },
                        error: function (data, filename, file) {
                            console.log(data, filename, file);
                        }
                    });
                    //手动选择上传图片
                    $(document).on("change", "#selectimage", function () {
                        $.each($(this)[0].files, function (i, file) {
                            $("").uploadFile(file, file.name);
                        });
                        //$("#message").pasteUploadImage();
                    });
                    $(".editor", form).each(function () {
                        $(this).markdown({
                            resize: 'vertical',
                            language: 'zh',
                            iconlibrary: 'fa',
                            autofocus: false,
                            savable: false,
                            additionalButtons: [
                                [{
                                    name: "groupCustom",
                                    data: [{
                                        name: "cmdSelectImage",
                                        toggle: false,
                                        title: "Select image",
                                        icon: "fa fa-file-image-o",
                                        callback: function (e) {
                                            parent.Fast.api.open("general/attachment/select?element_id=&multiple=true&mimetype=image/*", __('Choose'), {
                                                callback: function (data) {
                                                    var urlArr = data.url.split(/\,/);
                                                    $.each(urlArr, function () {
                                                        var url = Fast.api.cdnurl(this);
                                                        e.replaceSelection("\n" + '![输入图片说明](' + url + ' "在这里输入图片标题")');
                                                    });
                                                    e.$element.blur();
                                                    e.$element.focus();
                                                }
                                            });
                                            return false;
                                        }
                                    }, {
                                        name: "cmdSelectAttachment",
                                        toggle: false,
                                        title: "Select image",
                                        icon: "fa fa-file",
                                        callback: function (e) {
                                            parent.Fast.api.open("general/attachment/select?element_id=&multiple=true&mimetype=*", __('Choose'), {
                                                callback: function (data) {
                                                    var urlArr = data.url.split(/\,/);
                                                    $.each(urlArr, function () {
                                                        var url = Fast.api.cdnurl(this);
                                                        e.replaceSelection("\n" + '[输入链接说明](' + url + ')');
                                                    });
                                                    e.$element.blur();
                                                    e.$element.focus();
                                                }
                                            });
                                            return false;
                                        }
                                    }]
                                }]
                            ]
                        });
                        $(this).pasteUploadImage();
                    });
                });
            }
        } catch (e) {

        }

    };
});

});