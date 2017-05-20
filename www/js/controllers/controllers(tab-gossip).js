angular.module('gossip.controllers', []).controller('TabLifeGossipCtrl', function($scope, AccountFactory, $ionicSideMenuDelegate, $http, $stateParams, $state, gossipFactory, $timeout, $rootScope, $ionicPopup, $ionicLoading, $ionicHistory) {
    ionic.Platform.ready(function() {


        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getserect').then(function(response) {
            if (response.data.length > 0) {
                $scope.secretList = response.data;
            } else {
                $scope.secretList = "";
            }
        })
    })

    $scope.goToLove = function() {
        $state.go('love');
    }

    $scope.searchFriend = function() {
        $state.go('searchFriend');
    };

    $scope.goToStatus = function() {
        $state.go('createNewStatus');
    }
    $scope.openMenu = function() {
        $ionicSideMenuDelegate.toggleLeft();
    }


    $scope.logout = function() {
        $ionicLoading.show({
            template: 'Logging out....'
        });
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=logout').success(function(response) {
            $timeout(function() {
                $ionicLoading.hide();
                $ionicHistory.clearCache();
                $ionicHistory.clearHistory();
                $ionicHistory.nextViewOptions({
                    disableBack: true,
                    historyRoot: true
                });
                $state.go('login');
            }, 30);
        });
    }

    $scope.$on('scroll.refreshComplete', function() {

        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=getserect').then(function(response) {
            if (response.data.length > 0) {
                $scope.secretList = response.data;
            } else {
                $scope.secretList = "";
            }
        })
    })


    /*          Function For the Tab Life  Secret             */

    $scope.AddSecretlike = function(secret) {
        if (secret.YouLike == 'Y') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=deleteSecret_like&secretID=' + secret.SecretID + '&secretMessageID=' + secret.SecretMessageID).then(function(response) {
                if (parseInt(secret.Liked) > 1) {
                    secret.Liked = parseInt(secret.Liked) - 1
                } else {
                    secret.Liked = "0";
                }
                secret.YouLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=addSecret_like&secretID=' + secret.SecretID + '&secretMessageID=' + secret.SecretMessageID).then(function(response) {
                secret.Liked = parseInt(secret.Liked) + 1
                secret.YouLike = 'Y';
            })

            if (secret.YouDisLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=deleteSecret_dislike&secretID=' + secret.SecretID + '&secretMessageID=' + secret.SecretMessageID).then(function(response) {
                    if (parseInt(secret.DisLiked) > 1) {
                        secret.DisLiked = parseInt(secret.DisLiked) - 1;
                    } else {
                        secret.DisLiked = "0";
                    }
                    secret.YouDisLike = 'N'
                })
            }
        }
    };

    $scope.doRefresh = function() {
        $scope.$broadcast('scroll.refreshComplete');
    }

    $scope.AddSecretDisLike = function(secret) {
        if (secret.YouDisLike == 'Y') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=deleteSecret_dislike&secretID=' + secret.SecretID + '&secretMessageID=' + secret.SecretMessageID).then(function(response) {
                if (parseInt(secret.DisLiked) > 1) {
                    secret.DisLiked = parseInt(secret.DisLiked) - 1
                } else {
                    secret.DisLiked = "0";
                }
                secret.YouDisLike = 'N';

            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=addSecret_dislike&secretID=' + secret.SecretID + '&secretMessageID=' + secret.SecretMessageID).then(function(response) {
                secret.DisLiked = parseInt(secret.DisLiked) + 1
                secret.YouDisLike = 'Y';
            })

            if (secret.YouLike == 'Y') {
                $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=deleteSecret_like&secretID=' + secret.SecretID + '&secretMessageID=' + secret.SecretMessageID).then(function(response) {
                    if (parseInt(secret.Liked) > 1) {
                        secret.Liked = parseInt(secret.Liked) - 1;
                    } else {
                        secret.Liked = "0";
                    }
                    secret.YouLike = 'N'
                })
            }
        }
    }

    $scope.SecretComment = function(secret) {
        $scope.secret = secret;
        var myPopup = $ionicPopup.show({
            title: 'Comment',
            scope: $scope,
            templateUrl: 'templates/gossipComment.php',
            buttons: [
                { text: 'Cancel' }, {
                    text: '<b>Add Comment</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        var newComment = document.getElementById(secret.SecretMessageID + "_newcomment").value;
                        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_secretcomment&secretMessageID=' + secret.SecretMessageID + '&newComment=' + newComment + '&secretID=' + secret.SecretID).then(function(response) {
                            document.getElementById(secret.SecretMessageID + "_newcomment").value = '';
                            secret.Comment = parseInt(secret.Comment) + 1;
                            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_secretcomment&secretMessageID=' + secret.SecretMessageID + '&secretID=' + secret.SecretID).then(function(response) {
                                if (response.data.length > 0) {
                                    secret.CommentList = response.data;
                                } else {
                                    secret.CommentList = {};
                                }
                            })
                        });
                    }
                }
            ]
        });
    };

    $scope.showSecretFullComment = function(secret) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_secretfullcomment&secretMessageID=' + secret.SecretMessageID + '&secretID=' + secret.SecretID).then(function(response) {
            secret.ShowMore = 'Y';
            if (response.data.length > 0) {
                secret.CommentList = response.data;
            } else {
                secret.CommentList = {};
            }
        });
    }

    $scope.showSecretLessComment = function(secret) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_secretcomment&secretMessageID=' + secret.SecretMessageID + '&secretID=' + secret.SecretID).then(function(response) {
            secret.ShowMore = 'N';
            if (response.data.length > 0) {
                secret.CommentList = response.data;
            } else {
                secret.CommentList = {};
            }
        })
    }

    $scope.addSecretComment = function(secret) {
        var newComment = document.getElementById(secret.SecretMessageID + "_newcomment").value;
        $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=add_secretcomment&secretMessageID=' + secret.SecretMessageID + '&newComment=' + newComment + '&secretID=' + secret.SecretID).then(function(response) {
            document.getElementById(secret.SecretMessageID + "_newcomment").value = '';
            secret.Comment = parseInt(secret.Comment) + 1;
            $http.get('http://unicomhk.net/fyp/www/php/actionTabLifeStatus.php?action=get_secretcomment&secretMessageID=' + secret.SecretMessageID + '&secretID=' + secret.SecretID).then(function(response) {
                if (response.data.length > 0) {
                    secret.CommentList = response.data;
                } else {
                    secret.CommentList = {};
                }
            })
        });
    }


})
