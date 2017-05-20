<ion-view>
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToPreviousPage()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content class="createNewuser">
        <div class="list list-inset">
            <label class="item item-input">
                <input type="text" placeholder="Pin" ng-model="data.pin">
            </label>
            <label class="item item-input">
                <input type="text" placeholder="Username" ng-model="data.username">
            </label>
            <label class="item item-input">
                <input type="password" placeholder="Password" ng-model="data.password">
            </label>
            <label class="item item-input">
                <input type="tel" placeholder="Telephone" ng-model="data.telephone">
            </label>
        </div>
        <button class="button button-block" ng-click="nextToTabLife()" style="bottom:0">Create</button>
    </ion-content>
</ion-view>
