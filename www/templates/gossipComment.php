<div class="item sercetcomment">
    <a ng-if="secret.Comment > 5 && secret.ShowMore =='N'" ng-click="showSecretFullComment(secret)">Show More</a>
    <a ng-if="secret.Comment > 5 && secret.ShowMore =='Y'" ng-click="showSecretLessComment(secret)">Show Less</a>
    <ion-item class="item-avatar" ng-repeat="secretComment in secret.CommentList">
        <img ng-src="{{secretComment.Icon}}" />
        <h2>{{secretComment.SecretName}}</h2>
        <p>{{secretComment.Comment}}</p>
    </ion-item>
    <input type="text" placeholder="Plase feedback in here" id="{{secret.SecretMessageID}}_newcomment" style="width:100%" />
</div>
