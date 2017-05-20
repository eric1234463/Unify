<ion-view hide-nav-bar="true">
    <ion-content class="background">
        <div class="row" style="margin-bottom:5%;margin-top: 5%">
            <div class="col col-25"></div>
            <div class="col col-50 col-center">
                <img src="img/icon.png" style="width:100%;margin: auto;" />
            </div>
            <div class="col col-25"></div>
        </div>

        <div class="row">
            <div class="col col-20"></div>
            <div class="col col-60 col-center" style="text-align:center">
                <span class="AppsTitle">UniFy</span>
            </div>
            <div class="col col-20"></div>
        </div>
        <!--
        <hr style="width:90%">
        <div class="row">
            <div class="col col-5"></div>
            <div class="col col-90 col-center" style="text-align:center">
                <span class="Slogan">The
                    <span class="SloganText">U</span>niversity's
                <span class="SloganText">N</span>etwork
                <span class="SloganText">I</span>nteraction
                <span class="SloganText">F</span>or
                <span class="SloganText">Y</span>outh
                </span>
            </div>
            <div class="col col-5"></div>
        </div>
        <hr style="width:90%">
        -->
        <form ng-submit="formlogin()">
            <div class="list list-inset login">
                <label class="item item-input" id="username">
                    <i class="icon ion-person loginIcon"></i>
                    <input type="text" placeholder="Username" ng-model="data.username" ng-focus="usernameFocus()" ng-blur="usernameBlur()">
                </label>
                <br>
                <label class="item item-input" id="password">
                    <i class="icon ion-locked loginIcon"></i>
                    <input type="password" placeholder="Password" ng-model="data.password" ng-focus="passwordFocus()" ng-blur="passwordBlur()">
                </label>
            </div>
            <button class="button loginbutton" type="submit" >Login</button>
        </form>
        <button class="button loginbutton" ng-click="createNewUser()" >Sign Up</button>
    </ion-content>
</ion-view>