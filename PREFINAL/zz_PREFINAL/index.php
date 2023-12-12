<?php
    session_start();
    include("backend.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        button {
            margin-left: 5px;
        }

        img {
            width: 36px;
        }

        .card {
            margin-bottom: 20px;
        }

        .author-username {
            font-size: 16px;
            color: gray;
        }

        .author-name {
            margin-left: 5px;
            font-size: 20px;
        }
        
        #body-container {
            margin-top: 35px;
            margin-left: 350px;
            margin-right: 350px;
            margin-bottom: 50px;
        }

        #createNewPost-button {
            padding-left: 25px;
            padding-right: 25px;
        }
    </style>
</head>
<header class="p-3 bg-dark">
    <div class="d-flex flex-row">
        <div class="p-2">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li class="" style="margin-right: 25px;"><h3 class="text-light">Forum<h3></li>
                    <li><a href="#" class="nav-link px-2 text-secondary" onclick="refresh();">Home</a></li>
                    <li><a href="#" class="nav-link px-2 text-white" onclick="refresh();">Posts</a></li>
                </ul>
            </div>
        </div>
        <div id="login-buttons" class="ml-auto p-2">
            <div class="text-end">
                <button type="button" class="btn btn-outline-light me-2" data-toggle="modal" data-target="#loginModal">Login</button>
                <button type="button" class="btn btn-warning" id="createNewUser" data-toggle="modal" data-target="#newUserModal">Sign-up</button>
                </div>
        </div>
        <div id="home-buttons" class="ml-auto p-2">
            <div class="text-end">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Account
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="location.reload()">Log out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</header>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <div id="body-container">
        <div id="createNewPost-container">
            <button class="btn btn-primary" type="button" id="createNewPost" data-toggle="modal" data-target="#createPostModal">Create Post</button>
        </div>
        <div id="posts-container"></div>
    </div>
    
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                        <label for="login-username">Username</label>
                        <input type="text" class="form-control" id="login-username" placeholder="Enter username">
                        </div>
                    </form>
                    <div id="login-responseMsg"></div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-dismiss="modal" data-target="#newUserModal">Create User</button>
                <button type="button" id="login-button" class="btn btn-primary">Login</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New User Modal -->
    <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="newUserModalLabel">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="col">
                                <label for="newUser-firstName">First Name</label>
                                <input type="text" class="form-control" id="newUser-firstName" placeholder="First name">
                            </div>
                            <div class="col">
                                <label for="newUser-lastName">Last Name</label>
                                <input type="text" class="form-control" id="newUser-lastName" placeholder="Last name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="newUser-username">Username</label>
                            <input type="text" class="form-control" id="newUser-username" placeholder="Enter username">
                        </div>
                        <div class="form-group">
                            <label for="newUser-email">Email</label>
                            <input type="email" class="form-control" id="newUser-email" placeholder="example@email.com">
                        </div>
                        <div class="form-group">
                            <label for="newUser-street">Street</label>
                            <input type="text" class="form-control" id="newUser-street" placeholder="Street">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="newUser-barangay">Barangay</label>
                                <input type="text" class="form-control" id="newUser-barangay" placeholder="Barangay">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="newUser-city">City</label>
                                <input type="text" class="form-control" id="newUser-city" placeholder="City">
                            </div>
                        </div>
                    </form>
                    <div id="newUser-responseMsg"></div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="create-new-user" class="btn btn-primary">Create User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="createPostModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create New Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                        <label for="createPost-title">Title</label>
                        <input type="text" class="form-control" id="createPost-title" placeholder="Enter Title">
                        <label for="createPost-body">Body</label>
                        <textarea class="form-control" id="createPost-body" placeholder="Enter body text"></textarea>
                        </div>
                    </form>
                    <div id="createPost-responseMsg"></div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-dismiss="modal">Close</button>
                <button type="button" id="create-new-post" class="btn btn-primary">Create Post</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let session = {
            "isLoggedIn": false,
        };

        $(document).ready(function() {
            
            if(!session.isLoggedIn) {
                $('#loginModal').modal('show');
                $('#login-buttons').css('display', 'block');
                $('#home-buttons').css('display', 'none');
                $('#createNewPost').css('display', 'none');
            }

            $('#create-new-user').click(function(){
                $.ajax({
                    url: 'backend.php',
                    method: 'POST',
                    data: {
                        action: 'create-new-user',
                        firstName: $('#newUser-firstName').val(),
                        lastName: $('#newUser-lastName').val(),
                        username: $('#newUser-username').val(),
                        email: $('#newUser-email').val(),
                        street: $('#newUser-street').val(),
                        barangay: $('#newUser-barangay').val(),
                        city: $('#newUser-city').val(),
                    },
                    success: (data) => {
                        let res = JSON.parse(data);
                        let message = res.message;
                        if(res.success) {
                            $('#newUser-responseMsg').empty();
                            $('#newUser-responseMsg').append(`<div class="alert alert-success" role="alert">${res.message}</div>`);
                        } else {
                            $('#newUser-responseMsg').empty();
                            $('#newUser-responseMsg').append(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
                        }

                        console.log("Create New User: " + data);
                        setTimeout(function() {
                            $('#newUser-responseMsg').empty();
                            if(res.success) {
                                $('#newUserModal').modal('hide');
                                setTimeout(function() {
                                    $('#loginModal').modal('show');
                                }, 200);
                            }
                        }, 3000);
                    },
                });
            });

            $('#newUserModal').on('hidden.bs.modal', function () {
                $(this).find('input').val('');
                $('#newUser-responseMsg').empty();
            });

            $('#login-button').click(function() {
                $.ajax({
                    url: 'backend.php',
                    method: 'POST', 
                    data: {
                        action: 'user-login',
                        username: $('#login-username').val(),
                    },
                    success:(data)=>{
                        let res = JSON.parse(data);
                        let message = res.message;
                        session = res.userInfo;
                        console.log(res);

                        if(res.success) {
                            $('#login-responseMsg').empty();
                            $('#login-responseMsg').append(`<div class="alert alert-success" role="alert">${res.message}</div>`);
                        } else {
                            $('#login-responseMsg').empty();
                            $('#login-responseMsg').append(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
                        }
                        setTimeout(function() {
                            $('#login-responseMsg').empty();
                            if(res.success) {
                                getPosts();
                                $('#loginModal').modal('hide');
                                $('#login-buttons').css('display', 'none');
                                $('#home-buttons').css('display', 'block');
                                $('#createNewPost').css('display', 'block');
                            }
                        }, 1500);
                    },
                });
            });

            $('#loginModal').on('hidden.bs.modal', function () {
                $(this).find('input').val('');
                $('#login-responseMsg').empty();
            });

            $('#create-new-post').click(function() {
                $.ajax({
                    url: 'backend.php',
                    method: 'POST', 
                    data: {
                        action: 'create-post',
                        title: $('#createPost-title').val(),
                        body: $('#createPost-body').val(),
                        uid: session.loggedInUser.id
                    },
                    success:(data) => {
                        console.log("NEW POST JSON: " + data);
                        let res = JSON.parse(data);
                        let message = res.message;

                        if(res.success) {
                            $('#createPost-responseMsg').empty();
                            $('#createPost-responseMsg').append(`<div class="alert alert-success" role="alert">${res.message}</div>`);
                            setTimeout(function() {   
                                $('#createPost-responseMsg').empty();
                                $('#createPostModal').modal('hide')
                                refresh();
                            }, 1500);
                        } else {
                            $('#createPost-responseMsg').empty();
                            $('#createPost-responseMsg').append(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
                            setTimeout(function() {
                                $('#createPost-responseMsg').empty();
                            }, 1500);
                        }
                    },
                });
            });

            $('#createPostModal').on('hidden.bs.modal', function () {
                            $(this).find('input').val('');
                            $(this).find('textarea').val('');
                            $('#createPost-responseMsg').empty();
            });
        });

        function getPosts() {
            getUsers().then(function(userList) {
                $.ajax({
                    url: 'backend.php',
                    method: 'POST',
                    data: {
                        action: 'get-posts',
                    },
                    success: function(data) {
                        let posts = JSON.parse(data);
                        posts.reverse();
                        for (let post of posts) {
                            let postAuthor;
                            for (let user of userList) {
                                if (user.id === post.uid) {
                                    postAuthor = user;
                                    break;
                                }
                            }

                            if (postAuthor && postAuthor.name) {
                                let deleteButton = '';
                                if (post.uid === session.loggedInUser.id) {
                                    deleteButton = `<button type="button" class="btn btn-outline-danger" onclick="deletePost(${post.id});">Delete</button>`;
                                }

                                let newPost = `
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="post-details">
                                                <img src="icons/default-user-icon.png" alt="Image">
                                                <span id="post-username" class="author-name">${postAuthor.name}</span>
                                                <span id="post-uid" class="author-username">@${postAuthor.username}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 id="post-title" class="card-title">${post.title}</h5>
                                                </div>
                                            </div>
                                            <p id="post-text" class="card-text">${post.body}</p>
                                            <div class="d-flex flex-row-reverse">
                                                ${deleteButton}
                                            </div>
                                            <div id="post${post.id}-postComment-container">
                                                <div class="input-group mb-3">
                                                    <input type="text" id="comment-to-${post.id}-text" class="form-control" placeholder="Create a Comment" aria-label="Create a Comment" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="button" onclick="commentTo(${post.id});">Comment</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="comment-to-${post.id}-responseMSG"></div>
                                            <div id="post${post.id}-comment-container"></div>
                                        </div>
                                    </div>`;
                                $('#posts-container').append(newPost);
                                getComments(post.id);
                            }
                        }
                    },
                });
            });
        }

        function deletePost(postID) {
            $.ajax({
                    url: 'backend.php',
                    method: 'POST', 
                    data: {
                        action: 'delete-post',
                        id: postID
                    },
                    success:(data) => {
                        console.log("Deleted Post: " + data);
                        deletePostComments(postID);
                    },
            });
        }

        function deletePostComments(postID) {
            $.ajax({
                    url: 'backend.php',
                    method: 'POST', 
                    data: {
                        action: 'delete-post-comments',
                        id: postID
                    },
                    success:(data) => {
                        console.log("Deleted Comments: " + data);
                        refresh();
                    },
            });
        }

        function commentTo(postID) {
            let commentBody = `#comment-to-${postID}-text`;
            $.ajax({
                url: 'backend.php',
                method: 'POST', 
                data: {
                    action: 'create-comment',
                    postID: postID,
                    uName: session.loggedInUser.name,
                    uEmail: session.loggedInUser.email,
                    body: $(commentBody).val(),
                },
                success:(data) => {
                    console.log("NEW COMMENT JSON: " + data);
                    let res = JSON.parse(data);
                    let message = res.message;

                    let responseMSG = `#comment-to-${postID}-responseMSG`;
                    if(res.success) {
                        $(responseMSG).empty();
                        $(responseMSG).append(`<div class="alert alert-success" role="alert">${res.message}</div>`);
                        setTimeout(function() {   
                            $(responseMSG).empty();
                            refresh();
                        }, 1500);
                    } else {
                        $(responseMSG).empty();
                        $(responseMSG).append(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
                        setTimeout(function() {
                            $(responseMSG).empty();
                        }, 1500);
                    }
                },
            });
        }

        function deleteComment(commentID) {
            $.ajax({
                    url: 'backend.php',
                    method: 'POST', 
                    data: {
                        action: 'delete-comment',
                        id: commentID
                    },
                    success:(data) => {
                        console.log("Deleted Comment: " + data);
                        refresh();
                    },
            });
        }

        function getComments(postID) {
            getUsers().then(function(userList) {
                $.ajax({
                    url: 'backend.php',
                    method: 'POST',
                    data: {
                        action: 'get-comments',
                    },
                    success: function(data) {
                        let comments = JSON.parse(data);
                        comments.reverse()
                        for (let comment of comments) {
                            if (comment.postId === postID) {
                                let commentAuthor;
                                for (let user of userList) {
                                    if (user.name === comment.name) {
                                        commentAuthor = user.username;
                                        break;
                                    }
                                }

                                let deleteButton = '';
                                if (comment.name === session.loggedInUser.name) {
                                    deleteButton = `<button type="button" class="btn btn-outline-danger" onclick="deleteComment(${comment.id});">Delete</button>`;
                                }

                                let commentContainerId = `#post${postID}-comment-container`;
                                let newComment = `
                                    <div class="card">
                                        <div class="card-body">
                                            <img src="icons/default-user-icon.png" alt="Image">
                                            <span id="comment${comment.id}-username" class="author-name">${comment.name}</span>
                                            <span id="comment${comment.id}-uid" class="author-username">@${commentAuthor}</span>
                                            <p id="comment${comment.id}-body" class="card-text">${comment.body}</p>
                                            <div class="d-flex flex-row-reverse">
                                                ${deleteButton}
                                            </div>
                                        </div>
                                    </div>`;
                                $(commentContainerId).append(newComment);
                            }
                        }
                    }
                });
            });
        }

        function getUsers() {
            return new Promise(function(resolve) {
                $.ajax({
                    url: 'backend.php',
                    method: 'POST',
                    data: {
                        action: 'get-users',
                    },
                    success: function(data) {
                        let users = JSON.parse(data);
                        resolve(users);
                    },
                });
            });
        }

        function refresh() {
            $('#posts-container').empty();
            setTimeout(function() { getPosts(); }, 200);
        }
    </script>
</body>
</html>