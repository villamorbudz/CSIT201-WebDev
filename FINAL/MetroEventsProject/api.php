<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$usersJSON = 'data\users.json';
$eventsJSON = 'data\events.json';
$notificationsJSON = 'data\notifications.json';

$_SESSION = [
  'isLoggedIn' => false,
  'loggedInUser' => []
];

if (isset($_POST['action'])) {
  $action = $_POST['action'];

  switch ($action) {
    case 'create-user':
      $userList = getUserList();
      $new_user = [];

      $user_firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : "";
      $user_lastName = isset($_POST["lastName"]) ? htmlspecialchars($_POST["lastName"]) : "";
      $user_name = htmlspecialchars($user_firstName . " " . $user_lastName);
      $user_username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : "";
      $user_email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : "";
      $user_password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : "";

      if (
        !empty($user_firstName)
        && !empty($user_lastName)
        && !empty($user_username)
        && !empty($user_email)
      ) {
        $userAlreadyExists = false;
        foreach ($userList as $user) {
          if ($user['username'] === $user_username) {
            $userAlreadyExists = true;
            break;
          }
        }

        if ($userAlreadyExists) {
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
          "password" => $user_password
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
      $userLogin_password = isset($_POST['password']) ? $_POST["password"] : null;

      if (empty($userLogin_username) || empty($userLogin_password)) {
        echo json_encode([
          "success" => false,
          "message" => "Please fill out all the fields.",
          "userInfo" => null
        ]);
        exit();
      } else {
        foreach ($userList as $user) {
          if ($userLogin_username === $user['username'] && password_verify($userLogin_password, $user['password'])) {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['loggedInUser'] = $user;
            echo json_encode([
              "success" => true,
              "message" => "Successfully logged in!",
              "userInfo" => [
                "id" => $_SESSION['loggedInUser']['id'],
                "name" => $_SESSION['loggedInUser']['name'],
                "username" => $_SESSION['loggedInUser']['username'],
                "email" => $_SESSION['loggedInUser']['email'],
              ],
            ]);
            exit();
          }
        }

        echo json_encode([
          "success" => false,
          "message" => "Incorrect username or password, please try again.",
          "userInfo" => null
        ]);
        exit();
      }
      break;
    case 'create-new-event':
      $eventList = getEventList();
      $newEvent_organizer = isset($_POST['organizer']) ? json_decode($_POST['organizer'], true) : "";
      $newEvent_title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : "";
      $newEvent_body = isset($_POST['body']) ? htmlspecialchars($_POST['body']) : "";
      $newEvent_venue = isset($_POST['venue']) ? htmlspecialchars($_POST['venue']) : "";
      $newEvent_date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : "";
      $newEvent_time = ($_POST['time']);

      if (empty($newEvent_title) || empty($newEvent_body) || empty($newEvent_venue) || empty($newEvent_date) || $newEvent_time == "Select Time") {
        echo json_encode([
          "success" => false,
          "message" => "Please fill out all the fields.",
          "eventInfo" => null
        ]);
        exit();
      }

      $event_id = count($eventList) + 1;
      $new_event = [
        "event-id" => $event_id,
        "organizer" => $newEvent_organizer,
        "participants" => [$newEvent_organizer],
        "upvotes" => 0,
        "rating" => 0,
        "title" => $newEvent_title,
        "body" => $newEvent_body,
        "venue" => $newEvent_venue,
        "date" => $newEvent_date,
        "time" => $newEvent_time
      ];
      echo json_encode([
        "success" => true,
        "message" => "Event Successfully Created!",
        "eventInfo" => $new_event,
      ]);

      array_push($eventList, $new_event);
      $json = json_encode($eventList, JSON_PRETTY_PRINT);
      $fileName = "data/events.json";
      file_put_contents($fileName, $json);
      exit();
      break;
    case 'cancel-event':
      $eventList = getEventList();
      $cancelEvent_eventID = isset($_POST['event-id']) ? htmlspecialchars($_POST['event-id']) : "";
      $cancelEvent_reason = isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : "";

      if(empty($cancelEvent_eventID) || empty($cancelEvent_reason)) {
        echo json_encode ([
          "success" => false,
          "message" => "Please enter a reason",
        ]);
      } else {
        foreach($eventList as $key => $event) {
          if($event['event-id'] == $cancelEvent_eventID) {
              unset($eventList[$key]);

              $json = json_encode($eventList, JSON_PRETTY_PRINT);
              $fileName = "data/events.json";
              file_put_contents($fileName, $json);
              echo json_encode([
                "success" => true,
                "message" => "Event Sucessfully Cancelled.",
                "reason" =>  $cancelEvent_reason
              ]);
              exit();
          }
        } 
      }
      break;
    case 'post-announcement':
      $notificationList = getNotificationList();
      $eventList = getEventList();
    
      $announcement_eventID = isset($_POST['event-id']) ? $_POST['event-id'] : "";
      $announcement_reason = isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : "";

      if(empty($announcement_eventID) || empty($announcement_reason)) {
        echo json_encode([
          "success" => false,
          "message" => "missing input"
        ]);
        exit();
      } else {
        foreach($eventList as $event) {
            echo json_encode([
              "eventID" => $event['event-id'],
              "targetEvent" => $announcement_eventID
            ]);
          if($event['event-id'] == $announcement_eventID) {
            echo json_encode([
              "eventID" => $event['event-id'],
              "targetEvent" => $announcement_eventID
            ]);
            $new_announcement = [
              "id" => count($notificationList) + 1,
              "type" => "announcement",
              "eventID" => $announcement_eventID,
              "message" => "\"{$event['title']}\" has been cancelled for \"{$announcement_reason}\""
            ];
      
            array_push($notificationList, $new_announcement);
            $json = json_encode($notificationList, JSON_PRETTY_PRINT);
            $fileName = "data/notifications.json";
            file_put_contents($fileName, $json);

            echo json_encode([
              "success" => true,
              "type" => "announcement",
              "eventID" => $announcement_eventID,
            ]);
            exit();
            break;
          }
        }
        echo json_encode([
          "success" => false,
          "message" => "event not found",
        ]);
        exit();
      }
      break;
    case 'post-request':
      $userList = getUserList();
      $eventList = getEventList();

      $request_eventID = isset($_POST['event-id']) ? htmlspecialchars($_POST['event-id']) : "";
      $request_userID = isset($_POST['user-id']) ? htmlspecialchars($_POST['user-id']) : "";

      if(empty($request_eventID || $request_userID)) {
        echo json_encode([
          "success" => false,
        ]);
        exit();
      }

      foreach($userList as $user) {
        if($request_userID == $user['id']) {
          foreach ($eventList as $event) {
            if($event['event-id'] == $request_eventID) {
              foreach($event['participants'] as $participant) {
                $new_request = [
                  "id" => count($notificationList) + 1,
                  "type" => "request",
                  "eventID" => $request_eventID,
                  "target-userID" => $participant['id'],
                  "message" => $user['name'] . " is requesting to join " . $event['title']
                ];

                array_push($notificationList, $new_request);
                $json = json_encode($notificationList, JSON_PRETTY_PRINT);
                $fileName = "data/notifications.json";
                file_put_contents($fileName, $json);
              }

              echo json_encode([
                "success" => true,
                "type" => "request",
                "eventID" => $request_eventID,
                "userID" => $request_userID
              ]);
              exit();
            }
          }
        }
      }

      echo json_encode([
        "success" => false
      ]);
      break;
      case 'join-event':
        $userList = getUserList();
        $eventList = getEventList();
        $joinEvent_eventID = isset($_POST['event-id']) ? htmlspecialchars($_POST['event-id']) : "";
        $joinEvent_userID = isset($_POST['user-id']) ? htmlspecialchars($_POST['user-id']) : "";
      
        foreach ($eventList as &$event) {
            if ($event['event-id'] == $joinEvent_eventID) {
                $isAlreadyParticipant = false;
                foreach ($event['participants'] as $participant) {
                    if ($participant['id'] == $joinEvent_userID) {
                        $isAlreadyParticipant = true;
                        break;
                    }
                }
    
                if (!$isAlreadyParticipant) {
                    foreach ($userList as $user) {
                        if ($user['id'] == $joinEvent_userID) {
                            $new_participant = [
                                "id" => $user['id'],
                                "name" => $user['name'],
                                "username" => $user['username'],
                                "email" => $user['email']
                            ];
                            $event['participants'][] = $new_participant;
                            break;
                        }
                    }
                    $json = json_encode($eventList, JSON_PRETTY_PRINT);
                    file_put_contents($eventsJSON, $json);
                }
    
                break;
            }
        }
        break;
    
      
        case 'leave-event':
          $userList = getUserList();
          $eventList = getEventList();
          $leaveEvent_eventID = isset($_POST['event-id']) ? htmlspecialchars($_POST['event-id']) : "";
          $leaveEvent_userID = isset($_POST['user-id']) ? htmlspecialchars($_POST['user-id']) : "";
        
          foreach($eventList as &$event) {
            if($event['event-id'] == $leaveEvent_eventID) {
              foreach($event['participants'] as $key => $participant) {
                if($participant['id'] == $leaveEvent_userID) {
                  unset($event['participants'][$key]);
                  $json = json_encode($eventList, JSON_PRETTY_PRINT);
                  file_put_contents($eventsJSON, $json);
                  break;
                }
              }
              break;
            }
          }
          break;
        
    case 'get-events':
      echo json_encode(getEventList());
      break;
    case 'get-notifications':
      echo json_encode(getNotificationList());
      break;
    case 'get-users':
      echo json_encode(getUserList());
      break;
  }
}

function getUserList() {
  global $usersJSON;
  if (!file_exists($usersJSON)) {
    return [];
  }
  $user_data = file_get_contents($usersJSON);
  $userList = json_decode($user_data, true);
  return array_values($userList);
}

function getEventList() {
  global $eventsJSON;
  if (!file_exists($eventsJSON)) {
    return [];
  }
  $event_data = file_get_contents($eventsJSON);
  $eventList = json_decode($event_data, true);
  return array_values($eventList);
}

function getNotificationList() {
  global $notificationsJSON;
  if (!file_exists($notificationsJSON)) {
    return [];
  }
  $notification_data = file_get_contents($notificationsJSON);
  $notificationList = json_decode($notification_data, true);
  return array_values($notificationList);
}
