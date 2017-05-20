<ion-view title="New Photo">
    <ion-nav-buttons side="right">
        <button class="button icon ion-android-favorite" ng-click="goToLove()" id="iconbutton"></button>
        <button class="button icon ion-search" ng-click="searchFriend()" id="iconbutton"></button>
        <button class="button icon ion-close-circled" ng-click="logout()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div class="list card">
            <div class="item item-avatar">
                <img src="img/96.png">
                <div ng-bind-html="accountName"></div>
            </div>
            <div class="item item-body" style="border-style:solid;border-width:3px;border-color:#d3d3d3">
                <img ng-show="imgURI !== undefined" id="photo" ng-src="{{imgURI}}">
            </div>
            <button class="button icon ion-camera" ng-click="takePhoto()">
            </button>
            <button class="button" ng-click="getImageSaveContact()">
                Submit
            </button>
        </div>
    </ion-content>
</ion-view>
