<ion-view title="Edit Status">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabLife()" id="iconbutton"></button>
        <button class="button" id="iconbutton" ng-click="RemoveStatus()">
            Remove
        </button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button" id="iconbutton" ng-click="UpdateNewStatus()">
            Submit
        </button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="list card">
            <div class="item item-avatar">
                <img ng-src="{{Icon}}" ng-if="Icon!=='N'" class="Icon">
                <div ng-bind-html="accountName"></div>
            </div>
            <div class="item item-body">
                <label class="item item-input">
                    <textarea rows="10" ng-model="$parent.Status"></textarea>
                </label>
            </div>
        </div>
    </ion-content>
</ion-view>
