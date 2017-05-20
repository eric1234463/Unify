angular.module('timetable.controllers', [])

.controller('TimetableCtrl', function($scope, $state, $http, $filter, $ionicPopup, detailedEventService, eventDateStorageService, editEventService) {

    //var below is used for weekDaysList option
    var resultDate = $filter('date')(eventDateStorageService.getDate(), "yyyy-MM-dd");
    ionic.Platform.ready(function() {
        $http.get('http://unicomhk.net/fyp/www/php/actionTimetable.php?inputDate=' + resultDate + '&action=getData')
            .then(function(response) {
                if (response.data.length > 0) {
                    console.log("Data Received");
                    $scope.recData = response.data;
                } else {
                    $scope.recData = {};
                }
            });
    });

    $scope.datepickerObject = {
        titleLabel: 'Calendar - Select Date', //Optional
        inputDate: eventDateStorageService.getDate(), //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'modal', //Optional can be popup
        showTodayButton: 'true', //Optional
        modalHeaderColor: 'bar-assertive', //Optional
        modalFooterColor: 'bar-assertive', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val === 'undefined') {

            } else {
                this.inputDate = val;
                resultDate = $filter('date')(val, "yyyy-MM-dd");

                $http.get('http://unicomhk.net/fyp/www/php/actionTimetable.php?inputDate=' + resultDate + '&action=getData')
                    .then(function(response) {
                        if (response.data.length > 0) {
                            console.log("Data Received");
                            $scope.recData = response.data;
                        } else {
                            $scope.recData = {};
                        }
                    });
            };
        },
        dateFormat: 'YYYY-MM-DD', //Optional
    };

    $scope.deleteEventTypeList = [
    {
        text: "Delete all occurences",
        value: "deleteAll"
    },
    {
        text: "Delete this only event entry",
        value: "deleteSingle"
    }];

    $scope.deleteEventType = {type: "deleteAll"};

    $scope.deleteEvent = function(EventID, EventRepeatID, event) {

        var choices = $ionicPopup.show({
            title: 'Delete',
            template: '<ion-radio ng-repeat="type in deleteEventTypeList" ng-model="deleteEventType.type" ng-value="type.value">{{ type.text }}</ion-radio>',
            scope: $scope,
            buttons: [
            {
                text: 'Cancel'
            },
            {
                text: 'Delete',
                type: 'button-assertive',
                onTap: function() {
                    console.log(EventID, EventRepeatID, $scope.deleteEventType.type);
                    $http.get('http://unicomhk.net/fyp/www/php/actionTimetable.php?action=deleteEvent&eventID=' + EventID + '&deleteEventType=' + $scope.deleteEventType.type + '&deleteEventRepeatID=' + EventRepeatID);
                    var index = $scope.recData.indexOf(event);
                    $scope.recData.splice(index, 1);  
                    return $scope.deleteEventType.type;
                }
            }]
        })
        choices.then(function(result) {
            console.log($scope.deleteEventType.type, result);
        })
    }

    $scope.editEvent = function(event) {
        editEventService.data(event);
        //$scope.addEvent = editEventService.get();
        $scope.addEvent.isEditEventCtrl = 1;
        $state.go('editEvent');
    }

    $scope.showDetailedEvent = function(event, date) {
        detailedEventService.pass(event);
        eventDateStorageService.date(new Date(date));
        $state.go('showDetailedEvent');
    };

    $scope.addEvent = function(date) {
        eventDateStorageService.date(new Date(date));
        $state.go('addEvent');
    };

})

.controller('ShowDetailsCtrl', function($scope, $state, detailedEventService, $rootScope, $ionicPopup, $http, editEventService) {
    ionic.Platform.ready(function() {
        $scope.data = detailedEventService.all();
    })

    $rootScope.$on('$stateChangeSuccess', function(event, next) {
        $scope.data = detailedEventService.all();
    })

    $scope.backToPrevPage = function() {
        $state.go('tab.timetable');
    };

    $scope.deleteEventTypeList = [
    {
        text: "Delete all occurences",
        value: "deleteAll"
    },
    {
        text: "Delete this only event entry",
        value: "deleteSingle"
    }];

    $scope.repeatListChoices = [{
        text: "None",
        value: "none"
    }, {
        text: "Everyday",
        value: "daily"
    }, {
        text: "Every Week",
        value: "weekly"
    }, {
        text: "Every 2 Weeks",
        value: "biweekly"
    }, {
        text: "Every Month",
        value: "monthly"
    }, {
        text: "Every Year",
        value: "yearly"
    }];

    $scope.data.repeatTypeText = function(val) {
        for (var i = 0; i < $scope.repeatListChoices.length; i++) {
            if ($scope.repeatListChoices[i].value == val) {
                return $scope.repeatListChoices[i].text;
            }
        }
    }

    $scope.deleteEventType = {type: "deleteAll"};

    $scope.deleteEvent = function(EventID, EventRepeatID, event) {
        var choices = $ionicPopup.show({
            title: 'Delete',
            template: '<ion-radio ng-repeat="type in deleteEventTypeList" ng-model="deleteEventType.type" ng-value="type.value"></ion-radio>',
            scope: $scope,
            buttons: [
            {
                text: 'Cancel'
            },
            {
                text: 'Delete',
                type: 'button-assertive',
                onTap: function() {
                    console.log(EventID, EventRepeatID, $scope.deleteEventType.type);
                    $http.get('http://unicomhk.net/fyp/www/php/actionTimetable.php?action=deleteEvent&eventID=' + EventID + '&deleteEventType=' + $scope.deleteEventType.type + '&deleteEventRepeatID=' + EventRepeatID);
                    $state.go('tab.timetable');
                    return $scope.deleteEventType.type;
                }
            }]
        })
        choices.then(function(result) {
            console.log($scope.deleteEventType.type, result);
        })
    }

    $scope.editEvent = function() {
        editEventService.data($scope.data);
        //$scope.addEvent = editEventService.get();
        $state.go('editEvent');
    }
})

.controller('AddEventCtrl', function($scope, $state, $http, $ionicPopup, $filter, eventDateStorageService, editEventService, $sanitize) {

     if ($scope.isEditEventCtrl == 1) {
        $scope.addEvent = editEventService.get();

        ionic.Platform.ready(function() {
            $scope.addEvent = editEventService.get();
        });

        $rootScope.$on('$stateChangeSuccess', function(event, next) {
            $scope.addEvent = editEventService.get();
        });

    }

    

    $scope.timetable = {
        from: $filter('date')(moment("10:30 am"), 'shortTime', "+0800"),
        to: $filter('date')(moment("10:30 am"), 'shortTime', "+0800"),
        // to: new Date(moment("10:30 am", "hh:mm a")),
    };

    $scope.$on('$destroy', function() {
        console.log('destroy');
    });

    $scope.addEvent = {
        fromTime: new Date(moment("08:30 am", "hh:mm a")),
        toTime: new Date(moment("10:30 am", "hh:mm a")),
        repeatType: "none",
        fromDate: $filter('date')(new Date(), "yyyy-MM-dd"),
        toDate: $filter('date')(new Date(), "yyyy-MM-dd"),
        isPersonal: 0,
        isAllDay: 0
    };

    $scope.fromDatePickerObject = {
        titleLabel: 'Calendar - Select Start Date', //Optional
        inputDate: new Date(), //Optional
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
                $scope.toDatePickerObject.inputDate = val;
                $scope.addEvent.fromDate = $filter('date')(val, "yyyy-MM-dd");
            };
        },
        dateFormat: 'YYYY-MM-DD', //Optional
    };

    $scope.toDatePickerObject = {
        titleLabel: 'Calendar - Select the End Date', //Optional
        inputDate: $scope.fromDatePickerObject.inputDate, //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'popup', //Optional can be popup
        showTodayButton: 'true', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val === 'undefined') {

            } else {
                if ($scope.fromDatePickerObject.inputDate > val) {
                    var alertPopup = $ionicPopup.alert({
                        title: 'Review inputs again',
                        template: "The date must be in the future of the beginning date"
                    });
                    this.inputDate = $scope.fromDatePickerObject.inputDate;
                } else {
                    this.inputDate = val;
                    $scope.addEvent.toDate = $filter('date')(val, "yyyy-MM-dd");    
                }
                
            };
        },
    };

    $scope.repeatUntilDatePickerObject = {
        titleLabel: 'Repeat Until', //Optional
        inputDate: $scope.toDatePickerObject.inputDate, //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'popup', //Optional can be popup
        showTodayButton: 'false', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val === 'undefined') {

            } else {
                this.inputDate = val;
                $scope.addEvent.repeatUntil = $filter('date')(val, "yyyy-MM-dd");
            };
        }
    };

    $scope.repeatListChoices = [{
        text: "None",
        value: "none"
    }, {
        text: "Everyday",
        value: "daily"
    }, {
        text: "Every Week",
        value: "weekly"
    }, {
        text: "Every 2 Weeks",
        value: "biweekly"
    }, {
        text: "Every Month",
        value: "monthly"
    }, {
        text: "Every Year",
        value: "yearly"
    }];

    $scope.takeText = function(value) {
        for (var i = 0; i < $scope.repeatListChoices.length; i++) {
            if ($scope.repeatListChoices[i].value == value) {
                return $scope.repeatListChoices[i].text;
            }
        }
    };

    $scope.repeatPopup = function() {
        var choices = $ionicPopup.show({
            title: 'Repeat',
            template: '<ion-radio ng-repeat="type in repeatListChoices" ng-model="addEvent.repeatType" ng-value="type.value">{{ type.text }}</ion-radio>',
            scope: $scope,
            buttons: [{
                text: 'Save',
                type: 'button-assertive',
                onTap: function() {
                    return $scope.addEvent.repeatType;
                }
            }]
        });
        choices.then(function(result) {
            console.log($scope.addEvent.repeatType);
        })
    }

    $scope.backToPrevPage = function() {
        $state.go('tab.timetable');
    };


    var checkToRemoveTime = function() {
        if ($scope.addEvent.isAllDay == 1 || $scope.addEvent.isAllDay == true) {
            $scope.addEvent.fromTime = null;
            $scope.addEvent.toTime = null;
            return 1;
        }
        return 0;
    };

    var checkFromDateWithRepeatDate = function() {
        // compare if not sameday AND repeated, show error
        if (!(angular.equals($scope.addEvent.fromDate, $scope.addEvent.toDate)) && (!(angular.equals($scope.addEvent.repeatType, 'none')))) {
            console.log("error: cannot remove because repeat date is set while end date is different from beginning date"); 
            return false;
        } else {
            // check if either not same day or same day but not repeated
            if (!(angular.equals($scope.addEvent.toDate, $scope.addEvent.fromDate)) || 
                (angular.equals($scope.addEvent.toDate, $scope.addEvent.fromDate) && (angular.equals($scope.addEvent.repeatType, 'none'))))
            {
                $scope.addEvent.repeatUntil = $scope.addEvent.toDate;
            }
            return true;
        };
    };

    var toTimeCheck = function() {
        if ($filter('date')($scope.addEvent.fromTime, "shortTime", "+0800") > $filter('date')($scope.addEvent.toTime, "shortTime", "+0800")) {
            if ($scope.addEvent.toDate > $scope.addEvent.fromDate) {
                return true;
            } else {
                $scope.addEvent.toTime = $scope.addEvent.fromTime;
                var alertPopup = $ionicPopup.alert({
                    title: 'Review time again',
                    template: "End time should be ahead of beginning time."
                });
                return false;
            }
        } else {
            return true;
        }
    }

    $scope.endAddEventCheck = function(val) {
        if (val)
 //       if (val && toTimeCheck())
            endAddEvent();
        else{
            var alertPopup = $ionicPopup.alert({
                title: 'Review inputs again',
                template: "Please revise the required fields."
            });
        }
    }

    var endAddEvent = function() {
        console.log($scope.addEvent.fromDate);
        console.log($scope.addEvent.repeatUntil);
        checkToRemoveTime();
        var checker = checkFromDateWithRepeatDate();
        if (checker) {
            // var post = {};
            // if($scope.addEvent.isEditEventCtrl == 1) {
            //     post.url = 'http://unicomhk.net/fyp/www/php/actionAddEvent.php';
            // }

            var post = {
                url: 'http://unicomhk.net/fyp/www/php/actionAddEvent.php',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    eventTitle: $sanitize($scope.addEvent.eventTitle),
                    eventDescription: $sanitize($scope.addEvent.eventDescription),
                    eventLocation: $sanitize($scope.addEvent.eventLocation),
                    fromTime: $filter('date')($scope.addEvent.fromTime,'shortTime','+0800'),
                    toTime: $filter('date')($scope.addEvent.toTime,'shortTime','+0800'),
                    fromDate: $scope.addEvent.fromDate,
                    toDate: $scope.addEvent.toDate,
                    repeatUntil: $scope.addEvent.repeatUntil,
                    personal: $scope.addEvent.isPersonal,
                    repeatType: $scope.addEvent.repeatType,
                    allDay: $scope.addEvent.isAllDay
                }
            }

            $http(post).then(function(response) {
                $state.go('tab.timetable');
            });
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Date Mismatch!',
                template: "Setting a different End date while repeating the event is not possible. Either set the end date to be the same as beginning date or choose repeat to 'None' !"
            });
        }
    }
})

.controller('EditEventCtrl', function($scope, $state, $http, $ionicPopup, $sanitize, $filter, $rootScope, eventDateStorageService, editEventService) {

    ionic.Platform.ready(function() {
        $scope.addEvent = editEventService.get();
    })

    $rootScope.$on('$stateChangeSuccess', function(event, next) {
        $scope.addEvent = editEventService.get();
    })


    $scope.backToPrevPage = function() {
        $state.go('tab.timetable');
    };


    $scope.$on('$destroy', function() {
        console.log('destroy');
    });


    $scope.fromDatePickerObject = {
        titleLabel: 'Calendar - Edit Start Date', //Optional
        inputDate: $filter('date')($scope.addEvent.fromDate, "yyyy-MM-dd"), //Optional
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
                $scope.addEvent.fromDate = $filter('date')(val, "yyyy-MM-dd");
            };
        },
        dateFormat: 'YYYY-MM-DD', //Optional
    };

    $scope.toDatePickerObject = {
        titleLabel: 'Calendar - Edit End Date', //Optional
        inputDate: $filter('date')($scope.addEvent.toDate, "yyyy-MM-dd"), //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'popup', //Optional can be popup
        showTodayButton: 'true', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val === 'undefined') {

            } else {
                if ($scope.fromDatePickerObject.inputDate > val) {
                    var alertPopup = $ionicPopup.alert({
                        title: 'Review inputs again',
                        template: "The date must be in the future of the beginning date"
                    });
                    this.inputDate = $scope.fromDatePickerObject.inputDate;
                } else {
                    this.inputDate = val;
                    $scope.addEvent.toDate = $filter('date')(val, "yyyy-MM-dd");    
                }
            };
        },
    };

    $scope.repeatUntilDatePickerObject = {
        titleLabel: 'Repeat Until', //Optional
        inputDate: $filter('date')($scope.addEvent.toDate, "yyyy-MM-dd"), //Optional
        mondayFirst: false, //Optional
        weekDaysList: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], //Optional
        templateType: 'popup', //Optional can be popup
        showTodayButton: 'false', //Optional
        callback: function(val) { //Mandatory
            if (val === undefined || val === 'undefined') {

            } else {
                this.inputDate = val;
                $scope.addEvent.repeatUntil = $filter('date')(val, "yyyy-MM-dd");
            };
        }
    };

    $scope.repeatListChoices = [{
        text: "None",
        value: "none"
    }, {
        text: "Everyday",
        value: "daily"
    }, {
        text: "Every Week",
        value: "weekly"
    }, {
        text: "Every 2 Weeks",
        value: "biweekly"
    }, {
        text: "Every Month",
        value: "monthly"
    }, {
        text: "Every Year",
        value: "yearly"
    }];

    $scope.takeText = function(value) {
        for (var i = 0; i < $scope.repeatListChoices.length; i++) {
            if ($scope.repeatListChoices[i].value == value) {
                return $scope.repeatListChoices[i].text;
            }
        }
    };

    $scope.repeatPopup = function() {
        var choices = $ionicPopup.show({
            title: 'Repeat',
            template: '<ion-radio ng-repeat="type in repeatListChoices" ng-model="addEvent.repeatType" ng-value="type.value">{{ type.text }}</ion-radio>',
            scope: $scope,
            buttons: [{
                text: 'Save',
                type: 'button-assertive',
                onTap: function() {
                    return $scope.addEvent.repeatType;
                }
            }]
        });
        choices.then(function(result) {
            console.log($scope.addEvent.repeatType);
        })
    }


    var checkToRemoveTime = function() {
        if ($scope.addEvent.isAllDay == 1 || $scope.addEvent.isAllDay == true) {
            $scope.addEvent.fromTime = null;
            $scope.addEvent.toTime = null;
            return 1;
        }
        return 0;
    };

    var checkFromDateWithRepeatDate = function() {
        // compare if not sameday AND repeated, show error
        if (!(angular.equals($scope.addEvent.fromDate, $scope.addEvent.toDate)) && (!(angular.equals($scope.addEvent.repeatType, 'none')))) {
            console.log("error: cannot remove because repeat date is set while end date is different from beginning date"); 
            return false;
        } else {
            // check if either not same day or same day but not repeated
            if (!(angular.equals($scope.addEvent.toDate, $scope.addEvent.fromDate)) || 
                (angular.equals($scope.addEvent.toDate, $scope.addEvent.fromDate) && (angular.equals($scope.addEvent.repeatType, 'none'))))
            {
                $scope.addEvent.repeatUntil = $scope.addEvent.toDate;
            }
            return true;
        };
    };

    var toTimeCheck = function() {
        if ($filter('date')($scope.addEvent.fromTime, "shortTime", "+0800") > $filter('date')($scope.addEvent.toTime, "shortTime", "+0800")) {
            if ($scope.addEvent.toDate > $scope.addEvent.fromDate) {
                return true;
            } else {
                $scope.addEvent.toTime = $scope.addEvent.fromTime;
                var alertPopup = $ionicPopup.alert({
                    title: 'Review time again',
                    template: "Time should be ahead of beginning time."
                });
                return false;
            }
        } else {
            return true;
        }
    }

     $scope.editEventTypeList = [
    {
        text: "Edit all occurences",
        value: "editAll"
    },
    {
        text: "Edit this only event entry",
        value: "editSingle"
    }];

    $scope.editEventType = {type: "editSingle"};

    // $scope.chooseEventEditType = function() {
    //     var choices = $ionicPopup.show({
    //         title: 'Edit',
    //         template: '<ion-radio ng-repeat="type in editEventTypeList" ng-model="editEventType.type" ng-value="type.value"></ion-radio>',
    //         scope: $scope,
    //         buttons: [
    //         {
    //             text: 'Cancel'
    //         },
    //         {
    //             text: 'Edit',
    //             type: 'button-assertive',
    //             onTap: function() {
    //                 return $scope.editEventType.type;
    //             }
    //         }]
    //     })
    // }


    $scope.endAddEventCheck = function(val) {
        if (val && toTimeCheck()) {
            //endAddEvent($scope.chooseEventEditType());
            endAddEvent();
        }
        else{
            var alertPopup = $ionicPopup.alert({
                title: 'Review inputs again',
                template: "Please revise the required fields."
            });
        }
    }

    var endAddEvent = function() {
        console.log($scope.addEvent.fromDate);
        console.log($scope.addEvent.repeatUntil);
        checkToRemoveTime();
        var checker = checkFromDateWithRepeatDate();
        if (checker) {

            var post = {
                url: 'http://unicomhk.net/fyp/www/php/actionEditEvent.php',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    eventID: $scope.addEvent.eventID,
                    eventRepeatID: $scope.addEvent.eventRepeatID,
//                    editType: editType,
                    eventTitle: $sanitize($scope.addEvent.eventTitle),
                    eventDescription: $sanitize($scope.addEvent.eventDescription),
                    eventLocation: $sanitize($scope.addEvent.eventLocation),
                    fromTime: $filter('date')($scope.addEvent.fromTime,'shortTime','+0800'),
                    toTime: $filter('date')($scope.addEvent.toTime,'shortTime','+0800'),
                    fromDate: $scope.addEvent.fromDate,
                    toDate: $scope.addEvent.toDate,
                    repeatUntil: $scope.addEvent.repeatUntil,
                    personal: $scope.addEvent.isPersonal,
                    repeatType: $scope.addEvent.repeatType,
                    allDay: $scope.addEvent.isAllDay
                }
            }

            $http(post).then(function(response) {
                $state.go('tab.timetable');
            });
        } else {
            var alertPopup = $ionicPopup.alert({
                title: 'Date Mismatch!',
                template: "Setting a different End date while repeating the event is not possible. Either set the end date to be the same as beginning date or choose repeat to 'None' !"
            });
        }
    }

    /*  All the code below are from past tests that might have worked and buggy. It's kept to be a 
        basis and backup incase new code underneath does not work. */

    // $scope.endAddEvent = function() {
    //     console.log($scope.addEvent.fromDate);
    //     console.log($scope.addEvent.repeatUntil);
    //     checkToRemoveTime();
    //     var checker = checkFromDateWithRepeatDate();
    //     if (checker) {
    //         // var post = {};
    //         // if($scope.addEvent.isEditEventCtrl == 1) {
    //         //     post.url = 'http://unicomhk.net/fyp/www/php/actionAddEvent.php';
    //         // }

    //         var post = {
    //             url: 'http://unicomhk.net/fyp/www/php/actionAddEvent.php',
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //             },
    //             data: {
    //                 eventTitle: $scope.addEvent.eventTitle,
    //                 eventDescription: $scope.addEvent.eventDescription,
    //                 eventLocation: $scope.addEvent.eventLocation,
    //                 fromTime: $filter('date')($scope.addEvent.fromTime,'shortTime','+0800'),
    //                 toTime: $filter('date')($scope.addEvent.toTime,'shortTime','+0800'),
    //                 fromDate: $scope.addEvent.fromDate,
    //                 toDate: $scope.addEvent.toDate,
    //                 repeatUntil: $scope.addEvent.repeatUntil,
    //                 personal: $scope.addEvent.isPersonal,
    //                 repeatType: $scope.addEvent.repeatType,
    //                 allDay: $scope.addEvent.isAllDay
    //             }
    //         }

    //         $http(post).then(function(response) {
    //             $state.go('tab.timetable');
    //         });
    //     } else {
    //         var alertPopup = $ionicPopup.alert({
    //             title: 'Date Mismatch!',
    //             template: "Setting a different End date while repeating the event is not possible. Either set the end date to be the same as beginning date or choose repeat to 'None' !"
    //         });
    //     }
    //}


}); // closing bracket. Do not comment or delete.
