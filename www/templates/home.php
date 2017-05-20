<style>
#HomeInformation {
    color: #FFF;
    text-align: center;
    margin: 0;
    padding: 0;
    border: none;
}

#HomeTab {
    border: none;
    margin: 0;
    padding: 0;
}

#HomeProfile {
    padding: 0;
    margin: 0;
    height: 300px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    opacity: 0.7;
}

#HomeIcon {
    border-radius: 50%;
    height: 100%;
    margin-left: auto;
    margin-right: auto;
    display: block;
    width: 144px;
    opacity: 1;
}

.row {
    padding: 0;
    margin: 0
}

#title {
    height: 50px;
    $icon
}

.float-button {
    top: 0;
    background-color: #FFF;
}

.button:hover {
    color: #000;
}

#iconbutton {
    background: none;
}

.tabs {
    position: relative;
}

#date {
    line-height: 100%;
    text-align: center;
}

.PhotoContent {
    width: 100%;
}

.photo {
    width: 90%;
    border-radius: 5px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    text-align: center;
    font-weight: 900;
    font-size: 40px;
    color: #FFF;
    line-height: 300px;
    opacity: 0.8;
    margin: auto;
}

#ask {
    margin: auto;
    width: 100%;
    background: transparent;
    border-color: #FFF;
    border-width: 1px;
}
</style>
<ion-view hide-nav-bar="true">
    <ion-content>
        <div id="HomeProfile" style="{{CoverPhoto}}">
            <div class="row" id="title">
                <div class="col col-25" id="HomeInformation">
                    <button class="button icon ion-ios-arrow-back" ng-click="backToTabMore()" id="iconbutton"></button>
                </div>
                <div class="col col-50" id="HomeInformation" style="margin: auto;">
                    <div ng-bind-html="accountName"></div>
                </div>
                <div class="col col-25" id="HomeInformation">
                    <button class="button icon ion-edit" ng-if="edit=='Y'" ng-click="goToEdit()" id="iconbutton"></button>
                    <button class="button icon ion-plus" ng-if="edit=='N' && friendchecker=='N'" ng-click="AddFriend()" id="iconbutton"></button>
                    <button class="button icon ion-minus" ng-if="edit=='N' && friendchecker=='Y'" ng-click="removeFriend()" id="iconbutton"></button>
                </div>
            </div>
            <div class="row">
                <div class="col col-25">
                </div>
                <div class="col col-50" style="margin: auto;">
                    <img src="{{Icon}}" id="HomeIcon" />
                </div>
                <div class="col col-25">
                </div>
            </div>
            <div class="row" style="min-height: 50px">
                <div class="col col-25">
                </div>
                <div class="col col-50" style="margin: auto;">
                    <button class="button" ng-if="edit=='N'" id="ask" ng-click="goTAsk()">Ask Me?</button>
                </div>
                <div class="col col-25">
                </div>
            </div>
            <div class="row">
                <div class="col col-25" id="HomeInformation">
                    Friend
                    <div ng-bind-html="friendAmount"></div>
                </div>
                <div class="col col-25" id="HomeInformation">
                    Post
                    <div ng-bind-html="statusCount"></div>
                </div>
                <div class="col col-25" id="HomeInformation" ng-click="goToLikeRrank()">
                    Love
                    <div ng-bind-html="likedCount"></div>
                </div>
                <div class="col col-25" id="HomeInformation" ng-click="goToDisLikeRrank()">
                    Hate
                    <div ng-bind-html="disLikedCount"></div>
                </div>
            </div>
        </div>
        <ion-tabs class="tabs-icon-left tabs-calm">
            <ion-tab title="Profile" icon-off="ion-ios-person-outline" icon-on="ion-ios-person">
                <ion-nav-view>
                    <ul class="list">
                        <div class="item item-divider">
                            Self Introduction
                        </div>
                        <div class="item item-text-wrap" ng-bind-html="selfIntrodution">
                        </div>
                        <div class="item item-divider">
                            Basic Information
                        </div>
                        <li class="item item-icon-left">
                            <i class="icon ion-university" id="ProfileIcon" style="background:#DD2C00"></i>School
                            <span class="item-note" ng-bind-html="school"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-ios-book" id="ProfileIcon" style="background:#FF6D00"></i>Programme
                            <span class="item-note" ng-bind-html="program"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-heart" id="ProfileIcon" style="background:#263238"></i>Relationship Status
                            <span class="item-note" ng-bind-html="relationship"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon icon-heart-broken" id="ProfileIcon" style="background:#2962FF"></i>Love Experience
                            <span class="item-note" ng-bind-html="experience"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-transgender" id="ProfileIcon" style="background:#64DD17"></i>Sexual Orientation
                            <span class="item-note" ng-bind-html="sexuality"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-male" ng-if="gender=='M'" id="ProfileIcon" style="background:#FFD600"></i>
                            <i class="icon ion-female" ng-if="gender=='F'" id="ProfileIcon" style="background:#FFD600"></i> Gender
                            <span class="item-note" ng-bind-html="gender"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-iphone" id="ProfileIcon" style="background:#6200EA"></i>Telephone
                            <span class="item-note" ng-bind-html="Telephone"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-person" id="ProfileIcon" style="background:#00C853"></i>Birthday
                            <span class="item-note" ng-bind-html="birthday"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-calendar" id="ProfileIcon" style="background:#0091EA"></i>Year Of Entry
                            <span class="item-note" ng-bind-html="YearOfEntry"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon ion-android-arrow-up" id="ProfileIcon" style="background:#00B8D4"></i>Height
                            <span class="item-note" ng-bind-html="Height"></span>
                        </li>
                        <li class="item item-icon-left">
                            <i class="icon icon-weights" id="ProfileIcon" style="background:#00BFA5"></i>Weight
                            <span class="item-note" ng-bind-html="Weight"></span>
                        </li>
                    </ul>
                </ion-nav-view>
            </ion-tab>
            <ion-tab title="Life" icon-off="ion-ios-pulse" icon-on="ion-ios-pulse-strong" ng-if="edit=='Y' || friendchecker=='Y' || private =='N'">
                <ion-nav-view>
                    <ion-refresher pulling-text="Pull to refresh..." on-refresh="doRefresh()">
                    </ion-refresher>
                    <br/>
                    <div class="list card tabLifeSecretContent" ng-repeat="status in statusList">
                        <div class="item item-avatar item-icon-right">
                            <a class="icon subdued ion-edit" id="editBtn" ng-if="status.Edit == 'Y'" ng-click="editStatus(status)"></a>
                            <img ng-if="status.Icon!=='N'" ng-src="{{status.Icon}}" class="Icon">
                            <span class="ProfileInfo" ng-click="goToHome(status)"><i class="ion-person"></i>  {{status.AccountName}}</span>
                            <br>
                            <span class="ProfileInfo"><i class="ion-university"></i>  {{status.SchoolName}} - {{status.ProgramName}}</span>
                            <br>
                            <span class="ProfileInfo" am-time-ago="status.Time"></span>
                        </div>
                        <div class="item item-body">
                            <img class="full-image" ng-if="status.Photo!=='N'" ng-src="{{status.Photo}}" />
                            <span class="statusText">{{status.Status}}</span>
                        </div>
                        <div class="item tabs tabs-secondary tabs-icon-left" style="background-color:#009688;color:#FFF">
                            <a ng-class="status.YouLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="Addlike(status)">
                                <i class="icon ion-heart"><span ng-bind-html="status.Liked" style="font-size: 14px"></span></i> Love
                            </a>
                            <a ng-class="status.YouDisLike == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="AddDisLike(status)">
                                <i class="icon ion-heart-broken"><span ng-bind-html="status.DisLiked" style="font-size: 14px"></span></i> Hate
                            </a>
                            <a ng-class="status.showComment == 'Y' ? 'tab-item active' : 'tab-item '" ng-click="Comment(status)">
                                <i class="icon ion-chatbox"><span ng-bind-html="status.Comment" style="font-size: 14px"></span></i> Comment
                            </a>
                        </div>
                    </div>
                </ion-nav-view>
            </ion-tab>
            <ion-tab title="Timetable" icon-off="ion-ios-calendar-outline" icon-on="ion-ios-calendar" ng-if="edit=='Y' ||friendchecker=='Y' || private =='N'">
                <ion-nav-view>
                    <ion-list>
                        <ion-item class="item-divider">
                            School Events
                        </ion-item>
                        <ion-item class="item-icon-left item-remove-animate" ng-repeat="event in recData track by $index" ng-if="event.isPersonal == 0" item="item" ng-click="showDetailedEvent(event, event.date)">
                            <i class="icon ion-ios-book calm"></i>
                            <h2>{{ event.eventTitle }}</h2>
                            <p>{{ event.eventDescription }}</p>
                            <p>{{ event.eventLocation }}</p>
                            <p>{{ event.fromTime | date: "HH:mm a":'+0800' }} - {{ event.toTime | date: "HH:mm a":'+0800' }}</p>
                        </ion-item>
                        <ion-item class="item-divider">
                            Personal Events
                        </ion-item>
                        <ion-item class="item-icon-left item-remove-animate" ng-repeat="event in recData track by $index" ng-if="event.isPersonal == 1" ng-click="showDetailedEvent(event, event.date)">
                            <i class="icon ion-ios-bookmarks calm"></i>
                            <h2>{{ event.eventTitle }}</h2>
                            <p>{{ event.eventDescription | limitTo: 37}} ...</p>
                            <p>{{ event.eventLocation }}</p>
                            <p>{{ event.fromTime }} - {{ event.toTime }}</p>
                        </ion-item>
                    </ion-list>
                </ion-nav-view>
            </ion-tab>
            <ion-tab title="PhotoDiary" icon-off="ion-ios-book-outline" icon-on="ion-ios-book" ng-if="edit=='Y' || friendchecker=='Y' || private =='N'">
                <ion-nav-view>
                    <ul class="list">
                        <div class="row">
                            <div class="card PhotoContent">
                                <div class="item item-divider">
                                    Date
                                </div>
                                <div class="item item-text-wrap">
                                    <ionic-datepicker input-obj="datepickerObject">
                                        <button class="button button-full button-outline button-assertive">
                                            {{ datepickerObject.inputDate | date: 'dd-MM-yyyy' }}
                                        </button>
                                    </ionic-datepicker>
                                </div>
                            </div>
                        </div>
                        <div ng-repeat="Diary in DiaryList">
                            <div class="row">
                                <div class="card PhotoContent">
                                    <div class="item item-divider">
                                        Title
                                    </div>
                                    <div class="item item-text-wrap" ng-bind-html="Diary.StoryTitle">
                                    </div>
                                </div>
                            </div>
                            <div class="item item-text-wrap photo" ng-repeat="photo in Diary.StoryDiaryList" style="{{photo.PhotoSrc}}">
                                {{photo.StoryDesc}}
                            </div>
                        </div>
                    </ul>
                </ion-nav-view>
            </ion-tab>
        </ion-tabs>
</ion-view>
