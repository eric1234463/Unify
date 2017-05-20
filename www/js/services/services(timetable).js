angular.module('timetable.services', [])

	.service('detailedEventService', function( $http, $state ) {

		var details = {};

		return {
			all: function () {
				return details;
			},
			pass: function (data) {
				details = data;
			}
		};
	})

	.service('editEventService', function() {
		var editEvent = {};

		return {
			data: function(data) {
				editEvent = data;
			},
			get: function(){
				return editEvent;
			}
		}
	})

	.service('eventDateStorageService', function() {
		var chosenDate = new Date();

		return {
			date: function (date) {
				chosenDate = date;
			},
			getDate: function() {
				return chosenDate;
			}
		}
	})
	;