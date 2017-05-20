<ion-view title="Notes Detail">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToNote()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button" id="iconbutton" ng-click="UpdateNote()">
            Submit
        </button>
    </ion-nav-buttons>
    <ion-content>
        <div class="list" ng-repeat="Detail in DetailList">
            <ion-item class="item-divider"> Note Title : {{Detail.NoteTitle}}</ion-item>
            <ion-item class="item-divider">Note Type : {{Detail.NoteType}}</ion-item>
            <div class="card">
                <div class="item item-text-wrap" style="height: 300px">
                    <textarea row="5" style="width:100%;" ng-model="Detail.NoteDesc"></textarea>
                </div>
            </div>
            <button class="button button-full" ng-click="share()">
                Share to your friends
            </button>
    </ion-content>
</ion-view>
