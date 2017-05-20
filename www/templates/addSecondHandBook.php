<ion-view title="Create Second Hand Book">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToSecondHand()" id="iconbutton"></button>
    </ion-nav-buttons>
     <ion-nav-buttons side="right">
        <button class="button icon ion-checkmark" ng-click="submit()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content id="tabLife">
        <div class="list list-inset">
            <div class="item item-avatar item-icon-right">
                <img ng-src="{{book.PhotoSrc}}" ng-click="takeIcon()">
                <span class="ProfileInfo" ng-bind-html="book.AccountName"></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-ios-bookmarks"></i> Book Name: <input type="text" ng-model="book.Name"></input></span>
            </div>
              <div class="item item-icon-left">
                <i class="icon ion-ios-bookmarks"></i> Book Description:  <input type="text" ng-model="book.Desc"></input></span>
            </div>
            <div class="item item-icon-left">
                <i class="icon ion-social-usd"></i> Price:   <input type="text" ng-model="book.Price"></input></span>
            </div>

        </div>
    </ion-content>
</ion-view>
