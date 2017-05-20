<!--
  This template loads for the 'tab.friend-detail' state (app.js)
  'friend' is a $scope variable created in the FriendsCtrl controller (controllers.js)
  The FriendsCtrl pulls data from the Friends service (service.js)
  The Friends service returns an array of friend data
-->
<style>
#messageInput {
    border-color: #009688;
    border-width: 1px;
    border-radius: 5px;
    border-style: solid;
    height: 45px;
    width: 100%;
    font-size: 20px;
}

#chatSend {
    color: #FFF;
    background-color: #009688;
    border-radius: 50%;
    font-size: 12px;
    height: 45px;
    width: 45px;
    margin-left: 5px;
}

#footer {
    height: 55px;
}

.single-title {
    color: #FFF;
}

.group-title {
    color: #FFF;
}


/* allows the bar-footer to be elastic /*
/* optionally set a max-height */


/* maxlength on the textarea will prevent /*
/* it from getting too large also */

.bar-footer {
    overflow: visible !important;
}

.bar-footer textarea {
    resize: none;
    height: 25px;
    width: 100px;
}


/* fixes an ios bug bear */

button.ion-android-send {
    padding-top: 2px;
}


/* add this to keep your footer buttons down 
at the bottom as the textarea resizes */

.footer-btn-wrap {
    position: relative;
    height: 100%;
    width: 50px;
    top: 7px;
}

.footer-btn {
    position: absolute !important;
    bottom: 0;
}

img.profile-pic {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    position: absolute;
    bottom: 3px;
}

img.profile-pic.left {
    left: 10px;
}

img.profile-pic.right {
    right: 10px;
}

.ion-email {
    float: right;
    font-size: 32px;
    vertical-align: middle;
}

.message {
    font-size: 14px;
}

.message-detail {
    white-space: nowrap;
    font-size: 12px;
}

.bar.item-input-inset .item-input-wrapper input {
    width: 100% !important;
}

.message-wrapper {
    position: relative;
}

.message-wrapper:last-child {
    margin-bottom: 10px;
}

.chat-bubble {
    border-radius: 5px;
    display: inline-block;
    margin-bottom: 5px;
    padding: 5px 10px;
    position: relative;
    max-width: 80%;
}

.chat-bubble:before {
    content: "\00a0";
    display: block;
    height: 16px;
    width: 9px;
    position: absolute;
    bottom: -7.5px;
}

.chat-bubble.left {
    background-color: #e6e5eb;
    float: left;
}

.chat-bubble.right {
    background-color: #B2DFDB;
    float: right;
}

.chat-bubble.right a.autolinker {
    color: #fff;
    font-weight: bold;
}

.user-messages-top-icon {
    font-size: 28px;
    display: inline-block;
    vertical-align: middle;
    position: relative;
    top: -3px;
    right: 5px;
}

.msg-header-username {
    display: inline-block;
    vertical-align: middle;
    position: relative;
    top: -3px;
}

.chat {
    background-color: #b2b2b2;
    color: #FFF;
    !important;
}

.name {
    margin: 0;
    font-weight: bold;
    color: #F00;
}

.bold {
    font-weight: bold;
}

.cf {
    clear: both !important;
}

a.autolinker {
    color: #3b88c3;
    text-decoration: none;
}


/* loading */

.loader-center {
    height: 100%;
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-direction: normal;
    -moz-box-direction: normal;
    -webkit-box-orient: horizontal;
    -moz-box-orient: horizontal;
    -webkit-flex-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-flex-wrap: nowrap;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    -webkit-box-pack: center;
    -moz-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-align-content: stretch;
    -ms-flex-line-pack: stretch;
    align-content: stretch;
    -webkit-box-align: center;
    -moz-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
}

.loader .ion-loading-c {
    font-size: 64px;
}



#nav-button {
    color: #FFF;
}

.checkbox {
    width: 100%;
}
.groupnName{
    font-weight: bold;
    font-size: 16px;
}
</style>
<ion-view>
    <ion-nav-title>
        <div ng-if="isGroupChat==='N'">
            <h5 class="single-title">{{SenderName}}</h5></div>
        <div ng-if="isGroupChat==='Y'">
            <h5 class="group-title"><span class="groupnName">{{SenderName}}</span><br>{{account.GroupMember}}, You</h5></div>
    </ion-nav-title>
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" id="iconbutton" ng-click="backToTabChat()"></button>
        <div ng-if="isGroupChat === 'N'">
            <img ng-src="{{account.Icon}}" class="profile-pic">
        </div>
        <div ng-if="isGroupChat === 'Y'">
            <img ng-src="{{account.GroupIcon}}" class="profile-pic">
        </div>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <div ng-if="isGroupChat === 'N'">
            <button class="button icon ion-ios-home" id="iconbutton" ng-click="goToHome(account)"></button>
        </div>
    </ion-nav-buttons>
    <ion-content class="padding" delegate-handle="myScroll">
        <div ng-repeat="message in messageList">
            <div ng-if="message.IsGroupChat === 'Y'">
                <div id="bottom" class="chat-bubble left" ng-if="message.AccountName!==message.FriendAccountName">
                    <p style="{{message.UserColor}}" class="bold">{{message.FriendAccountName}}</p>
                    <span class="bold">{{message.Detail| colonToCode}}</span>
                    <span class="message-detail">
                    <time title="{{ message.Time | amDateFormat: 'dddd, MMMM Do YYYY, h:mm a' }}">{{ message.Time | amDateFormat: 'h:mm a' }}</time>
                </span>
                </div>
                <div id="bottom" class="chat-bubble right" ng-if="message.AccountName===message.FriendAccountName">
                    <span class="bold">{{message.Detail| colonToCode}}</span>
                    <span class="message-detail">
                    <time title="{{ message.Time | amDateFormat: 'dddd, MMMM Do YYYY, h:mm a' }}">{{ message.Time | amDateFormat: 'h:mm a' }}</time>
                </span>
                </div>
                <div class="cf"></div>
            </div>
            <div ng-if="message.IsGroupChat === 'N'">
                <div id="bottom" class="chat-bubble left" ng-if="message.AccountName!==message.FriendAccountName">
                    <span class="bold">{{message.Detail| colonToCode}}</span>
                    <span class="message-detail">
                    <time title="{{ message.Time | amDateFormat: 'dddd, MMMM Do YYYY, h:mm a' }}">{{ message.Time | amDateFormat: 'h:mm a' }}</time>
                </span>
                </div>
                <div id="bottom" class="chat-bubble right" ng-if="message.AccountName===message.FriendAccountName">
                    <span class="bold">{{message.Detail| colonToCode}}</span>
                    <span class="message-detail">
                    <time title="{{ message.Time | amDateFormat: 'dddd, MMMM Do YYYY, h:mm a' }}">{{ message.Time | amDateFormat: 'h:mm a' }}</time>
                </span>
                </div>
                <div class="cf"></div>
            </div>
        </div>
    </ion-content>
    <ion-footer-bar class="bar-stable item-input-inset" id="footer" keyboard-attach>
        <textarea ng-model="messageArea" id="messageInput" /></textarea>
        </div>
        <div class="footer-btn-wrap">
            <button class="button footer-btn" id="chatSend" ng-click="send()" ng-disabled="!isDisabled">
                <i class="icon ion-android-send" style="font-size: 18px"></i>
            </button>
        </div>
    </ion-footer-bar>
</ion-view>
