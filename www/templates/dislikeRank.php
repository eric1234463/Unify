<style>
.rankIcon {
    width: 20px;
}
</style>
<div class="list">
    <div class="item item-divider">
        <a class="subdued ion-heart-broken" style="color:#92A8D1"> </a> Hate
    </div>
    <a class="item item item-avatar item-button-right  item item-icon-left" ng-repeat="like in dislikeList">
        <img ng-src="{{like.Icon}}">
        <div>
            <i ng-if="like.Rank==1" class="icon icon-number"></i>
            <i ng-if="like.Rank==2" class="icon icon-number2"></i>
            <i ng-if="like.Rank==3" class="icon icon-number3"></i>
            <span class="ProfileInfo">{{like.AccountName}}</span>
            <br>
            <span class="ProfileInfo">{{like.SchoolName}} - {{like.ProgramName}}</span>
            <br>
            <span class="ProfileInfo">Hate : {{like.LikedCount}}</span>
        </div>
    </a>
</div>
