<style>
#swipe-icon {
    color: white;
}
</style>
<ion-view title="Timetable" name="timetable-view">
    <ion-nav-buttons side="right">
        <button class="button button-icon icon ion-plus-round" ng-click="addEvent(datepickerObject.inputDate)" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <ionic-datepicker input-obj="datepickerObject">
            <button class="button button-full button-outline button-assertive" style="border-color: #FFF">
                {{ datepickerObject.inputDate | date: 'dd MMMM yyyy' }}
                <i class="ion-ios-arrow-down"></i>
            </button>
        </ionic-datepicker>
        <ion-list can-swipe="canSwipe = true">
            <ion-item class="item-divider">
                School Events
            </ion-item>
            <ion-item class="item-icon-left item-remove-animate" ng-repeat="event in recData track by $index" ng-if="event.isPersonal == 0" item="item" ng-click="showDetailedEvent(event, event.date)">
                <i class="icon ion-ios-book calm"></i>
                <h2>{{ event.eventTitle }}</h2>
                <p>{{ event.eventDescription }}</p>
                <p>{{ event.eventLocation }}</p>
                <p ng-if="event.isAllDay == 0">{{ event.fromTime | date: "HH:mm a":'+0800' }} - {{ event.toTime | date: "HH:mm a":'+0800' }}</p>
                <!-- Show below if it's an all day -->
                <p ng-if="event.isAllDay == 1"> All day event. </p>
                <ion-option-button class="button" ng-click="editEvent(event)" style="min-width: 70px;min-height: 75px;background-color:#2962FF">
                    <i class="icon ion-edit" style="display:block;right:18px;color: #FFF;background-color: transparent;width: 41px"></i>
                    <div class="label">Edit</div>
                </ion-option-button>
                <ion-option-button class="button"  ng-click="deleteEvent(event.eventID, event.eventRepeatID)" style="min-width: 70px;min-height: 75px;background-color:#DD2C00">
                    <i class="icon ion-trash-a" style="display:block;right:18px;color: #FFF;background-color: transparent;width: 41px"></i>
                    <div class="label">Delete</div>
                </ion-option-button>
            </ion-item>
            <ion-item class="item-divider">
                Personal Events
            </ion-item>
            <ion-item class="item-icon-left item-remove-animate" ng-repeat="event in recData track by $index" ng-if="event.isPersonal == 1" ng-click="showDetailedEvent(event, event.date)">
                <i class="icon ion-ios-bookmarks calm"></i>
                <h2>{{ event.eventTitle }}</h2>
                <p>{{ event.eventDescription | limitTo: 37}} ...</p>
                <p>{{ event.eventLocation }}</p>
                <p ng-if="event.isAllDay == 0">{{ event.fromTime | date: "HH:mm a":'+0800' }} - {{ event.toTime | date: "HH:mm a":'+0800' }}</p>
                <!-- Show below if it's an all day -->
                <p ng-if="event.isAllDay == 1"> All day event. </p>
                <ion-option-button class="button"  ng-click="editEvent(event)" style="min-width: 70px;min-height: 75px;background-color:#2962FF">
                    <i class="icon ion-edit" style="display:block;right:18px;color: #FFF;background-color: transparent;width: 41px"></i>
                    <div class="label">Edit</div>
                </ion-option-button>
                <ion-option-button class="button" ng-click="deleteEvent(event.eventID, event.eventRepeatID)" style="min-width: 70px;min-height: 75px;background-color:#DD2C00">
                    <i class="icon ion-trash-a" style="display:block;right:18px;color: #FFF;background-color: transparent;width: 41px"></i>
                    <div class="label">Delete</div>
                </ion-option-button>
            </ion-item>
        </ion-list>
    </ion-content>
</ion-view>
