'use strict';
/** 
 * controllers for GoogleMap  
 * AngularJS Directive 
 */
app.controller("ApplicationMainCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        info.wmsbudgetyearinfo($stateParams.fiscalyear, '1').then(function(result) {
            $scope.budgetinfo = result.data[0]
        })

        function monthstr() {
            var month = new Array();
            month[0] = "January";
            month[1] = "February";
            month[2] = "March";
            month[3] = "April";
            month[4] = "May";
            month[5] = "June";
            month[6] = "July";
            month[7] = "August";
            month[8] = "September";
            month[9] = "October";
            month[10] = "November";
            month[11] = "December";
            var d = new Date();
            var n = month[d.getMonth()];
            return n;
        }
        $('#showdate').datepicker({
            format: "yyyy/mm",
            todayHighlight: true,
            autoclose: true,
            viewMode: "years",
            minViewMode: "years"
        }).on('changeDate', function(e) {
            var currMonth = new Date(e.date).getMonth() + 1;
            var curryear = String(e.date).split(" ")[3];
            $scope.changeDateMonthEX(curryear)
        });
        $scope.showDatePicker = function() {
            $("#showdate").datepicker('show');
        }
        $scope.changeDateMonthEX = function(year) {
            if (year !== '0') {
                $state.go('app.inventory.inapps.dashboard', {
                    "fiscalyear": year,
                    "idsection": '2'
                })
            }
        };
    }
])

app.controller("InventMainCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        var groupColumn = 1;
        var dataarr = {};

        $.fn.dataTable.ext.buttons.addprojectbudget = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {

                $scope.openAside = function(position) {
                    $aside.open({
                        templateUrl: 'assets/views/aside/aside_add_projectlist.html',
                        placement: position,
                        size: 'md',
                        backdrop: true,
                        controller: function($scope, $uibModalInstance) {
                            /*$timeout(function () {
                                $http({
                                    method: 'GET',
                                    url: 'assets/views/action/asideaction/aside_getSecProjectlist.php',
                                    params: {
                                        tidsection: $stateParams.idsection
                                    }
                                }).then(function (response) {
                                    $scope.getSecProjectlist = response.data
        
                                });
        
        
                            }, 0);*/
                            $scope.asideproinfo = {
                                idproject: '',
                                project: ''
                            };

                            $scope.addproject = function(form, info) {


                                info.action = 'INSERT'
                                info.idproject = info.selectedpro.idproject
                                info.fiscalyear = $stateParams.fiscalyear
                                var data = $.param({
                                    datapost: info
                                });


                                $http({
                                    method: 'POST',
                                    url: "assets/views/action/asideaction/aside_addprojectbudget.php",
                                    data: data,
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                }).then(function(response) {

                                    if (response.data[0].action == 'INSERT') {
                                        $uibModalInstance.close()
                                        tableProjectList.draw(false)


                                    }
                                    SweetAlert.swal({
                                        title: "Saved!",
                                        text: "Your imaginary file has been SAVE!!.",
                                        type: "success",
                                        confirmButtonColor: "#007AFF",
                                        showConfirmButton: false,
                                        timer: 1000
                                    })
                                })
                            }



                            $scope.onSelect = function($item, $model, $label) {
                                console.log($model)
                            }
                            $scope.ProjectKeyup = function(viewValue) {
                                    var a = $scope.asideproinfo.idproject
                                    return $http.get('./assets/views/action/asideaction/aside_getSecProjectlist.php?sProject=' + viewValue).then(function(res) {
                                        return res.data;
                                    });
                                }
                                /*
                                $scope.addreceive = function (form, info) {
                                    info.province_id = getIfNotSet($scope.receiveinfo.modelprovince.province_id, 0, true)
                                    info.idcodename = getIfNotSet($scope.receiveinfo.modelcodename.idcodename, 0, true)
                                    info.action = 'INSERT'
                                    var data = $.param({
                                        datapost: info
                                    });
                                    if (form.$valid) {
                                        form.$setPristine();
                                        $http({
                                            method: 'POST',
                                            url: "assets/views/action/dbmanage_receive_spec.php",
                                            data: data,
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded'
                                            },
                                        }).success(function (response) {
                                            SweetAlert.swal({
                                                title: "Saved!",
                                                text: "Your imaginary file has been Saved.",
                                                type: "success",
                                                confirmButtonColor: "#007AFF",
                                                showConfirmButton: false,
                                                timer: 1000
                                            })
                                            $uibModalInstance.dismiss()
                                            tableReceiveSpec.draw()
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
                                    }
                                    $(':input', '#collectorcode').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
                                };
                                $scope.receiveinfo = {
                                    receive_no: '',
                                    date_receive: '',
                                    country_id: 1,
                                    country: 'Thailand',
                                    idspectype: 1,
                                    spectype: 'QSBG'
                                };
                                $scope.selectedtype = function (id, arr) {
                                    for (var d = 0, len = arr.length; d < len; d += 1) {
                                        if (arr[d].idspectype == id) {
                                            $scope.receiveinfo.spectype = arr[d].spectype
                                        }
                                    }
                                }
                                $scope.selectedflora = function (id, arr) {
                                    for (var d = 0, len = arr.length; d < len; d += 1) {
                                        if (arr[d].country_id == id) {
                                            $scope.receiveinfo.floraname = arr[d].country
                                        }
                                    }
                                }
                                $scope.tabReceive = 1;
                                $scope.setTabReceive = function (newTab) {
                                    $scope.tabReceive = newTab;
                                };
                                $scope.isSetReceive = function (tabNum) {
                                    return $scope.tabReceive === tabNum;
                                };
                                var today = new Date();
                                $scope.formatDate = function (today) {
                                    var dd = today.getDate();
                                    var mm = today.getMonth() + 1;
                                    var yyyy = today.getFullYear();
                                    if (dd < 10) {
                                        dd = '0' + dd;
                                    }
                                    if (mm < 10) {
                                        mm = '0' + mm;
                                    }
                                    today = dd + '/' + mm + '/' + yyyy;
                                    return today
                                }
                                var newdate = $scope.formatDate(today);
                                $(function () {
                                    $http({
                                        method: 'POST',
                                        url: "assets/views/action/selectReceiveSpec.php",
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded'
                                        },
                                    }).success(function (response) {
                                        $scope.receiveinfo.mixreceiveno = response[0].mixreceiveno;
                                        $scope.receiveinfo.receive_no = response[0].receive_no;
                                        $scope.receiveinfo.dateth = response[0].dateth;
                                        $scope.receiveinfo.date_receive = response[0].date_receive;
                                    });
                                });
                                $(function () {
                                    $http({
                                        method: 'GET',
                                        url: 'assets/views/action/getFlora.php'
                                    }).success(function (result) {
                                        $scope.getFlora = [];
                                        $scope.getFlora = result;
                                    });
                                })
                                $scope.location = ''
                                $scope.onSelect = function ($item, $model, $label) { }
                                $scope.LocationKeyup = function (viewValue) {
                                    var a = $scope.receiveinfo.country_id
                                    return $http.get('./assets/views/action/ReturnProvince.php?sProvince=' + viewValue + '&&idcountry=' + a).then(function (res) {
                                        return res.data;
                                    });
                                }
                                $scope.myModelCollectorCodeList = '';
                                $scope.CollectorCodeKeyup = function (viewValue) {
                                    return $http.get('./assets/views/action/ReturnCodename.php?sCollectorCode=' + viewValue).then(function (res) {
                                        return res.data;
                                    });
                                }*/
                        }
                    });
                };
                $scope.openAside('right');
            }
        }

        var tableProjectList = $('#ProjectListTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_projectlist.php",
                "type": "POST",
                "data": function(d) {
                    d.iduser = getIfNotSet.inputData($scope.iduser, 0, true);
                    d.fiscalyear = getIfNotSet.inputData($stateParams.fiscalyear, 0, true);
                    d.idsection = $scope.idsection
                }
            },
            "createdRow": function(row, data, index) {
                if (data[1] == 'Unknown') {
                    $(row).addClass('highlight');
                }
                $('td', row).eq(0).addClass('label-softgreen');
            },
            "rowCallback": function(row, data) {
                var rowid = data.DT_RowId;
                var rowidres = rowid.substring(4);
                if ($.inArray(rowidres, dataarr) !== -1) {
                    $(row).addClass('selected');
                }
            },
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                var columns = [1, 2];
                for (var x = 0; x < columns.length; x++) {
                    api.column(columns[x], {
                        page: 'current'
                    }).data().each(function(group, i) {
                        var rowData = api.row(i).data();
                        if (x == 0) {}
                        if (x == 1) {
                            console.log(group)
                            if (last !== group) {
                                var rowData = api.row(i).data();
                                $(rows).eq(i).before('<tr class="group label-info  bigfonttr"><td class="groupheader" colspan="4"><span class="text-large text-white" >' + rowData[1] + '</span></td></tr>');
                            }
                            last = group;
                        }
                    });
                }
            },
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 30, 50],
                ['10 rows', '30 rows', '50 rows']
            ],
            "dom": "<'row'<'col-sm-6'B><'col-sm-6'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-6'><'col-sm-6 text-right'> >",
            "buttons": [],
            "pagingType": "input",
            "columns": [{
                "class": "",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "visible": false
            }, {
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // section
                "data": "1",
                "width": "",
                "orderable": true,
                "visible": false
            }, {
                // project
                "data": "2",
                "width": "",
                "orderable": false
            }, {
                // budget
                "data": "3",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                // user
                "data": "4",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                // action
                "data": "",
                "width": "",
                "orderable": false,
                "class": "center"
            }],
            "columnDefs": [{
                "visible": true,
                "targets": groupColumn
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs adddis"' + '>' + '<i class="' + 'fa fa-th-list' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-bookmark' + '"></i>' + '</a>';
                },
                "targets": 6,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {

                    if (full[4] == false) {
                        return '<span class="label label-info">' + 'INIT' + '</span>'
                    }
                    if (full[4] == true) {
                        return '<span class="label label-success">' + 'SUCCESS' + '</span>'
                    }
                },
                "targets": 5,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc'],
                [2, 'asc']
            ]
        });
        $("#ProjectListTbl tbody").on("click", "a.adddis", function(event) {
            var tr = $(this).closest('tr');
            var data = tableProjectList.row($(this).closest('tr')).data();
            var rowid = data.DT_RowId;

            if (rowid) {
                var rowidres = rowid.substring(4);
                $timeout(function() {
                    $http({
                        method: 'GET',
                        url: 'assets/views/action/purchase_dashboard/db_manage_budgettype.php',
                        params: {
                            tidproject_budget: rowidres
                        }
                    }).then(function(response) {
                        $state.go('app.inventory.inapps.purchaseplan', {
                            "idproject_budget": rowidres
                        })

                    });


                }, 0);




            }

        });

    }
])

app.controller("PdetailsRightCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        var groupColumn = 1;
        var dataarr = {};
        var tableInventReport = $('#InventReportTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_inventreport.php",
                "type": "POST",
                "data": function(d) {
                    d.iduser = 1; /*getIfNotSet.inputData($scope.iduser, '', true);*/
                    d.idsection = $scope.idsection
                }
            },
            "createdRow": function(row, data, index) {
                if (data[1] == 'Unknown') {
                    $(row).addClass('highlight');
                }
                /* $('td', row).eq(2).addClass('label-softgreen');*/
                $('td', row).eq(0).addClass('label-softgreen');
            },
            "rowCallback": function(row, data) {
                var rowid = data.DT_RowId;
                var rowidres = rowid.substring(4);
                if ($.inArray(rowidres, dataarr) !== -1) {
                    $(row).addClass('selected');
                }
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                 var rows = api.rows({
                     page: 'current'
                 }).nodes();
                 var last = null;
                 var columns = [1, 2];
                 for (var x = 0; x < columns.length; x++) {
                     api.column(columns[x], {
                         page: 'current'
                     }).data().each(function(group, i) {
                         var rowData = api.row(i).data();
                         if (x == 0) {}
                         if (x == 1) {
                             console.log(group)
                             if (last !== group) {
                                 var rowData = api.row(i).data();
                                 $(rows).eq(i).before('<tr class="group label-info  bigfonttr"><td></td><td class="groupheader" colspan="1"><span class="text-large margin-right-5" >' + rowData[1] + '</span></td><td colspan="3" class="text-right"></td></tr>');
                             }
                             last = group;
                         }
                     });
                 }*/
            },
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "scrollCollapse": true,
            "lengthMenu": [
                [10, 30, 50],
                ['10 rows', '30 rows', '50 rows']
            ],
            "dom": "<'row'<'col-sm-6'B><'col-sm-6'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-6'><'col-sm-6 text-right'> >",
            "buttons": [],
            "pagingType": "input",
            "columns": [{
                "class": "",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "visible": true
            }, {
                "data": "0",
                "width": "",
                "orderable": true,
                "visible": true
            }, {
                "data": "1",
                "width": "",
                "orderable": true,
                "visible": true
            }, {
                "data": "2",
                "width": "",
                "orderable": false
            }, {
                "data": "3",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "4",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "5",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "6",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "7",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "8",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "9",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "10",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "11",
                "width": "",
                "orderable": false,
                "class": "center"
            }, {
                "data": "12",
                "width": "",
                "orderable": false,
                "class": "center"
            }],
            "columnDefs": [{
                    "visible": true,
                    "targets": groupColumn
                }
                /*, {
                                render: function(data, type, full, meta) {
                                    return '<a class="btn btn-transparent btn-xs adddis"' + '>' + '<i class="' + 'fa fa-th-list' + '"></i>' + '</a>' + '<a ' + 'class="bookmark btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-bookmark' + '"></i>' + '</a>';
                                },
                                "targets": 4,
                                "width": "35%",
                                "orderable": false
                            }*/
            ],
            "order": [
                [1, 'asc'],
                [2, 'asc']
            ]
        });

        tableInventReport.on('order.dt search.dt', function() {
            tableInventReport.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
        $("#InventReportTbl tbody").on("click", "a.adddis", function(event) {
            var tr = $(this).closest('tr');
            var data = tableProjectList.row($(this).closest('tr')).data();
            var rowid = data.DT_RowId;
            var rowidres = rowid.substring(4);
            $state.go('app.inventory.inapps.projectdetails', {
                "idproject": rowidres
            })
        });

    }
])
app.controller("PurchasePlanLeftCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {


    }
])
app.controller("ProjectDetailsRightCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        info.projectbudgetdetails($stateParams.idproject_budget).then(function(result) {

            $scope.projectinfo = result.data[0]
            $scope.res1 = result.data[0].budgetlist[0]
            $scope.res2 = result.data[0].budgetlist[1]
            $scope.res3 = result.data[0].budgetlist[2]
            $scope.switchsetting = result.data[0].approval

        })

        info.calculatebudget($stateParams.idproject_budget).then(function(result) {
            $scope.calculatebudgetinfo = result.data[0]
        })

        $scope.budgetVerified = function() {

            if ($scope.switchsetting == false) {
                SweetAlert.swal({
                    title: "ต้องการแก้ไขหรือไม่?",
                    text: "รายการงบประมาณได้ผ่านการตรวจสอบแล้ว ",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "ตกลง",
                    cancelButtonText: "ยกเลิก",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $http({
                            method: 'GET',
                            url: 'assets/views/action/purchase_details/db_manage_verified.php',
                            params: {
                                tverified: $scope.switchsetting,
                                tidproject_budget: $stateParams.idproject_budget
                            }
                        }).then(function(response) {
                            $scope.switchsetting = false
                        });
                    } else {
                        $scope.switchsetting = true
                    }
                });

            } else {
                $http({
                    method: 'GET',
                    url: 'assets/views/action/purchase_details/db_manage_verified.php',
                    params: {
                        tverified: $scope.switchsetting,
                        tidproject_budget: $stateParams.idproject_budget
                    }
                }).then(function(response) {

                    if (response.data[0].success == 1) {
                        SweetAlert.swal({
                            title: "เสร็จสิ้น",
                            text: "การวางแผนการใช้งบประมาณเสร็จสิ้น",
                            type: "success",
                            confirmButtonColor: "#007AFF"
                        });

                        $scope.switchsetting = true


                    } else {

                        SweetAlert.swal({
                            title: "ไม่สามารถดำเนินการได้",
                            text: "การวางแผนการใช้งบประมาณยังไม่เสร็จสิ้น",
                            type: "error",
                            confirmButtonColor: "#007AFF"
                        });

                        $scope.switchsetting = false
                    }
                });
            }
        }
        $scope.goshopping = function(id1) {
            $state.go('app.inventory.inapps.pshopping', {
                "idproject_budget": id1
            })
        }

        $scope.openBudget = function(position, info, proallinfo) {
            $aside.open({
                templateUrl: '../STANDARD/assets/views/aside/aside_add_budgettype.html',
                placement: position,
                size: 'md',
                backdrop: true,
                controller: function($scope, $uibModalInstance) {

                    // select textbox
                    $timeout(function() {
                        $http({
                            method: 'GET',
                            url: 'assets/views/action/asideaction/getBudgettype.php',
                            params: {
                                tidbudget_type: info.idbudget_type
                            }
                        }).then(function(response) {
                            $scope.getBudgettype = response.data

                        });

                        $http({
                            method: 'GET',
                            url: 'assets/views/action/asideaction/db_select_projectbalance.php',
                            params: {
                                tidproject_budget: proallinfo.idproject_budget
                            }
                        }).then(function(response) {
                            $scope.projectbalance = response.data[0].projectbalance
                        });


                    }, 0);

                    $scope.selectedexpense = function(id, arr) {
                        for (var d = 0, len = arr.length; d < len; d += 1) {
                            if (arr[d].idexpense_type == id) {
                                return $scope.expenseinfo = arr[d]
                            }
                        }

                    }

                    // add expensetype
                    $scope.addexpensetype = function(form, datainfo) {


                        datainfo.expense_cost = getIfNotSet.inputData(datainfo.expense_cost, 0, true);
                        datainfo.action = 'INSERT'
                        datainfo.idproject_budgettype = info.idproject_budgettype
                        var data = $.param({
                            datapost: datainfo
                        });
                        $http({
                            method: 'POST',
                            url: "assets/views/action/purchase_plan/db_manage_pdetails.php",
                            data: data,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                        }).then(function(response) {

                            if (response.data[0].action == 'UPDATEROW') {
                                $uibModalInstance.close()

                                if (response.data[0].trash_state == true) {
                                    info.expenselist.push({
                                            'idproject_details': response.data[0].idproject_details,
                                            'expense_type': datainfo.expense_type,
                                            'expense_cost': datainfo.expense_cost,
                                            'idbudget_type': response.data[0].idbudget_type
                                        })
                                        // $uibModalInstance.closed.then(function () { info.res.push({ 'expense_type': datainfo.expense_type, 'expense_cost': datainfo.expense_cost }) })
                                }

                                if (response.data[0].trash_state == false) {
                                    function changeDesc(value, desc) {
                                        for (var i = 0; i <= info.expenselist.length - 1; i++) {
                                            if (info.expenselist[i].idproject_details == value) {
                                                info.expenselist[i].expense_cost = desc;
                                                break;
                                            }
                                        }
                                    }
                                    changeDesc(response.data[0].idproject_details, datainfo.expense_cost)
                                        // DOM method
                                        // var idproject_details = response.data[0].idproject_details
                                        // var table = $("#expensetable tbody");
                                        // table.find('tr.' + idproject_details).each(function (key, val) {
                                        // var $tds = $(this).find('td')
                                        // $tds.eq(1).text(datainfo.expense_cost)
                                        // });
                                }
                            }
                            if (response.data[0].action == 'INSERTROW') {
                                console.log(response)
                                $uibModalInstance.close()
                                $uibModalInstance.closed.then(function() {
                                    info.expenselist.push({
                                        'idproject_details': response.data[0].idproject_details,
                                        'expense_type': datainfo.expense_type,
                                        'expense_cost': datainfo.expense_cost,
                                        'idbudget_type': response.data[0].idbudget_type

                                    })
                                })
                            }
                            SweetAlert.swal({
                                title: "Saved!",
                                text: "Your imaginary file has been SAVE!!.",
                                type: "success",
                                confirmButtonColor: "#007AFF",
                                showConfirmButton: false,
                                timer: 1000
                            })
                        })
                    }
                }
            }).result.then(
                function() {
                    // success case
                },
                function(res) {
                    // dismiss case
                    console.log('cancel');
                }
            )
        };

        $scope.remove_ex = function(rowid, groupid) {
            var indexgroup = groupid - 1
            $scope.datainfo = {}
            $scope.datainfo.action = 'DELETE'
            $scope.datainfo.idproject_details = rowid

            var index = -1;
            var comArr = eval($scope.projectinfo.budgetlist);
            for (var i = 0; i < comArr.length; i++) {
                if (comArr[i].idbudget_type === groupid) {

                    var comArrSub = comArr[i].expenselist
                    for (var j = 0; j < comArrSub.length; j++) {
                        if (comArrSub[j].idproject_details === rowid) {

                            index = j;
                            break;

                        }
                    }
                }
            }
            var data = $.param({
                datapost: $scope.datainfo
            });


            if (index === -1) {
                alert("Something gone wrong");
                console.log($scope.projectinfo.budgetlist)
            } else {
                $http({
                    method: 'POST',
                    url: 'assets/views/action/purchase_plan/db_manage_pdetails.php',
                    data: data,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                }).then(function(response) {
                    $scope.projectinfo.budgetlist[indexgroup].expenselist.splice(index, 1);
                    info.calculatebudget($stateParams.idproject_budget).then(function(result) {
                        $scope.calculatebudgetinfo = result.data[0]
                    })
                });
            }


        }

        $scope.edit_ex = function(id1, id2) {
            $state.go('app.inventory.inapps.ppedit', {
                "idproject_details": id1,
                "idproject_budget": id2
            })
        }
        $scope.openExpenseDetails = function(position, id) {
            $aside.open({
                templateUrl: '../STANDARD/assets/views/aside/aside_purchase_plan.html',
                placement: position,
                size: 'md',
                backdrop: true,
                controller: function($scope, $uibModalInstance) {

                    $(function() {
                        $http({
                            method: 'GET',
                            url: 'assets/views/action/purchase_plan/db_select_expensetype.php',
                            params: {
                                tproject_details: id,
                                tfiscalyear: $stateParams.fiscalyear
                            }
                        }).then(function(response) {
                            $scope.expenseplanning = response.data[0]
                            console.log($scope.expenseplanning)
                        });
                    })
                }
            }).result.then(
                function() {
                    // success case
                },
                function(res) {
                    // dismiss case
                    console.log('cancel');
                }
            )
        };

    }
])
app.controller("PurchasePlanRightEditCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        info.purchaseexpect($stateParams.idproject_details, $stateParams.idproject_budget).then(function(result) {
            $scope.purchaseexpectinfo = result.data[0]
        })



        const createdCell = function(cell) {
            let original;
            cell.setAttribute('contenteditable', true)
            cell.setAttribute('spellcheck', false)
            cell.addEventListener("focus", function(e) {
                original = e.target.textContent
            })
            cell.addEventListener('keypress', function(e) {

                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (original !== e.target.textContent) {
                        const row = tablePurchasePlanList.row(e.target.parentElement)
                        var datainfo_convert = {
                            texpect_date: row.data()[1],
                            tidproject_details: $stateParams.idproject_details,
                            texpect_cost: e.target.textContent,
                            tidproject_details_expect: row.data()[3],
                            tidproject_budget: $stateParams.idproject_budget,
                            taction: 'INSERT'

                        }
                        var data = $.param({
                            datapost: datainfo_convert
                        });
                        $http({
                            method: 'POST',
                            url: "assets/views/action/purchase_plan/db_manage_projectdetailsexpect.php",
                            data: data,
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                        }).then(function(response) {
                            if (response.data[0].success == 1) {

                                info.purchaseexpect($stateParams.idproject_details, $stateParams.idproject_budget).then(function(result) {
                                    $scope.purchaseexpectinfo = result.data[0]
                                })
                            } else {
                                SweetAlert.swal({
                                    title: "ไม่สามารถดำเนินการได้",
                                    text: "งบประมาณเหลือไม่เพียงพอ!!",
                                    type: "error",
                                    confirmButtonColor: "#007AFF"
                                });
                                e.target.textContent = 0
                            }
                            tablePurchasePlanList.draw(false)
                        })
                    }
                }
            })
        }

        var tablePurchasePlanList = $('#PurchasePlanListTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_pplandetails.php",
                "type": "POST",
                "data": function(d) {
                    d.idproject_details = getIfNotSet.inputData($stateParams.idproject_details, '', true);
                    d.fiscalyear = getIfNotSet.inputData($stateParams.fiscalyear, '', true);

                }
            },
            "scrollCollapse": true,
            "lengthMenu": [
                [12],
                ['12 ROWS']
            ],
            "dom": "<'row'<'col-sm-6'B><'col-sm-6'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-6'><'col-sm-6 text-right'> >",
            "buttons": [],
            "pagingType": "input",
            "columns": [{
                "class": "",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "visible": false
            }, {
                // row id
                "data": "DT_RowId",
                "width": "15px",
                "orderable": false,
                "visible": true
            }, {
                // mont th
                "data": "0",
                "width": "250px",
                "orderable": false,
                "visible": true,
                "class": 'left'
            }, {
                // mix date
                "data": "1",
                "width": "",
                "orderable": false,
                "visible": false,
                "class": 'left'
            }, {
                // expect cost
                "data": "2",
                "width": "",
                "orderable": false,
                "visible": true,
                "class": 'left'
            }, {
                // idproject details expect
                "data": "3",
                "width": "",
                "orderable": false,
                "visible": false,
                "class": 'left'
            }, {
                // idproject details
                "data": "4",
                "width": "",
                "orderable": false,
                "visible": false,
                "class": 'left'
            }, {
                // action
                "data": "",
                "width": "",
                "orderable": false,
                "visible": true,
                "class": 'left'
            }],
            "columnDefs": [{
                targets: 4,
                createdCell: createdCell
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs openmodal"' + '>' + '<i class="' + 'fa fa-dollar' + '"></i>' + '</a>' + '<a ' + 'class="clearcost btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-minus-circle' + '"></i>' + '</a>';
                },
                "targets": 7
            }],
            "order": []
        });
        $("#PurchasePlanListTbl tbody").on("click", "a.openmodal", function(event) {
            var data_from_table = tablePurchasePlanList.row($(this).closest('tr')).data();

            $scope.openAside = function(position, data) {

                $aside.open({
                    templateUrl: '../STANDARD/assets/views/aside/aside_edit_pplan.html',
                    placement: position,
                    size: 'md',
                    backdrop: true,
                    controller: function($scope, $uibModalInstance) {

                        $scope.expectinfo = {}
                        $scope.expectinfo.month_th = data[0]
                        $scope.expectinfo.expect_date = data[1]
                        $scope.expectinfo.expect_cost = data[2]
                        $scope.expectinfo.idproject_details_expect = data[3]
                        $scope.expectinfo.idproject_details = data[4]

                        $timeout(function() {
                            $http({
                                method: 'GET',
                                url: 'assets/views/action/purchase_plan/db_select_expenseinfo.php',
                                params: {
                                    tidproject_details: $stateParams.idproject_details,
                                }
                            }).then(function(response) {
                                $scope.expectinfo.balance = response.data[0].balancecost

                            });
                        }, 0);

                        $scope.getCost = function(event) {
                            if (event > $scope.expectinfo.balance) {
                                alert("กรอกจำนวนตัวเลขเกินจำนวนเงินงบประมาณ");
                                $scope.expectinfo.expect_cost = $scope.expectinfo.balance;
                            }
                        }


                        $scope.addexpectcost = function(form, datainfo) {

                            var datainfo_convert = {
                                texpect_date: datainfo.expect_date,
                                tidproject_details: $stateParams.idproject_details,
                                texpect_cost: datainfo.expect_cost,
                                tidproject_details_expect: datainfo.idproject_details_expect,
                                taction: 'INSERT'
                            }

                            var data = $.param({
                                datapost: datainfo_convert
                            });

                            $http({
                                method: 'POST',
                                url: "assets/views/action/purchase_plan/db_manage_projectdetailsexpect.php",
                                data: data,
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                            }).then(function(response) {
                                if (response.data[0].success == 1) {
                                    $uibModalInstance.close()
                                    tablePurchasePlanList.draw(false)


                                }

                            })
                        }
                    }
                }).result.then(
                    function() {
                        // success case
                        info.purchaseexpect($stateParams.idproject_details, $stateParams.idproject_budget).then(function(result) {
                            $scope.purchaseexpectinfo = result.data[0]
                        })
                    },
                    function(res) {
                        // dismiss case
                        console.log('cancel');
                    }
                )
            };
            $scope.openAside('right', data_from_table)



        });

        $("#PurchasePlanListTbl tbody").on("click", "a.clearcost", function(event) {
            var data_from_table = tablePurchasePlanList.row($(this).closest('tr')).data();

            var datainfo_convert = {
                texpect_date: data_from_table[1],
                tidproject_details: $stateParams.idproject_details,
                texpect_cost: data_from_table[2],
                tidproject_details_expect: data_from_table[3],
                taction: 'DELETE'
            }

            var data = $.param({
                datapost: datainfo_convert
            });

            $http({
                method: 'POST',
                url: "assets/views/action/purchase_plan/db_manage_projectdetailsexpect.php",
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function(response) {
                if (response.data[0].success == 1) {
                    tablePurchasePlanList.draw(false)
                    info.purchaseexpect($stateParams.idproject_details, $stateParams.idproject_budget).then(function(result) {
                        $scope.purchaseexpectinfo = result.data[0]
                    })
                }

            })

        });




    }
])
app.controller("ProjectShoppingCartCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {
        $scope.expenseplanning = {
            disburse: 2000000
        }

        $.fn.dataTable.ext.buttons.addproduct = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.inventory.inapps.productlist', {})
            }
        };
        $.fn.dataTable.ext.buttons.saveplanning = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                var rows = tableExpensePlanning.columns(0).data();
                var comparr = []
                tableExpensePlanning.rows().every(function(row, rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var trqty = parseInt($('#ExpensePlanningTbl').find('tr#' + data.DT_RowId + ' input.tqty').val())
                    var travgprice = parseInt($('#ExpensePlanningTbl').find('tr#' + data.DT_RowId + ' input.avgprice').val())
                    comparr.push({
                        idproduct: data.DT_RowId,
                        tqty: trqty,
                        tavgprice: travgprice
                    })
                });
                var data = $.param({
                    datapost: comparr
                });
                $http({
                    method: 'POST',
                    data: data,
                    url: "assets/views/action/dbinsert_productplan2.php",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                }).success(function(response) {});
            }
        };
        var q = 2 // tabindex
        var previousWindowKeyDown = window.onkeydown;
        var dataarExpensePlanning = [];
        var tableExpensePlanning = $('#ExpensePlanningTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_shoppingcart.php",
                "type": "POST",
                "data": function(d) {
                    d.tidproduct = $stateParams.idproduct,
                        d.tidsize = getIfNotSet.inputData($scope.idsize, 0, true),
                        d.tidfeature = getIfNotSet.inputData($scope.idfeature, 0, true)
                }
            },
            "createdRow": function(row, data, index) {
                if (dataarExpensePlanning.length == 0) {
                    tableExpensePlanning.buttons('.deleteall').disable();
                } else {
                    tableExpensePlanning.buttons('.deleteall').enable();
                }
                /*   var qty = $(row).find('td:eq(3)').html();*/
                $(row).find('input.tqty').attr("tabindex", q)
                    /*  $(row).find('input.avgprice').attr("tabindex", b + q)*/
                q++
            },
            "rowCallback": function(row, data, cell) {
                $('td', row).eq(3).addClass('abba' + data.DT_RowId);
                /*       var api = this.api(),
                           data;
                       $('td', row).eq(3).addClass('abba' + data.DT_RowId);
                       var qty = $(row).find('td:eq(3)').html()
                       var currentInputValue = $(row).find('td:eq(3) input').val()
                       /*    var qty = document.getElementById("cellda31").value*/
                /*

                var qty = $(row).find('td:eq(3)').html()  */
                /*     .find('span.a-btn-icon-assign');*/
                /*      var sum = currentInputValue * data[7];
                      var intVal = function(i) {
                          return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                      };
                      $('td:eq(5)', row).html('<span class="sumprice">' + sum + '</span>');
                      var ff = $(row).find('td:eq(5) span').text()
                      var monTotal = api.cell('td:eq(9)').render('display')*/
            },
            "footerCallback": function(row, data, start, end, display) {
                /*   var api = this.api(),
                       data;
                   $('td', row).eq(3).addClass('abba' + data.DT_RowId);
                   var qty = $(row).find('td:eq(3)').html()
                   var currentInputValue = $(row).find('td:eq(3) input').val()
                   /*    var qty = document.getElementById("cellda31").value*/
                /*

                var qty = $(row).find('td:eq(3)').html()  */
                /*     .find('span.a-btn-icon-assign');*/
                /*      var sum = currentInputValue * data[7];
                      var intVal = function(i) {
                          return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                      };
                      $('td:eq(5)', row).html('<span class="sumprice">' + sum + '</span>');
                      var ff = $(row).find('td:eq(5) span').text()
                      var monTotal = api.cell('td:eq(9)').render('display')*/
            },
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            /*   "dom": "<'row'<'col-sm-6'B><'col-sm-6'p>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'>>",*/
            renderer: 'bootstrap',
            "deferRender": true,
            "scrollCollapse": true,
            "lengthMenu": [
                [20, 50, 100],
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
                    tableExpensePlanning.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }, {
                text: '<i class="fa fa-upload"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }, {
                text: '<i class="fa fa-trash"></i>',
                className: 'deleteall btn btn-wide-40 btn-transparent',
                extend: '',
                enabled: true
            }, {
                text: '<i class="fa fa-save"></i>',
                className: 'saveplan btn btn-wide-40 ',
                extend: 'saveplanning',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "25px",
                "visible": true
            }, {
                // idproduct
                "data": "0",
                "width": "10%",
                "orderable": false,
                "visible": true
            }, {
                // catagory
                "class": "text-center",
                "data": "1",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                // supplies type
                "data": "2",
                "width": "15%",
                "orderable": false,
                "visible": true
            }, {
                // product
                "data": "3",
                "width": "15%",
                "orderable": false,
                "visible": true
            }, {
                // size
                "data": "4",
                "width": "15%",
                "orderable": false,
                "visible": true
            }, {
                // feature
                "data": "5",
                "width": "10%",
                "orderable": false,
                "visible": true
            }, {
                //brand
                "class": "text-center",
                "data": "6",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //qty
                "class": "text-center",
                "data": "tqty",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //unit
                "class": "text-center",
                "data": "7",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //supplier
                "class": "text-center",
                "data": "8",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //price
                "class": "text-center",
                "data": "9",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //exsumm
                "class": "text-center",
                "data": null,
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //avg sum
                "class": "text-center",
                "data": null,
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //idbrand
                "class": "text-center",
                "data": "8",
                "width": "5%",
                "orderable": true,
                "visible": false
            }, {
                //idunit
                "class": "text-center",
                "data": "11",
                "width": "5%",
                "orderable": true,
                "visible": false
            }, {
                //idsupplier
                "class": "text-center",
                "data": "12",
                "width": "5%",
                "orderable": true,
                "visible": false
            }, {
                //idcatagory
                "class": "text-center",
                "data": "13",
                "width": "5%",
                "orderable": true,
                "visible": false
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return full[3] + ' ' + full[4] + ' ' + full[5];
                },
                "targets": 4,
                "width": "35%",
                "orderable": false
            }, {
                "targets": 8,
                "width": "35%",
                "orderable": false,
                "data": "tqty",
                render: function(data, type, full, meta, row) {
                    return '<input id="' + 'cellda' + full['DT_RowId'] + '" class="tqty number3p" type="text" value="' + full[14] + '" >' + '' + '</input>';
                }
            }, {
                "targets": 12,
                "width": "35%",
                "orderable": false,
                render: function(data, type, full, meta) {
                    var pricestack = full[9] * full[14]
                    return '<span class="sumprice label label-info">' + pricestack + '</span>';
                },
                'createdCell': function(td, cellData, rowData, row, col) {
                    td.id = 'tdid' + rowData.DT_RowId;
                }
            }, {
                render: function(data, type, full, meta) {
                    return '<input tabindex="' + full[16] + '"' + 'id="' + 'avgprice' + full['DT_RowId'] + '" class="avgprice number" type="text"  value="' + full[15] + '">' + '' + '</input>';
                },
                "targets": 13,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [13, 'asc'],
                [12, 'asc'],
                [14, 'asc'],
                [15, 'asc']
            ]
        });

    }
])
app.controller('ExpensePlanCtrl', ["$scope", "$http", "$uibModal", "$timeout", "info", "$state", "SweetAlert", "$aside", "$stateParams", "UserService", "ServicePDF", "$element",
    function($scope, $http, $uibModal, $timeout, info, $state, SweetAlert, $aside, $stateParams, UserService, ServicePDF, $element) {
        function getIfNotSet(value, newValue, overwriteNull) {
            if (typeof(value) === 'undefined' && overwriteNull === true) {
                return newValue;
            } else if (value === null && overwriteNull === true) {
                return newValue;
            } else if (value === 0 && overwriteNull === true) {
                return newValue;
            } else if (value === '' && overwriteNull === true) {
                return newValue;
            } else {
                return value;
            }
        }
        $scope.refreshdata = UserService.sharedCheckdup;

        function load_product(page) {
            $.ajax({
                url: "assets/views/action/productlist/db_fetch_item.php",
                method: "POST",
                data: {
                    page: page
                },
                success: function(data) {
                    $('#display_item').html(data);
                }
            });
        }
        $timeout(function() {
            load_product();
        }, 10);
        /** Begin code **/
        $('#display_item').on('click', '.pagination_link', function() {
            var page = $(this).attr("id");
            load_product(page);
        });
        $('#display_item').on('click', '.aside', function() {
            var a = $(this).data()
            $state.go('app.inventory.inapps.productdetails', {
                "idproduct": a.idproduct
            })
        });
    }
]);
app.controller('ProductDetailsCtrl', ["$scope", "$http", "$uibModal", "$timeout", "info", "$state", "SweetAlert", "$aside", "$stateParams", "UserService", "ServicePDF", "$element",
    function($scope, $http, $uibModal, $timeout, info, $state, SweetAlert, $aside, $stateParams, ServicePDF, $element) {
        info.callproduct($stateParams.idproduct).then(function(result) {
            $scope.p_details = result.data[0]
        })


        /*
        $scope.refreshdata = UserService.sharedCheckdup;
        var data = $.param({
            tproductid: $stateParams.idproduct
        });
        setTimeout(function() {
            $http({
                method: 'POST',
                data: data,
                url: "assets/views/action/selectproduct.php",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(response) {
                console.log(response)
                $scope.p_details = response[0]
            });
        }, 10);
        $scope.getidfeature = function(a) {
            $scope.idfeature = a
            tableProductCompound.draw();
            $scope.obj.value1 = a
        }
        $scope.getidsize = function(b) {
            $scope.idsize = b
            tableProductCompound.draw();
            $scope.obj.value3 = b
        }
        $scope.getidunit = function(b) {
            $scope.idunit = b
            tableProductCompound.draw();
            $scope.obj.value2 = b
        }
        $scope.obj = {
            value1: 0,
            value2: 0,
            value3: 0,
            value4: 0,
            value5: 0
        }
        $scope.changestyle1 = function(id) {
            return $scope.obj.value1 === id
        };
        $scope.changestyle2 = function(id) {
            return $scope.obj.value2 === id
        };
        $scope.changestyle3 = function(id) {
            return $scope.obj.value3 === id
        };
        $scope.changestyle4 = function(id) {
            return $scope.obj.value4 === id
        };
        $scope.changestyle5 = function(id) {
            return $scope.obj.value5 === id
        };
        //-- Click on detail
        $(".attr,.attr2").on("click", function() {
            var clase = $(this).attr("class");
            $("." + clase).removeClass("active");
            $(this).addClass("active");
        })
        $scope.idsize = '';
        $scope.idfeature = '';
        $.fn.dataTable.ext.buttons.goplanning = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                console.log('sofsd')
                $state.go('app.inventory.inapps.expense', {
                    "idproject": $stateParams.idproject,
                    "idprojectactivity": $stateParams.idprojectactivity
                })
            }
        };
        $.fn.dataTable.ext.buttons.goinventory = {
            className: 'buttons-alert',
            action: function(e, dt, node, config) {
                $state.go('app.inventory.inapps.subinven', {
                    "idsection": 2, //$scope.idsection
                    "idprojectactivity": $stateParams.idprojectactivity,
                })
            }
        };
        var dataarProductCompound = [];
        var tableProductCompound = $('#ProductCompoundTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_product.php",
                "type": "POST",
                "data": function(d) {
                    d.tidproduct = $stateParams.idproduct,
                        d.tidsize = getIfNotSet($scope.idsize, 0, true),
                        d.tidfeature = getIfNotSet($scope.idfeature, 0, true),
                        d.tidunit = getIfNotSet($scope.idunit, 0, true)
                }
            },
            "createdRow": function(row, data, index) {
                if (dataarProductCompound.length == 0) {
                    tableProductCompound.buttons('.deleteall').disable();
                } else {
                    tableProductCompound.buttons('.deleteall').enable();
                }
            },
            "rowCallback": function(row, data) {
                var rowid = data.DT_RowId;
                if ($.inArray(rowid, dataarProductCompound) !== -1) {
                    $(row).addClass('selected');
                }
            },
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
            },
            stateLoadCallback: function(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
            },
            "dom": "<'row'<'col-sm-6'B><'col-sm-6'p>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-12'>>",
            renderer: 'bootstrap',
            "scrollCollapse": true,
            "lengthMenu": [
                [20, 50, 100],
                ['20 rows', '50 rows', '100 rows']
            ],
            "buttons": [{
                text: '<span class="badge"><i class="fa fa-thumb-tack margin-right-5"></i>Planning</span>',
                className: '',
                extend: 'goplanning',
                enabled: true
            }, {
                text: '<span class="badge"><i class="fa fa-shopping-cart margin-right-5"></i>Stock</span>',
                className: 'btn btn-wide-20 btn-transparent',
                extend: 'goinventory',
                enabled: true
            }, {
                text: '<i class="fa fa-repeat"></i>',
                action: function(row) {
                    tableReceiveDetails.draw(false)
                },
                className: 'btn btn-wide-40 btn-transparent',
                enabled: true
            }],
            "pagingType": "input",
            "columns": [{
                "class": "especial select-checkbox",
                "orderable": false,
                "data": null,
                "defaultContent": "",
                "width": "25px",
                "visible": false
            }, {
                // idproduct
                "data": "0",
                "width": "10%",
                "orderable": false,
                "visible": false
            }, {
                // product
                "data": "1",
                "width": "15%",
                "orderable": false
            }, {
                // size
                "data": "2",
                "width": "15%",
                "orderable": false,
                "visible": false
            }, {
                // feature
                "data": "3",
                "width": "15%",
                "orderable": false,
                "visible": false
            }, {
                // brand
                "data": "4",
                "width": "10%",
                "orderable": false
            }, {
                //unit
                "class": "text-center",
                "data": "5",
                "width": "5%",
                "orderable": false,
                "visible": true
            }, {
                //supplier
                "class": "text-center",
                "data": "6",
                "width": "5%",
                "orderable": false
            }, {
                //price
                "class": "text-center",
                "data": "7",
                "width": "5%",
                "orderable": false
            }, {
                //action
                "class": "text-center",
                "data": "",
                "width": "5%",
                "orderable": false
            }, {
                //idbrand
                "class": "text-center",
                "data": "8",
                "width": "5%",
                "orderable": true,
                "visible": false
            }, {
                //idunit
                "class": "text-center",
                "data": "9",
                "width": "5%",
                "orderable": true,
                "visible": false
            }, {
                //idsupplier
                "class": "text-center",
                "data": "10",
                "width": "5%",
                "orderable": true,
                "visible": false
            }],
            "columnDefs": [{
                render: function(data, type, full, meta) {
                    return full[1] + ' ' + full[2] + ' ' + full[3];
                },
                "targets": 2,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a class="btn btn-transparent btn-xs addplan"' + '>' + '<i class="' + 'fa fa-thumb-tack' + '"></i>' + '</a>' + '<a class="btn btn-transparent btn-xs addstock"' + '>' + '<i class="' + 'fa fa-shopping-cart' + '"></i>' + '</a>';
                },
                "targets": 9,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [11, 'asc'],
                [10, 'asc'],
                [12, 'asc'],
            ]
        });
        $('#ProductCompoundTbl tbody').on('click', '.addplan', function() {
            var tr = $(this).closest('tr');
            var tdata = tableProductCompound.row(tr).data();
            var id = tdata.DT_RowId;
            $scope.productdata = {
                idproduct_compound: id,
                action: 'INSERT'
            }
            var data = $.param({
                datapost: $scope.productdata
            });
            $http({
                method: 'POST',
                data: data,
                url: "assets/views/action/dbinsert_productplan.php",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(response) {});
        });
        $('#ProductCompoundTbl tbody').on('click', '.addstock', function() {
            var tr = $(this).closest('tr');
            var tdata = tableProductCompound.row(tr).data();
            var id = tdata.DT_RowId;
            $scope.productdata = {
                tidproduct_compound: id,
                tidproject: 5,
                taction: 'INSERT'
            }
            var data = $.param({
                datapost: $scope.productdata
            });
            $http({
                method: 'POST',
                data: data,
                url: "assets/views/action/dbinsert_subinven.php",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(response) {});
        });*/
    }
]);