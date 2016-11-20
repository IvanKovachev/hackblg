<template>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <h3>Tasks <button v-if="!addActive" class="btn btn-sm btn-success pull-right" v-on:click="toggleAdd">New Task</button></h3>
            <div class="filters-holder">
                <span v-bind:class="{ active: hash=='all' }" v-on:click="showTasks('all')">all</span>
                | <span v-bind:class="{ active: hash=='active' }" v-on:click="showTasks('active')">active</span>
                | <span v-bind:class="{ active: hash=='completed' }" v-on:click="showTasks('completed')">completed</span>
            </div>
            <div v-if="addActive">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" v-model="newTask.title" name="title" id="title">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" v-model="newTask.description" id="description" name="description" ></textarea>
                </div>

                <div class="form-group">
                    <label for="target_completions">Target completions</label>
                    <input type="number" class="form-control" v-model="newTask.target_completions" id="target_completions" name="target_completions">
                </div>
                <div class="buttons">
                    <button class="btn btn-primary pull-right" v-on:click="addNew">add</button>
                    <button class="btn btn-default pull-right" v-on:click="cancelAdd">cancel</button>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <ul id="items">
                <li v-if="tasks.length > 0" v-for="(task, index) in tasks" class="list-item">
                    <div class="clearfix">
                        <div class="item-main clearfix">
                            <div class="complete-action pull-left">
                                <span title="Do Task" v-on:click="doTask(index)" class="glyphicon do-task action-item glyphicon-plus"></span>
                                <span title="Undo Task" v-on:click="undoTask(index)" class="glyphicon undo-task action-item glyphicon-minus"></span>
                            </div>
                            <div v-if="!task.edit" class="title pull-left" data-toggle="collapse" :data-target="'#collapse-task-'+ task.id">
                                {{task.title}}
                                <span v-if="task.completions >= task.target_completions">(<span class="glyphicon glyphicon-ok completed-item"></span>)</span>
                            </div>

                            <div v-if="task.edit" class="title pull-left form-group">

                                <input type="text" class="form-control" v-model="tasks[index].title">
                            </div>
                            <div class="pull-right edit" v-on:click="toggleEdit(index)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </div>
                        </div>

                        <div v-if="task.target_completions > 0" class="progress">
                            <div v-bind:class="{complete: task.completions >= task.target_completions}" class="progress-bar" role="progressbar"  :style="'width: ' + ((task.completions/task.target_completions)*100) + '%;'"></div>
                            <div class="progress-label">{{task.completions + '/' + task.target_completions}}</div>
                        </div>

                        <div :class="'collapse' + (task.edit?'in':'')" :id="'collapse-task-' + task.id">

                            <div v-if="!task.edit">{{task.description}}</div>
                            <div v-if="task.edit" class="item-edit form-group">
                                <div class="delete" v-on:click="deleteTask(index, $event)"><span class="glyphicon glyphicon-trash"></span></div>
                                <textarea class="form-control" v-model="tasks[index].description"></textarea>
                                <label>Target Completions</label>
                                <input class="form-control" type="number" v-model="tasks[index].target_completions">

                                <div class="buttons clearfix">
                                    <button class="btn btn-primary pull-right" v-on:click="saveTask(index, $event)">Save</button>
                                    <button class="btn btn-default pull-right" v-on:click="toggleEdit(index)">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li v-if="tasks.length <= 0">Add tasks from the button above.</li>
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
                tasks: [],
                newTask: {
                    title: '',
                    description: '',
                    target_completions: 1
                },
                loaded: false,
                addActive: false,
                hash: 'all'
        }
    },
    created: function(){
        this.getHash();
        console.log(this.hash);

        this.loadTasks();
    },
    methods: {
        getHash: function(){
            this.hash = window.location.hash.replace('#', '');
            this.hash = this.hash != ''? this.hash:'all';
        },
        loadTasks: function(){
            this.$http.get('/tasks/all/' + this.hash).then(function (response) {
                this.tasks = response.body;
            });
        },
        showTasks: function(filter) {
            console.log(filter);
            window.location.href = '#' + filter;
            this.hash = filter;
            this.loadTasks();
        },
        doTask: function(ind) {
            let vm = this;
            this.$http.post('/actions/doTask', {task_id: vm.tasks[ind].id}).then(function (response) {
                Vue.set(this.tasks, ind, response.body);
            });
        },
        undoTask: function(ind) {
            let vm = this;
            this.$http.post('/actions/undoTask', {task_id: vm.tasks[ind].id}).then(function (response) {
                Vue.set(this.tasks, ind, response.body);
            });
        },
        toggleEdit: function(ind) {
            let item = this.tasks[ind];
            item.edit = !this.tasks[ind].edit;
            Vue.set(this.tasks, ind, item);
        },
        toggleAdd: function() {
            this.addActive = !this.addActive;
        },
        saveTask: function(ind, el) {
            let t = $(el.target);
            let task = JSON.parse(JSON.stringify(this.tasks[ind]));
            let vm = this;
            this.$http.patch('/tasks/update', task).then(function (response) {
                vm.toggleEdit(ind);
            });
        },
        deleteTask: function(ind) {
            let vm = this;
            this.$http.delete('/tasks/' + this.tasks[ind].id).then(function (response) {
                console.log(response);
                vm.tasks.splice(ind, 1);
            });
        },
        cancelAdd: function(){
            this.newTask = {
                title: '',
                description: '',
                target_completions: 0
            };

            this.toggleAdd();
        },
        addNew: function(){
            let vm = this;

            if (this.newTask.title == '') {
                alert("Please fill in the title");
                return false;
            }

            if (this.newTask.target_completions < 1) {
                alert("Target completions need to be greater than 0");
                return false;
            }

            this.$http.post('/tasks', this.newTask).then(function (response) {
                vm.cancelAdd();

                if (response.body.id && response.body.id > 0)
                    vm.tasks.unshift(response.body);
            });
        }
    }
}
</script>
