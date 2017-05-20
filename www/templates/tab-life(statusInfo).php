<div class="list">
    <div class="item item-divider">
        <a class="subdued ion-heart" style="color:#ed8986"> </a> Love
    </div>
    <a class="item item item-avatar item-button-right" ng-repeat="like in likeList">
        <img ng-if="like.Icon !=='N'" ng-src="{{like.Icon}}" />
        <span class="ProfileInfo">{{like.LikeAccountName}}</span>
        <br>
        <span class="ProfileInfo">{{like.SchoolName}} - {{like.ProgramName}}</span>
    </a>
    <div class="item item-divider">
        <a class="subdued icon ion-heart-broken" style="color:#92A8D1"> </a> Hurt
    </div>
    <a class="item item item-avatar item-button-right" ng-repeat="disLike in disLikeList" ng-click="addFactory(friend)">
        <img ng-if="disLike.Icon !=='N'" ng-src="{{disLike.Icon}}" />
        <span class="ProfileInfo">{{disLike.LikeAccountName}}</span>
        <br>
        <span class="ProfileInfo">{{disLike.SchoolName}} - {{disLike.ProgramName}}</span>
    </a>
</div>
