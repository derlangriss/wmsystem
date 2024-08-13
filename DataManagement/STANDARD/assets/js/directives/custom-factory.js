'use strict';
/**
custom factory
 */
app.factory("utils", function() {
    return {
        findById: function(a, id) {
            for (var i = 0; i < a.length; i++)
                if (a[i].id == id) return a[i];
            return null
        }
    }
})
app.factory('UserService', function() {
    return {
        sharedObject: {
            value: '',
            value2: ''
        },
        sharedCheckdup: {
            value: '',
            value2: ''
        }
    };
    /*
    var service = {
        setUserName:setUserName,
        getUserName:getUserName
    };
     
    return service;
    var logbookData = {};
    function setUserName(a,b){
        return logbookData = {
            receive_no: '',
            date_receive: '',
            country_id: 1,
            country: 'Thailand',
            idspectype: 1,
            spectype: 'QSBG',
            idfamilygroup: '0',
            familygroup: 'Unknown',
            idlogbook_details: a,
            collectorcode:b
        };
    }
        
    function getUserName(){
      
    }*/
})
app.factory('getIfNotSet', function() {
    return {
        findById: function(a, id) {
            for (var i = 0; i < a.length; i++)
                if (a[i].id == id) return a[i];
            return null
        },
        inputData: function(value, newValue, overwriteNull) {
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
    }
})
app.factory("test", ["$http", // inject ค่า $http กับ urlData ไปใช้งาน  
    function($http) { // กำหนดตรงนี้ด้วย  
        var factory = {}; // สร้างตัวแปร object   
        // สร้างฟังก์ั่น ใน service myFriend ตัวนี้เป็น  
        // ฟังก์ชั่น สำหรับแสดงข้อมูล มีการส่งค่า id ไปด้วย โดยจะเป็นค่าว่างก็ได้  
        factory.collno = function(idcollection) {
            // ใช้ $http service ไปดึงข้อมูลมาแสด ส่งค่า get viewFriend กับ Id ที่เป็นตัวแปรไปด้วย
            if (idcollection == "") {
                return $http.get("assets/views/action/Edit.php");
            } else {
                return $http.get("assets/views/action/Edit.php?idcollection=" + idcollection);
            }
        };
        factory.specno = function(specid) {
            // ใช้ $http service ไปดึงข้อมูลมาแสด ส่งค่า get viewFriend กับ Id ที่เป็นตัวแปรไปด้วย
            if (specid == "") {
                return $http.get("assets/views/action/EditSpec.php");
            } else {
                return $http.get("assets/views/action/EditSpec.php?specid=" + specid);
            }
        };
        factory.durableno = function(durablelist_id) {
            // ใช้ $http service ไปดึงข้อมูลมาแสด ส่งค่า get viewFriend กับ Id ที่เป็นตัวแปรไปด้วย
            if (durablelist_id == "") {
                return $http.get("assets/views/action/EditSpec.php");
            } else {
                return $http.get("assets/views/action/EditSpec.php?specid=" + specid);
            }
        };
        // สร้างฟังก์ชั่น ใน service myFriend ตัวนี้เป็นฟังก์ชั่น  
        // สำหรับการอัพเดทข้อมูล มีการส่งค่าข้อมูลในฟอร์ม และ Id ของข้อมูลที่จะแก้ไขเข้ามาด้วย  
        factory.updateFriend = function(objFriend, Id) {
            // ใช้ http service ส่งค่าข้อมูลไปทำการแก้ไข และมีการส่งค่า get updateFriend กับ Id ที่เป็นตัวแปรไปด้วย  
            return $http.post(urlData + "?updateFriend&Id=" + Id, objFriend);
        };
        // สร้างฟังก์ั่น ใน service myFriend ตัวนี้เป็น  
        // ฟังก์ชั่น บันทึกข้อมล ส่งค่าแบบ post ส่งค่า object ชุดข้อมูล objFriend  
        factory.addFriend = function(objFriend) {
            // ใช้ $http service ส่งค่าแบบ post   
            // และมีการส่งตัวแปรแบบ get ชื่อ addFriend ไปเป็นเงื่อนไขทำงานคำสั่ง เพิ่มข้อมูล  
            return $http.post(urlData + "?addFriend", objFriend);
        };
        // สร้างฟังก์ั่น ใน service myFriend ตัวนี้เป็น  
        // ฟังก์ชั่น ลบข้อมูล โดยส่งค่า Id เข้าไปทำการลบข้อมูล  
        factory.deleteFriend = function(Id) {
            // ใช้ $http service ส่งค่าแบบ get   
            // และมีการส่งตัวแปรแบบ get ชื่อ deleteFriend  
            // กับ Id สำหรัลใย้ในการลบข้อมูล  
            return $http.get(urlData + "?deleteFriend&Id=" + Id);
        };
        return factory; // คืนค่า object ไปให้ myFriend service  
    }
])
app.factory('RefeshTableService', function() {
    return {
        refresh: {
            value: '',
            value2: ''
        }
    };
})
app.factory('RefeshTableService', function() {
    return {
        refresh: {
            value: '',
            value2: ''
        }
    };
})
app.factory("info", ["$http", function($http) {
    var factory = {};
    factory.pimageslist = function(idproduct) {
        if (idproduct == "") {
            return $http.get("assets/views/action/wmsadmin_product/db_select_pimages.php");
        } else {
            return $http.get("assets/views/action/wmsadmin_product/db_select_pimages.php?idproduct=" + idproduct);
        }

    };
    factory.productinfo = function(idproduct) {
        if (idproduct == "") {
            return $http.get("assets/views/action/wmsadmin_product/db_select_productinfo.php");
        } else {
            return $http.get("assets/views/action/wmsadmin_product/db_select_productinfo.php?idproduct=" + idproduct);
        }
    };
    factory.wmsbudgetyearinfo = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getwmsFiscalYearInfo.php");
        } else {
            return $http.get("assets/views/action/getwmsFiscalYearInfo.php?fiscalyear=" + id);
        }
    };
    factory.projectinfo = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getHBox_info.php");
        } else {
            return $http.get("assets/views/action/getHFiscalYearInfo.php?Sfiscalyear=" + id);
        }
    };
    factory.herbariumboxinfo = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getHBox_info.php");
        } else {
            return $http.get("assets/views/action/getHBox_info.php?idreceivedetails=" + id);
        }
    };
    factory.herbariumbox = function(id, idcode) {
        if (id == "") {
            return $http.get("assets/views/action/getBox_herbarium.php");
        } else {
            return $http.get("assets/views/action/getBox_herbarium.php?idreceive=" + id + "&&idcodename=" + idcode);
        }
    };
    factory.hboxdetails = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/gethboxdetails.php");
        } else {
            return $http.get("assets/views/action/gethboxdetails.php?idreceivedetails=" + id);
        }
    };
    factory.hrdetails = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getRdetails.php");
        } else {
            return $http.get("assets/views/action/getRdetails.php?idreceive=" + id);
        }
    };
    factory.planningdetails = function(id) {
        return $http.get("assets/views/action/getPlanningdetails.php");
    };
    factory.herbboxdetails = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getherbboxdetails.php");
        } else {
            return $http.get("assets/views/action/getherbboxdetails.php?idreceivedetails=" + id);
        }
    };
    factory.herbboxflora = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getherbboxflra.php");
        } else {
            return $http.get("assets/views/action/getherbboxflra.php?idreceivedetails=" + id);
        }
    };
    factory.selectrdetails = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/selectrdetails.php");
        } else {
            return $http.get("assets/views/action/selectrdetails.php?idreceive=" + id);
        }
    };
    factory.collectorherbarium = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getCollector_herbarium.php");
        } else {
            return $http.get("assets/views/action/getCollector_herbarium.php?idcodename=" + id);
        }
    };
    factory.collectordetails = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getCollector_herbarium_details.php");
        } else {
            return $http.get("assets/views/action/getCollector_herbarium_details.php?idcodename=" + id);
        }
    };
    factory.collno = function(idcollection) {
        if (idcollection == "") {
            return $http.get("assets/views/action/EditCollection.php");
        } else {
            return $http.get("assets/views/action/EditCollection.php?idcollection=" + idcollection);
        }
    };
    factory.collectiondata = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/EditCollection.php");
        } else {
            return $http.get("assets/views/action/EditCollection.php?collid=" + id);
        }
    };
    factory.collectordata = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getCollectorsarr.php");
        } else {
            return $http.get("assets/views/action/getCollectorsarr.php?collid=" + id);
        }
    };
    factory.imagesdata = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/getImagesarr.php");
        } else {
            return $http.get("assets/views/action/getImagesarr.php?collid=" + id);
        }
    };
    factory.productimages = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/wmsadmin_product/db_select_productimages.php");
        } else {
            return $http.get("assets/views/action/wmsadmin_product/db_select_productimages.php?idproduct=" + id);
        }
    };
    factory.durabledata = function(id) {
        if (id == "") {
            return $http.get("assets/views/action/EditDurable.php");
        } else {
            return $http.get("assets/views/action/EditDurable.php?durableid=" + id);
        }
    }
    factory.countproduct = function() {
        return $http.get("assets/views/action/wmsadmin_product/db_calculate_product.php");
    }
    factory.specno = function(specid) {
        // ใช้ $http service ไปดึงข้อมูลมาแสด ส่งค่า get viewFriend กับ Id ที่เป็นตัวแปรไปด้วย
        if (specid == "") {
            return $http.get("assets/views/action/EditSpecimens.php");
        } else {
            return $http.get("assets/views/action/EditSpecimens.php?specid=" + specid);
        }
    };
    factory.projectbudgetdetails = function(idproject_budget) {
        if (idproject_budget == "") {
            return $http.get("assets/views/action/purchase_details/db_select_projectinfo.php");
        } else {
            return $http.get("assets/views/action/purchase_details/db_select_projectinfo.php?idproject_budget=" + idproject_budget);
        }
    };
    factory.purchaseplan = function(id) {
        if (idproject == "") {
            return $http.get("assets/views/action/purchase_details/db_select_projectinfo.php");
        } else {
            return $http.get("assets/views/action/purchase_details/db_select_projectinfo.php?idproject_budget=" + id);
        }
    };
    factory.purchaseexpect = function(idproject_details, idproject_budget) {
        if (idproject_details == "") {
            return $http.get("assets/views/action/purchase_plan/db_select_expectinfo.php");
        } else {
            return $http.get("assets/views/action/purchase_plan/db_select_expectinfo.php?idproject_details=" + idproject_details + "&&idproject_budget=" + idproject_budget);
        }
    };
    factory.budgetcalculate = function(idproject_details) {
        if (idproject_details == "") {
            return $http.get("assets/views/action/purchase_plan/db_calculate_cost.php");
        } else {
            return $http.get("assets/views/action/purchase_plan/db_calculate_cost.php?idproject_details=" + idproject_details);
        }
    };
    factory.calculatebudget = function(idproject_budget) {
        if (idproject_budget == "") {
            return $http.get("assets/views/action/purchase_details/db_calculate_budget.php");
        } else {
            return $http.get("assets/views/action/purchase_details/db_calculate_budget.php?idproject_budget=" + idproject_budget);
        }
    };
    factory.callproduct = function(idproduct) {
        if (idproduct == "") {
            return $http.get("assets/views/action/product_details/db_select_product.php");
        } else {
            return $http.get("assets/views/action/product_details/db_select_product.php?idproduct=" + idproduct);
        }
    };

    return factory;
}])
app.factory('ServicePDF', function($http) {
    return {
        downloadPdf: function(labeltype) {
            return $http.get("assets/views/action/LabelPDF.php?labeltype=" + labeltype, {
                responseType: 'arraybuffer'
            }).then(function(response) {
                return response;
            });
        },
        downloadReport: function(reporttype, strmonth, stryear) {
            return $http.get("assets/views/action/ReportPDF.php?reporttype=" + reporttype + "&&strmonth=" + strmonth + "&&stryear=" + stryear, {
                responseType: 'arraybuffer'
            }).then(function(response) {
                return response;
            });
        },
        downloadSpecBox: function(strreportspectype, strmonth, stryear, strcontype, strboxid) {
            return $http.get("assets/views/action/ReportSpecBoxPDF.php?reportspectype=" + strreportspectype + "&&reportmonth=" + strmonth + "&&reportyear=" + stryear + "&&reportcontype=" + strcontype + "&&reportboxid=" + strboxid, {
                responseType: 'arraybuffer'
            }).then(function(response) {
                return response;
            });
        },
        downloadDurable: function(reporttype) {
            return $http.get("assets/views/action/ReportDuPDF.php?reporttype=" + reporttype, {
                responseType: 'arraybuffer'
            }).then(function(response) {
                return response;
            });
        }
    };
})
app.factory('printsum', function($http) {
    var factorytest = {};
    factorytest.getdata = function(callback) {
        $http.get('assets/views/action/printsum.php').success(callback);
    };
    factorytest.totaldata = function(labeltype) {
        return $http.get("assets/views/action/totaldata.php?labeltype=" + labeltype);
    };
    factorytest.totalcollection = function(labeltype) {
        return $http.get("assets/views/action/count_code.php?labeltype=" + labeltype);
    };
    factorytest.printspecimendata = function(labeltype) {
        return $http.get("assets/views/action/printsum.php?labeltype=" + labeltype);
    }
    return factorytest;
})
app.factory('calculatedbData', function($http) {
    var factoryCalculate = {};
    factoryCalculate.getSUMspec = function(sumtype) {
        return $http.get("assets/views/action/SumDataDetails.php?sumtype=" + sumtype);
    };
    factoryCalculate.getSummonth = function(sumtype, strmonth) {
        return $http.get("assets/views/action/SumDataDetails.php?sumtype=" + sumtype + "&&strmonth=" + strmonth);
    };
    factoryCalculate.totalcollection = function(labeltype) {
        return $http.get("assets/views/action/count_code.php?labeltype=" + labeltype);
    };
    factoryCalculate.printspecimendata = function(labeltype) {
        return $http.get("assets/views/action/printsum.php?labeltype=" + labeltype);
    }
    return factoryCalculate;
})
app.factory("herbariumdata", ['$http', 'toaster',
    function($http, toaster) { // This service connects to our REST API
        var obj = {};
        obj.post = function(q, object) {
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            var data = $.param(object.logbook);
            /*
            $http({
                method: 'POST',
                url: 'assets/views/action/dbHerbManage.php',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(results) {
                results.data;
            });*/
            return $http.post('assets/views/action/dbHerbManage.php', data, config).then(function(results) {
                return results.data;
            });
        };
        /*
        obj.toast = function (data) {
            toaster.pop(data.status, "", data.message, 250, 'trustedHtml');
        }
        obj.get = function (q) {
            return $http.get(serviceBase + q).then(function (results) {
                return results.data;
            });
        };
        
        obj.put = function (q, object) {
            return $http.put(serviceBase + q, object).then(function (results) {
                return results.data;
            });
        };
        obj.delete = function (q) {
            return $http.delete(serviceBase + q).then(function (results) {
                return results.data;
            });
        };*/
        return obj;
    }
])
app.factory("Data", ['$http', 'toaster',
    function($http, toaster) { // This service connects to our REST API
        var serviceBase = 'assets/api/v1/';
        var obj = {};
        obj.toast = function(data) {
            toaster.pop(data.status, "", data.message, 250, 'trustedHtml');
        }
        obj.get = function(q) {
            return $http.get(serviceBase + q).then(function(results) {
                return results.data;
            });
        };
        obj.post = function(q, object) {

            return $http.post(serviceBase + q, object).then(function(results) {
                return results.data;
            });
        };
        obj.put = function(q, object) {
            return $http.put(serviceBase + q, object).then(function(results) {
                return results.data;
            });
        };
        obj.delete = function(q) {
            return $http.delete(serviceBase + q).then(function(results) {
                return results.data;
            });
        };
        return obj;
    }
])