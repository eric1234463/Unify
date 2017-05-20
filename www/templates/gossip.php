<ion-view title="Gossip">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabMore()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div class="list list-inset">
            <ul class="list">
                <a class="item item item-avatar item-button-right" id="{{gossip.GossipID}}_box" ng-repeat="gossip in gossipList" >
                    <img ng-src="{{gossip.Icon}}">
                    <span class="AccountName">{{gossip.GossipName}}</span>
                    <button class="button ion-minus" style="background-color: #b2b2b2" ng-click="removeGossip(gossip)" >
                    </button>
                </a>
            </ul>
        </div>
    </ion-content>
</ion-view>
