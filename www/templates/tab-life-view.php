<style type="text/css">
#editBtn {
    top: 11px;
    position: absolute;
    height: 40px;
    width: 40px;
    color: #009688;
    font-size: 28px;
}

</style>
<ion-view view-title="Life">
    <ion-nav-buttons side="Left">
        <button class="button button-icon  ion-navicon" ng-click="openMenu()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button button-icon ion-android-favorite" ng-click="goToLove()" id="iconbutton"></button>
        <button class="button button-icon ion-search" ng-click="searchFriend()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content id="tabLifeBackground">
        <ion-refresher pulling-text="Pull to refresh..." on-refresh="doRefresh()">
        </ion-refresher>
        <div class="list card tabLifeSecretContent" ng-repeat="status in statusList">
            <div class="item item-avatar item-icon-right">
                <a class="icon subdued ion-edit" id="editBtn" ng-if="status.Edit == 'Y'" ng-click="editStatus(status)"></a>
                <img ng-if="status.ICON!=='N'" ng-src="{{status.ICON}}" class="Icon" ng-click="goToHome(status)">
                <span class="ProfileInfo" ng-click="goToHome(status)"><i class="ion-person"></i>  {{status.AccountName}}</span>
                <br>
                <span class="ProfileInfo"><i class="ion-university"></i>  {{status.SchoolName}} - {{status.ProgramName}}</span>
                <br>
                <span class="ProfileInfo" am-time-ago="status.Time"></span>
            </div>
            <div class="item item-body">
                <img class="full-image" ng-if="status.Photo!=='N'" ng-src="{{status.Photo}}" />
                <span class="statusText">{{status.Status}}</span>
            </div>
            <div class="item tabs tabs-secondary tabs-icon-left" style="background-color:#009688;color:#FFF">
                <a ng-class="status.YouLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="Addlike(status)">
                    <i class="icon ion-heart"><span ng-bind-html="status.Liked" style="font-size: 14px"></span></i> Love 
                </a>
                <a ng-class="status.YouDisLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="AddDisLike(status)">
                    <i class="icon ion-heart-broken"><span ng-bind-html="status.DisLiked" style="font-size: 14px"></span></i> Hate
                </a>
                <a ng-class="status.showComment == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="Comment(status)">
                    <i class="icon ion-chatbox"><span ng-bind-html="status.Comment" style="font-size: 14px"></span></i> Comment
                </a>
            </div>
        </div>
    </ion-content>
</ion-view>
