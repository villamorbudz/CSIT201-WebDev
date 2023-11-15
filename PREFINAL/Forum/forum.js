let Pages = 0;
let userList = [];          // list of all users
let Current_Page = [];
let userData;               // 



$(document).ready(function() {
    $("#create-new-user").click(function() {
        $.ajax({
            url: 'http://hyeumine.com/forumCreateUser.php',
            method: 'POST',
            data: {
                username: $("#firstNameInput").val() + ("-") + $("#lastNameInput").val()
            },
            success:(data)=>{
                userData = JSON.parse(data);
                const username = userData.username;
                const id = userData.id;
    
                const userObject = {
                    username: username,
                    id: id
                };

                if(userObject.username === '-') {
                    alert("Error: please enter a name!");
                    return;
                }
                userList.push(userObject);
                alert("User successfully created!");
                loginPage();

                console.log(data);
                console.log('UserList:', userList);
            }
        });
    });
    
    $("#login").click(function() {
        $.ajax({
            url: 'http://hyeumine.com/forumLogin.php',
            method: 'POST',
            data: {
                username: $("#fNameInput").val() + ("-") + $("#lNameInput").val()
            },
            success: function(data) {
                let res = JSON.parse(data);
    
                if (!res.success || res.user.username === "-") {
                    alert("Error: Login unsuccessful. Try again");
                    return;
                }

                if(validateLogin(res.user.username)) {
                    alert("Login Successful");
                    homePage();
                    console.log(res.user);
                    console.log(res.user.username);
                    GetPosts(1);
                } else {
                    alert("Error: Login unsuccessful. Try again");
                    return;
                }
            }
        });
    });

        $("#create-post").click(function(){
            postText =  $("#new-post-text").val();
            $.ajax({
                url:`http://hyeumine.com/forumNewPost.php`,
                method:"Post",
                data:{id:parseInt(userData.id),post:postText},
                success:(data)=>{
                    console.log(data);
                }
            });
        });
    
    let Forum_Posts = [];

    function GetPosts(pagenum){
        $.ajax({
            url:`http://hyeumine.com/forumGetPosts.php`,
            method:"Get",
            data:{page:pagenum},
            success:(data)=>{
                ReplyingPost = [];
                ReplyCount = 0;
                $("#posts").remove();
                $("#posts-container").append(`<div id="posts"></div>`);
                
                Forum_Posts = JSON.parse(data);
                Current_Page = Forum_Posts;

                if(Forum_Posts.length == 0){
                    alert("No Pages Left");
                    return 0;
                }

                Card_Numbers = 0;
                for(Indv of Forum_Posts){
                    ParseUsername(Indv);
                    test = DetectScipts(Indv);
                    createCards(test, "posts");
                    Card_Numbers++;
                }
                Waiting = 1;
            }
        });
        return 1;
    }
});

function validateLogin(usernameIn) {
    return userList.some(function(user) {
      return user.username === usernameIn;
    });
}

function sanitizeString(input) {
    return input.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/,/g, '&#44;').replace(/\./g, '.').replace(/@/g, 'q').replace(/ /g, '-');
}

function sanitizeUserData() {
    sanitizeString(userData.username);
}

function ParseUsername(postList) {
    if(!(Object.keys(userList).includes(postList.uid))){
        userList[postList.uid] = postList.user;
    }
}

function createNewUser() {
    var createUserPage = document.getElementById('create-user-page');
    createUserPage.style.display = 'block';

    var LoginPage = document.getElementById('user-login-page');
    LoginPage.style.display = 'none';
}

function loginPage() {
    var loginPage = document.getElementById('user-login-page');
    loginPage.style.display ='block';

    var createUserPage = document.getElementById('create-user-page');
       createUserPage.style.display = 'none';
    }

function homePage() {
    var homePage = document.getElementById('user-login-page');
    homePage.style.display ='none';

    var newPostPage = document.getElementById('create-new-post');
    newPostPage.style.display ='none';

    var forumPage = document.getElementById('forum-page');
    forumPage.style.display ='block';   
}

function newPost() {
    var newPostPage = document.getElementById('create-new-post');
    newPostPage.style.display ='block';

    var homePage = document.getElementById('forum-page');
    homePage.style.display ='none';
}

function ParseUsername(postList) {
    if(!(Object.keys(userList).includes(postList.uid))){
        userList[postList.uid] = postList.user;
    }
}

let Card_Numbers = 0;
let ReplyingPost = [];
let ReplyCount = 0;
let hasReplies;

function createCards(info, base){
    hasReplies = 0;

    $(`#${base}`).append(`
        <div id = "${sanitizeString(info.id)}" class = "card"></div>    
    `);

    if(Object.keys(info).includes("reply")){
        ReplyingPost.push(info.reply); 
        console.log("has Replies");
        hasReplies = 1;
    }

    $(`#${info.id}`).append(`
    <div id = "div${info.id}"style="margin-top: 2 0px; background-color: #f0f0f0;">
        <span style="margin-left: 25px; font-size: 25px; font-weight: bold; color: blue; background-color: #f0f0f0;">
            ${sanitizeString(info.user)}
        </span>
        <span style="font-size: 16px; font-weight: bold; color: gray; background-color: #f0f0f0;">
            ${"@"}
            ${sanitizeString(info.user)}
            ${sanitizeString(info.id)}
        </span>
        <span style="font-size: 14px; font-weight: normal; color: grey; background-color: #f0f0f0;">
            ${"•"}
            ${sanitizeString(info.date)}
        </span>
        <p style="margin-top: 0; margin-bottom: 0; margin-left: 25px; font-size: 25px; background-color: #f0f0f0;"class = "card-title">
            ${sanitizeString(info.post)}
        </p>
    </div>
    <div class = "post-buttons">
    <button type="submit">Reply</button>
    <button type="submit">Delete</button>
    </div>
    `);
}

function DetectScipts(info) {
    postinfo = JSON.parse(JSON.stringify(info));
    for(some in info){
        if(typeof(info[some] == String)){
            if(info[some][0] == "<"){
                postinfo[some] = "Blocked";
                alert("Script Detected: "+ some);
                console.log(info);
            }
        }
    }
    return postinfo;
}