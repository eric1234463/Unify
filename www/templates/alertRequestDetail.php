<ion-view title="Tocuhing Fate Detail">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToAlert()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="list list-inset">
            <div class="item item-avatar item-icon-right">
                <img ng-src="{{Detail.Icon}}" ng-click="goToHome()">
                <span class="ProfileInfo" ng-bind-html="Detail.RequestAccountName"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-ios-calendar"></i> Date: <span ng-bind-html="Detail.Date"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-log-in"></i> From: <span ng-bind-html="Detail.FromTime"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-log-out"></i> To: <span ng-bind-html="Detail.ToTime"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-ios-location"></i> Location: <span ng-bind-html="Detail.Location"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-email"></i> Message: <span ng-bind-html="Detail.Message"></span>
            </div>
            <button class="button button-full" ng-click="Accept(Detail)">
                Accept
            </button>
        </div>
    </ion-content>
</ion-view>
