angular.module('timetable.services', [])

	.service('detailedEventService', function( $http, $state ) {

		var details = {};

		function passData(data) {
			details = data;
		};

		function getEventTitle() {
			return details.eventTitle;
		};

		function getDescription() {
			return details.eventDescription;
		};

		function getLocation() {
			return details.eventLocation;
		};

		function getFromTime () {
			return details.fromTime;
		};

		function getToTime () {
			return details.toTime;
		};

		return {
			getEventTitle: getEventTitle,
			geteventDescription: getDescription,
			geteventLocation: getLocation,
			geteventFromTime: getFromTime,
			geteventToTime: getToTime
		};
	});