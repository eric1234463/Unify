<style type="text/css">
#input {
    background-color: #FFF;
}

#GossipIcon {
    border-radius: 50%;
    height: 100%;
    margin-left: auto;
    margin-right: auto;
    display: block;
    width: 144px;
}

.SpecialRow {
    border-width: 1px;
    border-style: inset;
}
</style>
<ion-view title="Create Gossip">
    <ion-nav-buttons side="left">
        <button class="button icon ion-close-round" ng-click="backToTabMore()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="Right">
        <button class="button icon ion-checkmark-round" ng-click="Submit()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div class="card padding-top">
            <div class="item item-input-inset SpecialRow">
                <h2 class="item-input-wrapper" id="input">
                        Gossip Icon
                </h2>
                <button class="button button-icon ion-android-camera" ng-click="takeIcon()">
                </button>
            </div>
            <div class="item item-input-inset SpecialRow" ng-if="data.Icon!=='N'">
                <img ng-src="{{data.Icon}}" id="GossipIcon">
            </div>
            <label class="item item-input item-stacked-label SpecialRow">
                <span class="input-label">Gossip Title</span>
                <input type="text" placeholder="i.e Gossip Title" ng-model="data.Title">
            </label>
            <label class="item item-input item-stacked-label SpecialRow">
                <span class="input-label">Gossip Description</span>
                <input type="text" placeholder="Please write down description" ng-model="data.desc">
            </label>
        </div>
    </ion-content>
</ion-view>
