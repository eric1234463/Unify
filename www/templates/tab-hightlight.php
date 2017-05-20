<ion-view title="Select your mentor">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToLife()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="list list-inset">
            <ul class="list">
                <a class="item item item-button-right" ng-repeat="account in accountList" id="{{account.id}}_box" >
                    <span class="ProfileInfo" ng-click="goToHome(friend)">{{account.name}}</span>
                    <br>
                    <span class="ProfileInfo">{{account.school}} - {{account.progam}}  {{account.year}}</span>
                    <br>
                    <span class="ProfileInfo">{{account.intro}}</span>
                    <button class="button ion-plus" style="" ng-click="addMentor(account)" >
                    </button>
                </a>
            </ul>
        </div>
    </ion-content>
</ion-view>