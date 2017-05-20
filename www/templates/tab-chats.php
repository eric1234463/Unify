<style>
.chatname {
    font-size: 16px;
    color: #009688;
    font-weight: bold;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.chatmessage {
    color: #aaa;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.match {
    min-width: 70px;
    min-height: 75px;
    background-color: #F06292
}

.delete {
    min-width: 70px;
    min-height: 75px;
    background: #FF8A65
}

.button-align-right {
    text-align: right;
}
</style>
<ion-view view-title="Chats">
    <ion-nav-buttons side="right">
        <button class="button icon ion-ios-contact" ng-click="gotoContactList()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <ion-refresher pulling-text="Pull to refresh..." on-refresh="doRefresh()">
        </ion-refresher>
        <div class="button-align-right">
            <button class="button button-clear button-positive" ng-click="createGroupChat()">New Group</button>
        </div>
        <ion-list>
            <ion-item class="item item-remove-animate item-avatar item-icon-right" ng-repeat="chat in chatList" ng-click="gotochat(chat)">
                <label>
                    <div ng-if="chat.IsGroupChat === 'N'">
                        <img ng-if="chat.Icon!=='N'" ng-src="{{chat.Icon}}" class="Icon">
                        <span class="chatname">{{chat.AccountName}}</span>
                        <span class="item-note">{{chat.CreateDt | amCalendar}}</span>
                        <p style="color:#aaa">{{chat.Detail}}</p>
                        <div ng-if="chat.Unread > 0">
                            <i class="icon ion-ios7-telephone-outline"><div class="badge badge-assertive icon-badge">{{chat.Unread}}</div></i>
                        </div>
                    </div>
                    <div ng-if="chat.IsGroupChat === 'Y'">
                        <img ng-if="chat.GroupIcon!=='N'" ng-src="{{chat.GroupIcon}}" ng-click="gotochat(chat)" class="Icon">
                        <span class="chatname">{{chat.AccountName}}</span>
                        <span class="item-note">{{chat.CreateDt | amCalendar}}</span>
                        <div ng-if="chat.SenderName === chat.UserName">
                            <p style="color: #aaa;" class="chatmessage">You : <span class="chatmessage">{{chat.Detail}}</span> </p>
                        </div>
                        <div ng-if="chat.SenderName !== chat.UserName">
                            <p style="color: #aaa;" class="chatmessage">{{chat.SenderName}} : <span class="chatmessage">{{chat.Detail}}</span> </p>
                        </div>
                        <div ng-if="chat.Unread > 0">
                            <i class="icon ion-ios7-telephone-outline"><span class="badge badge-assertive icon-badge" >{{chat.Unread}}</span></i>
                        </div>
                    </div>
                    <i class="icon ion-chevron-right icon-accessory"></i>
                    <ion-option-button class="button match" ng-if="chat.IsGroupChat === 'N'" ng-click="match(chat)">
                        <i class="icon ion-heart" style="display:block;right:18px;"></i>
                        <div class="label">Match</div>
                    </ion-option-button>
                    <!--                 <ion-option-button class="button-stable" ng-click="remove(chat)" style="min-width: 70px;min-height: 75px">
                    <i class="icon ion-more" style="display:block;right:18px;"></i>
                    <div class="label">More</div>
                </ion-option-button> -->
                    <ion-option-button class="button delete" ng-click="remove(chat)" ng-if="chat.IsGroupChat === 'N'">
                        <i class="icon ion-trash-a" style="display:block;right:18px;"></i>
                        <div class="label">Delete</div>
                    </ion-option-button>
                </label>
            </ion-item>
        </ion-list>
    </ion-content>
</ion-view>
