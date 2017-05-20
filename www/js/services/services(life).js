angular.module('life.services', [])

    .factory('statusFactory', function() {

        var status = {};
        var url ="";
        return {
            all: function() {
                return status;
            },
            getStatusID: function() {
                return status.StatusID;
            },
            getStatus: function() {
                return status.Status;
            },
            getUrl :function (){
                return url;
            }, 
            pass: function(var1,var2) {
                status = var1;
                url = var2;
            }
        }

    })

.factory('gossipFactory', function() {

    var gossip = {};

    return {
        all: function() {
            return gossip;
        },
        getSecretMessageID: function() {
            return gossip.SecretMessageID;
        },
        pass: function(var1) {
            gossip = var1;
        }
    }

})

.factory('HomeFactory', function($q) {

    var Account = {};
    var url ="";
    return {
        all: function() {
            return Account;
        },
        getAccountID: function() {
            return Account.AccountID;
        },
        getAccountName: function() {
            return Account.AccountName;
        },
        getUrl: function() {
            return url;
        },
        pass: function(var1,var2) {
            Account = var1;
            url = var2;
        }
    }
})

.factory('ProfileFactory', function($q) {
    var Profile = {};
    return {
        all: function() {
            return Profile;
        },
        pass: function(var1) {
            Profile = var1;
        }
    }
})


.factory('NoteFactory', function($q) {
    var Note = {};
    return {
        all: function() {
            return Note;
        },
        pass: function(var1) {
            Note = var1;
        }
    }
})


.factory('JobFactory', function($q) {
    var Job = {};
    return {
        all: function() {
            return Job;
        },
        pass: function(var1) {
            Job = var1;
        }
    }
})


