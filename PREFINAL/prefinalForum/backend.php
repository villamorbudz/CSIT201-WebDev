<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$usersJSON = 'data/users.json';
$postsJSON = 'data/posts.json';
$commentsJSON = 'data/comments.json';
$_SESSION = [
    'isLoggedIn' => false,
    'loggedInUser' => [],
    ];

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Create New User
    if($action === 'create-new-user') {
        $userList = getUserList();
        $response = [];
        $new_user = [];
    
        $user_firstName = isset($_POST["firstName"]) ? htmlspecialchars($_POST["firstName"]) : "";
        $user_lastName = isset($_POST["lastName"]) ? htmlspecialchars($_POST["lastName"]) : "";
        $user_name = htmlspecialchars($user_firstName . " " . $user_lastName);
        $user_username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : "";
        $user_email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : "";
        $user_street = isset($_POST["street"]) ? htmlspecialchars($_POST["street"]) : "";
        $user_barangay = isset($_POST["barangay"]) ? htmlspecialchars($_POST["barangay"]) : "";
        $user_city = isset($_POST["city"]) ? htmlspecialchars($_POST["city"]) : "";
    
        if(empty($user_firstName) 
            && empty($user_lastName) 
            && empty($user_username) 
            && empty($user_email) 
            && empty($user_street) 
            && empty($user_barangay) 
            && empty($user_city)) {
            echo json_encode([
                "success" => false,
                "message" => "Please fill out all the fields."
            ]);
            exit();
        } else {
            $userAlreadyExists = false;
            foreach($userList as $user) {
                if($user['username'] === $user_username) {
                    $userAlreadyExists = true;
                    break;
                }
            }
    
            if($userAlreadyExists) {
                echo json_encode([
                    "success" => false,
                    "message" => "User already exists, please choose a different username."
                ]);
                exit();
            }
    
            $user_id = count($userList) + 1;
            $new_user = [
                "id" => $user_id,
                "name" => $user_name,
                "username" => $user_username,
                "email" => $user_email,
                "address" => [
                    "street" => $user_street,
                    "barangay" => $user_barangay,
                    "city" => $user_city
                ]   
            ];
    
            array_push($userList, $new_user);
            $json = json_encode($userList, JSON_PRETTY_PRINT);
            $fileName = "data/users.json";
            file_put_contents($fileName, $json);
    
            echo json_encode([
                "success" => true,
                "message" => "User successfully created!",
                "user" => $new_user
            ]);
            exit();
        }
        
    }
    
    // Login
    if($action === 'user-login') {
        $userList = getUserList();
        $userLogin_username = isset($_POST['username']) ? htmlspecialchars($_POST["username"]) : "";
    
        if (empty($userLogin_username)) {
            echo json_encode([
                "success" => false,
                "message" => "Please enter a username!",
                "userInfo" => $_SESSION
            ]);
            exit();
        } else {
            foreach($userList as $users) {
                if($userLogin_username === $users['username']) {
                    $_SESSION['isLoggedIn'] = true;
                    $_SESSION['loggedInUser'] = $users;
                    echo json_encode([
                        "success" => true,
                        "message" => "Successfully logged in!",
                        "userInfo" => $_SESSION,
                    ]);
                    exit();
                }
            }    

            if (!$_SESSION['isLoggedIn']) {
                echo json_encode([
                    "success" => false,
                    "message" => "User does not exist, please try again.",
                    "userInfo" => $_SESSION
                ]);
                exit();
            }
        }
    }

    // New Post
    if($action === 'create-post') {
        $postList = getPostList();
        $new_post = [];

        $newPost_author = $_SESSION['loggedInUser'];
        $newPost_title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "";
        $newPost_body = isset($_POST["body"]) ? htmlspecialchars($_POST["body"]) : "";
        $newPost_uid = isset($_POST["uid"]) ? (int)$_POST["uid"] : "";

        if(!empty($newPost_title) && !empty($newPost_body)) {
            $post_id = count($postList) + 1;

            $new_post = [
                "uid" => $newPost_uid,
                "id" => $post_id,
                "title" => $newPost_title,
                "body" => $newPost_body
            ];

            array_push($postList, $new_post);
            $json = json_encode($postList, JSON_PRETTY_PRINT);
            $fileName = "data/posts.json";
            file_put_contents($fileName, $json);

            echo json_encode([
                "success" => true,
                "message" => "Post successfully created!",
                "postInfo" => $new_post
            ]);
            exit();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Please fill out all the fields.",
                "postInfo" => null
            ]);
            exit();
        }
    }

    if($action === 'create-comment') {
        $commentList = getCommentList();
        $new_comment = [];

        $newComment_postID = isset($_POST["postID"]) ? (int)$_POST["postID"] : "";
        $newComment_name = isset($_POST["uName"]) ? htmlspecialchars($_POST["uName"]) : "";
        $newComment_email = isset($_POST["uEmail"]) ? htmlspecialchars($_POST["uEmail"]) : "";
        $newComment_body = isset($_POST["body"]) ? htmlspecialchars($_POST["body"]) : "";

        if(!empty($newComment_body)) {

            $comment_id = count($commentList) + 1;
            $new_comment = [
                "postId" => $newComment_postID,
                "id" => $comment_id,
                "name" => $newComment_name,
                "email" => $newComment_email,
                "body" => $newComment_body
            ];

            array_push($commentList, $new_comment);
            $json = json_encode($commentList, JSON_PRETTY_PRINT);
            $fileName = "data/comments.json";
            file_put_contents($fileName, $json);

            echo json_encode([
                "success" => true,
                "message" => "Comment successfully created!",
                "commentInfo" => $new_comment
            ]);
            exit();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Please enter some text.",
                "commentInfo" => null
            ]);
            exit();
        }
    }

    // Get Users
    if($action === 'get-users') {
        $userList = getUserList();
        echo json_encode($userList);
    }

    // Get Posts
    if($action === 'get-posts') {
        $postsList = getPostList();
        echo json_encode($postsList);
    }

    // Get Comments
    if($action === 'get-comments') {
        $commentList = getCommentList();
        echo json_encode($commentList);
    }

    if($action === 'delete-post') {
        $postList = getPostList();
        $postID = isset($_POST["id"]) ? (int)$_POST["id"] : "";
        foreach($postList as $key => $post) {
            if($post['id'] == $postID) {
                unset($postList[$key]);
            $json = json_encode($postList, JSON_PRETTY_PRINT);
                $fileName = "data/posts.json";
                file_put_contents($fileName, $json);
                echo true;
                exit();
            }
        }
        echo false;
        exit();
    }

    if($action === 'delete-post-comments') {
        $commentList = getCommentList();
        $postID = isset($_POST["id"]) ? (int)$_POST["id"] : "";
        foreach($commentList as $key => $comment) {
            if($comment['postId'] === $postID) {
                unset($commentList[$key]);
                $json = json_encode($commentList, JSON_PRETTY_PRINT);
                $json = $json === "null" ? "[]" : $json;
                $fileName = "data/comments.json";
                file_put_contents($fileName, $json);
                echo true;
            }
        }
        echo false;
        exit();
    }

    if($action === 'delete-comment') {
        $commentList = getCommentList();
        $commentID = isset($_POST["id"]) ? (int)$_POST["id"] : "";
        foreach($commentList as $key => $comment) {
            if($comment['id'] === $commentID) {
                unset($commentList[$key]);
                $json = json_encode($commentList, JSON_PRETTY_PRINT);
                $json = $json === "null" ? "[]" : $json;
                $fileName = "data/comments.json";
                file_put_contents($fileName, $json);
                echo true;
                exit();
            }
        }
        echo false;
        exit();
    }
}

function getUserList() {
    global $usersJSON;
    if(!file_exists($usersJSON)) {
        return [];
    }
    $user_data = file_get_contents($usersJSON);
    $userList = json_decode($user_data, true);
    return array_values($userList); 
}

function getCommentList() {
    global $commentsJSON;
    if(!file_exists($commentsJSON)) {
        return [];
    }
    $comment_data = file_get_contents($commentsJSON);
    $commentList = json_decode($comment_data, true);
    return array_values($commentList);   
}

function getPostList() {
    global $postsJSON;
    if(!file_exists($postsJSON)) {
        return [];
    }
    $post_data = file_get_contents($postsJSON);
    $postList = json_decode($post_data, true);
    return array_values($postList); 
}
?>