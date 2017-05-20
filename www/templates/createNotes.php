<ion-view title="Create New Notes">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToNote()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button" id="iconbutton" ng-click="CreateNote()">
            Submit
        </button>
    </ion-nav-buttons>
    <ion-content>
        <div class="list">
            <label class="item item-input item-stacked-label">
                <span class="input-label">Note Title</span>
                <input type="text" placeholder="Note Title" ng-model="Note.noteTitle">
            </label>
            <label class="item item-input item-stacked-label">
                <span class="input-label">Note Type</span>
                <input type="text" placeholder="Note Type" ng-model="Note.noteType">
            </label>
            <div class="card">
                <div class="item item-text-wrap" style="height: 300px">
                    <textarea row="5" style="width:100%;" ng-model="$parent.noteDesc"></textarea>
                </div>
            </div>
    </ion-content>
</ion-view>
