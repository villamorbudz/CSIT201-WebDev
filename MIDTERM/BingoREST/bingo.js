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
                    alert('Please enter a game code!');
                } else if(parseInt(data) === 0) {
                    alert('Invalid game code! Please try again.');
                } else {
                    bingoCardData = JSON.parse(data);
                    gameCode = $("#gameCodeInput").val();
                    playcardToken = bingoCardData.playcard_token;
                    
                    if (currentBingoCard) {
                        currentBingoCard.replaceWith(newBingoCard(bingoCardData));
                    } else {
                        $('#bingoCard-container').append(newBingoCard(bingoCardData));
                    }

                    currentBingoCard = $('#bingoCard-container table');
                    $('#checkCards').css('display', 'inline');
                    $('#dashboard').css('display', 'block');
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
                    alert("Thats a BINGO! You have won the game!");
                } else {
                    alert("Not a BINGO! Keep trying!");
                }
            }
        });
    });

    $('#bingoCard-container').on('click', '.cardNum', function() {
        $(this).toggleClass('checked');
    });
});

function newBingoCard(data) {
    var newCard = '<table border="2">';
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
