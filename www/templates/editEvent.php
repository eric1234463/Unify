<style>
.border-right {
    border-right: 1px solid #e3e3e3;
}

.item {
    border-width: 1px;
}

.popup {
    max-height: 85%;
}

.popup-body {
    max-height: 80%;
}

.err {
    color: red;
}

</style>
<ion-view title="Edit Event"  ng-form="eventForm">
    <!-- <button class="button icon ion-android-close" ng-click="backToPrevPage()" id="iconbutton"></button> -->
    <!-- <h1 class="title">Create Event</h1> -->
    <ion-nav-buttons side="left">
        <button class="button icon button-icon ion-android-close" ng-click="backToPrevPage()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button button-icon icon ion-android-done" ng-click="endAddEventCheck(eventForm.$valid)" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content class="background">
        <div class="card padding-top">
            <label class="item item-input item-stacked-label">
                <span class="input-label" ng-class="{err: eventForm.title.$error.required}">Title</span> 
                <!-- Only show when empty -->
                <span ng-show="eventForm.title.$error.required" class="err form-control"> *Required </span>
                <input type="text" name="title" placeholder="i.e Course Title" ng-model="addEvent.eventTitle" required>
            </label>
            <label class="item item-input item-stacked-label">
                <span class="input-label">Description</span>
                <input type="text" ng-model="addEvent.eventDescription" ng-value="addEvent.eventDescription">
            </label>
            <label class="item item-input item-stacked-label">
                <span class="input-label">Location</span>
                <input type="text" ng-model="addEvent.eventLocation" ng-value="addEvent.eventLocation">
            </label>
        </div>
        <div class="card">
            <ion-toggle class="item" ng-true-value='1' ng-false-value='0' ng-model="addEvent.isAllDay" ng-checked="addEvent.isAllDay != 0">
                <i class="ion-ios-timer-outline padding-right"></i> All Day Event
            </ion-toggle>
            <!-- From Date and Time -->
            <div class="item">
                <div class="row item item-icon-left">
                    <i class="icon ion-log-in positive" style="left: 0"></i>
                    <ionic-datepicker class="col col-50 col-offset-10 border-right" input-obj="fromDatePickerObject">
                        <span class="input-label">Beginning Date</span>
                        <button class="button button-outline button-assertive">
                            {{ fromDatePickerObject.inputDate | date: 'EEE, d MMMM' }}
                        </button>
                    </ionic-datepicker>
                    <div class="col col-30 ng-show" ion-datetime-picker time am-pm ng-model="addEvent.fromTime" ng-hide="addEvent.isAllDay == '1'">
                        <span class="input-label padding-left">Time</span>
                        <strong class="button button-outline button-assertive">{{ addEvent.fromTime | date: "HH:mm a" }}</strong>
                    </div>
                </div>
                <!-- To Date and Time -->
                <div class="row item item-icon-left ">
                    <i class="icon ion-log-out positive" style="left: 0"></i>
                    <ionic-datepicker class="col col-50 col-offset-10 border-right" input-obj="toDatePickerObject">
                        <span class="input-label">End Date</span>
                        <button class="button button-outline button-assertive">
                            {{ toDatePickerObject.inputDate | date: 'EEE, d MMMM' }}
                        </button>
                    </ionic-datepicker>
                    <div class="col col-30" ion-datetime-picker time am-pm ng-model="addEvent.toTime" ng-hide="addEvent.isAllDay == '1'">
                        <span class="input-label padding-left">Time</span>
                        <strong class="button button-outline button-assertive">{{ addEvent.toTime | date: "HH:mm a"  }}</strong>
                    </div>
                </div>
            </div>
            <ion-toggle class="item" ng-true-value='1' ng-false-value='0' ng-model="addEvent.isPersonal" ng-checked="addEvent.isPersonal != 0">
                Personal Event
            </ion-toggle>
            <div class="item" ng-click="repeatPopup()">
                <i class="ion-loop"></i>
                <span class="padding-left" style="padding-right: 35%">Repeat</span>
                <span class="">{{ addEvent.repeatType === "none" ? "" : takeText(addEvent.repeatType ) }} </span>
            </div>
            <div class="item" ng-if="addEvent.repeatType != 'none'">
                <ionic-datepicker input-obj="repeatUntilDatePickerObject">
                    <span class="input-label">Repeat Until</span>
                    <button class="button button-outline button-assertive">
                        {{ repeatUntilDatePickerObject.inputDate | date: 'EEE, d MMMM' }}
                    </button>
                </ionic-datepicker>
            </div>
        </div>
    </form>
    </ion-content>
</ion-view>
