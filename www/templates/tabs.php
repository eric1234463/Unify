<!--
Create tabs with an icon and label, using the tabs-stable style.
Each tab's child <ion-nav-view> directive will have its own
navigation history that also transitions its views in and out.
-->

<ion-tabs class="tabs-striped tabs-icon myTab">
    <!-- Dashboard Tab -->
    <ion-tab icon-off="ion-ios-pulse" icon-on="ion-ios-pulse-strong" href="#/tab/life/tabLifeView">
        <ion-nav-view name="tab-life"></ion-nav-view>
    </ion-tab>
    <!-- Chats Tab -->
    <ion-tab icon-off="ion-ios-chatboxes-outline" icon-on="ion-ios-chatboxes" href="#/tab/chats" badge="data.badgeCount" badge-style="badge-assertive" ng-controller="tabCount">
        <ion-nav-view name="tab-chats"></ion-nav-view>
    </ion-tab>
    <ion-tab class="myTabStyle" icon-off="ion-compose" icon-on="ion-compose" href="#/tab/createNewStatus">
        <ion-nav-view name="tab-createNewStatus"></ion-nav-view>
    </ion-tab>
    <!-- Home Tab -->
    <ion-tab icon-off="ion-ios-bell-outline" icon-on="ion-ios-bell" href="#/tab/alert/alertNotification" badge="unread" badge-style="badge-assertive" ng-controller="Unread">
        <ion-nav-view name="tab-alert"></ion-nav-view>
    </ion-tab>
    <!-- Timetable Tab -->
    <ion-tab icon-off="ion-ios-calendar-outline" icon-on="ion-ios-calendar" href="#/tab/timetable">
        <ion-nav-view name="tab-timetable"></ion-nav-view>
    </ion-tab>
    <!-- More Tab -->
</ion-tabs>
