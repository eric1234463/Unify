angular.module('life.controllers', [])

.controller('LifeViewCtrl', function($ionicSideMenuDelegate, HomeFactory, $sanitize, $ionicScrollDelegate, $ionicPlatform, $scope, AccountFactory, $http, $stateParams, $state, statusFactory, $timeout, $rootScope, $ionicPopup, $ionicLoading, $ionicHistory) {
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getdata').then(function(response) {
            if (response.data.length > 0) {
                $scope.statusList = response.data;
            } else {
                $scope.statusList = {};
                $http.get('http://unicomhk.net/fyp/www/php/actionFunction.php?action=checkMentor').then(function(response) {
                    if (response.data != '') {
                        var confirmPopup = $ionicPopup.confirm({
                            title: 'Wellcome To The New User',
                            template: 'Would you want to join our mentorship system?'
                        });
                        confirmPopup.then(function(res) {
                            if (res) {
                                $state.go('accountList');
                            }
                        });
                    }
                });
            }
        })
    });
    $scope.$on('$ionicView.enter', function() {
        $ionicSideMenuDelegate.canDragContent(true)
    });
    $scope.openMenu = function() {
        $ionicSideMenuDelegate.toggleLeft();
    }

    $ionicPlatform.onHardwareBackButton(function() {
        if ($ionicHistory.currentStateName() == 'tab.alert.alertNotification' ||
            $ionicHistory.currentStateName() == 'tab.chats' ||
            $ionicHistory.currentStateName() == 'tab.timetable' ||
            $ionicHistory.currentStateName() == 'tab.createNewStatus' ||
            $ionicHistory.currentStateName() == 'tab.life.tabLifeView') {
            ionic.Platform.exitApp()
        }
    })

    $scope.goToLove = function() {
        $state.go('love');
    }
    $scope.goToStatus = function() {
        $state.go('createNewStatus');
    }
    $scope.goToHome = function(status) {
        HomeFactory.pass(status, 'tab.life.tabLifeView')
        $state.go('home');
    }

    $scope.searchFriend = function() {
        $state.go('searchFriend');
    };


    $scope.editStatus = function(status) {
        statusFactory.pass(status, "tab.life.tabLifeView");
        $state.go('editstatus')
    }


    $scope.doRefresh = function() {
        $scope.$broadcast('scroll.refreshComplete');
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


    $scope.$on('scroll.refreshComplete', function() {
        ionic.Platform.ready(function() {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getdata').then(function(response) {
                if (response.data.length > 0) {
                    $scope.statusList = response.data;
                } else {
                    $scope.statusList = {};
                }
            })
        });
    });


    /*          Function For the Tab Life                */
    $scope.Addlike = function(status) {
        if (status.YouLike == 'Y') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                if (parseInt(status.Liked) > 1) {
                    status.Liked = parseInt(status.Liked) - 1
                } else {
                    status.Liked = "0";
                }
                status.YouLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                status.Liked = parseInt(status.Liked) + 1
                status.YouLike = 'Y';
            })

            if (status.YouDisLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                    if (parseInt(status.DisLiked) > 1) {
                        status.DisLiked = parseInt(status.DisLiked) - 1;
                    } else {
                        status.DisLiked = "0";
                    }
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
                status.YouDisLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                status.DisLiked = parseInt(status.DisLiked) + 1
                status.YouDisLike = 'Y';
            })

            if (status.YouLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                    if (parseInt(status.Liked) > 1) {
                        status.Liked = parseInt(status.Liked) - 1;
                    } else {
                        status.Liked = "0";
                    }
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
                            $scope.status = status;
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




})


.controller('CreateNewStatusCtrl', function($scope, $state, $sanitize, $ionicSideMenuDelegate, AccountFactory, $http, $cordovaCamera, $window, $ionicPopup, $ionicLoading, $ionicHistory) {
    $scope.data = {};

    $scope.backToTabLife = function() {
        $state.go('tab.life.tabLifeView');
    }

    $scope.openMenu = function() {
        $ionicSideMenuDelegate.toggleLeft();
    }


    $scope.takePhoto = function() {
        var options = {
            quality: 100,
            targetWidth: 640,
            targetHeight: 640,
            allowEdit: true,
            destinationType: Camera.DestinationType.DATA_URL,
            sourceType: Camera.PictureSourceType.CAMERA,
            encodingType: Camera.EncodingType.JPEG,
            popoverOptions: CameraPopoverOptions,
            saveToPhotoAlbum: true
        };

        $cordovaCamera.getPicture(options).then(function(imageData) {
            $scope.imgURI = "data:image/jpeg;base64," + imageData;
        }, function(err) {
            // An error occured. Show a message to the user
            console.log(err);
        });
    }


    $scope.logout = function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=logout').success(function(response) {
            $ionicLoading.hide();
            $ionicHistory.clearCache();
            $ionicHistory.clearHistory();
            $ionicHistory.nextViewOptions({
                disableBack: true,
                historyRoot: true
            });
            $state.go('login');
        });
    }
    ionic.Platform.ready(function() {
        var accountName = AccountFactory.getAccountName();
        $scope.Icon = AccountFactory.getIcon();
        $scope.accountName = "<h2>" + accountName + "</h2>";
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getOwnSecret').then(function(response) {
            if (response.data.length > 0) {
                $scope.gossipList = response.data;
            } else {
                $scope.gossipList = {};
            }
        })
    });


    $scope.CreateNewStatus = function() {
        var accountName = AccountFactory.getAccountName();
        var ID = AccountFactory.getAccountID();
        var newStatus = $scope.newStatus;
        var action = "";
        var checker = false;
        var errorMessage = ""
        if ($scope.newStatus == undefined) {
            checker = true;
            errorMessage = "Please Select The Status!";
        }
        if ($scope.data.statusType == undefined) {
            checker = true;
            errorMessage += "Please Write The Status!";
        }

        if (checker == false) {
            if ($scope.data.statusType == 'Personal') {
                action = "create_status";
            } else {
                action = "create_gossip";
                ID = $scope.data.statusType;
            }
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/actionCreateNewStatus.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    ID: ID,
                    newStatus: newStatus,
                    action: action,
                    img: $scope.imgURI
                }
            }
            $http(post).then(function(response) {
                if (action == "create_status") {
                    $state.go('tab.life.tabLifeView')
                } else {
                    $state.go('tab.life.gossip')
                }
            })
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Error!',
                template: errorMessage
            });
        }
    }
})

.controller('SearchFriendCtrl', function($scope, $state, AccountFactory, $http, HomeFactory) {
    $scope.data = {};
    $scope.backToTabLife = function() {
        $state.go('tab.life.tabLifeView')
    }

    $scope.searchFriend = function() {
        var keyword = $scope.data.friendkeyword;
        if (keyword != '') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=search_friend&keyword=' + keyword).then(function(response) {
                $scope.friendList = response.data;
            })
        } else {
            $scope.friendList = {};
        }
    }
    $scope.searchGossip = function() {
        var keyword = $scope.data.gossipkeyword;
        if (keyword != '') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=search_gossip&keyword=' + keyword).then(function(response) {
                $scope.gossipList = response.data;
            })
        } else {
            $scope.gossipList = {};
        }
    }

    $scope.addGossip = function(gossip) {
        var gossipID = gossip.GossipID;
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_gossip&gossipID=' + gossipID).then(function(response) {
            gossip.GossipChecker = 'Y'
        })
    }

    $scope.removeGossip = function(gossip) {
        var gossipID = gossip.GossipID;
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=remove_gossip&gossipID=' + gossipID).then(function(response) {
            gossip.GossipChecker = 'N'
        })
    }

    $scope.addFriend = function(friend) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_friend&friendAccountID=' + friend.AccountID + "&friendAccountName=" + friend.AccountName).then(function(response) {
            friend.FriendChecker = 'Y'
        })
    }

    $scope.removeFriend = function(friend) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=remove_friend&friendAccountID=' + friend.AccountID + "&friendAccountName=" + friend.AccountName).then(function(response) {
            friend.FriendChecker = 'N'
        })
    }

    $scope.addFactory = function(friend) {
        HomeFactory.pass(friend, 'searchFriend');
        $state.go('home')
    }
})


.controller('StatusInfoCtrl', function($scope, $state, statusFactory, $http) {
    $scope.backToTabLife = function() {
        $state.go('tab.life.tabLifeView')
    }
    ionic.Platform.ready(function() {
        var statusID = statusFactory.getStatusID();
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getStatusLike&statusID=' + statusID).then(function(response) {
            if (response.data.length > 0) {
                $scope.likeList = response.data;
            } else {
                $scope.likeList = {};
            }
        })
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getStatusDisLike&statusID=' + statusID).then(function(response) {
            if (response.data.length > 0) {
                $scope.disLikeList = response.data;
            } else {
                $scope.disLikeList = {};
            }
        })
    });
})

.controller('LoveCtrl', function($scope, $state, statusFactory, $http, AccountFactory, $filter, $ionicPopup) {
    $scope.backToTabLife = function() {
        $state.go('tab.life.tabLifeView')
    }
    $scope.data = {};
    $scope.currentDate = {}
    ionic.Platform.ready(function() {
        var schoolID = AccountFactory.getSchoolID();
        $http.get('http://unicomhk.net/fyp/www/php/getCreateNewUserMasterData.php?datatype=study&SchoolID=' + schoolID).then(function(response) {
            $scope.programs = response.data;
        })
        $scope.currentDate = new Date();
        $scope.currentDate.setDate($scope.currentDate.getDate() + 1)
    })

    $scope.datepickerObject = {

        titleLabel: 'Calendar - Select Date', //Optional
        inputDate: $scope.currentDate, //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'popup', //Optional can be popup
        showTodayButton: 'true', //Optional
        modalHeaderColor: 'bar-assertive', //Optional
        modalFooterColor: 'bar-assertive', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val === 'undefined') {

            } else {
                this.inputDate = val;
                resultDate = $filter('date')(val, "yyyy-MM-dd")
            };
        },
        dateFormat: 'YYYY-MM-DD', //Optional
    };
    $scope.timetable = {
        from: new Date(moment("08:30 am", "hh:mm a")),
        to: new Date(moment("10:30 am", "hh:mm a")),
    };


    $scope.CreateNewDating = function() {
        var checker = false;
        var schoolID = AccountFactory.getSchoolID();
        var message = $scope.Message;
        var message = escape(message).replace(/%20/g, " ");
        var location = $scope.data.Location;
        var location = escape(location).replace(/%20/g, " ");
        date = $filter('date')($scope.datepickerObject.inputDate, "yyyy-MM-dd");
        var fromTime = $filter('date')($scope.timetable.from, 'shortTime', "+0800");
        var toTime = $filter('date')($scope.timetable.to, 'shortTime', "+0800");
        if (message == undefined || location == undefined || $scope.data.program == undefined || date < new Date()) {
            checker = true;
        }
        if (checker == false) {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=addDating&Date=' + date + '&schoolID=' + schoolID + '&ProgramID=' + $scope.data.program + '&to=' + toTime + "&from=" + fromTime + "&message=" + message + "&Location=" + location).then(function(response) {
                $state.go('tab.life.tabLifeView')
            })
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Error!',
                template: 'Miss Information'
            });
        }
    }
})

.controller('EditStatusCtrl', function($scope, $state, statusFactory, $ionicPopup, $http, AccountFactory, $ionicHistory) {
    $scope.backToTabLife = function() {
        url = statusFactory.getUrl();
        $state.go(url)
    }

    ionic.Platform.ready(function() {
        var accountName = AccountFactory.getAccountName();
        $scope.accountName = "<h2>" + accountName + "</h2>";
        $scope.Icon = AccountFactory.getIcon();
        $scope.Status = statusFactory.getStatus();
    })

    $scope.UpdateNewStatus = function() {
        var StatusID = statusFactory.getStatusID();
        var Status = $scope.Status;
        var Status = Status.replace(/%20/g, " ");
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=updateStatus&StatusID=' + StatusID + '&Status=' + Status).then(function(response) {
            $state.go('tab.life.tabLifeView')
        })
    }

    $scope.RemoveStatus = function() {
        var StatusID = statusFactory.getStatusID();
        var confirmPopup = $ionicPopup.confirm({
            title: 'Delete Status?',
            template: 'Would You Want to Remove This Status?'
        });
        confirmPopup.then(function(res) {
            if (res) {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=removeStatus&StatusID=' + StatusID).then(function(response) {
                    $state.go('tab.life.tabLifeView')
                })
            }
        });
    }
})

.controller('AccountListCtrl', function($scope, $state, statusFactory, $http, AccountFactory, $ionicPopup) {
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionFunction.php?action=mentor').then(function(response) {
            $scope.accountList = response.data;
        })
    });
    $scope.backToLife = function() {
        $state.go('tab.life.tabLifeView');
    }

    $scope.addMentor = function(account) {
        var confirmPopup = $ionicPopup.confirm({
            title: 'Mentorship system',
            template: 'Do you confirm to join our mentorship system?'
        });

        confirmPopup.then(function(res) {
            if (res) {
                $http.get('http://unicomhk.net/fyp/www/php/actionFunction.php?action=addmentor&mentor=' + account.id).then(function(response) {
                    $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_friend&friendAccountID=' + account.id + "&friendAccountName=" + account.name).then(function(response) {
                        var alertPopup = $ionicPopup.alert({
                            title: 'Success',
                            template: 'Success to join Mentorship'
                        });
                        alertPopup.then(function(res) {
                            $state.go('tab.life.tabLifeView');
                        });
                    });
                });
            } else {}
        });

    }
})
