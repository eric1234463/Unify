// Ionic Starter App
// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.php)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js

angular.module('starter', ['ionic', 'starter.controllers', 'ngCordova', 'ngCookies', 'ngSanitize', 'emojiApp', 'angularMoment', 'Photo.controllers', 'home.controllers', 'gossip.controllers', 'more.controllers', 'life.controllers', 'timetable.controllers', 'chat.controllers', 'alert.controllers', 'starter.services', 'life.services', 'notification.services', 'login.services', 'createNewUser.services', 'timetable.services', 'ionic-datepicker', 'ion-datetime-picker'])

.run(function($ionicPlatform) {
    $ionicPlatform.ready(function() {
        // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
        // for form inputs)
        if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
            cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
        }
        if (window.StatusBar) {
            // org.apache.cordova.statusbar required
            StatusBar.styleLightContent();
        }
    });
})

.config(function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $compileProvider, $httpProvider) {
    $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|local|data|ftp|file|blob):|data:image\//);
    $ionicConfigProvider.tabs.position('bottom');
    $ionicConfigProvider.views.maxCache(3);
    $ionicConfigProvider.navBar.alignTitle('center');

    $stateProvider
        .state('login', {
            url: '/login',
            templateUrl: 'templates/login.php',
            cache: false,
            controller: 'LoginCtrl'
        })


    .state('createNewUser', {
        url: '/createNewUser',
        templateUrl: 'templates/createNewUser.php',
        cache: false,
        controller: 'CreateNewUserCtrl'
    })

    .state('secondHandDetail', {
        url: '/secondHandDetail',
        templateUrl: 'templates/secondHandBookTradingDetail.php',
        cache: false,
        controller: 'SecondHandDetailCtrl'
    })

    .state('secondHand', {
        url: '/secondHand',
        templateUrl: 'templates/secondHandBookTrading.php',
        cache: false,
        controller: 'SecondHandCtrl'
    })

     .state('addsecondHand', {
        url: '/addsecondHand',
        templateUrl: 'templates/addSecondHandBook.php',
        cache: false,
        controller: 'AddSecondHandCtrl'
    })

    .state('job', {
        url: '/job',
        templateUrl: 'templates/job.php',
        cache: false,
        controller: 'JobCtrl'
    })

    .state('jobDetail', {
        url: '/jobDetail',
        templateUrl: 'templates/jobDetail.php',
        cache: false,
        controller: 'JobDetailCtrl'
    })

    .state('notes', {
        url: '/notes',
        templateUrl: 'templates/notes.php',
        cache: false,
        controller: 'NotesCtrl'
    })

    .state('notesDetail', {
        url: '/notesDetail',
        templateUrl: 'templates/notesDetail.php',
        cache: false,
        controller: 'NotesDetailCtrl'
    })

    .state('createNotes', {
        url: '/createNotes',
        templateUrl: 'templates/createNotes.php',
        cache: false,
        controller: 'CreateNoteCtrl'
    })

    .state('newUserphoneinfo', {
        url: '/newUserPhoneInfo',
        templateUrl: 'templates/newUserPhoneInfo.php',
        cache: false,
        controller: 'NewUserphoneinfoCtrl'
    })

    .state('createGossip', {
        url: '/createGossip',
        templateUrl: 'templates/createGossip.php',
        cache: false,
        controller: 'CreateGossipCtrl'
    })

    .state('home', {
        url: '/home',
        templateUrl: 'templates/home.php',
        cache: false,
        controller: 'HomeCtrl'
    })

    .state('editProfile', {
        url: '/editProfile',
        templateUrl: 'templates/editProfile.php',
        cache: false,
        controller: 'editProfileCtrl'
    })

    .state('setting', {
        url: '/setting',
        templateUrl: 'templates/setting.php',
        cache: false,
        controller: 'SettingCtrl'
    })

    .state('friendList', {
        url: '/friendList',
        templateUrl: 'templates/friendList.php',
        cache: false,
        controller: 'FriendListCtrl'
    })

    .state('accountList', {
        url: '/accountList',
        templateUrl: 'templates/accountList.php',
        cache: false,
        controller: 'AccountListCtrl'
    })

    .state('contactList', {
        url: '/contactList',
        templateUrl: 'templates/contactList.php',
        cache: false,
        controller: 'ContactListCtrl'
    })

    .state('createGroup', {
        url: '/createGroup',
        templateUrl: 'templates/createGroup.php',
        cache: false,
        controller: 'CreateGroupCtrl'
    })


    .state('forum', {
        url: '/forum',
        templateUrl: 'templates/forum.php',
        cache: false,
        controller: 'ForumCtrl'
    })





    .state('addEvent', {
        url: '/addEvent',
        cache: false,
        templateUrl: 'templates/addEvent.php',
        controller: 'AddEventCtrl'
    })

    .state('showDetailedEvent', {
        url: '/showDetailedEvent',
        cache: false,
        templateUrl: 'templates/showDetailedEvent.php',
        controller: 'ShowDetailsCtrl'
    })

    .state('editEvent', {
        url: '/editEvent',
        cache: false,
        templateUrl: 'templates/editEvent.php',
        controller: 'EditEventCtrl'
    })

    .state('gossip', {
        url: '/gossip',
        cache: false,
        templateUrl: 'templates/gossip.php',
        controller: 'GossipCtrl'
    })

    .state('likeRank', {
            url: '/likeRank',
            templateUrl: 'templates/likeRank.php',
            controller: 'LikeRankCtrl'
        })
        .state('dislikeRank', {
            url: '/dislikeRank',
            templateUrl: 'templates/dislikeRank.php',
            controller: 'DisLikeRankCtrl'
        })

    // setup an abstract state for the tabs directive
    .state('tab', {
        url: "/tab",
        abstract: true,
        cache: false,
        templateUrl: "templates/tabs.php"
    })


    // Each tab has its own nav history stack:

    .state('tab.chats', {
        url: '/chats',
        cache: false,
        views: {
            'tab-chats': {
                templateUrl: 'templates/tab-chats.php',
                controller: 'ChatsCtrl',
                cache: false,
            }
        }
    })

    .state('chat-detail', {
        url: '/chats/:chatId',
        cache: false,
        templateUrl: 'templates/chat-detail.php',
        controller: 'ChatDetailCtrl'
    })


    .state('tab.alert', {
        url: '/alert',
        views: {
            'tab-alert': {
                templateUrl: 'templates/tab-alert.php',
                abstract: true,
            }
        }
    })

    .state('alertNewWindow', {
        url: '/alert/:alertId',
        cache: false,
        templateUrl: 'templates/alertNewWindow.php',
        controller: 'AlertNewWindowCtrl'

    })

    .state('tab.alert.alertNotification', {
        url: '/alertNotification',
        cache: false,
        views: {
            'tab-alert-alertNotification': {
                templateUrl: 'templates/tab-alertNotification.php',
                controller: 'AlertCtrl',
            }
        }
    })

    .state('tab.alert.alertRequest', {
        cache: false,
        url: '/alertRequest',
        views: {
            'tab-alert-alertRequest': {
                templateUrl: 'templates/tab-alertRequest.php',
                controller: 'RequestCtrl',

            }
        }
    })

    .state('alertRequestDetail', {
        url: '/alert/alertRequestDetail',
        cache: false,
        templateUrl: 'templates/alertRequestDetail.php',
        controller: 'RequestDetailCtrl'

    })

    .state('tab.timetable', {
        url: '/timetable',
        views: {
            'tab-timetable': {
                templateUrl: 'templates/tab-timetable.php',
                controller: 'TimetableCtrl',
                cache: false
            }
        }
    })

    .state('tab.createNewStatus', {
        cache: false,
        url: '/createNewStatus',
        views: {
            'tab-createNewStatus': {
                templateUrl: 'templates/createNewStatus.php',
                controller: 'CreateNewStatusCtrl',
                cache: false
            }
        }
    })

    // .state('tab.more', {
    //     url: '/more',
    //     views: {
    //         'tab-more': {
    //             templateUrl: 'templates/tab-more.php',
    //             controller: 'MoreCtrl'
    //         }
    //     }
    // })

    // for tab lifE //

    .state('tab.life', {
        url: '/life',
        cache: true,
        views: {
            'tab-life': {
                templateUrl: 'templates/tab-life.php',
                abstract: true,
            }
        }
    })



    .state('tab.life.tabLifeView', {
        url: '/tabLifeView',
        cache: false,
        views: {
            'tab-life-tabLifeView': {
                templateUrl: 'templates/tab-life-view.php',
                controller: 'LifeViewCtrl',
            }
        }
    })

    .state('tab.life.photoDiary', {
        cache: false,
        url: '/tabLifePhotoDiary',
        views: {
            'tab-life-PhotoDiary': {
                templateUrl: 'templates/createNewPhotoDiary.php',
                controller: 'LifePhotoDiaryCtrl',
            }
        }
    })

    .state('createNewStatus', {
        cache: false,
        url: '/createNewStatus',
        templateUrl: 'templates/createNewStatus.php',
        controller: 'CreateNewStatusCtrl'
    })

    .state('tab.life.gossip', {
        cache: false,
        url: '/gossip',
        views: {
            'tab-life-gossip': {
                templateUrl: 'templates/tab-life-gossip.php',
                controller: 'TabLifeGossipCtrl'
            }
        }
    })

    .state('searchFriend', {
        url: '/searchFriend',
        cache: false,
        templateUrl: 'templates/searchFriend.php',
        controller: 'SearchFriendCtrl'
    })

    .state('love', {
        url: '/love',
        templateUrl: 'templates/speedating.php',
        cache: false,
        controller: 'LoveCtrl'
    })

    .state('statusinfo', {
        url: '/statusInfo',
        templateUrl: 'templates/tab-life(statusInfo).php',
        cache: false,
        controller: 'StatusInfoCtrl'
    })

    .state('editstatus', {
        url: '/editstatus',
        templateUrl: 'templates/editStatus.php',
        cache: false,
        controller: 'EditStatusCtrl'
    })

    // if none of the above states are matched, use this as the fallback
    $urlRouterProvider.otherwise('/login');
})
