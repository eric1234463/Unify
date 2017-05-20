<ion-view title="Setting">
    <ion-nav-buttons side="left">
        <button class="button icon ion-close" ng-click="backToTabLife()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button icon ion-checkmark" ng-click="submit()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div>
            <ul class="list">
                <ion-toggle class="item" ng-model="data.Private" ng-checked="data.checked">Private Account</ion-toggle>
            </ul>
        </div>
    </ion-content>
</ion-view>
