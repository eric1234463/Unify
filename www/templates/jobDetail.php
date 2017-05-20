<ion-view title="Internship Detail">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToJob()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div class="card">
            <div class="item item-divider">
                Job Type
            </div>
            <div class="item item-text-wrap">
                {{job.JobType}}
            </div>
            <div class="item item-divider">
                Job Title
            </div>
            <div class="item item-text-wrap">
                {{job.JobTitle}}
            </div>
            <div class="item item-divider">
                Email
            </div>
            <div class="item item-text-wrap">
                {{job.Email}}
            </div>
            <div class="item item-divider">
                Job Description
            </div>
            <div class="item item-text-wrap">
                {{job.JobDesc}}
            </div>
        </div>
    </ion-content>
</ion-view>
