<?php
session_start();
include('api.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="styles.css">
  <title>Metro Events</title>
</head>
<header class="p-3 bg-dark">
  <div class="d-flex flex-row">
    <div class="p-2">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li class="" style="margin-right: 25px;">
            <h3 class="text-light">Metro Events<h3>
          </li>
        </ul>
      </div>
    </div>
    <div class="ml-auto p-2">
      <div id="login-buttons" class="text-end">
        <button type="button" class="btn btn-outline-light me-2" data-toggle="modal" data-target="#loginModal">Login</button>
        <button type="button" class="btn btn-warning" id="createNewUser" data-toggle="modal" data-target="#createUserModal">Sign-up</button>
      </div>
      <div id="home-buttons" class="text-end">
        <button type="button" id="user-notifications" class="btn btn-outline-light btn-dark me-2 dropdown-toggle mx-2 border-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="bi bi-bell-fill"></i>
        </button>
        <div class="dropdown-menu" id="notifications-container" aria-labelledby="dropdownMenuButton"></div>

        <button type="button" id="new-event" class="btn btn-outline-light me-2 mx-2" data-toggle="modal" data-target="#createEventModal">
          Create Event
        </button>
        <button class="btn btn-link" onclick="refreshPage()">Log Out</button>
      </div>

</header>

<body>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
  <script src="metroEvents.js"></script>

  <div id="body-container">
    <div class="container d-flex-col" id="events-container"></div>
  </div>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
            <div class="form-group">
              <label for="login-password">Password</label>
              <input type="password" class="form-control" id="login-password" placeholder="Enter password">
            </div>
          </form>
          <div id="login-responseMsg"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-toggle="modal" data-dismiss="modal" data-target="#createUserModal">Create User</button>
          <button type="button" id="login-button" class="btn btn-primary">Login</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Create User Modal -->
  <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
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
              <label for="newUser-password">Password</label>
              <input type="password" class="form-control" id="newUser-password" placeholder="Enter password">
            </div>
          </form>
          <div id="createUser-responseMSG"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="create-new-user" class="btn btn-primary">Create User</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Event Modal -->
  <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createEventModalLabel">Create Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="newEvent-title">Title</label>
              <input type="text" class="form-control" id="newEvent-title" placeholder="Enter Title">
            </div>
            <div class="form-group">
              <label for="newEvent-body">Body</label>
              <input type="text" class="form-control" id="newEvent-body" placeholder="Enter Body">
            </div>
            <div class="form-group">
              <label for="newEvent-venue">Venue</label>
              <input type="text" class="form-control" id="newEvent-venue" placeholder="Enter Venue">
            </div>
            <div class="form-group dropdown">
              <div class="row">
                <div class="col">
                  <label for="newEvent-date">Date</label>
                  <div class="input-group date" id="datepicker">
                    <input type="text" id="newEvent-date" class="form-control">
                    <span class="input-group-append">
                      <span class="input-group-text bg-white">
                        <i class="bi bi-calendar3"></i>
                      </span>
                    </span>
                  </div>
                </div>
                <div class="col">
                  <label for="timepicker">Time</label>
                  <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle" id="timepicker" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Select Time
                    </button>
                    <div class="dropdown-menu" id="newEvent-time" aria-labelledby="dropdownMenuButton">
                      <script>
                        $(document).ready(function() {
                          var $timeButton = $('#timepicker');

                          function updateTimeText(time) {
                            $timeButton.text(time);
                          }

                          for (var period = 0; period < 2; period++) {
                            for (var hours = 0; hours < 12; hours++) {
                              for (var minutes = 0; minutes < 60; minutes += 15) {
                                var time = ('0' + (hours === 0 ? 12 : hours)).slice(-2) + ':' + ('0' + minutes).slice(-2);
                                var ampm = period === 0 ? 'AM' : 'PM';
                                var formattedTime = time + ' ' + ampm;

                                var $dropdownItem = $('<a>', {
                                  'class': 'dropdown-item',
                                  'href': '#',
                                  'text': formattedTime
                                });

                                $dropdownItem.on('click', function() {
                                  updateTimeText($(this).text());
                                });

                                $('#newEvent-time').append($dropdownItem);
                              }
                            }
                          }
                        });
                      </script>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="createEvent-responseMSG"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" id="create-new-event" class="btn btn-primary">Create Event</button>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cancel Event Modal -->
  <div class="modal fade" id="cancelEventModal" tabindex="-1" role="dialog" aria-labelledby="cancelEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelEventModalLabel">Cancel Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="cancelEvent-reason">Enter Reason: </label>
              <input type="text" class="form-control" id="cancelEvent-reason" placeholder="Enter reason">
            </div>
          </form>
          <div id="cancelEvent-responseMsg"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-toggle="modal" data-dismiss="modal" data-target="#cancelEventModal">Close</button>
          <button type="button" id="cancelEvent" class="btn btn-outline-danger">Cancel Event</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>