<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $usersJSON = 'data/users.json';
    $eventsJSON = 'data/events.json';

    $_SESSION = [
        'isLoggedIn' => false,
        'loggedInUser' => []
    ];

    if(isset($_POST['action'])) {
        $action = $_POST['action'];

        switch($action) {
            case "create-user":
                $userList = getUserList();
                $new_user = [];
            
                $user_firstName = isset($_POST["firstName"]) ? htmlspecialchars($_POST["firstName"]) : "";
                $user_lastName = isset($_POST["lastName"]) ? htmlspecialchars($_POST["lastName"]) : "";
                $user_name = htmlspecialchars($user_firstName . " " . $user_lastName);
                $user_username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : "";
                $user_email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : "";
            
                if(!empty($user_firstName) 
                    && !empty($user_lastName) 
                    && !empty($user_username) 
                    && !empty($user_email)) {
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
                            "message" => "Please choose a different username.",
                            "userInfo" => null
                        ]);
                        exit();
                    }
            
                    $user_id = count($userList) + 1;
                    $new_user = [
                        "id" => $user_id,
                        "name" => $user_name,
                        "username" => $user_username,
                        "email" => $user_email,
                    ];
            
                    array_push($userList, $new_user);
                    $json = json_encode($userList, JSON_PRETTY_PRINT);
                    $fileName = "data/users.json";
                    file_put_contents($fileName, $json);
            
                    echo json_encode([
                        "success" => true,
                        "message" => "User successfully created!",
                        "user" => [
                            "id" => $user_id,
                            "name" => $user_name,
                            "username" => $user_username,
                            "email" => $user_email,
                        ]
                    ]);
                    exit();
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Please fill out all the fields.",
                        "userInfo" => null
                    ]);
                    exit();
                }
                break;
            case 'user-login':
                $userList = getUserList();
                $userLogin_username = isset($_POST['username']) ? htmlspecialchars($_POST["username"]) : "";
            
                if (empty($userLogin_username)) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Please enter a name!",
                        "userInfo" => null
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
                break;
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
?>