'use strict';
/** 
 * controllers for GoogleMap  
 * AngularJS Directive 
 */
app.controller("adminMainProductCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        $scope.setTab = function(newTab) {
            $scope.tab = newTab;
        };
        $scope.isSet = function(tabNum) {
            return $scope.tab === tabNum;
        };
        $scope.setTab2 = function(newTab) {
            $scope.tab2 = newTab;
        };
        $scope.isSet2 = function(tabNum) {
            return $scope.tab2 === tabNum;
        };
        $scope.setTabPC = function(newTab) {
            $scope.tabPC = newTab;
        };
        $scope.isSetPC = function(tabNum) {
            return $scope.tabPC === tabNum;
        };
    }
])

app.controller("adminPallCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        $scope.setTab(1)
        info.countproduct().then(function(result) {
            $scope.statcount = result.data[0]
        });

        $.fn.dataTable.ext.buttons.addproduct = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.wmsadmin.product.pmanage.passign.padd')
            }
        };
        $scope.serat = function() {

            tableAdminProductlist.columns(7).search('false').draw();
        }
        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarProductlist = [];
        var tableAdminProductlist = $('#AdminProductlistTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminproductlist.php",
                "type": "POST",
                "data": function(d) {
                    d.tconfirm = true
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [15, 30, 100],
                ['20 rows', '50 rows', '100 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addproduct',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminProductlist.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // idproduct
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // product
                "class": "",
                "data": "1",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // size
                "class": "text-center",
                "data": "2",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // feature
                "class": "text-center",
                "data": "3",
                "width": "18%",
                "orderable": false,
                "visible": true
            }, {
                // unit
                "class": "text-center",
                "data": "4",
                "width": "15%",
                "orderable": false,
                "visible": true
            }, {
                // countlist
                "class": "text-center",
                "data": "5",
                "width": "15%",
                "orderable": false,
                "visible": true
            }, {
                // countlist
                "class": "text-center",
                "data": "",
                "width": "15%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a ' + 'class="bookmark btn-transparent btn-xs registerproduct"' + '>' + '<i class="' + 'fa fa-list-alt' + '"></i>' + '</a>' + '<a class="btn btn-transparent btn-xs editproduct"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 7,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

    }
])

app.controller("adminPwaitingCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        $scope.setTab(2)
        info.countproduct().then(function(result) {
            $scope.statcount = result.data[0]
        });
        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarAdminWaitinglist = [];
        var tableAdminWaitinglist = $('#AdminWaitinglistTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminproductwaiting.php",
                "type": "POST",
                "data": function(d) {
                    d.tconfirm = false
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [15, 30, 100],
                ['20 rows', '50 rows', '100 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminWaitinglist.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // idproduct
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // product
                "class": "",
                "data": "1",
                "width": "25%",
                "orderable": false,
                "visible": true
            }, {
                // type
                "class": "",
                "data": "5",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // size
                "class": "text-center",
                "data": "2",
                "width": "15%",
                "orderable": false,
                "visible": true
            }, {
                // unit
                "class": "text-center",
                "data": "3",
                "width": "15%",
                "orderable": false,
                "visible": true
            }, {
                // register
                "class": "text-center",
                "data": "4",
                "width": "10%",
                "orderable": false,
                "visible": true
            }, {
                // state
                "class": "text-center",
                "data": "7",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                // action
                "class": "text-center",
                "data": "4",
                "width": "10%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return full[6] + ' (' + full[5] + ')';
                },
                "targets": 3,
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    if (full[2] == '') {
                        return '<span class="label label-danger">' + 'ไม่ระบุฯ' + '</span>';
                    } else {
                        return full[2];
                    }
                },
                "targets": 4,
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    if (full[3] == '') {
                        return '<span class="label label-danger">' + 'ไม่ระบุฯ' + '</span>';
                    } else {
                        return full[3];
                    }
                },
                "targets": 5,
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    if (full[7] == false) {
                        return '<a class="btn btn-transparent btn-xs state_confirm"><span class="label label-warning">' + 'W' + '</span></a>';
                    } else {
                        return '<span class="label label-success">' + 'COMPLETE' + '</span>';
                    }
                },
                "targets": 7,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs editproduct"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 8,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

        $("#AdminWaitinglistTbl tbody").on("click", ".editproduct", function(event) {
            var tr = $(this).closest('tr');
            var data = tableAdminWaitinglist.row($(this).closest('tr')).data();
            var rowid = data.DT_RowId;
            $state.go('app.wmsadmin.product.pmanage.passign.pedit', {
                "idproduct": rowid
            })
        })
        $("#AdminWaitinglistTbl tbody").on("click", ".state_confirm", function(event) {
            var tr = $(this).closest('tr');
            var data = tableAdminWaitinglist.row($(this).closest('tr')).data();
            var rowid = data.DT_RowId;
            var data = $.param({
                idproduct: rowid
            });
            $http({
                method: 'POST',
                url: 'assets/views/action/StateChange.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(response) {
                if (response[0].success == 0) {
                    SweetAlert.swal({
                        title: "ERROR",
                        text: "ไม่ได้ระบุขนาด หรือ หน่วยนับ สินค้า",
                        type: "error",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1000
                    })
                } else {
                    tableAdminWaitinglist.draw(false)
                    info.countproduct().success(function(result) {
                        $scope.statcount = result[0]
                    });
                }
            });
        })
        $("#AdminWaitinglistTbl tbody").on("click", ".proinfo", function(event) {
            var tr = $(this).closest('tr');
            var data = tableAdminWaitinglist.row($(this).closest('tr')).data();
            var rowid = data.DT_RowId;
            var data = $.param({
                idproduct: rowid
            });
            console.log('sompong')
            $scope.openAside = function(position, idproduct) {
                $aside.open({
                    templateUrl: 'assets/views/pages_productinfo.html',
                    placement: position,
                    size: 'md',
                    backdrop: true,
                    controller: function($scope, $uibModalInstance) {
                        $scope.tab = 1;
                        $scope.setTab = function(newTab) {
                            $scope.tab = newTab;
                        };
                        $scope.isSet = function(tabNum) {
                            return $scope.tab === tabNum;
                        };
                        var data = $.param({
                            tidproduct: idproduct
                        });
                        $http({
                            method: 'POST',
                            data: data,
                            url: "assets/views/action/selectproductinfo.php",
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                        }).success(function(response) {
                            $scope.productinfo = response[0]
                        });
                    }
                });
            };
            $scope.openAside('right', rowid)
        })
    }
])
app.controller("adminPformCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

    }
])
app.controller("adminPcustomCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        $scope.setTab(5)
    }
])
app.controller("adminCustomPctypeCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        $scope.setTabPC(1)
        $scope.adminpctype = {
            'idptype': '',
            'ptype': '',
            'ptype_note': '',
            'idmaterial_cost': 12,
            'idexpense_type': 4,
            'idmaterial_type': 0
        }

        $timeout(function() {
            $http({
                method: 'GET',
                url: 'assets/views/action/wmsadmin_product/db_select_expensetype.php'
            }).then(function(resultex) {
                $scope.getExpensetype = resultex.data
                $('.selectexpense-type .cs-placeholder').text(resultex.data[0].expense_type)


            });
        }, 200);

        $timeout(function() {
            $http({
                method: 'GET',
                url: 'assets/views/action/wmsadmin_product/db_select_materialtype.php'
            }).then(function(resultex) {
                $scope.getMaterialtype = resultex.data
                $('.selectmaterial-type .cs-placeholder').text(resultex.data[0].material_type)
            });
        }, 200);

        $.fn.dataTable.ext.buttons.addproduct = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.wmsadmin.product.pmanage.passign.padd')
            }
        };
        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarProductlist = [];
        var tableAdminPCtype = $('#AdminPCtypeTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminpctype.php",
                "type": "POST",
                "data": function(d) {
                    /* d.tconfirm = true*/
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 20, 50],
                ['10 rows', '20 rows', '50 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addproduct',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminPCtype.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // idpctype
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // pctype
                "data": "1",
                "width": "",
                "orderable": true,
                "visible": true
            }, {
                // expense_type
                "class": "",
                "data": "2",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // material_type
                "class": "text-center",
                "data": "3",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // ptype_note
                "class": "text-center",
                "data": "4",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // idmaterial_cost
                "class": "text-center",
                "data": "5",
                "width": "18%",
                "orderable": false,
                "visible": false
            }, {
                // idexpense_type
                "class": "text-center",
                "data": "6",
                "width": "15%",
                "orderable": false,
                "visible": false
            }, {
                // idmaterial_type
                "class": "text-center",
                "data": "7",
                "width": "15%",
                "orderable": false,
                "visible": false
            }, {
                // action
                "class": "text-center",
                "data": "",
                "width": "15%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs editpctype"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 9,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

        $("#AdminPCtypeTbl tbody").on("click", ".editpctype", function(event) {
            var data = tableAdminPCtype.row($(this).closest('tr')).data();
            $timeout(function() {
                $scope.adminpctype = {
                    'idptype': data.DT_RowId,
                    'ptype': data[1],
                    'ptype_note': data[4],
                    'idmaterial_cost': data[5],
                    'idexpense_type': data[6],
                    'idmaterial_type': data[7]
                }

            }, 10);
            $('.selectexpense-type .cs-placeholder').text(data[2])
            $('.selectexpense-type .cs-options li')
                .removeClass('cs-selected')
            $('.selectexpense-type .cs-options li').filter('[data-value]').each(function(e) {
                var dataoption = "number:" + data[6]
                if ($(this).data('value') == dataoption) {
                    $(this).addClass('cs-selected')
                }
            })

            $('.selectmaterial-type .cs-placeholder').text(data[3])
            $('.selectmaterial-type .cs-options li')
                .removeClass('cs-selected')
            $('.selectmaterial-type .cs-options li').filter('[data-value]').each(function(e) {
                var dataoption = "number:" + data[7]
                if ($(this).data('value') == dataoption) {
                    $(this).addClass('cs-selected')
                }
            })
        })

        $scope.ManagePCtype = function(dataarr) {

            if (dataarr.idptype == '') {
                dataarr.action = 'INSERT'
            } else dataarr.action = 'UPDATE'

            var data = $.param({
                datapost: dataarr
            });

            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_pctype.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                if (response.data[0].success == 1) {
                    SweetAlert.swal({
                        title: "บันทึกสำเร็จ!",
                        text: "ข้อมูลได้รับการแก้ไขแล้ว.",
                        type: "success",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1000
                    })
                    $timeout(function() {
                        $scope.adminpctype = {
                            'idptype': '',
                            'ptype': '',
                            'ptype_note': '',
                            'idmaterial_cost': 12,
                            'idexpense_type': 4,
                            'idmaterial_type': 0
                        }
                    }, 10);
                    $('.selectexpense-type .cs-placeholder').text('ไม่ระบุฯ')
                    $('.selectexpense-type .cs-options li')
                        .removeClass('cs-selected')
                    $('.selectexpense-type .cs-options li').filter('[data-value]').each(function(e) {
                        var dataoption = "number:4"
                        if ($(this).data('value') == dataoption) {
                            $(this).addClass('cs-selected')
                        }
                    })

                    $('.selectmaterial-type .cs-placeholder').text('ไม่ระบุฯ')
                    $('.selectmaterial-type .cs-options li')
                        .removeClass('cs-selected')
                    $('.selectmaterial-type .cs-options li').filter('[data-value]').each(function(e) {
                        var dataoption = "number:0"
                        if ($(this).data('value') == dataoption) {
                            $(this).addClass('cs-selected')
                        }
                    })

                    tableAdminPCtype.draw(false)
                } else {
                    SweetAlert.swal({
                        title: "ERROR",
                        text: "รายการข้อมูลซ้ำ หรือ ไม่ได้ระบุรายการหลัก",
                        type: "error",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1500
                    })


                }
            });
        }

        /**key press on textbox */
        $('#tspecing').on('keypress', function(e) {

            if (e.key == 'Enter') {
                console.log('sompong')
                $scope.ManagePCtype($scope.adminpctype)
            }
        });



    }
])
app.controller("adminCustomPcsizeCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        $scope.setTabPC(2)
        $scope.adminpcsize = {
            'idsize': '',
            'size': '',
            'size_desc': '',
            'size_note': ''
        }

        $timeout(function() {
            $http({
                method: 'GET',
                url: 'assets/views/action/wmsadmin_product/db_select_expensetype.php'
            }).then(function(resultex) {
                $scope.getExpensetype = resultex.data
                $('.selectexpense-type .cs-placeholder').text(resultex.data[0].expense_type)


            });
        }, 200);

        $timeout(function() {
            $http({
                method: 'GET',
                url: 'assets/views/action/wmsadmin_product/db_select_materialtype.php'
            }).then(function(resultex) {
                $scope.getMaterialtype = resultex.data
                $('.selectmaterial-type .cs-placeholder').text(resultex.data[0].material_type)
            });
        }, 200);


        $.fn.dataTable.ext.buttons.addpcsize = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.wmsadmin.product.pmanage.passign.padd')
            }
        };
        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarProductlist = [];
        var tableAdminPCsize = $('#AdminPCsizeTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminpcsize.php",
                "type": "POST",
                "data": function(d) {
                    /* d.tconfirm = true*/
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 20, 50],
                ['10 rows', '20 rows', '50 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addpcsize',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminPCsize.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // idsize
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // size_desc
                "class": "",
                "data": "1",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // size_desc
                "class": "",
                "data": "2",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // size_note
                "class": "text-center",
                "data": "3",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // action
                "class": "text-center",
                "data": "",
                "width": "15%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs editsize"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 5,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

        $("#AdminPCsizeTbl tbody").on("click", ".editsize", function(event) {
            var data = tableAdminPCsize.row($(this).closest('tr')).data();
            console.log(data)
            $timeout(function() {
                $scope.adminpcsize = {
                    idsize: data.DT_RowId,
                    size: data[1],
                    size_desc: data[2],
                    size_note: data[3]
                }
            }, 10);
        })

        $scope.ManagePCsize = function(dataarr) {

            if (dataarr.idsize == '') {
                dataarr.action = 'INSERT'
            } else dataarr.action = 'UPDATE'

            var data = $.param({
                datapost: dataarr
            });

            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_pcsize.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                if (response.data[0].success == 1) {
                    SweetAlert.swal({
                        title: "บันทึกสำเร็จ!",
                        text: "ข้อมูลได้รับการแก้ไขแล้ว.",
                        type: "success",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1000
                    })
                    $timeout(function() {
                        $scope.adminpcsize = {
                            'idsize': '',
                            'size': '',
                            'size_desc': '',
                            'size_note': ''
                        }
                    }, 10);
                    tableAdminPCsize.draw()
                } else SweetAlert.swal({
                    title: "ERROR",
                    text: "รายการข้อมูลซ้ำ หรือ ไม่ได้ระบุรายการหลัก",
                    type: "error",
                    confirmButtonColor: "#007AFF",
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        }

        /**key press on textbox */
        $('#tspecing').on('keypress', function(e) {
            if (e.key == 'Enter') {
                $scope.ManagePCsize($scope.adminpcsize)
            }
        });
    }
])
app.controller("adminCustomPcfeatureCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        $scope.setTabPC(3)
        $scope.adminpcfeature = {
            'idfeature': '',
            'feature': '',
            'feature_desc': '',
            'feature_note': ''
        }
        $.fn.dataTable.ext.buttons.addpcfeature = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.wmsadmin.product.pmanage.passign.padd')
            }
        };
        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarProductlist = [];
        var tableAdminPCfeature = $('#AdminPCfeatureTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminpcfeature.php",
                "type": "POST",
                "data": function(d) {
                    /* d.tconfirm = true*/
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 20, 50],
                ['10 rows', '20 rows', '50 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addpcfeature',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminPCfeature.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // pctype
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // expense_type
                "class": "",
                "data": "1",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // material_type
                "class": "text-center",
                "data": "2",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // idmaterial_cost
                "class": "text-center",
                "data": "3",
                "width": "18%",
                "orderable": false,
                "visible": false
            }, {
                // action
                "class": "text-center",
                "data": "",
                "width": "15%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs editfeature"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 5,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

        $("#AdminPCfeatureTbl tbody").on("click", ".editfeature", function(event) {
            var data = tableAdminPCfeature.row($(this).closest('tr')).data();
            console.log(data)
            $timeout(function() {
                $scope.adminpcfeature = {
                    idfeature: data.DT_RowId,
                    feature: data[1],
                    feature_desc: data[2],
                    feature_note: data[3]
                }
            }, 10);
        })

        $scope.ManagePCfeature = function(dataarr) {

            if (dataarr.idfeature == '') {
                dataarr.action = 'INSERT'
            } else dataarr.action = 'UPDATE'

            var data = $.param({
                datapost: dataarr
            });

            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_pcfeature.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                if (response.data[0].success == 1) {
                    SweetAlert.swal({
                        title: "บันทึกสำเร็จ!",
                        text: "ข้อมูลได้รับการแก้ไขแล้ว.",
                        type: "success",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1000
                    })
                    $timeout(function() {
                        $scope.adminpcfeature = {
                            'idfeature': '',
                            'feature': '',
                            'feature_desc': '',
                            'feature_note': ''
                        }
                    }, 10);
                    tableAdminPCfeature.draw()
                } else SweetAlert.swal({
                    title: "ERROR",
                    text: "รายการข้อมูลซ้ำ หรือ ไม่ได้ระบุรายการหลัก",
                    type: "error",
                    confirmButtonColor: "#007AFF",
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        }

        /**key press on textbox */
        $('#tspecing').on('keypress', function(e) {
            if (e.key == 'Enter') {
                $scope.ManagePCfeature($scope.adminpcfeature)
            }
        });
    }
])
app.controller("adminCustomPcbrandCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        $scope.setTabPC(4)
        $scope.adminpcbrand = {
            'idbrand': '',
            'brand': '',
            'brand_desc': '',
            'brand_note': ''
        }

        $.fn.dataTable.ext.buttons.addpcbrand = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.wmsadmin.product.pmanage.passign.padd')
            }
        };

        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarProductlist = [];
        var tableAdminPCbrand = $('#AdminPCbrandTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminpcbrand.php",
                "type": "POST",
                "data": function(d) {
                    /* d.tconfirm = true*/
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 20, 50],
                ['10 rows', '20 rows', '50 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addpcbrand',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminPCbrand.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // idbrand
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // brand
                "class": "",
                "data": "1",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // brand_desc
                "class": "",
                "data": "2",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // brand_note
                "class": "text-center",
                "data": "3",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // action
                "class": "text-center",
                "data": "",
                "width": "15%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs editbrand"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 5,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

        $("#AdminPCbrandTbl tbody").on("click", ".editbrand", function(event) {
            var data = tableAdminPCbrand.row($(this).closest('tr')).data();
            $timeout(function() {
                $scope.adminpcbrand = {
                    'idbrand': data.DT_RowId,
                    'brand': data[1],
                    'brand_desc': data[2],
                    'brand_note': data[3]
                }
            }, 10);
        })

        $scope.ManagePCbrand = function(dataarr) {

            if (dataarr.idbrand == '') {
                dataarr.action = 'INSERT'
            } else dataarr.action = 'UPDATE'

            var data = $.param({
                datapost: dataarr
            });

            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_pcbrand.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                if (response.data[0].success == 1) {
                    SweetAlert.swal({
                        title: "บันทึกสำเร็จ!",
                        text: "ข้อมูลได้รับการแก้ไขแล้ว.",
                        type: "success",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1000
                    })
                    $timeout(function() {
                        $scope.adminpcbrand = {
                            'idbrand': '',
                            'brand': '',
                            'brand_desc': '',
                            'brand_note': ''
                        }
                    }, 10);
                    tableAdminPCbrand.draw()
                } else SweetAlert.swal({
                    title: "ERROR",
                    text: "รายการข้อมูลซ้ำ หรือ ไม่ได้ระบุรายการหลัก",
                    type: "error",
                    confirmButtonColor: "#007AFF",
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        }

        /**key press on textbox */
        $('#tspecing').on('keypress', function(e) {
            if (e.key == 'Enter') {
                $scope.ManagePCbrand($scope.adminpcbrand)
            }
        });
    }
])
app.controller("adminCustomPcunitCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        $scope.setTabPC(5)
        $scope.adminpcunit = {
            'idunit': '',
            'unit': '',
            'unit_desc': '',
            'unit_note': ''
        }

        $.fn.dataTable.ext.buttons.addpcunit = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.wmsadmin.product.pmanage.passign.padd')
            }
        };

        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarProductlist = [];
        var tableAdminPCunit = $('#AdminPCunitTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminpcunit.php",
                "type": "POST",
                "data": function(d) {
                    /* d.tconfirm = true*/
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 20, 50],
                ['10 rows', '20 rows', '50 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addpcunit',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminPCunit.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // idunit
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // unit
                "class": "",
                "data": "1",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // unit_desc
                "class": "text-center",
                "data": "2",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // unit_note
                "class": "text-center",
                "data": "3",
                "width": "18%",
                "orderable": false,
                "visible": false
            }, {
                // action
                "class": "text-center",
                "data": "",
                "width": "15%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs editunit"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 5,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

        $("#AdminPCunitTbl tbody").on("click", ".editunit", function(event) {
            var data = tableAdminPCunit.row($(this).closest('tr')).data();
            console.log(data)
            $timeout(function() {
                $scope.adminpcunit = {
                    idunit: data.DT_RowId,
                    unit: data[1],
                    unit_desc: data[2],
                    unit_note: data[3]
                }
            }, 10);
        })

        $scope.ManagePCunit = function(dataarr) {
            if (dataarr.idunit == '') {
                dataarr.action = 'INSERT'
            } else dataarr.action = 'UPDATE'

            var data = $.param({
                datapost: dataarr
            });

            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_pcunit.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                if (response.data[0].success == 1) {
                    SweetAlert.swal({
                        title: "บันทึกสำเร็จ!",
                        text: "ข้อมูลได้รับการแก้ไขแล้ว.",
                        type: "success",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1000
                    })
                    $timeout(function() {
                        $scope.adminpcunit = {
                            'idunit': '',
                            'unit': '',
                            'unit_desc': '',
                            'unit_note': ''
                        }
                    }, 10);
                    tableAdminPCunit.draw()
                } else SweetAlert.swal({
                    title: "ERROR",
                    text: "รายการข้อมูลซ้ำ หรือ ไม่ได้ระบุรายการหลัก",
                    type: "error",
                    confirmButtonColor: "#007AFF",
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        }

        /**key press on textbox */
        $('#tspecing').on('keypress', function(e) {
            if (e.key == 'Enter') {

                $scope.ManagePCunit($scope.adminpcunit)

            }
        });
    }
])
app.controller("adminCustomPcvendorCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        $scope.setTabPC(6)

        $scope.adminpcvendor = {
            'idvendor': '',
            'vendor': '',
            'vendor_tel': '',
            'vendor_email': '',
            'vendor_contact': '',
            'vendor_note': ''
        }

        $.fn.dataTable.ext.buttons.addpcvendor = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.wmsadmin.product.pmanage.passign.padd')
            }
        };
        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarProductlist = [];
        var tableAdminPCvendor = $('#AdminPCvendorTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminpcvendor.php",
                "type": "POST",
                "data": function(d) {
                    /* d.tconfirm = true*/
                }
            },
            "createdRow": function(row, data, index) {},
            "rowCallback": function(row, data, cell) {},
            "footerCallback": function(row, data, start, end, display) {},
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 'l><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'i>>",
            "renderer": 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 20, 50],
                ['10 rows', '20 rows', '50 rows']
            ],
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addpcvendor',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableAdminPCvendor.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "",
                "visible": false
            }, {
                // idvendor
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // vendor
                "class": "",
                "data": "1",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // vendor_tel
                "class": "text-center",
                "data": "2",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // vendor_email
                "class": "text-center",
                "data": "3",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // vendor_contact
                "class": "text-center",
                "data": "4",
                "width": "20%",
                "orderable": false,
                "visible": true
            }, {
                // vendor_note
                "class": "text-center",
                "data": "5",
                "width": "18%",
                "orderable": false,
                "visible": false
            }, {
                // action
                "class": "text-center",
                "data": "",
                "width": "15%",
                "orderable": false,
                "visible": true
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs proinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i> ' + full[1] + '</a>';
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs editvendor"' + '>' + '<i class="' + 'fa fa-edit' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-trash' + '"></i>' + '</a>';
                },
                "targets": 7,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc']
            ]
        });

        $("#AdminPCvendorTbl tbody").on("click", ".editvendor", function(event) {
            var data = tableAdminPCvendor.row($(this).closest('tr')).data();
            console.log(data)
            $timeout(function() {
                $scope.adminpcvendor = {
                    'idvendor': data.DT_RowId,
                    'vendor': data[1],
                    'vendor_tel': data[2],
                    'vendor_email': data[3],
                    'vendor_contact': data[4],
                    'vendor_note': data[5]
                }
            }, 10);
        })

        $scope.ManagePCvendor = function(dataarr) {

            if (dataarr.idvendor == '') {
                dataarr.action = 'INSERT'
            } else dataarr.action = 'UPDATE'

            var data = $.param({
                datapost: dataarr
            });

            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_pcvendor.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                if (response.data[0].success == 1) {
                    SweetAlert.swal({
                        title: "บันทึกสำเร็จ!",
                        text: "ข้อมูลได้รับการแก้ไขแล้ว.",
                        type: "success",
                        confirmButtonColor: "#007AFF",
                        showConfirmButton: false,
                        timer: 1000
                    })
                    $timeout(function() {
                        $scope.adminpcvendor = {
                            'idvendor': '',
                            'vendor': '',
                            'vendor_tel': '',
                            'vendor_email': '',
                            'vendor_contact': '',
                            'vendor_note': '',
                        }
                    }, 10);
                    tableAdminPCvendor.draw()
                } else SweetAlert.swal({
                    title: "ERROR",
                    text: "รายการข้อมูลซ้ำ หรือ ไม่ได้ระบุรายการหลัก",
                    type: "error",
                    confirmButtonColor: "#007AFF",
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        }

        /**key press on textbox */
        $('#tspecing').on('keypress', function(e) {
            if (e.key == 'Enter') {
                $scope.ManagePCvendor($scope.adminpcvendor)
            }
        });
    }
])
app.controller("adminPaddCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        $scope.setTab(3)
        $scope.setTab2(1)

        $(function() {
            $('.number').mask('0000');
        });
        var rows = 0;
        var fadeTime = 700;
        var delayTime = 50;
        var animTime = 0;
        var animateTable = function(i, rows) {
            $(".content-loading").fadeOut("slow");;
        }

        $scope.animateTable2 = function(i, rows) {
            $(".content-loading").fadeIn("slow");;
            $timeout(function() {
                animateTable();
            }, 200);
        }

        $timeout(function() {
            animateTable();
        }, 2000);

        $timeout(function() {
            $(function() { // can replace the onload function with any other even like showing of a dialog
                $('.autofocus').focus();
            })
            $('#focusguard-1').on('focus', function() {
                $('#p_subunit').focus();
            });
            $('#focusguard-2').on('focus', function() {
                $('#p_unit').focus();
            });
        }, 100);

        $scope.adminproductinfo = {
            product: 'ระบุชื่อสินค้า',
            p_image: 'assets/images/product/NA.jpg',
            idmaterial_type: 0,
            idexpense_type: 4
        }

        $timeout(function() {
            $http({
                method: 'GET',
                url: 'assets/views/action/wmsadmin_product/db_select_expensetype.php'
            }).then(function(resultex) {
                $scope.getExpensetype = resultex.data
                $('.selectexpense-type .cs-placeholder').text(resultex.data[0].expense_type)


            });
        }, 200);

        $timeout(function() {
            $http({
                method: 'GET',
                url: 'assets/views/action/wmsadmin_product/db_select_materialtype.php'
            }).then(function(resultex) {
                $scope.getMaterialtype = resultex.data
                $('.selectmaterial-type .cs-placeholder').text(resultex.data[0].material_type)
            });
        }, 200);

        $scope.onSelect = function($item, $model, $label) {
            $scope.adminproductinfo.idmaterial_cost = $model.idmaterial_cost
            $scope.adminproductinfo.idptype = $model.idptype
        }
        $scope.myModelpgrouplist = '';
        $scope.PgroupKeyup = function(viewValue) {
            return $http.get('./assets/views/action/wmsadmin_product/db_select_PSubInfo.php?PSubInfo=' + 'Pgroup' + '&&sPgroup=' + viewValue).then(function(res) {
                return res.data;
            });
        }

        $scope.addProduct = function(info) {

            info.action = 'INSERT'
            info.idlogin_user = getIfNotSet.inputData($scope.idlogin_user, '0', true)
            var data = $.param({
                datapost: info
            });
            $http({
                method: 'POST',
                url: "assets/views/action/wmsadmin_product/db_manage_pdetails.php",
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {

                if (response.data[0].success === 1) {
                    $state.go('app.wmsadmin.product.pmanage.passign.pedit', {
                        "idproduct": response.data[0].idproduct
                    })
                } else {
                    alert('error')
                }


                /* if (response[0].success == '1') {
                     SweetAlert.swal({
                         title: "Saved!",
                         text: "Your imaginary file has been Saved.",
                         type: "success",
                         confirmButtonColor: "#007AFF",
                         showConfirmButton: false,
                         timer: 500
                     })
                     $state.go('app.rdadmininven.productmanage.pedit', {
                         "idproduct": response[0].idproduct
                     })

                 } else {

                 }*/
            });
            /*  if (form.$valid) {
                  info.idcatagory_type = getIfNotSet.inputData($scope.productinfo.idcatagory_type, '0', true)
                  info.action = 'INSERT'
                  var data = $.param({
                      datapost: info
                  });
                  $http({
                      method: 'POST',
                      url: "assets/views/action/dbinsert_product.php",
                      data: data,
                      headers: {
                          'Content-Type': 'application/x-www-form-urlencoded'
                      },
                  }).success(function(response) {
                      if (response[0].success == '1') {
                          SweetAlert.swal({
                              title: "Saved!",
                              text: "Your imaginary file has been Saved.",
                              type: "success",
                              confirmButtonColor: "#007AFF",
                              showConfirmButton: false,
                              timer: 500
                          })
                          $state.go('app.rdadmininven.productmanage.pedit', {
                              "idproduct": response[0].idproduct
                          })

                      } else {

                      }
                  });
              } else {
                  var field = null,
                      firstError = null;
                  for (field in form) {
                      if (field[0] != '$') {
                          if (firstError === null && !form[field].$valid) {
                              firstError = form[field].$name;
                          }
                          if (form[field].$pristine) {
                              form[field].$dirty = true;
                          }
                      }
                  }
                  angular.element('.ng-invalid[name=' + firstError + ']').focus();
                  errorMessage();
              }*/
        };









    }
])
app.controller("adminPeditCtrl", ["$scope", "$http", "$timeout", "$stateParams", "$state", "SweetAlert", "info", "getIfNotSet", "ngNotify", "$aside", "$element", 'toaster', 'flowFactory', 'FileUploader', function($scope, $http, $timeout, $stateParams, $state, SweetAlert, info, getIfNotSet, ngNotify, $aside, $element, toaster, flowFactory, FileUploader) {

    $scope.setTab(3)
    $scope.setTab2(1)

    $(function() {
        $('.number').mask('0000');
    });
    var rows = 0;
    var fadeTime = 700;
    var delayTime = 50;
    var animTime = 0;
    var animateTable = function(i, rows) {
        $(".content-loading").fadeOut("slow");;
    }

    $scope.animateTable2 = function(i, rows) {
        $(".content-loading").fadeIn("slow");;
        $timeout(function() {
            animateTable();
        }, 200);
    }

    $timeout(function() {
        animateTable();
    }, 2000);

    $timeout(function() {
        $(function() { // can replace the onload function with any other even like showing of a dialog
            $('.autofocus').focus();
        })
        $('#focusguard-1').on('focus', function() {
            $('#p_subunit').focus();
        });
        $('#focusguard-2').on('focus', function() {
            $('#p_unit').focus();
        });
    }, 100);

    /*  $scope.selectedexpense = function(id) {
          var data = $.param({
              idexpense_type: id
          });
          $http({
              method: 'POST',
              url: 'assets/views/action/wmsadmin_product/db_selected_material.php',
              data: data,
              headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
              },
          }).then(function(response) {
              $scope.getMaterialtype = response.data;
          });
      }
      info.productinfo($stateParams.idproduct).then(function(result) {
          if (result.data[0].success === 1) {
              $scope.adminproductinfo = result.data[0]
              $(function() {
                  $http({
                      method: 'GET',
                      url: 'assets/views/action/wmsadmin_product/db_select_expensetype.php'
                  }).then(function(result) {
                      $scope.getExpensetype = [];
                      $scope.getExpensetype = result.data;
                      $scope.selectedexpense(result.data[0].idexpense_type)
                  });
              })
              $('.selectexpense-type .cs-placeholder').text(result.data[0].expense_type)
              $('.selectmaterial-type .cs-placeholder').text(result.data[0].material_type)
          }
      });*/





    /*call product infomation (info)*/
    info.productinfo($stateParams.idproduct).then(function(result) {

        if (result.data[0].success === 1) {

            $scope.adminproductinfo = result.data[0]
            $('.selectexpense-type .cs-placeholder').text(result.data[0].expense_type)
            $('.selectmaterial-type .cs-placeholder').text(result.data[0].material_type)

        }
    });

    $timeout(function() {
        $http({
            method: 'GET',
            url: 'assets/views/action/wmsadmin_product/db_select_expensetype.php'
        }).then(function(resultex) {
            $scope.getExpensetype = resultex.data

        });
    }, 200);

    $timeout(function() {
        $http({
            method: 'GET',
            url: 'assets/views/action/wmsadmin_product/db_select_materialtype.php'
        }).then(function(resultex) {
            $scope.getMaterialtype = resultex.data
        });
    }, 200);

    $scope.selectedExpenseInit = function(id, arr) {
        $timeout(function() {
            for (var d = 0, len = arr.length; d < len; d += 1) {
                if (arr[d].idexpense_type == id) {
                    $scope.seletectedexp = arr[d]
                }
            }
            $scope.adminproductinfo.idexpense_type = id
            $('.selectexpense-type .cs-placeholder').text($scope.seletectedexp.expense_type)
            $scope.adminproductinfo.expense_type = $scope.seletectedexp.expense_type
        }, 200);

    }
    $scope.selectedMaterialtype = function(id, arr) {
        for (var d = 0, len = arr.length; d < len; d += 1) {
            if (arr[d].idmaterial_type == id) {
                $scope.seletectedmat = arr[d]
            }
        }
        $scope.adminproductinfo.material_type = $scope.seletectedmat.material_type
    }

    $scope.editproduct = function(i) {
        i.action = 'UPDATE'
        var data = $.param({
            datapost: i
        });
        $http({
            method: 'POST',
            url: 'assets/views/action/wmsadmin_product/db_manage_productinfo.php',
            data: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).then(function(response) {
            if (response.data[0].success === 1) {
                SweetAlert.swal({
                    title: "บันทึกสำเร็จ!",
                    text: "ข้อมูลได้รับการแก้ไขแล้ว.",
                    type: "success",
                    confirmButtonColor: "#007AFF",
                    showConfirmButton: false,
                    timer: 500
                })
            } else {
                alert('error')
            }
        });
    }

    $scope.onSelect = function($item, $model, $label) {}
    $scope.myModelPSizelist = '';
    $scope.PSizeKeyup = function(viewValue) {
        return $http.get('./assets/views/action/wmsadmin_product/db_select_PSubInfo.php?PSubInfo=' + 'PSize' + '&&sPSize=' + viewValue).then(function(res) {
            return res.data;
        });
    }
    $scope.myModelPFeaturelist = '';
    $scope.PFeatureKeyup = function(viewValue) {
        return $http.get('./assets/views/action/wmsadmin_product/db_select_PSubInfo.php?PSubInfo=' + 'PFeature' + '&&sPFeature=' + viewValue).then(function(res) {
            return res.data;
        });
    }
    $scope.myModelPUnitlist = '';
    $scope.PUnitKeyup = function(viewValue) {
        return $http.get('./assets/views/action/wmsadmin_product/db_select_PSubInfo.php?PSubInfo=' + 'PUnit' + '&&sPUnit=' + viewValue).then(function(res) {
            return res.data;
        });
    }
    $scope.myModelPBrandlist = '';
    $scope.PBrandKeyup = function(viewValue) {
        return $http.get('./assets/views/action/wmsadmin_product/db_select_PSubInfo.php?PSubInfo=' + 'PBrand' + '&&sPBrand=' + viewValue).then(function(res) {
            return res.data;
        });
    }
    $scope.myModelPVendorlist = '';
    $scope.PVendorKeyup = function(viewValue) {
        return $http.get('./assets/views/action/wmsadmin_product/db_select_PSubInfo.php?PSubInfo=' + 'PVendor' + '&&sPVendor=' + viewValue).then(function(res) {
            return res.data;
        });
    }

    $scope.addPSize = function() {
        var sizelength = getIfNotSet.inputData($scope.adminproductinfo.sizeea, 0, true);
        if (sizelength == 0) {
            $scope.adminproductinfo.sizeea = [];
            var result = $.grep($scope.adminproductinfo.sizeea, function(e) {
                return e.idsize == $scope.myModelPSizelist.idsize
            });
        } else {
            var result = $.grep($scope.adminproductinfo.sizeea, function(e) {
                return e.idsize == $scope.myModelPSizelist.idsize
            });
        }

        if ($scope.myModelPSizelist === '') {
            alert("Please fill collector name");
        } else if (result.length === 0) {
            $scope.sizeinfo = {
                tidsize: $scope.myModelPSizelist.idsize,
                tsize: $scope.myModelPSizelist.size,
                tsize_desc: $scope.myModelPSizelist.size_desc,
                tidproduct: $stateParams.idproduct,
                tpsubtype: 'PSize',
                action: 'INSERT'
            }
            var data = $.param({
                datapost: $scope.sizeinfo
            });

            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                var itemseq = $scope.adminproductinfo.sizeea.length;
                $scope.sizeseq = itemseq + 1;
                $scope.adminproductinfo.sizeea.push({
                    'idsize': response.data[0].idsize,
                    'size': response.data[0].size,
                    'size_desc': response.data[0].size_desc,
                    'sizeseq': $scope.sizeseq,
                    'idpsize': response.data[0].idpsize
                });
                $scope.myModelPSizelist = ''
            });
        } else {
            alert("Size already added");
        }
        $(':input', '#sizetab').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
    };

    $scope.addPFeature = function() {

        var featurelength = getIfNotSet.inputData($scope.adminproductinfo.featureea, 0, true);
        if (featurelength == 0) {
            $scope.adminproductinfo.featureea = [];
            var result = $.grep($scope.adminproductinfo.featureea, function(e) {
                return e.idfeature == $scope.myModelPFeaturelist.idfeature
            });
        } else {
            var result = $.grep($scope.adminproductinfo.featureea, function(e) {
                return e.idfeature == $scope.myModelPFeaturelist.idfeature
            });
        }


        if ($scope.myModelPFeaturelist === '') {
            alert("Please fill collector name");
        } else if (result.length === 0) {
            $scope.featureinfo = {
                tidfeature: $scope.myModelPFeaturelist.idfeature,
                tfeature: $scope.myModelPFeaturelist.feature,
                tfeature_desc: $scope.myModelPFeaturelist.feature_desc,
                tidproduct: $stateParams.idproduct,
                tpsubtype: 'PFeature',
                action: 'INSERT'
            }
            var data = $.param({
                datapost: $scope.featureinfo
            });
            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                var itemseq = $scope.adminproductinfo.featureea.length;
                $scope.featureseq = itemseq + 1;
                $scope.adminproductinfo.featureea.push({
                    'idfeature': response.data[0].idfeature,
                    'feature': response.data[0].feature,
                    'feature_desc': response.data[0].feature_desc,
                    'featureseq': $scope.featureseq,
                    'idpfeature': response.data[0].idpfeature
                });
                $scope.myModelPFeaturelist = ''
            });
        } else {
            alert("Feature already added");
        }

    };
    $scope.addPBrand = function() {
        var brandlength = getIfNotSet.inputData($scope.adminproductinfo.brandea, 0, true);
        if (brandlength == 0) {
            $scope.adminproductinfo.brandea = [];
            var result = $.grep($scope.adminproductinfo.brandea, function(e) {
                return e.idbrand == $scope.myModelPBrandlist.idbrand
            });
        } else {
            var result = $.grep($scope.adminproductinfo.brandea, function(e) {
                return e.idbrand == $scope.myModelPBrandlist.idbrand
            });
        }


        if ($scope.myModelPBrandlist === '') {
            alert("Please fill collector name");
        } else if (result.length === 0) {
            $scope.brandinfo = {
                tidbrand: $scope.myModelPBrandlist.idbrand,
                tbrand: $scope.myModelPBrandlist.brand,
                tbrand_desc: $scope.myModelPBrandlist.brand_desc,
                tidproduct: $stateParams.idproduct,
                tpsubtype: 'PBrand',
                action: 'INSERT'
            }
            var data = $.param({
                datapost: $scope.brandinfo
            });
            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                var itemseq = $scope.adminproductinfo.brandea.length;
                $scope.brandseq = itemseq + 1;
                $scope.adminproductinfo.brandea.push({
                    'idbrand': response.data[0].idbrand,
                    'brand': response.data[0].brand,
                    'brand_desc': response.data[0].brand_desc,
                    'brandseq': $scope.brandseq,
                    'idpbrand': response.data[0].idpbrand
                });
                $scope.myModelPBrandlist = ''
            });
        } else {
            alert("brand already added");
        }

    };
    $scope.addPUnit = function() {
        var unitlength = getIfNotSet.inputData($scope.adminproductinfo.unitea, 0, true);
        if (unitlength == 0) {
            $scope.adminproductinfo.unitea = [];
            var result = $.grep($scope.adminproductinfo.unitea, function(e) {
                return e.idunit == $scope.myModelPUnitlist.idunit
            });
        } else {
            var result = $.grep($scope.adminproductinfo.unitea, function(e) {
                return e.idunit == $scope.myModelPUnitlist.idunit
            });
        }


        if ($scope.myModelPUnitlist === '') {
            alert("Please fill collector name");
        } else if (result.length === 0) {
            $scope.unitinfo = {
                tidunit: $scope.myModelPUnitlist.idunit,
                tunit: $scope.myModelPUnitlist.unit,
                tunit_desc: $scope.myModelPUnitlist.unit_desc,
                tidproduct: $stateParams.idproduct,
                tpsubtype: 'PUnit',
                action: 'INSERT'
            }
            var data = $.param({
                datapost: $scope.unitinfo
            });
            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                var itemseq = $scope.adminproductinfo.unitea.length;
                $scope.unitseq = itemseq + 1;
                $scope.adminproductinfo.unitea.push({
                    'idunit': response.data[0].idunit,
                    'unit': response.data[0].unit,
                    'unit_desc': response.data[0].unit_desc,
                    'unitseq': $scope.unitseq,
                    'idpunit': response.data[0].idpunit
                });
                $scope.myModelPUnitlist = ''
            });
        } else {
            alert("unit already added");
        }

    };
    $scope.addPVendor = function() {
        var vendorlength = getIfNotSet.inputData($scope.adminproductinfo.vendorea, 0, true);
        if (vendorlength == 0) {
            $scope.adminproductinfo.vendorea = [];
            var result = $.grep($scope.adminproductinfo.vendorea, function(e) {
                return e.idvendor == $scope.myModelPVendorlist.idvendor
            });
        } else {
            var result = $.grep($scope.adminproductinfo.vendorea, function(e) {
                return e.idvendor == $scope.myModelPVendorlist.idvendor
            });
        }
        if ($scope.myModelPVendorlist === '') {
            alert("Please fill collector name");
        } else if (result.length === 0) {
            $scope.vendorinfo = {
                tidvendor: $scope.myModelPVendorlist.idvendor,
                tvendor: $scope.myModelPVendorlist.vendor,
                tvendor_desc: $scope.myModelPVendorlist.vendor_desc,
                tidproduct: $stateParams.idproduct,
                tpsubtype: 'PVendor',
                action: 'INSERT'
            }
            var data = $.param({
                datapost: $scope.vendorinfo
            });
            $http({
                method: 'POST',
                url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                var itemseq = $scope.adminproductinfo.vendorea.length;
                $scope.vendorseq = itemseq + 1;
                $scope.adminproductinfo.vendorea.push({
                    'idvendor': response.data[0].idvendor,
                    'vendor': response.data[0].vendor,
                    'vendor_desc': response.data[0].vendor_desc,
                    'vendorseq': $scope.vendorseq,
                    'idpvendor': response.data[0].idpvendor
                });
                $scope.myModelPVendorlist = ''
            });
        } else {
            alert("vendor already added");
        }

    };
    $scope.removePSize = function(id) {
        $scope.sizeinfo = {
            tidpsize: id,
            tidproduct: $stateParams.idproduct,
            tpsubtype: 'PSize',
            action: 'DELETE'
        }
        var index = -1;
        var comArr = eval($scope.adminproductinfo.sizeea);
        for (var i = 0; i < comArr.length; i++) {
            if (comArr[i].idpsize === id) {
                index = i;
                break;
            }
        }
        if (index === -1) {
            alert("Something gone wrong");
        }
        var data = $.param({
            datapost: $scope.sizeinfo
        });
        $http({
            method: 'POST',
            url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
            data: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).then(function(response) {
            if (response.data[0].success == 1) {
                $scope.adminproductinfo.sizeea.splice(index, 1);
                for (var i = 0; i < $scope.adminproductinfo.sizeea.length; i++) {
                    var b = i + 1;
                    var a = $scope.adminproductinfo.sizeea[i].sizeseq;
                    $scope.adminproductinfo.sizeea[i].sizeseq = b;
                }
            } else alert('error')

        });

    };

    $scope.removePFeature = function(id) {
        $scope.featureinfo = {
            tidpfeature: id,
            tidproduct: $stateParams.idproduct,
            tpsubtype: 'PFeature',
            action: 'DELETE'
        }
        var index = -1;
        var comArr = eval($scope.adminproductinfo.featureea);
        for (var i = 0; i < comArr.length; i++) {
            if (comArr[i].idpfeature === id) {
                index = i;
                break;
            }
        }
        if (index === -1) {
            alert("Something gone wrong");
        }
        var data = $.param({
            datapost: $scope.featureinfo
        });
        $http({
            method: 'POST',
            url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
            data: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).then(function(response) {
            if (response.data[0].success == 1) {
                $scope.adminproductinfo.featureea.splice(index, 1);
                for (var i = 0; i < $scope.adminproductinfo.featureea.length; i++) {
                    var b = i + 1;
                    var a = $scope.adminproductinfo.featureea[i].featureseq;
                    $scope.adminproductinfo.featureea[i].featureseq = b;
                }
            }

        });

    };
    $scope.removePBrand = function(id) {
        $scope.brandinfo = {
            tidpbrand: id,
            tidproduct: $stateParams.idproduct,
            tpsubtype: 'PBrand',
            action: 'DELETE'
        }
        var index = -1;
        var comArr = eval($scope.adminproductinfo.brandea);
        for (var i = 0; i < comArr.length; i++) {
            if (comArr[i].idpbrand === id) {
                index = i;
                break;
            }
        }
        if (index === -1) {
            alert("Something gone wrong");
        }
        var data = $.param({
            datapost: $scope.brandinfo
        });
        $http({
            method: 'POST',
            url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
            data: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).then(function(response) {
            if (response.data[0].success == 1) {
                $scope.adminproductinfo.brandea.splice(index, 1);
                for (var i = 0; i < $scope.adminproductinfo.brandea.length; i++) {
                    var b = i + 1;
                    var a = $scope.adminproductinfo.brandea[i].brandseq;
                    $scope.adminproductinfo.brandea[i].brandseq = b;
                }
            }

        });

    };
    $scope.removePUnit = function(id) {
        $scope.unitinfo = {
            tidpunit: id,
            tidproduct: $stateParams.idproduct,
            tpsubtype: 'PUnit',
            action: 'DELETE'
        }
        var index = -1;
        var comArr = eval($scope.adminproductinfo.unitea);
        for (var i = 0; i < comArr.length; i++) {
            if (comArr[i].idpunit === id) {
                index = i;
                break;
            }
        }
        if (index === -1) {
            alert("Something gone wrong");
        }
        var data = $.param({
            datapost: $scope.unitinfo
        });
        $http({
            method: 'POST',
            url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
            data: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).then(function(response) {
            if (response.data[0].success == 1) {
                $scope.adminproductinfo.unitea.splice(index, 1);
                for (var i = 0; i < $scope.adminproductinfo.unitea.length; i++) {
                    var b = i + 1;
                    var a = $scope.adminproductinfo.unitea[i].unitseq;
                    $scope.adminproductinfo.unitea[i].unitseq = b;
                }
            }

        });

    };
    $scope.removePVendor = function(id) {
        $scope.vendorinfo = {
            tidpvendor: id,
            tidproduct: $stateParams.idproduct,
            tpsubtype: 'PVendor',
            action: 'DELETE'
        }
        var index = -1;
        var comArr = eval($scope.adminproductinfo.vendorea);
        for (var i = 0; i < comArr.length; i++) {
            if (comArr[i].idpvendor === id) {
                index = i;
                break;
            }
        }
        if (index === -1) {
            alert("Something gone wrong");
        }
        var data = $.param({
            datapost: $scope.vendorinfo
        });
        $http({
            method: 'POST',
            url: 'assets/views/action/wmsadmin_product/db_manage_PSubinfo.php',
            data: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).then(function(response) {
            if (response.data[0].success == 1) {
                $scope.adminproductinfo.vendorea.splice(index, 1);
                for (var i = 0; i < $scope.adminproductinfo.vendorea.length; i++) {
                    var b = i + 1;
                    var a = $scope.adminproductinfo.vendorea[i].vendorseq;
                    $scope.adminproductinfo.vendorea[i].vendorseq = b;
                }
            }
        });

    };

    /**key press on textbox */
    $('#p_size').on('keypress', function(e) {
        if (e.key == 'Enter') {
            $scope.addPSize()
        }
    });
    $('#p_feature').on('keypress', function(e) {
        if (e.key == 'Enter') {
            $scope.addPFeature()
        }
    });
    $('#p_brand').on('keypress', function(e) {
        if (e.key == 'Enter') {
            $scope.addPBrand()
        }
    });
    $('#p_unit').on('keypress', function(e) {
        if (e.key == 'Enter') {
            $scope.addPUnit()
        }
    });
    $('#p_vendor').on('keypress', function(e) {
        if (e.key == 'Enter') {
            $scope.addPVendor()
        }
    });

    info.pimageslist($stateParams.idproduct).then(function(result) {
        if (result.data.length === 0) {} else if (result.data[0].idproduct === undefined) {} else {
            $scope.imagearr = result.data;
            var getIsLiked = function(i) {
                var url = $scope.imagearr[i].pimages_path;
                var idproduct = $scope.imagearr[i].idproduct;
                var idpimages = $scope.imagearr[i].idpimages;
                $http.get(url, {
                    responseType: "blob"
                }).then(function(data, status, headers, config) {
                    var imageurl = url;
                    var beconfuse = 'test';
                    var iname = imageurl.substr(imageurl.lastIndexOf('/') + 1);
                    var mimetype = data.data.type;
                    var file = new File([data.data], iname, {
                        type: mimetype,
                        beconfuse: beconfuse
                    });
                    var dummy = new FileUploader.FileItem(uploaderImages, {});
                    dummy.idpimages = idpimages;
                    dummy.idproduct = idproduct;
                    dummy.file.name = iname;
                    dummy.file.size = data.data.size;
                    dummy._file = file;
                    dummy.progress = 100;
                    dummy.isUploaded = true;
                    dummy.isSuccess = true;
                    uploaderImages.queue.push(dummy);
                })
            }
            for (var i = 0; i < $scope.imagearr.length; i++) {
                getIsLiked(i);
            }
        }
    });

    var uploaderImages = $scope.uploaderImages = new FileUploader({
        url: 'assets/views/action/wmsadmin_product/db_upload_pimages.php',
        removeAfterUpload: false
    });
    $scope.removeFile = function(item) {
        var index = getIfNotSet.inputData(item.idpimages, 0, true);
        if (index !== 0) {
            $http({
                method: 'GET',
                url: 'assets/views/action/wmsadmin_product/db_remove_pimages.php',
                params: {
                    query: index
                }
            }).then(function(result) {
                if (result.data[0].success == 1) {
                    item.remove();
                }
            });

        } else {
            item.remove();
        }
    }

    // FILTERS
    uploaderImages.filters.push({
        name: 'imageFilter',
        fn: function(item /*{File|FileLikeObject}*/ , options) {
            var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
            return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
        }
    });

    // CALLBACKS
    uploaderImages.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/ , filter, options) {};
    uploaderImages.onAfterAddingFile = function(fileItem) {
        console.info('onAfterAddingFile', fileItem);
    };
    uploaderImages.onAfterAddingAll = function(addedFileItems) {
        console.info('onAfterAddingAll', addedFileItems);
    };
    uploaderImages.onBeforeUploadItem = function(item) {
        console.info('onBeforeUploadItem', item);
        item.formData = [{
            idproduct: $stateParams.idproduct,
            secondParam: 'value'
        }];
    };
    uploaderImages.onProgressItem = function(fileItem, progress) {
        console.info('onProgressItem', fileItem, progress);
    };
    uploaderImages.onProgressAll = function(progress) {
        console.info('onProgressAll', progress);
    };
    uploaderImages.onErrorItem = function(fileItem, response, status, headers) {
        console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploaderImages.onCancelItem = function(fileItem, response, status, headers) {
        console.info('onCancelItem', fileItem, response, status, headers);
    };
    uploaderImages.onCompleteItem = function(fileItem, response, status, headers) {
        console.info('onCompleteItem', fileItem, response, status, headers);
    };
    uploaderImages.onCompleteAll = function() {
        console.info('onCompleteAll');
    };


}]);