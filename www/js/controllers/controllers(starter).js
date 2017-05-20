angular.module('starter.controllers', []).controller('LoginCtrl', function($scope, $cookies, $ionicSideMenuDelegate, LoginService, $ionicPopup, $state, AccountFactory, $ionicLoading, $http, $cordovaDevice) {
    ionic.Platform.ready(function() {
        /* only can be work in phone for auto login
        var uuid = $cordovaDevice.getUUID();
        $http.get('http://unicomhk.net/fyp/www/php/loginChecker.php?uuid=' + $cordovaDevice.getUUID()).then(function(response) {
            if (response.data == 'success') {
                AccountFactory.getAccountinfo();
                $state.go('tab.life.tabLifeView');
            }
        })
        */
        $ionicSideMenuDelegate.canDragContent(false)
    })

    $scope.data = {};
    $scope.usernameFocus = function() {
        document.getElementById("username").style.borderBottom = "3px solid #004D40";
    }
    $scope.usernameBlur = function() {
        document.getElementById("username").style.borderColor = "#FFF";
    }
    $scope.passwordFocus = function() {
        document.getElementById("password").style.borderBottom = "3px solid #004D40";

    }
    $scope.passwordBlur = function() {
        document.getElementById("password").style.borderColor = "#FFF";
    }
    $scope.formlogin = function() {
        if ($scope.data.username == undefined || $scope.data.password == undefined) {
            var alertPopup = $ionicPopup.alert({
                title: 'Miss information!',
                template: 'Please input both Username and Password!'
            });
        } else {
            LoginService.loginUser($scope.data.username, $scope.data.password).success(function(data) {
                AccountFactory.getAccountinfo();
                $state.go('tab.life.tabLifeView');
            }).error(function(data) {
                var alertPopup = $ionicPopup.alert({
                    title: 'Login failed!',
                    template: 'Please input correct AccountName Or Password!'
                });
            });
        }
    }
    $scope.$on('$ionicView.leave', function() {
        $ionicSideMenuDelegate.canDragContent(true)
    });

    $scope.createNewUser = function() {
        $state.go('createNewUser');
    }
})

.controller('CreateNewUserCtrl', function($scope, $state, $http, $ionicSideMenuDelegate, createNewUserService, $ionicPopup) {
    $scope.createNewUser = {};
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/getCreateNewUserMasterData.php?datatype=school').then(function(response) {
            $scope.schools = response.data;
        })
    });

    $ionicSideMenuDelegate.canDragContent(false)
    $scope.$on('$ionicView.leave', function() {
        $ionicSideMenuDelegate.canDragContent(true)
    });
    $scope.getMasterDataForStudy = function() {
        var schoolID = $scope.createNewUser.school;
        $http.get('http://unicomhk.net/fyp/www/php/getCreateNewUserMasterData.php?datatype=study&SchoolID=' + schoolID).then(function(response) {
            $scope.programs = response.data;
        })
        $http.get('http://unicomhk.net/fyp/www/php/getCreateNewUserMasterData.php?datatype=email&SchoolID=' + schoolID).then(function(response) {
            $scope.createNewUser.emaildomain = response.data;
        })
    }
    $scope.nextTophoneinfo = function() {
        if ($scope.createNewUser.school !== undefined && $scope.createNewUser.program !== undefined && $scope.createNewUser.year !== undefined &&
            $scope.createNewUser.gender !== undefined && $scope.createNewUser.email !== undefined) {
            createNewUserService.passdata($scope.createNewUser);
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/actionCreateNewUser.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    action: 'sendEmail',
                    email: $scope.createNewUser.email + $scope.createNewUser.emaildomain
                }
            }
            $http(post).then(function(response) {
                $state.go('newUserphoneinfo');
            })
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Data Error!',
                template: 'Please input correct data!'
            });
        }
    }


    $scope.backToPreviousPage = function() {
        $state.go('login');
    }
}).

controller('NewUserphoneinfoCtrl', function($scope, $state, $ionicPopup, $ionicSideMenuDelegate, createNewUserService, $http) {
    $ionicSideMenuDelegate.canDragContent(false)
    $scope.$on('$ionicView.leave', function() {
        $ionicSideMenuDelegate.canDragContent(true)
    });
    $scope.data = {};
    $scope.nextToTabLife = function() {
        var year = createNewUserService.getyear();
        var school = createNewUserService.getschool();
        var gender = createNewUserService.getgender();
        var program = createNewUserService.getprogram();
        var email = createNewUserService.getemail();
        var checker = false;

        if (!/^[a-zA-Z0-9]*$/g.test($scope.data.username)) {
            checker = true;
        }
        if ($scope.data.telephone == undefined || $scope.data.username == undefined || $scope.data.password == undefined) {
            checker = true;
        }
        var telephone = parseInt($scope.data.telephone);
        if (isNaN(telephone)) {
            checker = true;
        }
        if ($scope.data.telephone.length < 8) {
            checker = true;
        }
        if (checker == true) {
            var alertPopup = $ionicPopup.alert({
                title: 'Data Error!',
                template: 'Please input correct data!'
            });
        } else {
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/actionCreateNewUser.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    action: 'createNewUser',
                    year: year,
                    school: school,
                    gender: gender,
                    program: program,
                    username: $scope.data.username,
                    password: $scope.data.password,
                    telephone: $scope.data.telephone,
                    pin: $scope.data.pin,
                    email: email
                }
            }
            $http(post).then(function(response) {
                if (response.data == "failed") {
                    var alertPopup = $ionicPopup.alert({
                        title: 'Duplicate Username!',
                        template: 'Please input another name Or The Pin is Not Correct!'
                    });
                } else {
                    $state.go('tab.life.tabLifeView');
                }
            });
        }
    }
    $scope.backToPreviousPage = function() {
        $state.go('createNewUser');
    }
})
