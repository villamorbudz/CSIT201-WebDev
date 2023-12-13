<?php
    session_start();
    include('api.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Metro Events</title>
</head>
<header class="p-3 bg-dark">
    <div class="d-flex flex-row">
        <div class="p-2">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li class="" style="margin-right: 25px;"><h3 class="text-light">MetroEvents<h3></li>
                </ul>
            </div>
        </div>
        <div class="ml-auto p-2">
            <div id="login-buttons" class="text-end">
                <button type="button" class="btn btn-outline-light me-2" data-toggle="modal" data-target="#loginModal">Login</button>
                <button type="button" class="btn btn-warning" id="createNewUser" data-toggle="modal" data-target="#createUserModal">Sign-up</button>
            </div>
            <div id="home-buttons" class="text-end">
                <button type="button" id="new-event" class="btn btn-outline-light me-2" data-toggle="modal" data-target="#createEventModal">
                    Create Event
                </button>
            </div>   
</header>
<body>
    <div id="body-container">
        <div id="events-container">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="..." alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>
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
                <button type="button" class="btn btn-warning" data-toggle="modal" data-dismiss="modal" data-target="#createUserModal">Create User</button>
                <button type="button" id="login-button" class="btn btn-primary">Login</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
        <div class="modal-dialog" role="document">
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
                        <div class="form-row">
                            <div class="col">
                                <label for="newUser-date">Date</label>
                                <input type="text" class="form-control" id="newUser-date" placeholder="mm/dd/yy">
                            </div>
                            <div class="col">
                                <label for="newUser-time">Time</label>
                                <input type="text" class="form-control" id="newUser-time" placeholder="Time">
                            </div>
                    </form>
                    <div id="createEvent-responseMSG"></div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="create-new-event" class="btn btn-primary">Create Event</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="metroEvents.js"></script>
</body>
</html>