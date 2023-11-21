var playcardTokens = [];

$(document).ready(function(){
    $("#gameCode").click(function(){
        $.ajax({
            url:'http://www.hyeumine.com/getcard.php?',
            method:"GET",
            data:{bcode:$("#gameCodeInput").val()},
            success:(data)=>{
                if(parseInt(data) === 0) {
                    alert('Invalid game code! Try again.');
                } else {
                    getCards(data);
                    playcardTokens = JSON.parse(data).playcard_token;
                }
                console.log(data);
            }
        });
    });

    $("#checkCards").click(function(){
        $.ajax({
            url:'http://www.hyeumine.com/checkwin.php?',
            method:"GET",
            data:{},
            success:(data)>={
                
            }
            
        });
    });
});

function getCards(data) {
    
    var bingoData = JSON.parse(data);
    playcardTokens.push(JSON.parse(data).playcard_token);

    function generateBingoCardHTML(data) {
      var html = '<table border="1">';
      html += '<tr><th>B</th><th>I</th><th>N</th><th>G</th><th>O</th></tr>';

      for (var i = 0; i < 5; i++) {
        html += '<tr>';
        html += '<td>' + data.card.B[i] + '</td>';
        html += '<td>' + data.card.I[i] + '</td>';
        html += '<td>' + data.card.N[i] + '</td>';
        html += '<td>' + data.card.G[i] + '</td>';
        html += '<td>' + data.card.O[i] + '</td>';
        html += '</tr>';
      }

      html += '</table>';
      return html;
    }

    $('#bingoCard-container').append(generateBingoCardHTML(bingoData));

    console.log(playcardTokens);
}

function checkCardWin(playcardToken) {

}