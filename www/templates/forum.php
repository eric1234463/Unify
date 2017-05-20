<ion-view title="Forum Name">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabMore()"></button>
    </ion-nav-buttons>
    <ion-content style="background-color:#adadad">
        <div class="row" style="padding:0;margin:0">
            <img style="height:100px;width:100%;padding:0;margin:0" src="img/banner.jpg" />
        </div>
        <div class="item tabs tabs-secondary tabs-icon-left" id="HomeTab">
            <a class="tab-item" href="#">
                <i class="icon ion-plus-circled"></i> Join
            </a>
            <a class="tab-item" href="#">
                <i class="icon ion-person-add"></i> Invite Friend
            </a>
            <a class="tab-item" href="#">
                <i class="icon ion-information-circled"></i> Information
            </a>
        </div>
        <label class="item item-input">
            <textarea placeholder="Please Write Your New Post" row="3"></textarea>
        </label>
        <div class="row" style="padding:0;margin:0;background:white">
            <button class="button" style="margin-left:75%;min-width:0px;min-height:0px">
                Submit
            </button>
        </div>
        <div class="list card">
            <div class="item item-avatar">
                <img src="img/96.png">
                <h2>Account Name</h2>
                <p>Time</p>
            </div>
            <div class="item item-body">
                <img src="img/96.png">
                <p>Status</p>
                <a href="#" class="subdued">1 Like</a>
                <a href="#" class="subdued">5 Comments</a>
            </div>
            <div class="item tabs tabs-secondary tabs-icon-left">
                <a class="tab-item" href="#">
                    <i class="icon ion-thumbsup"></i> like
                </a>
                <a class="tab-item" href="#">
                    <i class="icon ion-thumbsdown"></i> Dislike
                </a>
                <a class="tab-item" href="#">
                    <i class="icon ion-chatHomeMenu"></i> Comment
                </a>
                <a class="tab-item" href="#">
                    <i class="icon ion-share"></i> Share
                </a>
            </div>
        </div>
    </ion-content>
</ion-view>
