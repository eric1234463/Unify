<button  class="button button-full button-positive" ng-click="showStatusInfo(status)" style="background-color: #FFF;border: none;text-align: left;">
    <a class="tab-item">
        <a class="ion-heart heart"></a> <span class="heartCount" ng-bind-html="status.Liked"></span>
        <a class="ion-heart-broken hurt"></a> <span class="hurtCount" ng-bind-html="status.DisLiked"></span>
        <a class="ion-chatbox comment"></a> <span class="commentCount" ng-bind-html="status.Comment"></span>
    </a>
</button>
<a ng-if="status.Comment> 5 && status.ShowMore == 1" ng-click="showMoreComment(status)"> Show More </a>
<a ng-if="status.Comment > 5 && status.ShowMore == 0 " ng-click="showLessComment(status)"> Show Less </a>
<ion-item class="item-avatar" ng-repeat="comment in status.commentList"><img ng-src="{{comment.Icon}}" />
    <h2> {{comment.CommentAccountName}} </h2>
    <p> {{comment.Comment}} </p>
</ion-item>
<input type="text" placeholder="Plase feedback in here" id="{{status.StatusID}}_newcomment" style="width:100%" />
