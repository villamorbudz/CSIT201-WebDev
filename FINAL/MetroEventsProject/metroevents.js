let session = [(isLoggedin = false), (userInfo = [])];

$(document).ready(function () {

  if (!isLoggedin) {
    $("#loginModal").modal("show");
    $("#login-buttons").css("display", "block");
  }

  $("#datepicker").datepicker();

  $("#create-new-user").click(function () {
    $.ajax({
      url: "api.php",
      method: "POST",
      data: {
        action: "create-user",
        firstName: $("#newUser-firstName").val(),
        lastName: $("#newUser-lastName").val(),
        username: $("#newUser-username").val(),
        email: $("#newUser-email").val(),
        password: $("#newUser-password").val(),
      },
      success: (data) => {
        let res = JSON.parse(data);
        if (res.success) {
          $("#createUser-responseMSG").empty();
          $("#createUser-responseMSG").append(
            `<div class="alert alert-success" role="alert">${res.message}</div>`
          );
        } else {
          $("#createUser-responseMSG").empty();
          $("#createUser-responseMSG").append(
            `<div class="alert alert-danger" role="alert">${res.message}</div>`
          );
        }

        console.log("Create New User: " + data);
        setTimeout(function () {
          $("#createUser-responseMSG").empty();
          if (res.success) {
            $("#createUserModal").modal("hide");
            setTimeout(function () {
              $("#loginModal").modal("show");
            }, 200);
          }
        }, 3000);
      },
    });
  });

  $("#createUserModal").on("hidden.bs.modal", function () {
    $(this).find("input").val("");
    $("#createUser-responseMSG").empty();
  });

  $("#login-button").click(function () {
    $.ajax({
      url: "api.php",
      method: "POST",
      data: {
        action: "user-login",
        username: $("#login-username").val(),
        password: $("#login-password").val(),
      },
      success: (data) => {
        let res = JSON.parse(data);
        session.isLoggedin = res.success;
        session.userInfo = res.userInfo;
        console.log(res);

        if (res.success) {
          $("#login-responseMsg").empty();
          $("#login-responseMsg").append(
            `<div class="alert alert-success" role="alert">${res.message}</div>`
          );
          setTimeout(function () {
            $("#login-responseMsg").empty();
            $("#login-buttons").css("display", "none");
            $("#home-buttons").css("display", "block");
            $("#loginModal").modal("hide");
          }, 1500);
        } else {
          $("#login-responseMsg").empty();
          $("#login-responseMsg").append(
            `<div class="alert alert-danger" role="alert">${res.message}</div>`
          );
        }
        setTimeout(function () {
          $("#login-responseMsg").empty();
        }, 1500);
        displayEvents();
      },
    });
  });

  $("#loginModal").on("hidden.bs.modal", function () {
    $(this).find("input").val("");
    $("#login-responseMsg").empty();
  });

  $("#create-new-event").click(function () {
    $.ajax({
      url: "api.php",
      method: "POST",
      data: {
        action: "create-new-event",
        title: $("#newEvent-title").val(),
        body: $("#newEvent-body").val(),
        venue: $("#newEvent-venue").val(),
        date: $("#newEvent-date").val(),
        time: $("#timepicker").text(),
        organizer: JSON.stringify(session.userInfo),
      },
      success: function (data) {
        console.log(session);
        let res = JSON.parse(data);
        console.log(res);
        if (res.success) {
          $("#createEvent-responseMSG").empty();
          $("#createEvent-responseMSG").append(
            `<div class="alert alert-success" role="alert">${res.message}</div>`
          );
        } else {
          $("#createEvent-responseMSG").empty();
          $("#createEvent-responseMSG").append(
            `<div class="alert alert-danger" role="alert">${res.message}</div>`
          );
        }

        setTimeout(function () {
          $("#createEvent-responseMSG").empty();
          if (res.success) {
            displayEvents();
            $("#createEventModal").modal("hide");
            resetCreateEventModal();
          }
        }, 1500);
      },
    });
  });

  $("#createEventModal").on("hidden.bs.modal", function () {
    resetCreateEventModal();
  });
});

function resetCreateEventModal() {
  $("#newEvent-title").val("");
  $("#newEvent-body").val("");
  $("#newEvent-venue").val("");
  $("#datepicker input").val("");
  $("#timepicker").text("Select Time");
  $("#createEvent-responseMSG").empty();
}

function getEvents() {
  return new Promise((resolve) => {
    $.ajax({
      url: "api.php",
      method: "POST",
      data: {
        action: "get-events",
      },
      success: (data) => {
        let events = JSON.parse(data);
        console.log(events);
        events.reverse();

        resolve(events);
      },
    });
  });
}

function getNotifications() {
  return new Promise((resolve) => {
    $.ajax({
      url: "api.php",
      method: "POST",
      data: {
        action: "get-notifications",
      },
      success: (data) => {
        let notifications = JSON.parse(data);
        console.log(notifications);
        notifications.reverse();

        resolve(notifications);
      },
    });
  });
}

function getUserNotifications() {
  return getNotifications().then((notifications) => {
    return notifications;
  });
}

function updateNotificationsContainer() {
  getUserNotifications().then((userNotifications) => {
    $("#notifications-container").empty();

    for (let notification of userNotifications) {
      let notificationHTML = `
        <div class="card dropdown-item">
          <p style="pointer-events: none;">${notification.message}</p>
        </div>
      `;
      $("#notifications-container").append(notificationHTML);
    }
  });
}





function displayEvents() {
  getEvents().then((events) => {
    $("#events-container").empty();

    for (let event of events) {
      attachEventHandlers();
    }

    getUserNotifications().then(() => {
      updateNotificationsContainer();
    });
  });
}

$(document).ready(function () {
  setInterval(updateNotificationsContainer, 2000);
});

function displayEvents() {
  getEvents().then((events) => {
      $("#events-container").empty();

      for (let event of events) {
          const isOrganizer = event.organizer.id === session.userInfo.id;
          const isParticipant = event.participants.some(participant => participant.id === session.userInfo.id);

          let buttonHTML = '';

          if (!isOrganizer) {
              if (isParticipant) {
                  buttonHTML = `
                      <button type="button" class="btn btn-success mx-2 joined-event-btn" data-event-id="${event["event-id"]}">Joined</button>
                  `;
              } else {
                  buttonHTML = `
                      <button type="button" class="btn btn-warning mx-2 join-event-btn" data-event-id="${event["event-id"]}">Join Event</button>
                  `;
              }
          }

          let cancelEventButtonHTML = '';
          if (isOrganizer) {
              cancelEventButtonHTML = `
                  <button type="button" class="btn btn-outline-danger mx-2" onclick="cancelEvent_modal(${event["event-id"]})">Cancel Event</button>
              `;
          }

          let newEvent = `
              <div class="container">
                  <div class="col card my-3">
                      <div class="card-body">
                          <p id="event${event["event-id"]}-date" class="card-text">${event.date} at ${event.time}</p>
                          <h3 id="event${event["event-id"]}-title" class="card-title">${event.title}</h3>
                          <h5 id="event${event["event-id"]}-venue" class="card-text">@ ${event.venue}</h5>
                          <p id="event${event["event-id"]}-body" class="card-text">${event.body}</p>
                          <p id="event${event["event-id"]}-participants" class="card-text">${event.participants.length} participant(s)</p>
                          <div class="btn-group">
                              ${buttonHTML}
                              ${cancelEventButtonHTML}
                          </div>
                      </div>
                  </div>
              </div>
          `;

          $("#events-container").append(newEvent);
      }

      attachEventHandlers();
      getUserNotifications();
  });
}


function attachEventHandlers() {
  $("#events-container").on("click", ".join-event-btn", async function () {
      var eventID = $(this).data("event-id");
      await joinEvent(eventID, session.userInfo.id);

      $(this).replaceWith(`<button type="button" class="btn btn-success mx-2 joined-event-btn" data-event-id="${eventID}">Joined</button>`);
  });

  $("#events-container").on("click", ".joined-event-btn", async function () {
      var eventID = $(this).data("event-id");
      var userID = session.userInfo.id;
      await leaveEvent(eventID, userID);

      $(this).replaceWith(`<button type="button" class="btn btn-warning mx-2 join-event-btn" data-event-id="${eventID}">Join Event</button>`);
  });
}

function joinEvent(eventID, userID) {
  return new Promise((resolve) => {
      $.ajax({
          url: "api.php",
          method: "POST",
          data: {
              action: "join-event",
              "event-id": eventID,
              "user-id": userID,
          },
          success: function (data) {
              resolve(data);
              displayEvents();
          },
      });
  });
}

function leaveEvent(eventID, userID) {
  return new Promise((resolve) => {
      $.ajax({
          url: "api.php",
          method: "POST",
          data: {
              action: "leave-event",
              "event-id": eventID,
              "user-id": userID,
          },
          success: function (data) {
              resolve(data);
              displayEvents();
          },
      });
  });
}

function cancelEvent_modal(eventID) {
  var cancelEventModal = $('#cancelEventModal');
  cancelEventModal.modal('show');

  $("#cancelEvent").click(function () {
      cancelEvent(eventID);
  });  
}

function cancelEvent(eventID) {
  var reason = $("#cancelEvent-reason").val();
  createAnnouncement(eventID, reason);
  $.ajax({
    url: "api.php",
    method: "POST",
    data: {
      action: "cancel-event",
      "event-id": eventID,
      "reason": reason,
    },
    success: function (data) {
      let res = JSON.parse(data);
      console.log(res);
      var responseMsg = $("#cancelEventModal #cancelEvent-responseMsg");
      var cancelEventModal = $('#cancelEventModal');
  
      if (res.success) {

        responseMsg.empty().append(
          `<div class="alert alert-success" role="alert">${res.message}</div>`
        );
      } else {
        responseMsg.empty().append(
          `<div class="alert alert-danger" role="alert">${res.message}</div>`
        );
      }
  
      setTimeout(function () {
        responseMsg.empty();
        if (res.success) {
          displayEvents();
          cancelEventModal.modal("hide");
          resetCancelEventModal();
        }
      }, 1500);
    },
  });
}

function resetCancelEventModal() {
$("#cancelEvent-reason").val("");
$("#cancelEvent-responseMsg").empty();
}

function createAnnouncement(eventID, reason) {
  $.ajax({
      url: "api.php",
      method: "POST",
      data: {
          action: "post-announcement",
          "event-id": eventID,
          "reason": reason
      },
      success: (data) => {
        let res = JSON.parse(data);
        console.log(res);
      },
  });
}


function createRequest(eventID) {
  $.ajax({
    url: "api.php",
    method: "POST",
    data: {
      action: "post-request",
      "event-id": eventID,

    },
    success: (data) => {
      let res = JSON.parse(data);
      console.log(res);
    },
  });
}

function refreshPage() {
  location.reload(true);
}
