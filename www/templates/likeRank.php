<style>
.rankIcon {
    width: 20px;
}
</style>
<div class="list">
    <div class="item item-divider">
        <a class="subdued ion-heart" style="color:#ed8986"> </a> Love
    </div>
    <a class="item item item-avatar item-button-right item item-icon-left" ng-repeat="like in likeList">
        <img ng-src="{{like.Icon}}">
        <div class="item item-icon-left">
            <i ng-if="like.Rank==1" class="icon icon-number3"></i>
            <i ng-if="like.Rank==2" class="icon icon-number2"></i>
            <i ng-if="like.Rank==3" class="icon icon-number"></i>
            <span class="ProfileInfo">{{like.AccountName}}</span>
            <br>
            <span class="ProfileInfo">{{like.SchoolName}} - {{like.ProgramName}}</span>
            <br>
            <span class="ProfileInfo">Love : {{like.LikedCount}}</span>
        </div>
    </a>
</div>
