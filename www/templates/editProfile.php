<style>
#HomeIcon {
    border-radius: 50%;
    height: 100%;
    margin-left: auto;
    margin-right: auto;
    display: block;
    width: 144px;
}

#HomeProfile {
    padding: 0;
    margin: 0;
    height: 250px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
}

#input {
    background-color: #FFF;
    text-align: right;
}
</style>
<ion-view title="Edit Profile">
    <ion-nav-buttons side="left">
        <button class="button icon ion-close" ng-click="backToHome()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button icon ion-checkmark" ng-click="submitProfile()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content>
        <div>
            <ul class="list">
                <div class="item item-input-inset item-button-right">
                    <h2 class="item-input-wrapper" id="input" >
                        Cover Photo
                    </h2>
                    <button class="button button-icon ion-android-camera" ng-click="takeCoverPhoto()">
                    </button>
                </div>
                <div class="item item-input-inset">
                    <img ng-src="{{CoverPhoto}}" id="HomeProfile" ng-if="CoverPhoto!=='N'" src="">
                </div>
                <div class="item item-input-inset item-button-right">
                    <h2 class="item-input-wrapper" id="input">
                        Icon
                    </h2>
                    <button class="button button-icon ion-android-camera" ng-click="takeIcon()">
                    </button>
                </div>
                <div class="item item-input-inset">
                    <img ng-src="{{icon}}" ng-if="icon!=='N'" id="HomeIcon">
                </div>
                <div class="item item-divider">
                    Self Introduction
                </div>
                <textarea class="item item-text-wrap" style="width: 100%" ng-model="$parent.intro">
                </textarea>
                <div class="item item-divider">
                    Basic Information
                </div>
                <label class="item item-input item-stacked-label">
                    <span class="input-label">Birthday</span>
                    <ionic-datepicker input-obj="datepickerObject">
                        <button class="button button-full button-outline button-assertive">
                            {{ datepickerObject.inputDate | date: 'dd-MM-yyyy' }}
                        </button>
                    </ionic-datepicker>
                </label>
                <label class="item item-input">
                    <span class="input-label">Weight</span>
                    <input type="text" ng-model="profile.Weight" id="input">
                </label>
                <label class="item item-input">
                    <span class="input-label">Height</span>
                    <input type="text" ng-model="profile.Height" id="input">
                </label>
                <label class="item item-input">
                    <span class="input-label">Telephone</span>
                    <input type="text" ng-model="profile.Telephone" id="input">
                </label>
                <label class="item item-input">
                    <span class="input-label">Love Experience</span>
                    <input type="text" ng-model="profile.Experience" id="input">
                </label>
                <label class="item item-input item-select">
                    Relationship Status
                    <select ng-model="profile.Relationship">
                        <option selected></option>
                        <option value="Available">Available</option>
                        <option value="Not Availabl">Not Available</option>
                        <option value="Complicated">Complicated</option>
                        <option value="Occupied">Occupied</option>
                    </select>
                </label>
                 <label class="item item-input item-select">
                    Sexual Orientation
                    <select ng-model="profile.Sexuality">
                        <option selected></option>
                        <option value="N/A">N/A</option>
                        <option value="HeHe">HeHe</option>
                        <option value="HeShe">HeShe</option>
                        <option value="SheShe">SheShe</option>
                        <option value="Bi">Bi</option>
                    </select>
                </label>
            </ul>
        </div>
    </ion-content>
</ion-view>
