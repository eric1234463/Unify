angular.module('notification.services', [])

.factory('NotificationFactory', function($q, $http) {
    var notification1 = [{}];
    return {
        getNotification: function() {
            return notification1;
        },
        passNotification: function(notification) {
            notification1 = notification;
        }
    }
})


.factory('RequestFactory', function() {

    var request = {};

    return {
        all: function() {
            return request;
        },
        pass: function(var1) {
            request = var1;
        }
    }

})
