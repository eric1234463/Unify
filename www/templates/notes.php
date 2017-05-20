<ion-view title="Notes">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabMore()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button button-icon icon ion-plus-round" ng-click="goTONewNote()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div class="list">
            <ion-item ng-repeat="note in noteList track by $index" ng-class="note.TypeChecker == 1 ? 'item-divider' : 'item item-icon-left '"  ng-click="goToNoteDetail(note)">
                <i ng-if="note.TypeChecker == 0" class="icon ion-ios-bookmarks calm"></i>
                <span ng-if="note.TypeChecker == 1">{{ note.NoteType }}</span>
                <p ng-if="note.TypeChecker == 0">{{ note.NoteTitle }}</p>
            </ion-item>
        </div>
    </ion-content>
</ion-view>
