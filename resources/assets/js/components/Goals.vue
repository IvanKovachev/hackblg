<template>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <h3>Goals <button v-if="!addActive" class="btn btn-sm btn-success pull-right" v-on:click="toggleAdd">New Goal</button></h3>
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
                        <button class="btn btn-primary pull-right" v-on:click="addTask">add</button>
                    </div>
                </div>

                <ul class="items-list">
                    <li v-for="(task, taskIndex) in newGoal.tasks">
                        {{task.title}} <span class="pull-right glyphicon glyphicon-trash"></span>
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
                                <span title="Complete Task" v-on:click="completeTask(index)" class="glyphicon do-goal action-item glyphicon-ok"></span>
                            </div>
                            <div v-if="!goal.edit" class="title pull-left" data-toggle="collapse" :data-target="'#collapse-goal-'+ goal.id">{{goal.title}}</div>
                            <div v-if="goal.edit" class="title pull-left form-group">

                                <input type="text" class="form-control" v-model="goals[index].title">
                            </div>
                            <div class="pull-right edit" v-on:click="toggleEdit(index)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </div>
                        </div>

                        <div v-if="goal.is_money_saving > 0" class="progress">
                            <div class="progress-bar" role="progressbar"  :style="'width: ' + ((goal.amount/goal.target_amount)*100) + '%;'"></div>
                            <div class="progress-label">${{parseFloat(goal.amount).toFixed(2)}} / ${{parseFloat(goal.target_amount).toFixed(2)}}</div>
                        </div>

                        <div :class="'collapse' + (goal.edit?'in':'')" :id="'collapse-goal-' + goal.id">

                            <div v-if="!goal.edit">{{goal.description}}</div>
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
                addActive: false
            }
    },
    created: function () {
        this.loaded = true;

        $(".container").removeClass("hide");

        this.$http.get('/goals/all').then(function (response) {
        console.log(response.body);
            this.goals = response.body;
        });

        this.$http.get('/tasks/all').then(function (response) {
            this.tasks = response.body;
        });
    },
    methods: {
        addTask: function(){
            if (this.newGoal.current_task >= 0) {
                this.newGoal.tasks.unshift(this.tasks[this.newGoal.current_task]);
                this.newGoal.current_task = -1;
            }
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
            this.$http.post('/goals', this.newGoal).then(function (response) {
                vm.cancelAdd();

                if (response.body.id && response.body.id > 0)
                    vm.goals.unshift(response.body);
            });
        }
    }
    }
</script>
