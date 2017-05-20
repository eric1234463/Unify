angular.module('home.controllers', [])

.controller('HomeCtrl', function($scope, $http, $window, $stateParams, $filter, $state, $timeout, $rootScope, ProfileFactory, $ionicPopup, $ionicLoading, $ionicHistory, statusFactory, HomeFactory, AccountFactory) {
    ionic.Platform.ready(function() {
        var AccountID = HomeFactory.getAccountID();
        var AccountName = HomeFactory.getAccountName();
        if (HomeFactory.getAccountID() !== AccountFactory.getAccountID()) {
            $scope.edit = 'N';
        } else {
            $scope.edit = 'Y';
        }
        $scope.AddFriend = function() {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_friend&friendAccountID=' + HomeFactory.getAccountID() + "&friendAccountName=" + HomeFactory.getAccountName()).then(function(response) {
                $scope.friendchecker = 'Y';
                $state.go($state.current, {}, { reload: true });
            })
        }

        $scope.removeFriend = function(friend) {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=remove_friend&friendAccountID=' + HomeFactory.getAccountID() + "&friendAccountName=" + HomeFactory.getAccountName()).then(function(response) {
                $scope.friendchecker = 'N';
                $state.go($state.current, {}, { reload: true });
            })
        }

        $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=getselfdata&AccountID=' + AccountID + '&AccountName=' + AccountName).then(function(response) {
            if (response.data.length > 0) {
                $scope.statusList = response.data;
            }
        })

        $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=getTimetable&AccountID=' + AccountID + '&inputDate=' + $filter('date')(new Date, 'yyyy-MM-dd')).then(function(response) {
            if (response.data.length > 0) {
                $scope.recData = response.data;
            } else {
                $scope.recData = {};
            }
        });
        $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=getAccountInfo&AccountID=' + AccountID + '&AccountName=' + AccountName).then(function(response) {
            ProfileFactory.pass(response.data[0]);
            $scope.accountName = response.data[0]['AccountName'] + " 's Home";
            $scope.friendAmount = response.data[0]['FriendAmount'];
            $scope.statusCount = response.data[0]['StatusCount'];
            $scope.likedCount = response.data[0]['LikedCount'];
            $scope.disLikedCount = response.data[0]['disLikedCount'];
            $scope.selfIntrodution = response.data[0]['SelfIntroduction'];
            $scope.school = response.data[0]['SchoolName'];
            $scope.program = response.data[0]['ProgramName'];
            $scope.gender = response.data[0]['Gender'];
            $scope.birthday = response.data[0]['Birthday'];
            $scope.YearOfEntry = response.data[0]['YearOfEntry'];
            $scope.Weight = response.data[0]['Weight'];
            $scope.Height = response.data[0]['Height'];
            $scope.friendchecker = response.data[0]['friendchecker'];
            $scope.Icon = response.data[0]['Icon'];
            $scope.Telephone = response.data[0]['Telephone'];
            $scope.private = response.data[0]['Private'];
            $scope.relationship = response.data[0]['Relationship'];
            $scope.experience = response.data[0]['Experience'];
            $scope.sexuality = response.data[0]['Sexuality'];
            $scope.CoverPhoto = 'background-image:url(' + response.data[0]['Banner'] + ')';
        })
        var today = new Date();
        today = $filter('date')(today, 'yyyy-MM-dd');
        $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=getPhotoDiary&AccountID=' + AccountID + "&date=" + today).then(function(response) {
            if (response.data.length > 0) {
                $scope.DiaryList = response.data;
            } else {
                $scope.DiaryList = {}
            }
        })
    });
    $scope.backToTabMore = function() {
        var url = HomeFactory.getUrl()
        $state.go("" + url);
    }

    $scope.goTAsk = function() {
        var AccountID = HomeFactory.getAccountID();
        var AccountName = HomeFactory.getAccountName();
        $scope.data = {};
        // An elaborate, custom popup
        var myPopup = $ionicPopup.show({
            template: '<input type="text" ng-model="data.qes">',
            title: 'Ask Me a Question?',
            subTitle: 'Please Enter Your Question!',
            scope: $scope,
            buttons: [
                { text: 'Cancel' }, {
                    text: '<b>Ask</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if (!$scope.data.qes) {
                            e.preventDefault();
                        } else {
                            $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=AskQuestion&AccountID=' + AccountID + '&AccountName=' + AccountName + "&question=" + $scope.data.qes).then(function(response) {})
                        }
                    }
                }
            ]
        })
    }
    $scope.goToLikeRrank = function() {
        var AccountID = HomeFactory.getAccountID();
        $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=getLikeRank&AccountID=' + AccountID).then(function(response) {
            if (response.data.length > 0) {
                $scope.likeList = response.data;
            } else {
                $scope.likeList = {};
            }
        });

        var likePopup = $ionicPopup.alert({
            templateUrl: 'templates/likeRank.php',
            title: 'LikeRank',
            scope: $scope,
        })
    }

    $scope.goToDisLikeRrank = function() {
        var AccountID = HomeFactory.getAccountID();
        $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=getDisLikeRank&AccountID=' + AccountID).then(function(response) {
            if (response.data.length > 0) {
                $scope.dislikeList = response.data;
            } else {
                $scope.dislikeList = {};
            }
        });

        var dislikePopup = $ionicPopup.alert({
            templateUrl: 'templates/dislikeRank.php',
            title: 'DislikeRank',
            scope: $scope,
        })
    }

    $scope.showStatusInfo = function(status) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getStatusLike&statusID=' + status.StatusID).then(function(response) {
            if (response.data.length > 0) {
                $scope.likeList = response.data;
            } else {
                $scope.likeList = {};
            }
        })
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getStatusDisLike&statusID=' + status.StatusID).then(function(response) {
            if (response.data.length > 0) {
                $scope.disLikeList = response.data;
            } else {
                $scope.disLikeList = {};
            }
        })
        var myPopup = $ionicPopup.alert({
            templateUrl: 'templates/tab-life(statusInfo).php',
            title: 'Like',
            scope: $scope,
        })
    }

    $scope.editStatus = function(status) {
        statusFactory.pass(status);
        $state.go('editstatus')
    }

    $scope.Addlike = function(status) {
        if (status.YouLike == 'Y') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                if (parseInt(status.Liked) > 1) {
                    status.Liked = parseInt(status.Liked) - 1
                } else {
                    status.Liked = "0";
                }
                $scope.likedCount = parseInt($scope.likedCount) - 1
                status.YouLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                status.Liked = parseInt(status.Liked) + 1
                $scope.likedCount = parseInt($scope.likedCount) + 1
                status.YouLike = 'Y';
            })

            if (status.YouDisLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                    if (parseInt(status.DisLiked) > 1) {
                        status.DisLiked = parseInt(status.DisLiked) - 1;
                    } else {
                        status.DisLiked = "0";
                    }
                    $scope.disLikedCount = parseInt($scope.disLikedCount) - 1
                    status.YouDisLike = 'N'
                })
            }
        }
    };

    $scope.AddDisLike = function(status) {
        if (status.YouDisLike == 'Y') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                if (parseInt(status.Liked) > 1) {
                    status.DisLiked = parseInt(status.DisLiked) - 1
                } else {
                    status.DisLiked = "0";
                }
                $scope.disLikedCount = parseInt($scope.disLikedCount) - 1
                status.YouDisLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                status.DisLiked = parseInt(status.DisLiked) + 1
                $scope.disLikedCount = parseInt($scope.disLikedCount) + 1
                status.YouDisLike = 'Y';
            })

            if (status.YouLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                    if (parseInt(status.Liked) > 1) {
                        status.Liked = parseInt(status.Liked) - 1;
                    } else {
                        status.Liked = "0";
                    }
                    $scope.likedCount = parseInt($scope.likedCount) - 1
                    status.YouLike = 'N'
                })
            }
        }
    };

    $scope.Comment = function(status) {
        $scope.status = status;
        var myPopup = $ionicPopup.show({
            title: 'Comment',
            scope: $scope,
            templateUrl: 'templates/comment.php',
            buttons: [
                { text: 'Cancel' }, {
                    text: '<b>Add Comment</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var newComment = document.getElementById(status.StatusID + "_newcomment").value;
                        if (newComment !== '') {
                            var statusID = status.StatusID;
                            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_comment&statusID=' + statusID + '&newComment=' + newComment).then(function(response) {
                                status.Comment = parseInt(status.Comment) + 1
                            });
                            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_comment&statusID=' + statusID).then(function(response) {
                                status.ShowMore = '1';
                                if (response.data.length > 0) {
                                    status.commentList = response.data;
                                } else {
                                    status.commentList = {};
                                }
                            })
                        }
                    }
                }
            ]
        });
    };

    $scope.showLessComment = function(status) {
        var statusID = status.StatusID;
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_comment&statusID=' + statusID).then(function(response) {
            status.ShowMore = '1';
            if (response.data.length > 0) {
                status.commentList = response.data;
            } else {
                status.commentList = {};
            }
        })
    }


    $scope.showMoreComment = function(status) {
        var statusID = status.StatusID;
        var comment = status.StatusID + "_comment";
        var commentBox = status.StatusID + "_commentBox";
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_fullcomment&statusID=' + statusID).then(function(response) {
            status.ShowMore = '0';
            if (response.data.length > 0) {
                status.commentList = response.data;
            } else {
                status.commentList = {};
            }
        });

    };


    $scope.AddComment = function(status) {
        var newComment = document.getElementById(status.StatusID + "_newcomment").value;
        var statusID = status.StatusID;
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_comment&statusID=' + statusID + '&newComment=' + newComment).then(function(response) {
            document.getElementById(status.StatusID + "_newcomment").value = '';
            status.Comment = parseInt(status.Comment) + 1
        });
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_comment&statusID=' + statusID).then(function(response) {
            status.ShowMore = 'N';
            if (response.data.length > 0) {
                status.commentList = response.data;
            } else {
                status.commentList = {};
            }
        })
    }

    $scope.datepickerObject = {
        titleLabel: 'Calendar - Select Date', //Optional
        inputDate: new Date(), //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'popup', //Optional can be popup
        showTodayButton: 'true', //Optional
        modalHeaderColor: 'bar-calm', //Optional
        modalFooterColor: 'bar-calm', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val == -'undefined') {} else {
                var AccountID = HomeFactory.getAccountID();
                this.inputDate = val;
                val = $filter('date')(val, 'yyyy-MM-dd');
                $http.get('http://unicomhk.net/fyp/www/php/actionHome.php?action=getPhotoDiary&AccountID=' + AccountID + "&date=" + val).then(function(response) {
                    if (response.data.length > 0) {
                        $scope.DiaryList = response.data;
                    } else {
                        $scope.DiaryList = {}
                    }
                })
            }
        },
        dateFormat: 'YYYY-MM-DD', //Optional
    };

    $scope.goToEdit = function() {
        $state.go('editProfile');
    }
})

.controller('editProfileCtrl', function($scope, $http, $cordovaCamera, $cordovaImagePicker, $filter, ProfileFactory, $stateParams, $state, $timeout, $rootScope, $ionicPopup, $ionicLoading, $ionicHistory, statusFactory, HomeFactory, AccountFactory) {
    var profile = ProfileFactory.all();
    $scope.profile = {};
    $scope.backToHome = function() {
        $state.go('home');
    }

    $scope.submitProfile = function() {
        $scope.intro = escape($scope.intro).replace(/%20/g, " ");
        var birthday = $scope.datepickerObject.inputDate;
        birthday = $filter('date')(birthday, 'yyyy-MM-dd');
        var uploadPhoto = 'Y';
        var uploadIcon = 'Y';
        if (profile.Banner == $scope.CoverPhoto) {
            uploadPhoto = 'N'
        }
        uploadIcon = 'Y';
        if (profile.Icon == $scope.icon) {
            var uploadIcon = 'N'
        }
        var post = {
            method: 'POST',
            url: 'http://unicomhk.net/fyp/www/php/actionProfile.php',
            headers: {
                'Content-Type': 'application/json',
            },
            data: {
                action: 'editProfile',
                icon: $scope.icon,
                birthday: birthday,
                intro: $scope.intro,
                Height: $scope.profile.Height,
                Weight: $scope.profile.Weight,
                Telephone: $scope.profile.Telephone,
                CoverPhoto: $scope.CoverPhoto,
                Experience: $scope.profile.Experience,
                Relationship: $scope.profile.Relationship,
                Sexuality: $scope.profile.Sexuality,
                uploadPhoto: uploadPhoto,
                uploadIcon: uploadIcon
            }
        }
        $http(post).then(function(response) {
            $state.go('home');
        })
    }

    ionic.Platform.ready(function() {
        $scope.intro = profile.SelfIntroduction;
        if (profile.Birthday == '0000-00-00') {
            profile.Birthday = new Date();
        } else {
            profile.Birthday = new Date(profile.Birthday);
        }
        $scope.icon = profile.Icon;
        $scope.CoverPhoto = profile.Banner;
        $scope.profile.Height = profile.Height;
        $scope.profile.Weight = profile.Weight;
        $scope.profile.Telephone = profile.Telephone;
        $scope.profile.Relationship = profile.Relationship;
        $scope.profile.Experience = profile.Experience;
        $scope.profile.Sexuality = profile.Sexuality;

    })
    $scope.pickCoverPhoto = function() {
        $cordovaImagePicker.getPictures(options)
            .then(function(results) {
                for (var i = 0; i < results.length; i++) {
                    console.log('Image URI: ' + results[i]);
                }
            }, function(error) {
                // error getting photos
            });
    }
    $scope.takeCoverPhoto = function() {
        var options = {
            quality: 100,
            targetWidth: 450,
            targetHeight: 250,
            allowEdit: true,
            destinationType: Camera.DestinationType.DATA_URL,
            sourceType: Camera.PictureSourceType.CAMERA,
            encodingType: Camera.EncodingType.JPEG,
            popoverOptions: CameraPopoverOptions,
            saveToPhotoAlbum: true
        };

        $cordovaCamera.getPicture(options).then(function(imageData) {
            $scope.CoverPhoto = "data:image/jpeg;base64," + imageData;
        }, function(err) {
            // An error occured. Show a message to the user
            console.log(err);
        });
    }


    $scope.takeIcon = function() {
        var options = {
            quality: 100,
            targetWidth: 144,
            targetHeight: 144,
            allowEdit: true,
            destinationType: Camera.DestinationType.DATA_URL,
            sourceType: Camera.PictureSourceType.CAMERA,
            encodingType: Camera.EncodingType.JPEG,
            popoverOptions: CameraPopoverOptions,
            saveToPhotoAlbum: true
        };

        $cordovaCamera.getPicture(options).then(function(imageData) {
            $scope.icon = "data:image/jpeg;base64," + imageData;
        }, function(err) {
            // An error occured. Show a message to the user
            console.log(err);
        });
    }

    $scope.datepickerObject = {
        titleLabel: 'Calendar - Select Date', //Optional
        inputDate: profile.Birthday, //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'popup', //Optional can be popup
        showTodayButton: 'true', //Optional
        modalHeaderColor: 'bar-calm', //Optional
        modalFooterColor: 'bar-calm', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val == -'undefined') {} else {
                this.inputDate = val;
            }
        },
        dateFormat: 'YYYY-MM-DD', //Optional
    };

})
