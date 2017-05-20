angular.module('more.controllers', [])

.controller('MoreCtrl', function($scope, $cookies, $timeout, $ionicSideMenuDelegate, $state, $http, HomeFactory, AccountFactory, $ionicLoading, $ionicHistory, $rootScope) {
    $scope.goToNote = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('notes');
    }

    $scope.goToSeondHand = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('secondHand');
    }

    $scope.goToJob = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('job');
    }
    $scope.goToHome = function() {
        $ionicSideMenuDelegate.toggleLeft();
        HomeFactory.pass(AccountFactory.getAll(), 'tab.life.tabLifeView')
        $state.go('home');
    };

    $scope.goToFriendList = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('friendList');
    }
    $scope.goToGossip = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('gossip');
    }
    $scope.gotoStoryDiary = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('storyDiary');
    }
    $scope.goToCreateGossip = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('createGossip');
    }

    $scope.goToSetting = function() {
        $ionicSideMenuDelegate.toggleLeft();
        $state.go('setting');
    }

    $scope.logout = function() {
        $cookies.remove('AccountID');
        $cookies.remove('AccountName');
        $cookies.remove('SchoolID');
        $cookies.remove('Icon');
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
        $ionicSideMenuDelegate.toggleLeft();
    }
})

.controller('NotesCtrl', function($scope, $state, $http, $ionicPopup, $cordovaCamera, NoteFactory) {
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=getNote').then(function(response) {
            $scope.noteList = response.data;
        })
    });

    $scope.backToTabMore = function() {
        $state.go('tab.life.tabLifeView');
    };

    $scope.goToNoteDetail = function(note) {
        NoteFactory.pass(note);
        $state.go('notesDetail');
    }
    $scope.goTONewNote = function() {
        $state.go('createNotes');
    }
})

.controller('NotesDetailCtrl', function($scope, $state, $http, $ionicPopup, $cordovaCamera, NoteFactory) {
    ionic.Platform.ready(function() {
        var note = NoteFactory.all();
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=getNoteDetail&NoteID=' + note.NoteID).then(function(response) {
            $scope.DetailList = response.data;
        })
    });
    $scope.share = function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=getFriendList').then(function(response) {
            $scope.friendList = response.data;

            var alertPopup = $ionicPopup.alert({
                title: 'Share to your friend',
                scope: $scope,
                template: '<ul class="list"><a class="item item item-avatar item-button-right SpecialRow" ng-repeat="friend in friendList" id="{{friend.AccountID}}_box"><img ng-src="{{friend.Icon}}" ng-if="friend.Icon!==N" class="Icon" /><span class="ProfileInfo" ng-click="goToHome(friend)">{{friend.AccountName}}</span><br><span class="ProfileInfo">{{friend.SchoolName}} - {{friend.ProgramName}}</span><button class="button ion-plus"  ng-click="shareNote(friend)"></button></a></ul>',
            });

            alertPopup.then(function(res) {});
        })
    }
    $scope.backToNote = function() {
        $state.go('notes');
    };
    $scope.shareNote = function(friend) {
        var note = NoteFactory.all();
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=shareNote&AccountID=' + friend.AccountID + '&NoteID=' + note.NoteID + '&NoteTitle=' + note.NoteTitle + '&NoteType=' + note.NoteType).then(function(response) {
            document.getElementById(friend.AccountID + '_box').style.display = "none";
        })
    }
    $scope.UpdateNote = function() {
        var note = NoteFactory.all();
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=UpdateNote&NoteID=' + note.NoteID + '&NoteDesc=' + $scope.DetailList[0].NoteDesc).then(function(response) {
            $state.go('notes');
        })
    }

})

.controller('CreateNoteCtrl', function($scope, $state, $http, $ionicPopup, $cordovaCamera, NoteFactory) {
    $scope.Note = {};
    ionic.Platform.ready(function() {
        $scope.noteDesc = "";
    })
    $scope.backToNote = function() {
        $state.go('notes');
    };
    $scope.CreateNote = function() {
        var checker = "N";
        if ($scope.Note.noteTitle == undefined) {
            checker = "Y";
        }
        if ($scope.Note.noteType == undefined) {
            checker = "Y";
        }
        if ($scope.noteDesc == undefined) {
            checker = "Y";
        }

        if (checker == "N") {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=CreateNote&NoteTitle=' + $scope.Note.noteTitle + '&NoteDesc=' + $scope.noteDesc + '&NoteType=' + $scope.Note.noteType).then(function(response) {
                $state.go('notes');
            })
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Miss Information',
                template: 'Please fullfill all the information before create'
            });
        }

    }

})

.controller('CreateGossipCtrl', function($scope, $state, $http, $ionicPopup, $cordovaCamera) {
    $scope.backToTabMore = function() {
        $state.go('tab.life.tabLifeView');
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
            $scope.data.Icon = "data:image/jpeg;base64," + imageData;
        }, function(err) {
            console.log(err);
        });
    }

    $scope.Submit = function() {
        var checker = 'N';
        if ($scope.data.desc == undefined) {
            checker = 'Y'
        }
        if ($scope.data.Title == undefined) {
            checker = 'Y'
        }
        if ($scope.data.Icon == 'N') {
            checker = 'Y'
        }
        if (checker == 'Y') {
            var alertPopup = $ionicPopup.alert({
                title: 'Miss Information',
                template: 'Please fullfill all the information before create'
            });
        } else {
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/actionProfile.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    action: 'createGossip',
                    desc: $scope.data.desc,
                    title: $scope.data.Title,
                    icon: $scope.data.Icon
                }
            }
            $http(post).then(function(response) {
                $state.go('tab.life.tabLifeView');
            })

        }
    }
    ionic.Platform.ready(function() {
        $scope.data = {};
        $scope.data.Icon = 'N';
    });
})


.controller('FriendListCtrl', function($scope, $state, $http, HomeFactory) {
    $scope.data = {};
    $scope.backToTabMore = function() {
        $state.go('tab.life.tabLifeView');
    };

    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=getFriendList').then(function(response) {
            if (response.data !== null) {
                $scope.friendList = response.data;
            } else {
                $scope.friendList = {};
            }
        });
    });
    $scope.removefriend = function(friend) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=removeFriend&friendAccountID=' + friend.AccountID + "&friendAccountName=" + friend.AccountName).then(function(response) {
            document.getElementById(friend.AccountID + '_box').style.display = "none";
        })
    };

    $scope.goToHome = function(friend) {
        HomeFactory.pass(friend, 'friendList');
        $state.go('home')
    }

    $scope.search = function() {
        var keyword = $scope.data.keyword;
        if (keyword !== '') {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=searchFriend&keyword=' + keyword).then(function(response) {
                if (response.data !== null) {
                    $scope.friendList = response.data;
                } else {
                    $scope.friendList = {};
                }
            })
        } else {
            $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=getFriendList')
                .then(function(response) {
                    if (response.data !== null) {
                        $scope.friendList = response.data;
                    } else {
                        $scope.friendList = {};
                    }
                });
        }
    }
})


.controller('GossipCtrl', function($scope, $state, $http) {
    $scope.backToTabMore = function() {
        $state.go('tab.life.tabLifeView');
    };
    $scope.removeGossip = function(gossip) {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=deleteGossip&gossipID=' + gossip.GossipID).then(function(response) {
            document.getElementById(gossip.GossipID + '_box').style.display = 'none';
        });
    }
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=getGossip').then(function(response) {
            if (response.data !== null) {
                $scope.gossipList = response.data;
            } else {
                $scope.gossipList = {};
            }
        });
    });

})

.controller('JobCtrl', function($scope, $state, $http, AccountFactory, JobFactory) {
    ionic.Platform.ready(function() {
        $scope.data = {};
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=getJob').then(function(response) {
            if (response.data !== null) {
                $scope.jobList = response.data;
            } else {
                $scope.jobList = {};
            }
        });
    })
    $scope.backToTabMore = function() {
        $state.go('tab.life.tabLifeView');
    };
    $scope.searchJob = function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=searchJob&keyword=' + $scope.data.keyword).then(function(response) {
            if (response.data !== null) {
                $scope.jobList = response.data;
            } else {
                $scope.jobList = {};
            }
        });
    }
    $scope.goToJobDetail = function(job) {
        JobFactory.pass(job);
        $state.go('jobDetail');
    }
})

.controller('JobDetailCtrl', function($scope, $state, $http, AccountFactory, JobFactory) {
    ionic.Platform.ready(function() {
        $scope.job = JobFactory.all();
    })
    $scope.backToJob = function() {
        $state.go('job');
    };
})

.controller('SettingCtrl', function($scope, $state, $http, AccountFactory) {
    $scope.data = {};
    $scope.backToTabLife = function() {
        $state.go('tab.life.tabLifeView');
    };
    ionic.Platform.ready(function() {
        var account = AccountFactory.getAll();
        if (account.Private == 'Y') {
            $scope.data.Private = true;
            $scope.data.checked = true;
        } else {
            $scope.data.Private = false;
            $scope.data.checked = false;
        }
    });

    $scope.submit = function() {
        var account = AccountFactory.getAll();
        if ($scope.data.Private == true) {
            $scope.data.Private = 'Y';
            account.Private = 'Y';
        } else {
            $scope.data.Private = 'N';
            account.Private = 'N';
        }
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=setting&private=' + $scope.data.Private).then(function(response) {
            $state.go('tab.life.tabLifeView');
        });
    }

})

.controller('SecondHandCtrl', function($scope, $state, $http, JobFactory) {
    $scope.backToTabLife = function() {
        $state.go('tab.life.tabLifeView');
    };
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTabMore.php?action=showSecondHandBook').then(function(response) {
            $scope.bookList = response.data;
        });
    })
    $scope.goToBookDetail = function(book) {
        JobFactory.pass(book);
        $state.go('secondHandDetail');
    }
    $scope.goToAddBook = function() {
        $state.go('addsecondHand');
    }
})

.controller('SecondHandDetailCtrl', function($scope, $state, $http, JobFactory) {
    $scope.backToSecondHand = function() {
        $state.go('secondHand');
    };

    ionic.Platform.ready(function() {
        $scope.book = JobFactory.all();
    })

    $scope.interest = function(book) {

    }
})

.controller('AddSecondHandCtrl', function($scope, $state, $http, JobFactory, $cordovaCamera, $ionicPopup) {
    $scope.backToSecondHand = function() {
        $state.go('secondHand');
    };

    ionic.Platform.ready(function() {
        $scope.book = {};
    })

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
            $scope.book.PhotoSrc = "data:image/jpeg;base64," + imageData;
        }, function(err) {
            console.log(err);
        });
    }
    $scope.submit = function() {
        var checker = false;
        if ($scope.book.Desc == undefined) {
            checker = true;
        }
        if ($scope.book.PhotoSrc == undefined) {
            checker = true;
        }
        if ($scope.book.Price == undefined) {
            checker = true;
        }
        if ($scope.book.Name == undefined) {
            checker = true;
        }
        if (checker == true) {
            var alertPopup = $ionicPopup.alert({
                title: 'Miss Information',
                template: 'Please fullfill all the information before create'
            });
        } else {
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/actionProfile.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    action: 'createbook',
                    desc: $scope.book.Desc,
                    name: $scope.book.Name,
                    icon: $scope.book.PhotoSrc,
                    price: $scope.book.Price
                }
            }
            $http(post).then(function(response) {
                $state.go('secondHand');
            })
        }
    }
})
