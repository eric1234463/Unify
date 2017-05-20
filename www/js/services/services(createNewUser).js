angular.module('createNewUser.services', []).service('createNewUserService', function($q, $http, $state) {
    var createNewUser = {};

    function passdata(var1) {
        createNewUser = var1;
    };

    function getschool() {
        return createNewUser.school;
    };

    function getyear() {
        return createNewUser.year;
    };

    function getprogram() {
        return createNewUser.program;
    };

    function getgender() {
        return createNewUser.gender;
    };

    function getemail() {
        var email = createNewUser.email + createNewUser.emaildomain
        return email;
    };
    return {
        passdata: passdata,
        getschool: getschool,
        getyear: getyear,
        getprogram: getprogram,
        getgender: getgender,
        getemail: getemail
    }
})
