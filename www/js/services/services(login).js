angular.module('login.services', []).service('LoginService', function($q, $http, $cordovaDevice) {
    return {
        loginUser: function(name, pw) {
            var deferred = $q.defer();
            var promise = deferred.promise;
            var device = "";
            var post = {
                method: 'POST',
                url: 'http://unicomhk.net/fyp/www/php/accountLogin.php',
                headers: {
                    'Content-Type': 'application/json',
                },
                data: {
                    accountName: name,
                    password: pw,
                    //device : $cordovaDevice.getUUID()
                }
            }
            $http(post).then(function(response) {
                if (response.data.match('Pass')) {
                    deferred.resolve('Welcome ' + name + '!');
                } else {
                    deferred.reject('Wrong Information.');
                }
            });
            promise.success = function(fn) {
                promise.then(fn);
                return promise;
            }
            promise.error = function(fn) {
                promise.then(null, fn);
                return promise;
            }
            return promise;
        }
    }
})

.service('AccountFactory', function($q, $http,$cookies,$cookieStore ) {
    var account;
    return {
        getAccountinfo: function() {
            $http.get('http://unicomhk.net/fyp/www/php/accountLogin.php?getAccountinfo=true').then(function(response) {
                account = response.data
                var expireDate = new Date();
                expireDate.setDate(expireDate.getDate() + 90);
                $cookies.put('AccountID', account.AccountID,{'expires': expireDate})
                $cookies.put('AccountName', account.AccountName,{'expires': expireDate})
                $cookies.put('SchoolID', account.SchoolID,{'expires': expireDate})
                $cookies.put('Icon', account.Icon,{'expires': expireDate})
            })
        },

        getAccountID: function() {
            return account.AccountID;
        },

        getAccountName: function() {
            return account.AccountName;
        },
        getSchoolID: function() {
            return account.SchoolID;
        },
        getIcon: function() {
            return account.Icon;
        },
        getAll: function() {
            return account;
        }

    }
})
