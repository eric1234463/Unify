<ion-view title="Second Hand Book Detail">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToSecondHand()" id="iconbutton"></button>
    </ion-nav-buttons>
     <ion-nav-buttons side="right">
        <button class="button icon ion-checkmark" ng-click="buy()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="list list-inset">
            <div class="item item-avatar item-icon-right">
                <img ng-src="{{book.PhotoSrc}}" ng-click="goToHome()">
                <span class="ProfileInfo" ng-bind-html="book.AccountName"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-ios-bookmarks"></i> Book Name: <span ng-bind-html="book.Name"></span>
            </div>
              <div class="item item-icon-left">
                <i class="icon ion-ios-bookmarks"></i> Book Description: <span ng-bind-html="book.Desc"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-social-usd"></i> Price: <span ng-bind-html="book.Price"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-clock"></i> Post Time: <span am-time-ago="book.Time"></span>
            </div>
        </div>
    </ion-content>
</ion-view>
