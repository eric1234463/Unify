<ion-view title="Second Hand Book Trading">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabLife()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button button-icon icon ion-plus-round" ng-click="goToAddBook()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div class="list list-inset">
            <label class="item item-input">
                <i class="icon ion-search placeholder-icon"></i>
                <input type="text" placeholder="Search Book" ng-model="data.keyword"/>
            </label>
            <ul class="list">
                <a class="item item item-avatar item-button-right" ng-repeat="book in bookList| filter:data.keyword" ng-click="goToBookDetail(book)">
                    <img ng-if="book.PhotoSrc!=='N'" ng-src="{{book.PhotoSrc}}" class="Icon">
                    <span class="ProfileInfo" >{{book.Name}}</span>
                    <br>
                    <span class="ProfileInfo" am-time-ago="book.Time"></span>
                </a>
            </ul>
        </div>
    </ion-content>
</ion-view>
