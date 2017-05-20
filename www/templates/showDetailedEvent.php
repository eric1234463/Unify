<style>
    .background {
        color: white;
    }

    .background_personal {
        background-color: #009688;
        color: white;
    }

    
</style>

<ion-view title="" hide-nav-bar="true">
    <ion-content>
        <div ng-class="data.isPersonal == 0 ? 'background' : 'background_personal'">
            <button class="button icon ion-ios-arrow-back" ng-click="backToPrevPage()" id="iconbutton" style="display: block; background: none"></button>
            <div class="padding">
                <i class="icon ion-ios-information padding icon-left"></i>
                <h3 style="display: inline; color: white" class="padding-left">{{ data.eventTitle }}</h3>
            </div>
            <div class="padding icon-left">
                <i class="icon ion-android-calendar padding "></i>
                <span class="padding">{{ data.date | date: "EEEE, MMMM d, yyyy"}}</span>
                <br>
            </div>
            <div class="padding icon-left" ng-if="data.isAllDay == 0">
                <i class="icon ion-android-time padding"></i>
                <span class="padding">{{ data.fromTime }} - {{ data.toTime }}</span>
            </div>
            <div class="padding icon-left" ng-if="data.isAllDay == 1">
                <i class="icon ion-android-time padding"></i>
                <span class="padding">All day event.</span>
            </div>
            <div class="padding icon-left">
                <i class="icon ion-android-sync padding"></i>
                <span class="padding">{{ data.repeatTypeText(data.repeatType) }} recurring event.</span>
            </div>
            <div class="padding-vertical"></div>
        </div>
       
        <div class="padding">
            <i class="padding ion-ios-location"></i>
            <span class="padding">{{ data.eventLocation }}</span>
            <div class="padding-bottom"></div>
        </div>
        <!-- <div class="padding-bottom" style="border-bottom: black"></div> -->
        <div class="padding">
            <h4 class="padding-horizontal" style="color: #009688">Description</h4>
            <p>{{ data.eventDescription}}</p>
        </div>
        <button class="button button-assertive button-full" ng-click="deleteEvent(data.eventID, data.eventRepeatID, data)">Delete Event</button>
        <pre>
            {{ date }}
        </pre>
    </ion-content>
    <div class="float-button">
        <span class="height-fix">
            <a class="content" ng-click="editEvent()">
              <i class="button button-icon icon ion-edit calm" style="background: none"> </i>
            </a>
        </span>
    </div>
</ion-view>
