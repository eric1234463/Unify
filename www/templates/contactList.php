<ion-view title="Contact List">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabChat()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="list list-inset">
            <label class="item item-input">
                <i class="icon ion-search placeholder-icon"></i>
                <input type="text" placeholder="Search" ng-model="data.keyword" ng-keyup="search()" />
            </label>
            <ul class="list">
                <a class="item item item-avatar item-button-right" ng-repeat="contact in contactList" ng-click="addChat(contact)">
                    <img ng-src="{{contact.Icon}}">
                    <h2 class="ProfileInfo">{{contact.AccountName}}</h2>
                    <span class="ProfileInfo">{{contact.SchoolName}} - {{contact.ProgramName}}</span>
                </a>
            </ul>
        </div>
    </ion-content>
</ion-view>
