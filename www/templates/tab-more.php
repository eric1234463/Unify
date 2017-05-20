<style type="text/css">
.SpecialRow {
    border-width: 1px;
    border-style: inset;
}
</style>
<ion-view title="More" name="more-view">
    <ion-content>
        <div class="list">
            <div class="item item-divider">
                Personal
            </div>
            <a class="item item-icon-left SpecialRow" ng-click="goToHome()">
                <i class="icon ion-ios-home calm" style="background-color:#0D47A1"></i>
                <h2>Home</h2>
            </a>
            <a class="item item-icon-left SpecialRow" ng-click="goToFriendList()">
                <i class="icon ion-person-stalker calm" style="background-color:#D50000"></i>
                <h2>Friends</h2>
            </a>
            <a class="item item-icon-left SpecialRow">
                <i class="icon ion-image calm" style="background-color:#AA00FF"></i>
                <h2>Photo</h2>
            </a>
            <div class="item item-divider">
                Study
            </div>
            <a class="item item-icon-left SpecialRow">
                <i class="icon ion-ios-book calm" style="background-color:#6200EA"></i>
                <h2>Second Hand Book Trading</h2>
            </a>
            <div class="item item-divider">
                Gossip
            </div>
            <a class="item item-icon-left SpecialRow" ng-click="goToGossip()">
                <i class="icon ion-chatbubble calm" style="background-color:#00B8D4"></i>
                <h2>Gossip</h2>
            </a>
            <a class="item item-icon-left SpecialRow" ng-click="goToCreateGossip()">
                <i class="icon ion-android-create calm" style="background-color:#263238"></i>
                <h2>Create Gossip</h2>
            </a>
        </div>
    </ion-content>
</ion-view>
