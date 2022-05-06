'use strict';
/** 
 * controllers for GoogleMap  
 * AngularJS Directive 
 */
app.controller("InventoryAdminMainCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
    function($rootScope, $scope, $uibModal, $log, $http, info, $state, $timeout, $stateParams, SweetAlert, ServicePDF, $interval, $aside, getIfNotSet) {

        $('#showdate').datepicker({
            format: "yyyy/mm",
            todayHighlight: true,
            autoclose: true,
            viewMode: "years",
            minViewMode: "years"
        }).on('changeDate', function(e) {
            var currMonth = new Date(e.date).getMonth() + 1;
            var curryear = String(e.date).split(" ")[3];
            $scope.changeDateMonthEX(currMonth, curryear)
        });

        $scope.changeDateMonthEX = function(month, year) {
            if (month && year !== '0') {
                $state.go('app.dm.happ.main.dashboard', {
                    "fiscalyear": year
                })
            }
        };
    }
])
app.controller("InventoryPmanageLeftCtrl", ["$rootScope", "$scope", "$uibModal", "$log", "$http", "info", "$state", "$timeout", "$stateParams", "SweetAlert", "ServicePDF", "$interval", "$aside", "getIfNotSet",
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
app.controller("InventoryPmanageCtrl", ["$scope", "$uibModal", "$log", "$http", "$state", "info", "$timeout", "$stateParams", "SweetAlert", "ngNotify", "$interval", "$aside", "getIfNotSet",
    function($scope, $uibModal, $log, $http, $state, info, $timeout, $stateParams, SweetAlert, ngNotify, $interval, $aside, getIfNotSet) {
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
                            $scope.currentStep = 1;
                            var errorMessage = function(i) {
                                ngNotify.set('please complete the form in this step before proceeding', {
                                    theme: 'pure',
                                    position: 'top',
                                    type: 'error',
                                    button: true,
                                    sticky: false,
                                });
                            }
                            $scope.myModelinvestinfo = '';
                            $scope.addproject = function(form, info) {
                                if (form.$valid && $scope.investigators.length > 0) {

                                    info.action = 'INSERT'
                                    info.idproject = getIfNotSet.inputData(info.selectedpro.idproject, 0, true);
                                    info.fiscalyear = $stateParams.fiscalyear
                                    info.investigators = $scope.investigators
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
                                        if (response.data[0].success == '1') {
                                            /*$uibModalInstance.close()*/
                                            tableProjectList.draw(false)
                                            SweetAlert.swal({
                                                title: "Saved!",
                                                text: "Your imaginary file has been SAVE!!.",
                                                type: "success",
                                                confirmButtonColor: "#007AFF",
                                                showConfirmButton: false,
                                                timer: 1000
                                            })
                                            $(':input', '#probudget_form').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
                                            $scope.resetform()
                                        }

                                    })
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
                                /* $(':input', '#probudget_form').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');*/


                            }
                            $scope.removeUser = function(id) {
                                var index = -1;
                                var comArr = eval($scope.investigators);
                                for (var i = 0; i < comArr.length; i++) {
                                    if (comArr[i].iduser === id) {
                                        index = i;
                                        break;
                                    }
                                }
                                if (index === -1) {
                                    alert("Something gone wrong");
                                }

                                function keyValue(key, value) {
                                    this.Key = investigatorseq;
                                    this.Value = 1;
                                };
                                $scope.investigators.splice(index, 1);
                                for (var i = 0; i < $scope.investigators.length; i++) {
                                    var b = i + 1;
                                    var a = $scope.investigators[i].investigatorseq;
                                    $scope.investigators[i].investigatorseq = b;
                                }
                            };

                            $scope.onSelectProject = function($item, $model, $label) {
                                if ($model.idproject) {
                                    $scope.emailstatus = true
                                }
                                console.log($model)
                            }

                            $scope.asideproinfo = {
                                'selectedpro': null,
                                'budget_cost': null

                            }

                            $scope.master = $scope.asideproinfo;

                            $scope.checkbudgetcost = function() {
                                if ($scope.asideproinfo.budget_cost > 0) {
                                    return true
                                } else {
                                    return false
                                }
                            }

                            $scope.resetform = function() {
                                $scope.asideproinfo = {
                                    'selectedpro': null,
                                    'budget_cost': null
                                }
                                $scope.emailstatus = false
                                $scope.investigators = [];
                            }

                            $scope.ProjectKeyup = function(viewValue) {

                                if ($scope.asideproinfo.selectedpro !== null) {
                                    $scope.emailstatus = false
                                }
                                return $http.get('./assets/views/action/asideaction/aside_getSecProjectlist.php?sProject=' + viewValue).then(function(res) {
                                    return res.data;
                                });
                            }

                            $scope.onSelectInvestigator = function($item, $model, $label) {
                                console.log($model)
                            }

                            $scope.InvestigatorKeyup = function(viewValue) {

                                return $http.get('./assets/views/action/asideaction/aside_getInvestigatorlist.php?sInvestigator=' + viewValue).then(function(res) {
                                    return res.data;
                                });
                            }
                            $scope.investigators = [];

                            $scope.addInvestigator = function() {
                                //find id in array collectors 
                                var result = $.grep($scope.investigators, function(e) {
                                    return e.iduser == $scope.myModelinvestinfo.iduser
                                });
                                //check value in model and array
                                if ($scope.myModelinvestinfo === '') {
                                    alert("กรุณากรอกชื่อผู้ดำเนินโครงการ");
                                } else if (result.length === 0) {
                                    var countinvestigators = $scope.investigators;
                                    var investnum = countinvestigators.length;
                                    $scope.investigatorseq = investnum + 1;
                                    $scope.investigators.push({
                                        'firstname_th': $scope.myModelinvestinfo.firstname_th,
                                        'lastname_th': $scope.myModelinvestinfo.lastname_th,
                                        'iduser': $scope.myModelinvestinfo.iduser,
                                        'investigatorseq': $scope.investigatorseq
                                    });
                                } else {
                                    alert("มีรายชื่ออยู่ในรายการแล้ว");
                                }
                                $(':input#invest').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
                            };
                        }
                    });
                };

                $scope.openAside('right');

            }
        }

        var tableProjectList = $('#AdminProjectListTbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "assets/scripts/server_processing_adminprojectlist.php",
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
                                $(rows).eq(i).before('<tr class="group label-info  bigfonttr"><td class="groupheader" colspan="5"><span class="text-large text-white" >' + rowData[1] + '</span></td></tr>');
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
            "buttons": [{
                text: '<i class="fa fa-plus"></i>',
                className: 'btn btn-wide-40 btn-transparent',
                extend: 'addprojectbudget',
                enabled: true
            }],
            "dom": "<'row'<'col-sm-4'B><'col-sm-4 '><'col-sm-4'p>>" + "<'row'<'col-sm-12 toolbar'>>" + "<'row'<'col-sm-12 margin-bottom-10'tr>>" + "<'row'<'col-sm-6'i><'col-sm-6 right'<'pull-right'l>>>",
            "renderer": 'bootstrap',
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
                    return '<a class="btn btn-transparent btn-xs showproinfo"' + '>' + '<i class="' + 'fa fa-info-circle' + '"></i>' + '</a>' + full[2];
                },
                "targets": 3,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {
                    return '<a ' + 'class="removepro btn-transparent btn-xs"' + '>' + '<i class="' + 'fa fa-minus-circle' + '"></i>' + '</a>';
                },
                "targets": 7,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {

                    if (full[4] == 0) {
                        return '<span class="label label-info">' + 'INIT' + '</span>'
                    }
                    if (full[4] == 1) {
                        return '<a class="btn btn-warning btn-xs adddis"' + '>' + 'WAITING' + '</a>'
                    }
                    if (full[4] == 2) {
                        return '<span class="label label-success">' + 'SUCCESS' + '</span>'
                    }
                },
                "targets": 5,
                "width": "35%",
                "orderable": false
            }, {
                render: function(data, type, full, meta) {


                    if (full[6] == true) {
                        return 'SUCCESS'
                    }
                    if (full[6] == false) {
                        return 'WAITING'
                    }
                },
                "targets": 6,
                "width": "35%",
                "orderable": false
            }],
            "order": [
                [1, 'asc'],
                [2, 'asc']
            ]
        });
        $("#AdminProjectListTbl tbody").on("click", "a.showproinfo", function(event) {
            var tr = $(this).closest('tr');
            var data = tableProjectList.row($(this).closest('tr')).data();
            var rowid = data.DT_RowId;


            $scope.openAside = function(position) {
                $aside.open({
                    templateUrl: 'assets/views/aside/aside_add_projectlist.html',
                    placement: position,
                    size: 'md',
                    backdrop: true,
                    controller: function($scope, $uibModalInstance) {
                        $scope.currentStep = 1;
                        var errorMessage = function(i) {
                            ngNotify.set('please complete the form in this step before proceeding', {
                                theme: 'pure',
                                position: 'top',
                                type: 'error',
                                button: true,
                                sticky: false,
                            });
                        }
                        $scope.myModelinvestinfo = '';
                        $scope.addproject = function(form, info) {
                            if (form.$valid && $scope.investigators.length > 0) {

                                info.action = 'INSERT'
                                info.idproject = getIfNotSet.inputData(info.selectedpro.idproject, 0, true);
                                info.fiscalyear = $stateParams.fiscalyear
                                info.investigators = $scope.investigators
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
                                    if (response.data[0].success == '1') {
                                        /*$uibModalInstance.close()*/
                                        tableProjectList.draw(false)
                                        SweetAlert.swal({
                                            title: "Saved!",
                                            text: "Your imaginary file has been SAVE!!.",
                                            type: "success",
                                            confirmButtonColor: "#007AFF",
                                            showConfirmButton: false,
                                            timer: 1000
                                        })
                                        $(':input', '#probudget_form').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
                                        $scope.resetform()
                                    }

                                })
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
                            /* $(':input', '#probudget_form').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');*/


                        }
                        $scope.removeUser = function(id) {
                            var index = -1;
                            var comArr = eval($scope.investigators);
                            for (var i = 0; i < comArr.length; i++) {
                                if (comArr[i].iduser === id) {
                                    index = i;
                                    break;
                                }
                            }
                            if (index === -1) {
                                alert("Something gone wrong");
                            }

                            function keyValue(key, value) {
                                this.Key = investigatorseq;
                                this.Value = 1;
                            };
                            $scope.investigators.splice(index, 1);
                            for (var i = 0; i < $scope.investigators.length; i++) {
                                var b = i + 1;
                                var a = $scope.investigators[i].investigatorseq;
                                $scope.investigators[i].investigatorseq = b;
                            }
                        };

                        $scope.onSelectProject = function($item, $model, $label) {
                            if ($model.idproject) {
                                $scope.emailstatus = true
                            }
                            console.log($model)
                        }

                        $scope.asideproinfo = {
                            'selectedpro': null,
                            'budget_cost': null

                        }

                        $scope.master = $scope.asideproinfo;

                        $scope.checkbudgetcost = function() {
                            if ($scope.asideproinfo.budget_cost > 0) {
                                return true
                            } else {
                                return false
                            }
                        }

                        $scope.resetform = function() {
                            $scope.asideproinfo = {
                                'selectedpro': null,
                                'budget_cost': null
                            }
                            $scope.emailstatus = false
                            $scope.investigators = [];
                        }

                        $scope.ProjectKeyup = function(viewValue) {

                            if ($scope.asideproinfo.selectedpro !== null) {
                                $scope.emailstatus = false
                            }
                            return $http.get('./assets/views/action/asideaction/aside_getSecProjectlist.php?sProject=' + viewValue).then(function(res) {
                                return res.data;
                            });
                        }

                        $scope.onSelectInvestigator = function($item, $model, $label) {
                            console.log($model)
                        }

                        $scope.InvestigatorKeyup = function(viewValue) {

                            return $http.get('./assets/views/action/asideaction/aside_getInvestigatorlist.php?sInvestigator=' + viewValue).then(function(res) {
                                return res.data;
                            });
                        }
                        $scope.investigators = [];

                        $scope.addInvestigator = function() {
                            //find id in array collectors 
                            var result = $.grep($scope.investigators, function(e) {
                                return e.iduser == $scope.myModelinvestinfo.iduser
                            });
                            //check value in model and array
                            if ($scope.myModelinvestinfo === '') {
                                alert("กรุณากรอกชื่อผู้ดำเนินโครงการ");
                            } else if (result.length === 0) {
                                var countinvestigators = $scope.investigators;
                                var investnum = countinvestigators.length;
                                $scope.investigatorseq = investnum + 1;
                                $scope.investigators.push({
                                    'firstname_th': $scope.myModelinvestinfo.firstname_th,
                                    'lastname_th': $scope.myModelinvestinfo.lastname_th,
                                    'iduser': $scope.myModelinvestinfo.iduser,
                                    'investigatorseq': $scope.investigatorseq
                                });
                            } else {
                                alert("มีรายชื่ออยู่ในรายการแล้ว");
                            }
                            $(':input#invest').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
                        };
                    }
                });
            };

            $scope.openAside('right');

        });

        $("#AdminProjectListTbl tbody").on("click", "a.removepro", function(event) {
            var tr = $(this).closest('tr');
            var data = tableProjectList.row($(this).closest('tr')).data();
            var rowid = data.DT_RowId;
            if (rowid) {
                var rowidres = rowid.substring(4);
                $state.go('app.inventory.inapps.purchaseplan', {
                    "idproject": rowidres
                })
            }

        });

    }
])