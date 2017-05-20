<style>
.Unread {
    background-color: #FFFFE0;
}
.Notification{
    font-weight: bold;
}
</style>
<ion-view title="Notification">
    <ion-content>
        <div class="list">
            <div ng-class="notification.Read == 'N' ? 'Unread item-icon-right item item-avatar-left item-text-wrap': 'item-icon-right item item-avatar-left item-text-wrap'" ng-repeat="notification in notificationList">
                <img ng-click="goToHome(notification)" ng-src="{{notification.Icon}}">
                <span ng-if="notification.NotificationTypeID !=='UNI-NT-2016-00007' && notification.NotificationTypeID !=='UNI-NT-2016-00008'" ng-click="newWindow();passData(notification)" class="Notification">
                    <span class="ProfileInfo " style="font-size: 18px">{{notification.FriendAccountName}}</span> {{notification.NotificationDesc}}
                </span>

                <span ng-if="notification.NotificationTypeID =='UNI-NT-2016-00007'" ng-click="answerQuestion(notification)" class="Notification">
                    <span class="ProfileInfo" style="font-size: 18px ">{{notification.FriendAccountName}}</span> {{notification.NotificationDesc}} 
                </span>

                <span ng-if="notification.NotificationTypeID =='UNI-NT-2016-00008'" ng-click="viewAnswer(notification)" class="Notification">
                    <span class="ProfileInfo" style="font-size: 18px ">{{notification.FriendAccountName}}</span> {{notification.NotificationDesc}} 
                </span>
                <p am-time-ago="notification.CreateDt"></p>
            </div>
        </div>
        <ion-infinite-scroll on-infinite="moreStatus()" distance="1%"></ion-infinite-scroll>
    </ion-content>
</ion-view>;
