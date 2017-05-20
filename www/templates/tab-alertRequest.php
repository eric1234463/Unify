<ion-view title="Tocuhing Fate">
    <ion-content>
        <div class="list">
            <div class="item-icon-right item item-avatar-left item-text-wrap" ng-repeat="request in requestList" ng-click="goToDetail(request)">
                <img ng-src="{{request.Icon}}">
                <span><span class="ProfileInfo">{{request.RequestAccountName}}</span> want to date with you.
                <br> Hope you can have fun</span>
                <p am-time-ago="request.CreateDt"></p>
            </div>
        </div>
    </ion-content>
</ion-view>;
