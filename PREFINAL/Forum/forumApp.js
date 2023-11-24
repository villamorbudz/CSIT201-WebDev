let isloggedIn = false;
let loggedInUser;
let newUserData;
let posts = [];
let page = 1;

$(document).ready(function() {

    if(!isloggedIn) {
        $('#loginModal').modal('show');
        $('#currentUser-info').empty();
    }

    $('#create-new-user').click(function(){
        $.ajax({
            url: 'http://hyeumine.com/forumCreateUser.php',
            method: 'POST', 
            data: {username: $("#new-first-name").val() + $("#new-last-name").val()},
            success:(data)=>{
                let newUser = JSON.parse(data);
                newUserData = {
                    id: newUser.id,
                    username: newUser.username
                };
                
                    if (newUser.username === "") {
                        $('#newUser-responseMsg').empty();
                        $('#newUser-responseMsg').append('<div class="alert alert-danger" role="alert">Please enter a name!</div>');
                    } else {
                        setTimeout(function() {   
                            $('#newUser-responseMsg').empty();
                            $('#newUser-responseMsg').append('<div class="alert alert-success" role="alert">User successfully created!');
                        }, 250); 
                    }

                $('#createUserModal').on('hidden.bs.modal', function (e) {
                    $('#new-first-name').val('');
                    $('#new-last-name').val('');
                    $('#newUser-responseMsg').empty();
                });
                console.log(data);
            }
        });
    });

    $('#user-login').click(function(){
        $.ajax({
            url: 'http://hyeumine.com/forumLogin.php',
            method: 'POST', 
            data: {username: $("#login-first-name").val() + $("#login-last-name").val()},
            success:(data)=>{
                let res = JSON.parse(data);
                isloggedIn = res.success;
                
                if($("#login-first-name").val() === '') {
                    $('#login-responseMsg').empty();
                    $('#login-responseMsg').append('<div class="alert alert-danger" role="alert">Please enter a name!</div>');
                } else {
                    if(isloggedIn) {
                        loggedInUser = {
                            id: res.user.id,
                            username: res.user.username
                        }
                        $('#login-responseMsg').empty();
                        $('#login-responseMsg').append('<div class="alert alert-success" role="alert">Login Successful!');
    
                        setTimeout(function() {
                            $('#loginModal').modal('hide');
                            homePage();
                            getPosts(page);
                        }, 0);
                        console.log(isloggedIn);
                        console.log(loggedInUser);
                    } else {
                        $('#login-responseMsg').empty();
                        $('#login-responseMsg').append('<div class="d-block alert alert-danger" role="alert">User does not exist.</div>');
                    }
                }

                $('#createUserModal').on('hidden.bs.modal', function (e) {
                    $('#login-first-name').val('');
                    $('#login-last-name').val('');
                    $('#login-responseMsg').empty();
                });

                setTimeout(function() {   
                    $('#login-first-name').val('');
                    $('#login-last-name').val('');
                    $('#login-responseMsg').empty();
                }, 3500);
            }
        });
    });

    $('#create-new-post').click(function(){
        $.ajax({
            url: 'http://hyeumine.com/forumNewPost.php ',
            method: 'POST', 
            data: {id: loggedInUser.id, post: $("#new-post-text").val()},
            success:(data)=>{
                if($("#new-post-text").val() === '') {
                    $('#newPost-responseMsg').empty();
                    $('#newPost-responseMsg').append('<div class="alert alert-danger" role="alert">Please enter some text.</div>');
                    deletePost(posts[0].id);
                } else {
                    $('#posts-container').empty();
                    $('#newPost-responseMsg').empty();
                    $('#newPost-responseMsg').append('<div class="alert alert-success" role="alert">Post successful.</div>');
                }

                setTimeout(function() {   
                    $("#new-post-text").val('');
                    $('#newPost-responseMsg').empty();
                }, 3500);
                getPosts(page);
                console.log(data);
                
            }
        });
    });

    // $('.btn.btn-info').click(function(){
    //     let postReply = `
            
    //     `;
    
    //     $('post${postInfo.id}-postReply-container').append(postReply);
    // });
    

    $('#sign-up').click(function(){
        $('#loginModal').modal('hide');
    });

    $("#logOut").click(function() {
        setTimeout(function() {
            location.reload();
        }, 250);
    });
});

function homePage() {
    $('#login-header').css('display', 'none');
    $('#home-header').css('display', 'block');
    $('#homePage-container').css('display', 'block');
    $('#currentUser-info').append(`<h6>Logged in as: ${loggedInUser.username}</h6>`);
}

function setUsername(inputString) {
    return inputString.replace(/[^a-zA-Z0-9]/g, '');
}

function getPosts(pageNum) {
    $('#posts-container').empty();
    console.log("Page: " + page);
    $.ajax({
        url: 'http://hyeumine.com/forumGetPosts.php',
        method: 'GET',
        data: { page: pageNum},
        success: (data) => {
            let postsData = JSON.parse(data);
            postsData.forEach((postData) => {
                let postInfo = {
                    uid: postData.uid,
                    username: setUsername(postData.user),
                    id: postData.id,
                    post: postData.post,
                    date: postData.date,
                };

                appendPost(postInfo);

                if (postData.reply && postData.reply.length > 0) {
                    postData.reply.forEach((replyData) => {
                        let replyInfo = {
                            uid: replyData.uid,
                            username: setUsername(replyData.user),
                            id: replyData.id,
                            post: replyData.reply,
                            date: replyData.date,
                        } ;

                        appendReply(postInfo.id, replyInfo);
                    });
                }
            });
        },
    });
}

function appendPost(postInfo) {
    let deleteButton = '';
    if (postInfo.uid === loggedInUser.id) {
        deleteButton = '<button type="button" class="btn btn-outline-danger">Delete</button>';
    }

    let newPost = `
        <div class="card">
            <div class="card-body">
                <span id="post-username" class="card-title">${postInfo.username}</span>
                <span id="post-uid" class="post-details">@${postInfo.username}${postInfo.uid}</span>
                <span id="post-date" class="post-details">${postInfo.date}</span>
                <p id="post-text" class="card-text">${postInfo.post}</p>
                <div class="d-flex flex-row-reverse">
                    ${deleteButton}
                </div>
                <div id="post${postInfo.id}-postReply-container">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Create Reply" aria-label="Create Reply" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">Reply</button>
                        </div>
                    </div>
                </div>
                <div id="post${postInfo.id}-reply-container"></div>
            </div>
        </div>`;

    $('#posts-container').append(newPost);
}

function appendReply(parentUid, replyInfo) {
    let deleteButton = '';
    if (replyInfo.uid === loggedInUser.id) {
        deleteButton = '<button type="button" class="btn btn-outline-danger">Delete</button>';
    }

    let replyContainerId = `#post${parentUid}-postReply-container`;

    let newReply = `
        <div class="card">
            <div class="card-body">
                <span id="reply-username" class="card-title">${replyInfo.username}</span>
                <span id="reply-uid" class="post-details">@${replyInfo.username}${replyInfo.uid}</span>
                <span id="reply-date" class="post-details">${replyInfo.date}</span>
                <p id="reply-text" class="card-text">${replyInfo.post}</p>
                <div class="d-flex flex-row-reverse">
                    ${deleteButton}
                </div>
            </div>
        </div>`;

    $(replyContainerId).append(newReply);
}




function deletePost(id) {
    $.ajax({
        url: 'http://hyeumine.com/forumDeletePost.php ',
        method: 'GET',
        data: {id: id},
        success: (data) => {
            $('#posts-container').empty();
            getPosts(page);
        },
    });
}

function getPrevPage() {
    if (page > 1) {
        page--;
        getPosts(page);
    }
}

function getNextPage() {
    page++;
    getPosts(page);
}