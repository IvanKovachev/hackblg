
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
var $ = require('jquery');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(require('vue-resource'))
Vue.component('task-item', require('./components/TasksItem.vue'));


const tasks = new Vue({
    el: '#app-tasks',
    data: {
        tasks: []
    },
    created: function () {
        console.log("pop");
        // make sure you have vue-resource inlcued in your html head
        this.$http.get('/tasks/all').then(function (response) {
            this.tasks = response.body;
            console.log(response);
        })
    },
    methods: {
        doTask: function(el) {
            // console.log(el, this);
            let taskId = $(el.target).parents('.task-item').first().attr("data-id");
            Task.doTask(taskId);
        },
        undoTask: function(el) {
            // console.log(el, this);
            let taskId = $(el.target).parents('.task-item').first().attr("data-id");
            Task.undoTask(taskId);
        }
    }
});

var Task = {
    doTask: function(taskId){
        let p = this;
        $.post('/actions/doTask', {task_id: taskId, _token: Laravel.csrfToken}, function(resp){
            console.log(resp);
            p.updateProgress(resp);
        })
    },
    undoTask: function(taskId){
        let p = this;
        $.post('/actions/undoTask', {task_id: taskId, _token: Laravel.csrfToken}, function(resp){
            console.log(resp);
            p.updateProgress(resp);
        })
    },
    updateProgress: function(task) {
        let progress = $(".task-item[data-id='" + task.id + "']").find(".progress-bar").first();
        progress.css({
            width: ((task.completions/task.target_completions)*100) + "%"
        }).html(task.completions + "/" + task.target_completions);
    }
}


// Events->
// $(function(){
//     $("#tasks").on("click", ".do-task", function(){
//         console.log(Laravel.csrfToken);
//         let taskId = $(this).parents('.task-item').first().attr("data-id");
//         Task.doTask(taskId);
//     });
//
//     $("#tasks").on("click", ".undo-task", function(){
//         let taskId = $(this).parents('.task-item').first().attr("data-id");
//         Task.undoTask(taskId);
//     });
//
//     $("#tasks").on("click", ".title", function(){
//         ;
//     });
// });
//<-Events
