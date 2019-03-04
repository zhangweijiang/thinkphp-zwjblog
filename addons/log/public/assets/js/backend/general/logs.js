define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'upload', 'jstree'], function ($, undefined, Backend, Table, Form, Upload) {
    var elTree = $('#treeview');// 目录元素
    var table = $("#table"); // 表格元素

    var Controller = {
        // 是否启动过表格
        startTable: false,

        action: {
            'index': "general/logs/index",
        },

        index: function () {
            Table.api.init({
                search: false,
                advancedSearch: false,
                pagination: false,
                responseHandler: function (res) {
                    var info = res.info;
                    document.getElementById('info-size').innerHTML = info.size;
                    document.getElementById('info-update_time').innerHTML = info.update_time;
                    return res;
                },
                columns: [
                    [
                        {
                            field: 'id',
                            title: 'Id',
                            sortable: true,
                            operate: false,
                        },
                        {
                            field: 'level',
                            title: 'Level',
                            searchList: {
                                'error': 'ERROR',
                                'notice': 'NOTICE',
                                'info': 'INFO',
                                'debug': 'DEBUG',
                                'sql': 'SQL'
                            },
                            formatter: function (value) {
                                var label_class;
                                switch (value) {
                                    case 'ERROR':
                                        label_class = 'danger';
                                        break;
                                    case 'NOTICE':
                                        label_class = 'warning';
                                        break;
                                    default:
                                        label_class = 'info';
                                        break;
                                }
                                return '<span class="label label-' + label_class + '">' + value + '</span>';
                            }
                        },
                        {
                            field: 'method',
                            title: __('Method'),
                            searchList: {
                                'get': 'GET',
                                'post': 'POST',
                                'put': 'PUT',
                                'patch': 'PATCH',
                                'delete': 'DELETE',
                                'copy': 'COPY',
                                'head': 'HEAD',
                                'options': 'OPTIONS',
                                'purge': 'PURGE',
                                'view': 'VIEW',
                            },
                        },
                        {
                            field: 'url',
                            title: __('Url'),
                            formatter: function (value) {
                                var str = value.substr(0, 50);
                                var tail = value.length > 50 ? '...' : '';
                                str += tail;
                                return '<a class="btn-dialog" href="' + value + '" title="Url">' + str + '</a>';
                            },
                        },
                        {
                            field: 'time',
                            title: __('Time'),
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime,
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Controller.api.events.detail,
                            formatter: function (value, row) {
                                return '<a href="javascript:;" class="btn btn-xs btn-success btn-detail"  data-toggle="tooltip" title="详情" data-table-id="table"><i class=""></i>详情</a>';
                            }
                        }
                    ]
                ],
            });

            Controller.api.bindevent();
        },

        api: {
            events: {
                detail: {
                    'click .btn-detail': function (e, value, row, index) {
                        var content = '<div style="white-space: pre-wrap;background: #333;color: #fff; padding: 10px;padding-top: 3px;min-height: 600px;">' + row.content + '</div>';
                        layer.open({
                            type: 1,
                            title: '详细信息',
                            fixed: true, //不固定
                            maxmin: true,
                            area: ['800px', '600px'],
                            content: content,
                            shade: 0
                        });
                    }
                }
            },

            // 修改表格数据
            diyForm: function (file_paths) {
                var params = '?file_paths=' + file_paths;
                var url = Controller.action.index + params;
                if (this.startTable) {
                    table.bootstrapTable('refresh', {url: url});
                } else {
                    table.bootstrapTable({
                        url: url,
                    });
                    Table.api.bindevent(table);
                    this.startTable = true;
                }
            },

            bindevent: function () {
                elTree.jstree("destroy");
                Controller.api.rendertree(nodeData);
                $(document).on("click", "#expandall", function () {
                    elTree.jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
                // 开关目录
                $(document).on("click", "a.btn-channel", function () {
                    $("#right-content").toggleClass("col-md-9", $("#left-content").hasClass("hidden"));
                    $("#left-content").toggleClass("hidden");
                });
            },

            rendertree: function (content) {
                elTree
                    .on("changed.jstree", function (e, data) {
                        if (data.action == 'model' || (data.node && data.node.type == 'file')) {
                            Controller.api.diyForm(data.selected[0]);
                        }
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                        },
                        "types": {
                            "folder": {
                                "icon": "jstree-folder",
                            },
                            "file": {
                                "icon": "jstree-file",
                            }
                        },
                        "plugins": ["types"],
                        "core": {
                            'check_callback': false,
                            "data": content
                        }
                    });
            }
        }
    };
    return Controller;
});