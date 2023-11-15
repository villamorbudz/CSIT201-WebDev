let Pages = 0;
let userList = [];
let Current_Page = [];
let userData;

// start

$(document).ready(function() {
    // let GeneratedTexts = [];
    let Error = {
        Login: 0
    };

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
    
                userList.push(userObject);
    
                console.log(data);
                console.log('UserList:', userList);

                alert("User successfully created!");
            }
        });
    });
    
    $("#login").click(function() {
        $.ajax({
            url: 'http://hyeumine.com/forumLogin.php',
            method: 'POST',
            data: {
                username: $("#firstNameInput").val() + ("-") + $("#lastNameInput").val()
            },
            success: function(data) {
                let res = JSON.parse(data);
    
                if (!res.success) {
                    alert("Error: Login unsuccessful. Try again");
                    console.log(res);
                    Error.Login = 1;
                    return;
                }
    
                if (userData != null) {
                    if (userData.id !== res.user.id && userData.username !== res.user.username) {
                        alert("Error: Logged in to a different account");
                        console.log(res);
                        Error.Login = 2;
                        return;
                    }
                }
                console.log(res);
                alert("Login Successful");
                homePage();
                GetPosts(1);
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
                console.log(data);
                $("#posts").remove();
                $("#posts-container").append(`<div id="posts"></div>`);
                
                Forum_Posts = JSON.parse(data);
                Current_Page = Forum_Posts;

                if(Forum_Posts.length == 0){
                    alert("No Pages Left");
                    return 0;
                }
                
                Card_Numbers = 0;
                console.log(Current_Page);
                for(Indv of Forum_Posts){
                    //console.log(Indv);
                    ParseUsername(Indv);
                    test = DetectScipts(Indv);
                    createCards(test, "posts");
                    Card_Numbers++;
                }
                console.log(Forum_Posts);
                // LoadUsers();
                Waiting = 1;
            }
        });
        return 1;
    }
});

function sanitizeString(input) {
    return input.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/,/g, '&#44;').replace(/\./g, '.').replace(/@/g, 'q').replace(/ /g, '-');
}

function sanitizeUserData() {
    sanitizeString(userData.username);
}

function ParseUsername(postList){
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

    var homePage = document.getElementById('forum-page');
    homePage.style.display ='block';
    
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
        <span style=" margin-left: 25px; font-size: 25px; font-weight: bold; color: blue; background-color: #f0f0f0;">
            ${sanitizeString(info.user)}
            #${sanitizeString(info.id)}
        </span>
        <span style="font-size: 15px; font-weight: normal; color: grey; background-color: #f0f0f0;">
            ${sanitizeString(info.date)}
        </span>
        <p style="margin-left: 30px; margin-bottom: 0; font-size: 25px; background-color: #f0f0f0;"class = "card-title">${sanitizeString(info.post)}</p>
    </div>
    <div class = "post-buttons">
    <button type="submit">Reply</button>
    <button type="submit">Delete</button>
    </div>
    `);
}

function DetectScipts(info){
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

function newPost() {
    var homePage = document.getElementById('forum-page');
    homePage.style.display ='none';

    var createPostPage = document.getElementById('create-new-post');
    createPostPage.style.display = 'block';
}