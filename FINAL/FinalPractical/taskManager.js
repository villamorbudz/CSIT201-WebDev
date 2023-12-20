var TaskQueue = [];
var HPQueue1 = [];
var RQueue2 = [];
var RQueue3 = [];
var RQueue4 = [];
var tasksCTR = 0;

function addRandomTask() {
    tasksCTR++;
    var newTask = {
        id: tasksCTR,
        number: randomNumber(1, 200),
        type: randomTaskType()
    }; 

    TaskQueue.push(newTask);
    displayTaskQueue();
    console.log(TaskQueue);
}

function displayTaskQueue() {
    var taskQueueContainer = document.getElementById('taskQueue-container');
    taskQueueContainer.innerHTML = '';

    for(var i = 0; i < TaskQueue.length; i++) {
        if(TaskQueue[i].type === "highPriority") {
            var newHPTask = `<span id="${TaskQueue[i].id}-task" class="highPriority-task">${TaskQueue[i].number}</span>`;
            $('#taskQueue-container').append(newHPTask);
        } else if(TaskQueue[i].type === "regular") {
            var newRTask = `<span id="${TaskQueue[i].id}-task" class="regular-task">${TaskQueue[i].number}</span>`;
            $('#taskQueue-container').append(newRTask);
        }
    }
}

function randomTaskType() {
    var randomNum = randomNumber(1, 10);
    if(randomNum === 3) {
        return "highPriority";
    } else {
        return "regular";
    }
}

function randomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function admitTask() {
    var intervalId = setInterval(function () {
        if (TaskQueue.length > 0) {
            var task = TaskQueue[0];

            if (task.type === "highPriority") {
                HPQueue1.push(task);
                $('#HPQueue1-task-container').append(`<span id="${task.id}-task" class="highPriority-task">${task.number}</span>`);
                console.log(HPQueue1);
            } else {
                var minQueue = getMinQueue();
                minQueue.push(task);
                $(`#${getQueueName(minQueue)}-task-container`).append(`<span id="${task.id}-task" class="regular-task">${task.number}</span>`);
                console.log(RQueue2);
                console.log(RQueue3);
                console.log(RQueue4);
            }

            TaskQueue.splice(0, 1);
            displayTaskQueue();
            console.log(task);
        } else {
            clearInterval(intervalId)
        }
    }, 500);
}

function performTasks() {   // to implement
    var intervalId = setInterval(function () {
        if (TaskQueue.length > 0) {
            var task = TaskQueue[0];

            if (task.type === "highPriority") {
                HPQueue1.push(task);
                $('#HPQueue1-task-container').append(`<span id="${task.id}-task" class="highPriority-task">${task.number}</span>`);
                console.log(HPQueue1);
            } else {
                var minQueue = getMinQueue();
                minQueue.push(task);
                $(`#${getQueueName(minQueue)}-task-container`).append(`<span id="${task.id}-task" class="regular-task">${task.number}</span>`);
                console.log(RQueue2);
                console.log(RQueue3);
                console.log(RQueue4);
            }

            TaskQueue.splice(0, 1);
            displayTaskQueue();
            console.log(task);
        } else {
            clearInterval(intervalId)
        }
    }, 500);
}


function getMinQueue() {
    var queues = [RQueue2, RQueue3, RQueue4];
    
    var minQueue = queues.reduce(function (prev, current) {
        return prev.length <= current.length ? prev : current;
    });

    return minQueue;
}

function getMaxQueue() {
    var queues = [RQueue2, RQueue3, RQueue4];
    
    var maxQueue = queues.reduce(function (prev, current) {
        return prev.length >= current.length ? prev : current;
    });

    return maxQueue;
}


function getQueueName(QueueName) {
    if(QueueName== RQueue2) {
        return "RQueue2";
    } else if (QueueName == RQueue3) {
        return "RQueue3";
    } else {
        return "RQueue4";
    }
}