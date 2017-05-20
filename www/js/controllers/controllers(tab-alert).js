angular.module('alert.controllers', [])

.controller('AlertCtrl', function($scope, $state, $http, NotificationFactory, HomeFactory, $ionicPopup) {
    $scope.data = {};
    var startLimit = 0;
    var endLimit = 10;
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=getData&startLimit=' + startLimit + '&endLimit=' + endLimit).then(function(response) {
            if (response.data.length > 0) {
                $scope.notificationList = response.data;
            } else {
                $scope.notificationList = {};
            }
        });
    })

    $scope.viewAnswer = function(notification) {
        $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=viewAnswer&QuestionID=' + notification.NotificationInformationID + '&NotificationID=' + notification.NotificationID).then(function(response) {
            $scope.question = response.data;
            var alertPopup = $ionicPopup.alert({
                title: 'View Answer',
                template: '<span class="ProfileInfo" style="font-size:24px">' + $scope.question[0].Question + '</span>' + '<br>' + $scope.question[0].Answer,
            });
        })
    }
    $scope.answerQuestion = function(notification) {
        $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=getQuestion&QuestionID=' + notification.NotificationInformationID).then(function(response) {
            $scope.question = response.data;
            var myPopup = $ionicPopup.show({
                template: '<span class="ProfileInfo" style="font-size:24px">' + $scope.question[0].Question + '<br> <input type="text" ng-model="data.answer">',
                title: 'Answer Question',
                scope: $scope,
                buttons: [
                    { text: 'Cancel' }, {
                        text: '<b>Answer</b>',
                        type: 'button',
                        onTap: function(e) {
                            $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=answerQuestion&QuestionID=' + notification.NotificationInformationID + '&NotificationID=' + notification.NotificationID + '&answer=' + $scope.data.answer + '&FriendaccountID=' + notification.FriendAccountID).then(function(response) {
                                var alertPopup = $ionicPopup.alert({
                                    title: 'Submit Answer',
                                    template: 'You already answer the question'
                                });
                            })
                            return $scope.data.answer;
                        }
                    }
                ]
            });
        })
    }
    $scope.moreStatus = function() {
        startLimit = endLimit;
        endLimit = endLimit + 5;
        $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=getData&startLimit=' + startLimit + '&endLimit=' + endLimit).then(function(response) {
            if (response.data.length > 0) {
                $scope.moreNotificationList = response.data;
            } else {
                $scope.morewotificationList = {};
            }
            for (i = 0; i < $scope.moreNotificationList.length; i++) {
                $scope.notificationList.push($scope.moreNotificationList[i]);
            }
        })
        $scope.$broadcast('scroll.infiniteScrollComplete');
    }

    $scope.checkUnread = function(notification) {
        if (notification.Read == 'N')
            return notification.Read
    }

    $scope.newWindow = function() {
        $state.go('alertNewWindow');
    }

    $scope.passData = function(notification) {
        NotificationFactory.passNotification(notification);

    }

    $scope.checkStatus = function(status) {
        if (status != null) {
            if (status.length > 10) {
                status = status.substring(0, 9) + "...";
            }
        }
        return status;
    }
    $scope.goToHome = function(notification) {
        HomeFactory.pass(notification, 'tab.alert')
        $state.go('home');
    };

})

.controller('AlertNewWindowCtrl', function($scope, $state, $http, NotificationFactory, statusFactory, HomeFactory, $ionicPopup, $ionicPosition, $anchorScroll, $location, $ionicScrollDelegate) {
    ionic.Platform.ready(function() {
        var notification = NotificationFactory.getNotification();
        var ID = "";
        $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=getNotificationOwnerDetail&OID=' + notification.OwnerAccountID + '&SID=' + notification.StatusID + '&NID=' + notification.NotificationInformationID + '&TYPEID=' + notification.NotificationTypeID).then(function(response) {
            $scope.notificationDetail = response.data;
            if ($scope.notificationDetail[0].NotificationTypeID == 'UNI-NT-2016-00006') {
                var myPopup = $ionicPopup.show({
                    title: 'Comment',
                    scope: $scope,
                    template: '<a ng-if= "notificationDetail[0].Comment> 5 && notificationDetail[0].ShowMore == 1" ng-click = "showMoreComment(notificationDetail[0])"> Show More </a> <a ng-if = "notificationDetail[0].Comment > 5 && notificationDetail[0].ShowMore == 0 "ng-click = "showLessComment(notificationDetail[0])" > Show Less </a> <ion-item class = "item-avatar" ng-repeat = "comment in notificationDetail[0].commentList" ><img ng-src = "{{comment.Icon}}" /><h2> {{comment.CommentAccountName}} </h2> <p> {{comment.Comment}} </p></ion-item> <input type = "text" placeholder="Plase feedback in here" id="{{notificationDetail[0].StatusID}}_newcomment" style="width:100%"/>',
                    buttons: [
                        { text: 'Cancel' }, {
                            text: '<b>Add Comment</b>',
                            type: 'button-positive',
                            onTap: function(e) {
                                var newComment = document.getElementById(notification.StatusID + "_newcomment").value;
                                var statusID = notification.StatusID;
                                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_comment&statusID=' + statusID + '&newComment=' + newComment).then(function(response) {
                                    notification.Comment = parseInt(notification.Comment) + 1
                                });
                                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_comment&statusID=' + statusID).then(function(response) {
                                    notification.ShowMore = '1';
                                    if (response.data.length > 0) {
                                        notification.commentList = response.data;
                                    } else {
                                        notification.commentList = {};
                                    }
                                })
                                $scope.notification = notification;
                            }
                        }
                    ]
                });
                $location.hash($scope.notificationDetail[0].SelectCommentID);
            }
        })
    })

    $scope.goToHome = function(notification) {
        HomeFactory.pass(notification, 'alertNewWindow')
        $state.go('home');
    };
    $scope.editStatus = function(status) {
        statusFactory.pass(status, 'alertNewWindow');
        $state.go('editstatus')
    }

    $scope.backToAlert = function() {
        $state.go('tab.alert');
    }

    $scope.showStatusInfo = function(notifciation) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getStatusLike&statusID=' + notifciation.StatusID).then(function(response) {
            if (response.data.length > 0) {
                $scope.likeList = response.data;
            } else {
                $scope.likeList = {};
            }
        })
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getStatusDisLike&statusID=' + notifciation.StatusID).then(function(response) {
            if (response.data.length > 0) {
                $scope.disLikeList = response.data;
            } else {
                $scope.likeList = {};
            }
        })

        var myPopup = $ionicPopup.alert({
            templateUrl: 'templates/tab-life(statusInfo).php',
            title: 'Like',
            scope: $scope,
        })

    }

    $scope.Addlike = function(status) {
        if (status.YouLike == 'Y') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                if (parseInt(status.Likes) > 1) {
                    status.Likes = parseInt(status.Likes) - 1
                } else {
                    status.Likes = "0";
                }
                status.YouLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                status.Likes = parseInt(status.Likes) + 1
                status.YouLike = 'Y';
            })

            if (status.YouDisLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                    if (parseInt(status.DisLikes) > 1) {
                        status.DisLikes = parseInt(status.DisLikes) - 1;
                    } else {
                        status.DisLikes = "0";
                    }
                    status.YouDisLike = 'N'
                })
            }
        }
    };

    $scope.AddDisLike = function(status) {
        if (status.YouDisLike == 'Y') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                if (parseInt(status.Likes) > 1) {
                    status.DisLikes = parseInt(status.DisLikes) - 1
                } else {
                    status.DisLikes = "0";
                }
                status.YouDisLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_dislike&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                status.DisLikes = parseInt(status.DisLikes) + 1
                status.YouDisLike = 'Y';
            })

            if (status.YouLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=delete_like&statusID=' + status.StatusID + '&statusAccountID=' + status.AccountID + "&statusAccountName=" + status.AccountName).then(function(response) {
                    if (parseInt(status.Likes) > 1) {
                        status.Likes = parseInt(status.Likes) - 1;
                    } else {
                        status.Likes = "0";
                    }
                    status.YouLike = 'N'
                })
            }
        }
    };


    $scope.AddComment = function(status) {
        var newComment = document.getElementById(status.StatusID + "_newcomment").value;
        var statusID = status.StatusID;
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_comment&statusID=' + statusID + '&newComment=' + newComment).then(function(response) {
            document.getElementById(status.StatusID + "_newcomment").value = '';
            status.Comments = parseInt(status.Comments) + 1
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

    $scope.Comment = function(status) {
        var myPopup = $ionicPopup.show({
            title: 'Comment',
            scope: $scope,
            template: '<a ng-if= "notificationDetail[0].Comment> 5 && notificationDetail[0].ShowMore == 1" ng-click = "showMoreComment(notificationDetail[0])"> Show More </a> <a ng-if = "notificationDetail[0].Comment > 5 && notificationDetail[0].ShowMore == 0 "ng-click = "showLessComment(notificationDetail[0])" > Show Less </a> <ion-item class = "item-avatar" ng-repeat = "comment in notificationDetail[0].commentList" ><img ng-src = "{{comment.Icon}}" /><h2> {{comment.CommentAccountName}} </h2> <p> {{comment.Comment}} </p></ion-item> <input type = "text" placeholder="Plase feedback in here" id="{{notificationDetail[0].StatusID}}_newcomment" style="width:100%"/>',
            buttons: [
                { text: 'Cancel' }, {
                    text: '<b>Add Comment</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var newComment = document.getElementById(notificationDetail[0].StatusID + "_newcomment").value;
                        if (newComment !== '') {
                            var statusID = notificationDetail[0].StatusID;
                            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_comment&statusID=' + statusID + '&newComment=' + newComment).then(function(response) {
                                notification.Comment = parseInt(notification.Comment) + 1
                            });
                            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_comment&statusID=' + statusID).then(function(response) {
                                notification.ShowMore = '1';
                                if (response.data.length > 0) {
                                    notificationDetail[0].commentList = response.data;
                                } else {
                                    notificationDetail[0].commentList = {};
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




.controller('Unread', function($scope, $http) {
    ionic.Platform.ready(function() {
        var Unread = 0;
        $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=unread').then(function(response) {
            if (response.data.length > 0) {
                $scope.unread = response.data;
                if ($scope.unread == '0') {
                    $scope.unread = '';
                }
            } else {
                $scope.unread = '';
            }

        })
    })
})




.controller('RequestCtrl', function($scope, $state, $http, RequestFactory, statusFactory, HomeFactory, $ionicPopup, $ionicPosition, $anchorScroll, $location, $ionicScrollDelegate) {
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=getRequest').then(function(response) {
            if (response.data.length > 0) {
                $scope.requestList = response.data;
            } else {
                $scope.requestList = {};
            }
        });
    })

    $scope.goToDetail = function(request) {
        RequestFactory.pass(request);
        $state.go('alertRequestDetail');
    }
})

.controller('RequestDetailCtrl', function($scope, $state, $http, RequestFactory, statusFactory, HomeFactory, $ionicPopup, $ionicPosition, $anchorScroll, $location, $ionicScrollDelegate) {
    ionic.Platform.ready(function() {
        $scope.Detail = RequestFactory.all();
    })

    $scope.backToAlert = function() {
        $state.go('tab.alert')
    }

    $scope.Accept = function(Detail) {
        var confirmPopup = $ionicPopup.confirm({
            title: 'Accept Dating',
            template: 'Are you sure you want to dating with ' + Detail.RequestAccountName + ' ? '
        });

        confirmPopup.then(function(res) {
            if (res) {
                $http.get('http://unicomhk.net/fyp/www/php/actionNotification.php?action=finishRequest&RequestAccountID=' + Detail.RequestAccountID + '&OwnerAccountID=' + Detail.OwnerAccountID + '&RequestID=' + Detail.RequestID + '&RequestAccountName=' + Detail.RequestAccountName).then(function(response) {
                    $state.go('tab.alert');
                })
            }
        });
    }
})
