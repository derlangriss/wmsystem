'use strict';
/** 
  * controllers for GoogleMap 
  * AngularJS Directive
*/
app.controller("GridFormSpecimenCtrl", ["$scope","$http", "$timeout", "$stateParams", "SweetAlert", "test", 
    function ($scope, $http, $timeout, $stateParams, SweetAlert, test) {

    var selected = [];
    var table = $('#Coll_Specimenlist').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "assets/scripts/server_processing_specimendata.php",
            "type": "POST"
        },
        "dom": '<"top"pl><"clear">rti',
        "scrollX": true,
        "scrollY": "300px",
        "scrollCollapse": true


    });

    $('#example').DataTable( {
        "scrollX": true
    } );
  
    
}])
