<style>
.Unread {
    background-color: #FFFFCC;
}

.Read {
    background-color: #FFFFFF;
}

#editBtn {
    top: 11px;
    position: absolute;
    height: 40px;
    width: 40px;
    color: #FFF;
    font-size: 28px;
    background-color: #FFF;
    border-radius: 50%;
}
</style>
<ion-view title="Notification Detail" name="singleNotification">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" id="iconbutton" ng-click="backToAlert()"></button>
    </ion-nav-buttons>
    <ion-content>
        <ion-refresher pulling-text="Pull to refresh..." on-refresh="doRefresh()">
        </ion-refresher>
        <div class="list card tabLifeSecretContent" ng-repeat="notification in notificationDetail">
            <div class="item item-avatar item-icon-right">
                <a class="icon subdued ion-edit " id="editBtn" ng-if="notification.Edit == 'Y'" ng-click="editStatus(notification)"></a>
                <img ng-click="goToHome(notification)" ng-src="{{notification.Icon}}" class="Icon">
                <span class="ProfileInfo"><i class="ion-person"></i>  {{notification.AccountName}}</span>
                <br>
                <span class="ProfileInfo"><i class="ion-university"></i>  {{notification.SchoolName}} - {{notification.ProgramName}}</span>
                <br>
                <span class="ProfileInfo" am-time-ago="notification.CreateDt"></span>
            </div>
            <div class="item item-body">
                <img class="full-image" ng-if="notification.PhotoSrc!=='N'" ng-src="{{notification.PhotoSrc}}" />
                <span class="statusText">{{notification.Status}}</span>
            </div>
            <div class="item tabs tabs-secondary tabs-icon-left" style="background-color:#009688;color:#FFF">
                <a ng-class="notification.YouLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="Addlike(notification)">
                    <i class="icon ion-heart"><span style="font-size: 14px" ng-bind-html="notification.Likes"></span></i> Love
                </a>
                <a ng-class="notification.YouDisLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="AddDisLike(notification)">
                    <i class="icon ion-heart-broken"><span  style="font-size: 14px" ng-bind-html="notification.DisLikes"></span></i> Hurt
                </a>
                <a class="tab-item" ng-click="Comment(notification)">
                    <i class="icon ion-chatbox"><span  style="font-size: 14px" ng-bind-html="notification.Comment"></span></i> Comment
                </a>
            </div>
        </div>
    </ion-content>
</ion-view>
