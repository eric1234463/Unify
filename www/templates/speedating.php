<ion-view title="Touching Fate">
    <ion-nav-buttons side="left">
        <button class="button icon ion-ios-arrow-back" ng-click="backToTabLife()" id="iconbutton"></button>
    </ion-nav-buttons>
    <ion-nav-buttons side="right">
        <button class="button" id="iconbutton" ng-click="CreateNewDating()">
            Submit
        </button>
    </ion-nav-buttons>
    <ion-content id="tabLifeBackground">
        <div class="card padding-top">
            <label class="item item-input item-stacked-label">
                <span class="input-label">Location</span>
                <input type="text" placeholder="Please input the location you want to meet" ng-model="data.Location">
            </label>
            <label class="item item-input item-select">
                Program
                <select ng-model="data.program" ng-options="program.ProgramID as program.ProgramName for program in programs">
                </select>
            </label>
            <label class="item item-input item-select">
                Gender
                <select ng-model="data.gender">
                    <option value=""></option>
                    <option value="M">M</option>
                    <option value="F">F</option>
                </select>
            </label>
            <div class="item item-icon-left" ionic-datepicker input-obj="datepickerObject">
                <i class="icon ion-ios-calendar positive col col-30"></i> Date:
                <strong>    
                    {{ datepickerObject.inputDate | date: 'dd MMMM yyyy' }}
                </strong>
            </div>
            <div class="item item-icon-left" ion-datetime-picker time am-pm ng-model="timetable.from">
                <i class="icon ion-log-in"></i> From:
                <strong>{{ timetable.from | date: "HH:mm a" }}</strong>
            </div>
            <div class="item item-icon-left" ion-datetime-picker time am-pm ng-model="timetable.to">
                <i class="icon ion-log-out"></i> To:
                <strong>{{ timetable.to | date: "HH:mm a"  }}</strong>
            </div>
            <div class="item item-body">
                <label class="item item-input">
                    <textarea rows="10" ng-model="$parent.Message" placeholder="Message You Want to Say"></textarea>
                </label>
                <br/>
            </div>
        </div>
    </ion-content>
</ion-view>
