var playcardToken;
var bingoCardData;
var currentBingoCard;
var gameCode;

$(document).ready(function(){
    $("#gameCode").click(function(){
        $.ajax({
            url:'http://www.hyeumine.com/getcard.php?',
            method:"GET",
            data:{bcode:$("#gameCodeInput").val()},
            success:(data)=>{
                if($("#gameCodeInput").val() === '') {
                    $('#modal').modal('show');
                    $("#modal .modal-title").html("<h5>Error</h5>");
                    $("#modal .modal-body").html("<p>Please enter a game code!</p>");
                } else if(parseInt(data) === 0) {
                    $('#modal').modal('show');
                    $("#modal .modal-title").html("<h5>Error</h5>");
                    $("#modal .modal-body").html("<p>Invalid game code, please try again.</p>");
                } else {
                    bingoCardData = JSON.parse(data);
                    gameCode = $("#gameCodeInput").val();
                    playcardToken = bingoCardData.playcard_token;
                    
                    if (currentBingoCard) {
                        currentBingoCard.replaceWith(newBingoCard(bingoCardData));
                        $('#gameCodeText').replaceWith("<h5>Game Code: " + gameCode + "</h5>");
                    } else {
                        $('#bingoCard-container').append(newBingoCard(bingoCardData));
                    }

                    currentBingoCard = $('#bingoCard-container table');
                    $('#checkCards').css('display', 'inline');
                    $('#dashboard').css('display', 'inline');
                }
            }
        });
    });

    $("#dashboard").click(function(){
        window.open("http://www.hyeumine.com/bingodashboard.php?bcode=" + gameCode, '_blank');
    });
    
    var i = 0;
    $("#checkCards").click(function(){
        $.ajax({
            url:'http://www.hyeumine.com/checkwin.php?',
            method:"GET",
            data:{playcard_token: playcardToken},
            success:(data)=>{
                if(parseInt(data) === 1) {
                    $("#modal .modal-title").html("<h5>BINGO!!!</h5>");
                    $("#modal .modal-body").html("<p>Its a Bingo! You have won the game.</p>");
                } else {
                    $("#modal .modal-title").html("<h5>Result</h5>");
                    $("#modal .modal-body").html("<p>Not a BINGO. Try again.</p>");
                }

                $('#modal').on('shown.bs.modal', function () {
                    $('#myInput').trigger('focus')
                })
            }
        });
    });

    $('#bingoCard-container').on('click', '.cardNum', function() {
        $(this).toggleClass('checked');
    });

});

function newBingoCard(data) {
    var newCard = '<table id="bingoCard" border="2">';
    newCard += '<tr><th>B</th><th>I</th><th>N</th><th>G</th><th>O</th></tr>';

    for (var i = 0; i < 5; i++) {
        newCard += '<tr>';
        newCard += '<td>'+ '<button type="checkbox" class ="cardNum">' + data.card.B[i] + '</button>' + '</td>';
        newCard += '<td>'+ '<button type="checkbox" class ="cardNum">' + data.card.I[i] + '</button>' + '</td>';
        newCard += '<td>'+ '<button type="checkbox" class ="cardNum">' + data.card.N[i] + '</button>' + '</td>';
        newCard += '<td>'+ '<button type="checkbox" class ="cardNum">' + data.card.G[i] + '</button>' + '</td>';
        newCard += '<td>'+ '<button type="checkbox" class ="cardNum">' + data.card.O[i] + '</button>' + '</td>';
        newCard += '</tr>';
    }

    newCard += '</table>';
    return newCard;
}