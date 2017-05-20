<style type="text/css">
#nav-button {
    color: #FFF;
}
</style>
<ion-view title="New Group">
    <ion-nav-buttons side="left">
        <button id="nav-button" class="button button-clear button-stable" ng-click="backToTabChat()">Cancel</button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button id="nav-button" type="submit" class="button button-clear button-stable" ng-click="addGroupChat()" ng-disabled="!isDisabled">Next</button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="item item-body">
            <div class="item item-avatar" href="#">
                <img ng-src="{{Icon}}" ng-click="TakePhoto()">
                <label class="item item-input">
                    <input type="text" placeholder="Group Subject" ng-model="$parent.newGroup" style="width:100%;" />
                </label>
            </div>
        </div>
        <div class="item item-divider">
            Add Particpants
        </div>
        <div class="list list-inset">
            <label class="item item-input">
                <i class="icon ion-search placeholder-icon"></i>
                <input type="text" placeholder="Search" ng-model="data.keyword" ng-keyup="search()" />
            </label>
            <ul class="list">
                <ion-checkbox class="item item-avatar item-checkbox-right" ng-repeat="contact in contactList | filter: data.keyword" ng-checked="contact.Checked=='true'" ng-model="contact.Checked" ng-click="Checked(contact)">
                    <img ng-src="{{contact.Icon}}">
                    <h2 class="ProfileInfo">{{contact.AccountName}}</h2>
                    <span class="ProfileInfo">{{contact.SchoolName}} - {{contact.ProgramName}}</span>
                </ion-checkbox>
            </ul>
        </div>
    </ion-content>
</ion-view>
