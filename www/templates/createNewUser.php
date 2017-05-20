<ion-view>
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToPreviousPage()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-content class="createNewuser">
        <div class="list list-inset">
            <div class="item createNewuser">
                <label class="item item-input item-select">
                    School You Study
                    <select ng-model="createNewUser.school" ng-options="school.SchoolID as school.SchoolName for school in schools" ng-mousedown="getMasterDataForStudy" ng-change="getMasterDataForStudy()">
                    </select>
                </label>
            </div>
            <div class="item createNewuser">
                <label class="item item-input item-select">
                    Program You Study
                    <select ng-model="createNewUser.program" ng-options="program.ProgramID as program.ProgramName for program in programs" ng-disabled="createNewUser.school==undefined">
                    </select>
                </label>
            </div>
            <div class="item createNewuser">
                <label class="item item-input item-select">
                    Year of Entry
                    <select ng-model="createNewUser.year">
                        <option selected></option>
                        <option value="2000">2000</option>
                        <option value="2001">2001</option>
                        <option value="2002">2002</option>
                        <option value="2003">2003</option>
                        <option value="2004">2004</option>
                        <option value="2005">2005</option>
                        <option value="2006">2006</option>
                        <option value="2007">2007</option>
                        <option value="2008">2008</option>
                        <option value="2009">2009</option>
                        <option value="2010">2010</option>
                        <option value="2011">2011</option>
                        <option value="2012">2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                        <option value="2015">2015</option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                    </select>
                </label>
            </div>
            <div class="item createNewuser">
                <label class="item item-input item-select">
                    Gender
                    <select ng-model="createNewUser.gender">
                        <option selected></option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                </label>
            </div>

            <div class="item createNewuser">
                <input type="text" id="emailBox" placeholder="Type School Email" ng-model="createNewUser.email">
                <input type="text" id="domainBox" ng-model="createNewUser.emaildomain" disabled="true">
            </div>
        </div>
        <button class="button button-block" ng-click="nextTophoneinfo()">
            Next
        </button>
    </ion-content>
</ion-view>
