<ion-view view-title="Gossip">
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
        <div class="list card tabLifeSecretContent" ng-repeat="secret in secretList">
            <div class="item item-avatar tabLifeSecretStyle">
                <img ng-src="{{secret.Icon}}" />
                <span class="ProfileInfo">{{secret.SecretName}}</span>
                <br>
                <span class="ProfileInfo" am-time-ago="secret.Time"></span>
            </div>
            <div class="item item-body">
                <img class="full-image" ng-if="secret.Photo!=='N'" ng-src="{{secret.Photo}}" />
                <span class="statusText">{{secret.SecretMessage}}</span>
            </div>
            <div class="item tabs tabs-secondary tabs-icon-left" style="background-color:#009688;color: #FFF">
                <a ng-class="secret.YouLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="AddSecretlike(secret)">
                    <i class="icon ion-heart"><span  style="font-size: 14px" ng-bind-html="secret.Liked"></i> Love
                </a>
                <a ng-class="secret.YouDisLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="AddSecretDisLike(secret)">
                    <i class="icon ion-heart-broken"><span  style="font-size: 14px" ng-bind-html="secret.DisLiked"></i> Hurt
                </a>
                <a ng-class="secret.showComment == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="SecretComment(secret)">
                    <i class="icon ion-chatbox"><span style="font-size: 14px" ng-bind-html="secret.Comment"></i> Comment
                </a>
            </div>
        </div>
    </ion-content>
</ion-view>
