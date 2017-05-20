<style type="text/css">
.SpecialRow {
    border-width: 1px;
    border-style: inset;
}
</style>
<ion-view title="Search">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabLife()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-tabs class="tabs-icon-left tabs-calm tabs-top">
        <ion-tab title="Friend" icon-off="icon ion-compose" icon-on="icon ion-compose">
            <ion-nav-view>
                <ion-content>
                    <div class="list list-inset" id="searchFriend">
   <!--                      <div class="item item-divider item-text-wrap">
                            You can use height, weight, name, school and program to be the filter
                        </div> -->
                        <label class="item item-input">
                            <i class="icon ion-search placeholder-icon"></i>
                            <input type="text" placeholder="Search Friend" ng-model="data.friendkeyword" ng-keyup="searchFriend()" />
                        </label>
                        <ul class="list">
                            <a class="item item item-avatar item-button-right" ng-repeat="friend in friendList">
                                <img ng-if="friend.Icon!=='N'" ng-src="{{friend.Icon}}" class="Icon">
                                <span class="ProfileInfo" ng-click="addFactory(friend)">{{friend.AccountName}}</span>
                                <br>
                                <span class="ProfileInfo">{{friend.SchoolName}} - {{friend.ProgramName}}</span>
                                <button class="button ion-plus" ng-click="addFriend(friend)" id="{{friend.AccountID}}_button" ng-if="friend.FriendChecker==='N'">
                                </button>
                                <button class="button ion-minus" style="background-color: #b2b2b2" ng-click="removeFriend(friend)" id="{{friend.AccountID}}_button" ng-if="friend.FriendChecker==='Y'">
                                </button>
                            </a>
                        </ul>
                    </div>
                </ion-content>
            </ion-nav-view>
        </ion-tab>
        <ion-tab title="Gossip" icon-off="icon ion-chatbubble" icon-on="icon ion-chatbubble">
            <ion-nav-view>
                <ion-content>
                    <div class="list list-inset" id="searchGossip">
                        <label class="item item-input">
                            <i class="icon ion-search placeholder-icon"></i>
                            <input type="text" placeholder="Search Gossip" ng-model="data.gossipkeyword" ng-keyup="searchGossip()" />
                        </label>
                        <ul class="list">
                            <a class="item item item-avatar item-button-right" ng-repeat="gossip in gossipList">
                                <img ng-src="{{gossip.Icon}}" />
                                <span class="ProfileInfo">{{gossip.GossipName}}</span>
                                <p>{{gossip.GossipDesc}}</p>
                                <button class="button ion-plus" ng-click="addGossip(gossip)" ng-if="gossip.GossipChecker==='N'" id="{{gossip.GossipID}}_button">
                                </button>
                                <button class="button ion-minus" ng-click="removeGossip(gossip)" ng-if="gossip.GossipChecker==='Y'" style="background-color: #b2b2b2" id="{{gossip.GossipID}}_button">
                                </button>
                            </a>
                        </ul>
                    </div>
                </ion-content>
            </ion-nav-view>
        </ion-tab>
    </ion-tabs>
</ion-view>
