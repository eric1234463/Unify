<style type="text/css">
.photoRow {
    background-color: #009688;
    border-radius: 10px;
}

.textarea {
    width: 90%;
    margin: auto;
    border-radius: 3px;
}
</style>
<ion-view title="New Photo Diary">
    <ion-nav-buttons side="Left">
        <button class="button button-icon  ion-navicon" ng-click="openMenu()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button button-icon ion-android-favorite" ng-click="goToLove()" id="iconbutton"></button>
        <button class="button button-icon ion-search" ng-click="searchFriend()" id="iconbutton"></button>
        <button class="button button-icon ion-android-done" id="iconbutton" ng-click="submit()"></button>
    </ion-nav-buttons>
    <ion-content id="tabLifeBackground">
        <div class="card">
            <div class="item item-avatar item-button-right">
                <img ng-src="{{Icon}}" ng-if="Icon!=='N'" class="Icon">
                <div ng-bind-html="accountName"></div>
                <button class="button icon ion-plus" ng-click="Vaildation(photo)" id="iconbutton"></button>
            </div>
            <div class="item item-text-wrap">
                <textarea ng-model="$parent.Title" placeholder="Please Put Your Diary Title Here"></textarea>
            </div>
            <div class="item item-body">
                <div class="photoRow" ng-repeat="photo in photoList">
                    <div class="row">
                        <button class="button icon ion-camera" ng-click="TakePhoto(photo)" id="iconbutton"></button>
                    </div>
                    <img class="full-image" ng-src="{{photo.Photo}}" ng-show="photo.Photo!==undefined" />
                    <div class="row">
                        <textarea class="textarea" rows="4" ng-model="photo.Status" placeholder="Please Put Your Photo Description Here"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </ion-content>
</ion-view>
