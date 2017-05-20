<style type="text/css">
</style>
<ion-view title="New Status">
    <ion-nav-buttons side="left">
        <button class="button button-icon  ion-navicon" ng-click="openMenu()" id="iconbutton"></button>
        <button class="button icon ion-camera" id="iconbutton" ng-click="takePhoto()"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button" id="iconbutton" ng-click="CreateNewStatus()">
            Submit
        </button>
    </ion-nav-buttons>
    <ion-content id="tabLifeBackground">
        <div class="list card">
            <div class="item item-avatar">
                <img ng-src="{{Icon}}" ng-if="Icon!=='N'" class="Icon">
                <div ng-bind-html="accountName"></div>
            </div>
            <label class="item item-input item-select">
                Status Type
                <select ng-model="data.statusType" ng-options="gossip.GossipID as gossip.GossipName for gossip in gossipList">
                    <option value="Personal">Personal</option>
                </select>
            </label>
            <div class="item item-body">
                <img class="full-image" ng-show="imgURI !== undefined" id="photo" ng-src="{{imgURI}}">
                <textarea placeholder="Please Write Your New Status" rows="10" style="width:100%;" ng-model="$parent.newStatus"></textarea>
            </div>
        </div>
    </ion-content>
</ion-view>
