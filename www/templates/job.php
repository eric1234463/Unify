<ion-view title="Internship">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabMore()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div class="list">
            <label class="item item-input">
                <i class="icon ion-search placeholder-icon"></i>
                <input type="text" placeholder="Find a keyword to search Job" ng-model="data.keyword" ng-keyup="searchJob()" />
            </label>
            <ion-item ng-repeat="job in jobList" class="item item-icon-left" ng-click="goToJobDetail(job)">
                <i class="icon ion-briefcase"></i>
                <h2>{{ job.JobType }}</h2>
                <p>{{ job.JobTitle }}</p>
            </ion-item>
        </div>
    </ion-content>
</ion-view>
