//new style
app.controller('loginCtrl', function($scope, $rootScope, $location, $http, Data, SweetAlert, $state) {
    //initially set those objects to null to avoid undefined error
    $scope.login = {};
    $scope.signup = {
        password: ''
    };
    var previousWindowKeyDown = window.onkeydown;
    $scope.doLogin = function(formdata) {

        Data.post('login', {
            formdata: formdata
        }).then(function(results) {

            Data.toast(results);
            if (results.status == "success") {
                $state.go('app.dashboard')
            }
            if (results.status == "error") {
                SweetAlert.swal({
                    title: "ERROR",
                    text: "Invalid User or password. Please try again",
                    type: "error",
                    confirmButtonColor: "#007AFF"
                }, function(isConfirm) {
                    window.onkeydown = previousWindowKeyDown;
                });
            }
        });
    };

    $scope.doSignup = function(formdata) {

        if (formdata.agree == true) {
            formdata.us_statusid = 2
            Data.post('signUp', {
                formdata: formdata
            }).then(function(results) {
                Data.toast(results);
                if (results.status == "success") {
                    $state.go('login.signin')
                } else {
                    SweetAlert.swal({
                        title: "ERROR",
                        text: "E-mail ได้ลงทะเบียนไว้แล้ว",
                        type: "error",
                        confirmButtonColor: "#007AFF"
                    }, function(isConfirm) {
                        window.onkeydown = previousWindowKeyDown;
                    });
                }
            })
        }

    };
    $scope.logout = function() {
        Data.get('logout').then(function(results) {
            Data.toast(results);
            $location.path('login');
        });
    }
    $scope.getSectionid = function() {
        $http({
            method: 'GET',
            url: 'assets/views/action/getSectionID.php'
        }).then(function(result) {

            $scope.items = result.data;
        });
    }
    $scope.getSectionid()
});