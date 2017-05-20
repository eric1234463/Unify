angular.module('Photo.controllers', [])

.controller('LifePhotoDiaryCtrl', function($scope, AccountFactory, $cordovaCamera, $ionicSideMenuDelegate, $http, $stateParams, $state, gossipFactory, $timeout, $rootScope, $ionicPopup, $ionicLoading, $ionicHistory) {
    $scope.goToLove = function() {
        $state.go('love');
    }

    $scope.searchFriend = function() {
        $state.go('searchFriend');
    }

    $scope.openMenu = function() {
        $ionicSideMenuDelegate.toggleLeft();
    }

    $scope.$on('$destroy', function() {
        console.log('destroy');
    });

    ionic.Platform.ready(function() {
        var accountName = AccountFactory.getAccountName();
        $scope.Icon = AccountFactory.getIcon();
        $scope.accountName = "<h2>" + accountName + "</h2>";
        $scope.photoList = [{
            'Photo': undefined,
            'Status': undefined
        }];
    });

    $scope.TakePhoto = function(photo) {
        var options = {
            quality: 100,
            targetWidth: 300,
            targetHeight: 300,
            allowEdit: true,
            destinationType: Camera.DestinationType.DATA_URL,
            sourceType: Camera.PictureSourceType.CAMERA,
            encodingType: Camera.EncodingType.JPEG,
            popoverOptions: CameraPopoverOptions,
            saveToPhotoAlbum: true
        };

        $cordovaCamera.getPicture(options).then(function(imageData) {
            photo.Photo = "data:image/jpeg;base64," + imageData;
        }, function(err) {
            // An error occured. Show a message to the user
            console.log(err);
        });
    }
    $scope.Vaildation = function() {
        checker = false
        var i = $scope.photoList.length - 1;
        if ($scope.photoList[i].Photo == undefined) {
            checker = true
        }

        if ($scope.photoList[i].Status == undefined) {
            checker = true
        }

        if (checker == false) {
            $scope.photoList.push({
                'Photo': undefined,
                'Status': undefined
            })
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Miss Information',
                template: 'Please fullfill all the information before insert new data!'
            });

        }
    }

    $scope.submit = function() {
        var checker = false;
        for (i = 0; i < $scope.photoList.length; i++) {
            if ($scope.photoList[i].Photo == undefined) {
                checker = true;
            }
            if ($scope.photoList[i].Status == undefined) {
                checker = true;
            }
            $scope.photoList[i].Status = escape($scope.photoList[i].Status).replace(/%20/g, " ");
        }
        if ($scope.Title == undefined) {
            checker = true;
        }
        if (checker == false) {
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/actionProfile.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    action: 'createStory',
                    photoDiary: $scope.photoList,
                    title: $scope.Title
                }
            }
            $http(post).then(function(response) {
                $state.go('tab.life.tabLifeView');
            })
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Miss Information',
                template: 'Please fullfill all the information before Submit'
            });
        }
    }
})
