angular.module('chat.controllers', [])


.controller('ChatsCtrl', function($scope, Chats, $ionicPopup, $ionicListDelegate, $http, ChatFactory, $state, $rootScope, $interval, $timeout) {
    var chatCheckTimer;

    ionic.Platform.ready(function() {
        var totalcount = 0;
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getData').then(function(response) {
            if (response.data.length > 0) {
                $scope.chatList = response.data;
                for (var i = 0; i < $scope.chatList.length; i++) {
                    totalcount += parseInt($scope.chatList[i].Unread);
                }
                $scope.data = {
                    badgeCount: totalcount
                };
            } else {
                $scope.chatList = {};
            }
        })
        $scope.$on('$destroy', function() {
            console.log('destroy');
        });
        chatCheckTimer = $interval(function() {

            var totalcount = 0;
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getData').then(function(response) {
                if ($scope.chatList.length == response.data.length) {
                    for (var i = 0; i < response.data.length; i++) {
                        if ($scope.chatList[i].Time !== response.data[i].Time) {
                            $scope.chatList[i] = response.data[i];
                        }
                    }
                } else {
                    $scope.chatList = response.data;
                }
                for (var i = 0; i < $scope.chatList.length; i++) {
                    totalcount += parseInt($scope.chatList[i].Unread);
                }
                $scope.data = {
                    badgeCount: totalcount
                };
            })

        }, 20000);
    });

    $scope.remove = function(chat) {
        var confirmPopup = $ionicPopup.confirm({
            title: 'Delete Chat',
            template: 'Are you sure you want to delete this chat?'
        })
        confirmPopup.then(function(res) {
            if (res) {
                $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=deleteChat&ChatID=' + chat.ChatID).then(function(response) {
                    var index = $scope.chatList.indexOf(chat);
                    $scope.chatList.splice(index, 1);
                    var alertPopup = $ionicPopup.alert({
                        title: 'Delete Success',
                        template: 'You are already Delete This Chat!'
                    });
                })
            } else {
                $ionicListDelegate.closeOptionButtons();
            }
        });
    }
    $scope.match = function(chat) {
        var confirmPopup = $ionicPopup.confirm({
            title: 'Match Up',
            template: 'You want to match with this people?'
        });

        confirmPopup.then(function(res) {
            if (res) {
                $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=match&ChatAccountID=' + chat.AccountID).then(function(response) {
                    if (response.data[0].relationship == 'success') {
                        var alertPopup = $ionicPopup.alert({
                            title: 'Match Success',
                            template: 'You and ' + chat.AccountName + ' are match !! <br> Hope you can have a good relationship <3'
                        });
                    }
                })
            } else {
                $ionicListDelegate.closeOptionButtons();
            }
        });
    }

    $scope.doRefresh = function() {
        var totalcount = 0;
        $timeout(function() {
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getData').then(function(response) {
                if ($scope.chatList.length == response.data.length) {
                    for (var i = 0; i < response.data.length; i++) {
                        if ($scope.chatList[i].Time !== response.data[i].Time) {
                            $scope.chatList[i] = response.data[i];
                        }
                    }
                } else {
                    $scope.chatList = response.data;
                }
                for (var i = 0; i < $scope.chatList.length; i++) {
                    totalcount += parseInt($scope.chatList[i].Unread);
                }
                $scope.data = {
                    badgeCount: totalcount
                };
            })

            // Stop the ion-refresher from spinning
            $scope.$broadcast('scroll.refreshComplete');
        }, 1000);

    };


    $scope.cancel = function() {
        $interval.cancel(chatCheckTimer);
    }

    $scope.$on('$destroy', function() {
        $scope.cancel();
    });

    $scope.gotochat = function(chat) {
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=passChatID&chatID=' + chat.ChatID + '&receiverID=' + chat.AccountID + '&receiverName=' + chat.AccountName).then(function(response) {});

        $state.go('chat-detail');
        ChatFactory.getSenderName(chat);
    };

    $scope.gotoContactList = function() {
        $state.go('contactList');
    };

    $scope.createGroupChat = function() {
        $state.go('createGroup');
    }

})

.controller('ChatDetailCtrl', function($scope, $stateParams, Chats, $state, ChatFactory, $http, $timeout, $ionicScrollDelegate, $interval, $rootScope, HomeFactory) {
    var messageCheckTimer;
    ionic.Platform.ready(function() {
        var SenderName = ChatFactory.passSenderName();
        var account = ChatFactory.passAll();
        var isGroupChat = ChatFactory.passIsGroupChat();
        $scope.emojiMessage = {};
        $scope.SenderName = SenderName;
        $scope.account = account;
        $scope.isGroupChat = isGroupChat;
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getMessage').then(function(response) {
            $scope.messageList = response.data;
            $timeout(function() {
                $ionicScrollDelegate.$getByHandle('myScroll').scrollBottom(false);
            }, 0)
        })
        messageCheckTimer = $interval(function() {
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getMessage').then(function(response) {
                if ($scope.messageList.length !== response.data.length) {
                    $scope.messageList = response.data;
                    $timeout(function() {
                        $ionicScrollDelegate.$getByHandle('myScroll').scrollBottom(false);
                    }, 0)
                }
            })
        }, 20000);
    });

    $scope.backToTabChat = function() {

        $state.go('tab.chats');

    }

    $scope.stop = function() {
        $interval.cancel(messageCheckTimer);
    }

    $scope.$on('$destroy', function() {
        $scope.stop();
    });


    $scope.send = function() {
        var message = document.getElementById('messageInput').value;
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=sendMessage&Message=' + message).then(function(response) {
            $scope.emojiMessage = null;
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getMessage').then(function(response) {
                $scope.messageList = response.data;
                $timeout(function() {
                    $ionicScrollDelegate.$getByHandle('myScroll').scrollBottom(false);
                }, 0)
            })
            document.getElementById('messageInput').value = "";
            $scope.isDisabled = false;
        })
    }

    $scope.$watch('messageArea', function(newVal, oldVal) {
        if ($.trim(newVal) == "") {
            $scope.isDisabled = false;
        } else {
            $scope.isDisabled = true;
        }
    });


    $scope.goToHome = function(friend) {
        HomeFactory.pass(friend, 'chat-detail');
        $state.go('home')
    }

})

.controller('ContactListCtrl', function($scope, $state, $http, ChatFactory) {
    $scope.data = {};
    $scope.backToTabChat = function() {

        $state.go('tab.chats');

    }

    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getFriendList')
            .then(function(response) {
                if (response.data !== null) {
                    $scope.contactList = response.data;
                } else {
                    $scope.contactList = {};
                }
            });
    });

    $scope.search = function() {
        var keyword = $scope.data.keyword;
        if (keyword !== '') {
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=searchFriend&keyword=' + keyword).then(function(response) {
                if (response.data !== null) {
                    $scope.contactList = response.data;
                } else {
                    $scope.contactList = {};
                }
            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getFriendList')
                .then(function(response) {
                    if (response.data !== null) {
                        $scope.contactList = response.data;
                    } else {
                        $scope.contactList = {};
                    }
                });
        }
    }

    $scope.addChat = function(contact) {
        var newChat = [{}];
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=findChat&receiverID=' + contact.AccountID + '&receiverName=' + contact.AccountName).then(function(response) {
            if (response.data.length === 0) {
                $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=addChat&receiverID=' + contact.AccountID + '&receiverName=' + contact.AccountName)
                    .then(function(response) {
                        newChat = response.data;
                    });
                $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=passChatID&chatID=' + newChat.ChatID + '&receiverID=' + newChat.ReceiverID + '&receiverName=' + newChat.FriendAccountName).then(function(response) {
                    $state.go('chat-detail');
                    ChatFactory.getSenderName(contact);
                });
            } else {
                $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=passChatID&chatID=' + response.data[0].ChatID + '&receiverID=' + response.data[0].ReceiverID + '&receiverName=' + response.data[0].FriendAccountName).then(function(response) {
                    $state.go('chat-detail');
                    ChatFactory.getSenderName(contact);

                });

            }
        });

    }

})

.controller('CreateGroupCtrl', function($scope, $state, $http, ChatFactory, $filter, $cordovaCamera) {
    $scope.data = {};
    $scope.backToTabChat = function() {
        $state.go('tab.chats');
    }

    ionic.Platform.ready(function() {
        $scope.Icon = "";
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getFriendList')
            .then(function(response) {
                if (response.data !== null) {
                    $scope.contactList = response.data;
                } else {
                    $scope.contactList = {};
                }
            });

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
            $scope.Icon = "data:image/jpeg;base64," + imageData;
        }, function(err) {
            // An error occured. Show a message to the user
            console.log(err);
        });
    }

    $scope.search = function() {
        var keyword = "";
        if ($scope.data.keyword == undefined) {
            keyword = "";
        } else {
            keyword = $scope.data.keyword;
        }

    }

    $scope.$watch('newGroup', function(newVal, oldVal) {
        if (newVal == false) {
            $scope.isDisabled = false;
        } else {
            angular.forEach(trues, function(value, key) {
                $scope.isDisabled = value.Checked && $scope.newGroup;
            })
        }
    });

    var trues = [];


    $scope.$watch("contactList", function(n, o) {

        trues = $filter("filter")(n, {
            Checked: true
        });
        if (trues && trues.length > 1) {
            angular.forEach(trues, function(value, key) {
                $scope.isDisabled = value.Checked && $scope.newGroup;
            })
        } else if ($scope.newGroup == null) {
            $scope.isDisabled = false;
        } else {
            $scope.isDisabled = false;
        }
    }, true);

    $scope.Checked = function(contact) {
        if (contact.Checked == 'true') {
            contact.Checked = 'false';
        } else {
            contact.Checked = 'true';
        }

    }


    $scope.addGroupChat = function() {
        var groupName = $scope.newGroup;
        $scope.newGroup = null;
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getChatIDAndGroupChatID').then(function(response) {
            $scope.groupChat = response.data;
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/actionCreateGroupChat.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    action: 'createGroupChat',
                    chatID: $scope.groupChat[0].ChatID,
                    receiverID: $scope.groupChat[0].GroupID,
                    receiverName: groupName,
                    senderID: $scope.contactList[0].OwnerAccountID,
                    icon: $scope.Icon
                }
            }
            $http(post).then(function(response) {});
            for (var i = 0; i < $scope.contactList.length; i++) {
                if ($scope.contactList[i].Checked == 'true') {
                    var post = {
                        method: 'POST',
                        url: 'http://unicomhk.net/fyp/www/php/actionCreateGroupChat.php',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        data: {
                            action: 'createGroupChat',
                            chatID: $scope.groupChat[0].ChatID,
                            receiverID: $scope.groupChat[0].GroupID,
                            receiverName: groupName,
                            senderID: $scope.contactList[i].AccountID,
                            icon: $scope.Icon
                        }
                    }
                    $http(post).then(function(response) {});

                }
            }

            //$http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=addGroupChat&chatID=' + $scope.groupChat[0].ChatID + '&receiverID=' + $scope.groupChat[0].GroupID + '&receiverName=' + groupName + '&senderID=' + $scope.contactList[0].OwnerAccountID).then(function(response) {});
            /*for (var i = 0; i < $scope.contactList.length; i++) {
                if ($scope.contactList[i].Checked == 'true') {
                    $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=addGroupChat&chatID=' + $scope.groupChat[0].ChatID + '&receiverID=' + $scope.groupChat[0].GroupID + '&receiverName=' + groupName + '&senderID=' + $scope.contactList[i].AccountID).then(function(response) {});
                }
            }*/
            var group = {
                AccountName: groupName,
                IsGroupChat: 'Y'
            };
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=passChatID&chatID=' + $scope.groupChat[0].ChatID + '&receiverID=' + $scope.groupChat[0].GroupID + '&receiverName=' + groupName).then(function(response) {
                $state.go('chat-detail');
                ChatFactory.getSenderName(group);

            });
        });

    }

})

.controller('tabCount', function($scope, Chats, $ionicPopup, $ionicListDelegate, $http, ChatFactory, $state, $rootScope, $interval, $timeout) {
    var chatCheckTimer;

    ionic.Platform.ready(function() {
        var totalcount = 0;
        var chatData = [{}];
        $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getData').then(function(response) {
            if (response.data.length > 0) {
                chatData = response.data;
                for (var i = 0; i < response.data.length; i++) {
                    totalcount += parseInt(response.data[i].Unread);
                }
                $scope.data = {
                    badgeCount: totalcount
                };
            }
        })

        chatCheckTimer = $interval(function() {

            var totalcount = 0;
            $http.get('http://unicomhk.net/fyp/www/php/actionChat.php?action=getData').then(function(response) {
                if (chatData.length == response.data.length) {
                    for (var i = 0; i < response.data.length; i++) {
                        if (chatData[i].Time !== response.data[i].Time) {
                            chatData[i] = response.data[i];
                        }
                    }
                } else {
                    chatData = response.data;
                }
                for (var i = 0; i < chatData.length; i++) {
                    totalcount += parseInt(chatData[i].Unread);
                }
                $scope.data = {
                    badgeCount: totalcount
                };
            })

        }, 20000);
    });

    $scope.cancel = function() {
        $interval.cancel(chatCheckTimer);
    }

    $scope.$on('$destroy', function() {
        $scope.cancel();
    });
})
