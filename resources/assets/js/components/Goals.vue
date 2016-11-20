<template>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <h3>Goals <button v-if="!addActive" class="btn btn-sm btn-success pull-right" v-on:click="toggleAdd">New Goal</button></h3>
            <div class="filters-holder">
                <span v-bind:class="{ active: hash=='all' }" v-on:click="showGoals('all')">all</span>
                | <span v-bind:class="{ active: hash=='active' }" v-on:click="showGoals('active')">active</span>
                | <span v-bind:class="{ active: hash=='completed' }" v-on:click="showGoals('completed')">completed</span>
            </div>
            <div v-if="addActive">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" v-model="newGoal.title" name="title" id="title">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" v-model="newGoal.description" id="description" name="description" ></textarea>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" v-model="newGoal.is_money_saving">Is money saving goal</label>
                </div>

                <div v-if="newGoal.is_money_saving">
                    <div class="row form-group">
                        <div class="col-xs-12 col-sm-6">
                            <label>Target amount</label>
                            <input type="text" class="form-control" v-model="newGoal.target_amount">
                        </div>

                        <div class="col-xs-12 col-sm-6">
                            <label>Starting amount</label>
                            <input type="text" class="form-control" v-model="newGoal.amount">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-10">
                        <select class="form-control items-select-list" v-model="newGoal.current_task">
                            <option v-for="(task, taskIndex) in tasks" v-bind:value="taskIndex">{{task.title}}</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <button class="btn btn-primary pull-right" v-on:click="addTask">add task</button>
                    </div>
                </div>

                <ul class="items-list">
                    <li v-for="(task, taskIndex) in newGoal.tasks">
                        {{task.title}}
                        <span v-if="task.completions >= task.target_completions">(<span class="glyphicon glyphicon-ok completed-item"></span>)</span>
                        <span v-on:click="deleteTaskFromAddGoal(taskIndex)" class="pull-right glyphicon glyphicon-trash"></span>
                        <div v-if="task.target_completions > 0" class="progress">
                            <div v-bind:class="{complete: task.completions >= task.target_completions}" class="progress-bar" role="progressbar"  :style="'width: ' + ((task.completions/task.target_completions)*100) + '%;'"></div>
                            <div class="progress-label">{{task.completions + '/' + task.target_completions}}</div>
                        </div>
                    </li>
                </ul>

                <div class="buttons">
                    <button class="btn btn-primary pull-right" v-on:click="addNew">save</button>
                    <button class="btn btn-default pull-right" v-on:click="cancelAdd">cancel</button>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <ul id="items">
                <li v-if="goals.length > 0" v-for="(goal, index) in goals" class="list-item">
                    <div class="clearfix">
                        <div class="item-main clearfix">
                            <div class="complete-action pull-left">
                                <span v-if="!goal.completed_on" title="Complete Task" v-on:click="completeTask(index)" class="glyphicon do-goal action-item glyphicon-ok"></span>
                                <span v-if="goal.completed_on" title="Uncomplete Task" v-on:click="uncompleteTask(index)"  class="glyphicon do-goal action-item glyphicon-ok completed-item"></span>
                            </div>
                            <div v-if="!goal.edit" class="title pull-left" data-toggle="collapse" :data-target="'#collapse-goal-'+ goal.id">{{goal.title}}</div>
                            <div v-if="goal.edit" class="title pull-left form-group">
                                <input type="text" class="form-control" v-model="goals[index].title">
                            </div>
                            <div class="pull-right edit" v-on:click="toggleEdit(index)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </div>
                        </div>

                        <div v-if="goal.is_money_saving" class="money-ops row form-group-sm">
                                <div class="col-xs-4 col-sm-4 col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" v-model="goals[index].update_money_amount" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-3">
                                    <select class="form-control" v-model="goals[index].money_action">
                                        <option value="1">Deposit</option>
                                        <option value="2">Withdraw</option>
                                    </select>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-3">
                                    <button class="btn btn-primary btn-sm btn-block" v-on:click="updateMoneyAmount(index)">go</button>
                                </div>
                        </div>

                        <div v-if="goal.is_money_saving > 0" class="progress">
                            <div v-bind:class="{complete: goal.amount >= goal.target_amount}" class="progress-bar" role="progressbar"  :style="'width: ' + ((goal.amount/goal.target_amount)*100) + '%;'"></div>
                            <div class="progress-label">${{parseFloat(goal.amount).toFixed(2)}} / ${{parseFloat(goal.target_amount).toFixed(2)}}</div>
                        </div>

                        <div :class="'collapse' + (goal.edit?'in':'')" :id="'collapse-goal-' + goal.id">

                            <div v-if="!goal.edit">
                                {{goal.description}}
                            </div>

                            <div v-if="goal.edit" class="item-edit form-group">
                                <div class="delete" v-on:click="deleteTask(index, $event)"><span class="glyphicon glyphicon-trash"></span></div>
                                <textarea class="form-control" v-model="goals[index].description"></textarea>
                                <label>Target Completions</label>

                                <div class="checkbox">
                                    <label><input type="checkbox" v-model="goals[index].is_money_saving">Is money saving goal</label>
                                </div>

                                <div v-if="goal.is_money_saving">
                                    <div class="row form-group">
                                        <div class="col-xs-12 col-sm-6">
                                            <label>Target amount</label>
                                            <input type="text" class="form-control" v-model="goals[index].target_amount">
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <label>Starting amount</label>
                                            <input type="text" class="form-control" v-model="goals[index].amount">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="item-edit form-group">

                                <div v-if="goal.tasks.length > 0 || goal.edit" class="linked-items well">
                                    <h4>Linked tasks:</h4>
                                    <ul class="items-list">
                                        <li v-for="(task, taskIndex) in goal.tasks">
                                            {{task.title}}
                                            <span v-if="task.completions >= task.target_completions">(<span class="glyphicon glyphicon-ok completed-item"></span>)</span>
                                            <span v-if="goal.edit" v-on:click="removeTaskFromGoal(taskIndex, index)" class="pull-right glyphicon glyphicon-trash"></span>
                                            <div v-if="task.target_completions > 0" class="progress">
                                                <div v-bind:class="{complete: task.completions >= task.target_completions}" class="progress-bar" role="progressbar"  :style="'width: ' + ((task.completions/task.target_completions)*100) + '%;'"></div>
                                                <div class="progress-label">{{task.completions + '/' + task.target_completions}}</div>
                                            </div>
                                        </li>
                                    </ul>

                                    <div v-if="goal.edit" class="row">
                                        <div class="col-xs-12 col-md-10">
                                            <select class="form-control items-select-list" v-model="goals[index].current_task">
                                                <option v-for="(task, taskIndex) in tasks" v-bind:value="taskIndex">{{task.title}}</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <button class="btn btn-primary pull-right" v-on:click="addEditTask(index)">add task</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="goal.edit" class="item-edit form-group">
                                <div class="buttons clearfix">
                                    <button class="btn btn-primary pull-right" v-on:click="saveTask(index, $event)">Save</button>
                                    <button class="btn btn-default pull-right" v-on:click="toggleEdit(index)">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li v-if="goals.length <= 0">Add goals from the button above.</li>
            </ul>
        </div>
    </div>
</template>
<style>

</style>
<script>
    export default{
        data() {
            return {
                goals: [],
                tasks: [],
                newGoal: {
                    title: '',
                    description: '',
                    is_money_saving: false,
                    amount: 0.0,
                    target_amount: 0.0,
                    tasks: [],
                    current_task: -1
                },
                loaded: false,
                addActive: false,
                hash: 'all'
            }
    },
    created: function () {
        this.loaded = true;
        this.getHash();
        $(".container").removeClass("hide");

        this.loadGoals();

        this.$http.get('/tasks/all').then(function (response) {
            this.tasks = response.body;
        });
    },
    methods: {
        updateMoneyAmount: function(ind) {
            let moneyAction = this.goals[ind].money_action == 1 ? 'depositMoney' : 'withdrawMoney';
            this.$http.post('/actions/' + moneyAction, {goal_id: this.goals[ind].id, amount: this.goals[ind].update_money_amount}).then(function (response) {
                let item = this.goals[ind];
                item.amount = response.body.amount;
                item.update_money_amount = 0;
                Vue.set(this.goals, ind, item);
            });
        },
        getHash: function(){
            this.hash = window.location.hash.replace('#', '');
            this.hash = this.hash != ''? this.hash:'all';
        },
        loadGoals: function(){
            this.$http.get('/goals/all/' + this.hash).then(function (response) {
                for(let goalInd in response.body) {
                    response.body[goalInd].update_money_amount = 0;
                    response.body[goalInd].money_action = 1;
                }

                this.goals = response.body;
            });
        },
        showGoals: function(filter) {
            console.log(filter);
            window.location.href = '#' + filter;
            this.hash = filter;
            this.loadGoals();
        },
        completeTask: function(ind) {
            let goalsDone = true;
            let vm = this;
            for (let task in vm.goals[ind].tasks) {
                if (task.completions >= task.target_completions)
                    goalsDone = false;
            }

            if (( ! this.goals[ind].is_money_saving || this.goals[ind].amount >= vm.goals[ind].target_amount) && goalsDone) {
                this.$http.post('/actions/completeGoal', {goal_id: vm.goals[ind].id}).then(function (response) {
                    let item = this.goals[ind];
                    item.completed_on = response.body.completed_on;
                    Vue.set(this.goals, ind, item);

                    this.showGoals(this.hash);
                });
            } else {
                alert("There are uncomplete objectives in this goal")
            }
        },
        uncompleteTask: function(ind) {
            let vm = this;

            this.$http.post('/actions/uncompleteGoal', {goal_id: vm.goals[ind].id}).then(function (response) {
                let item = this.goals[ind];
                item.completed_on = response.body.completed_on;
                Vue.set(this.goals, ind, item);

                this.showGoals(this.hash);
            });
        },
        addTask: function(){
            if (this.newGoal.current_task >= 0) {
                this.newGoal.tasks.unshift(this.tasks[this.newGoal.current_task]);
                this.newGoal.current_task = -1;
            }
        },
        addEditTask: function(ind){
            if (this.goals[ind].current_task >= 0) {
                this.goals[ind].tasks.unshift(this.tasks[this.goals[ind].current_task]);
                this.goals[ind].current_task = -1;
            }
        },
        deleteTaskFromAddGoal: function(ind) {
            this.newGoal.tasks.splice(ind, 1);
        },
        toggleEdit: function(ind) {
            let item = this.goals[ind];
            item.edit = !this.goals[ind].edit;
            Vue.set(this.goals, ind, item);
        },
        toggleAdd: function() {
            this.addActive = !this.addActive;
        },
        saveTask: function(ind, el) {
            let t = $(el.target);
            let goal = JSON.parse(JSON.stringify(this.goals[ind]));
            let vm = this;
            this.$http.patch('/goals/update', goal).then(function (response) {
                vm.toggleEdit(ind);
            });
        },
        deleteTask: function(ind) {
            let vm = this;
            this.$http.delete('/goals/' + this.goals[ind].id).then(function (response) {
                console.log(response);
                vm.goals.splice(ind, 1);
            });
        },
        cancelAdd: function(){
            this.newGoal = {
                title: '',
                description: '',
                is_money_saving: false,
                amount: 0.0,
                target_amount: 0.0,
                tasks: [],
                current_task: -1
            };

            this.toggleAdd();
        },
        addNew: function(){
            let vm = this;

            if (this.newGoal.title == '') {
                alert("Please fill in the title");
                return false;
            }

            this.$http.post('/goals', this.newGoal).then(function (response) {
                vm.cancelAdd();

                if (response.body.id && response.body.id > 0)
                    vm.goals.unshift(response.body);
            });
        },
        removeTaskFromGoal: function(taskInd, goalInd) {
        console.log(taskInd, goalInd);
            this.goals[goalInd].tasks.splice(taskInd, 1);
        }
    }
}
</script>
