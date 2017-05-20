<style type="text/css">
    .SpecialRow{
        border-width: 1px;
        border-style: inset;
    }
</style>
<ion-view title="FriendList">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabMore()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="list list-inset">
            <label class="item item-input">
                <i class="icon ion-search placeholder-icon"></i>
                <input type="text" placeholder="Search" ng-model="data.keyword" ng-keyup="search()" />
            </label>
            <ul class="list">
                <a class="item item item-avatar item-button-right" ng-repeat="friend in friendList" id="{{friend.AccountID}}_box">
                    <img ng-src="{{friend.Icon}}" ng-if="friend.Icon!=='N'" class="Icon" />
                    <span class="ProfileInfo" ng-click="goToHome(friend)">{{friend.AccountName}}</span>
                    <br>
                    <span class="ProfileInfo">{{friend.SchoolName}} - {{friend.ProgramName}}</span>
                    <button class="button ion-minus" style="background-color: #b2b2b2" ng-click="removefriend(friend)">
                    </button>
                </a>
            </ul>
        </div>
    </ion-content>
</ion-view>
