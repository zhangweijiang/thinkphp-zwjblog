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
