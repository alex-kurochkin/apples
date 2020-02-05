$(document).ready(function() {
    'use strict';

    let list = $('#apples-list');
    list.append('<tr>' +
        '<td>id</td>' +
        '<td>color</td>' +
        '<td>Fallen</td>' +
        '<td>Rotten</td>' +
        '<td>Eat it for %</td>' +
        '<td>Eaten %</td>' +
        '<td>Drop it</td>' +
        '</tr>');

    $.ajax({
        url: 'http://api-apples.local/apples/list',
        headers: {
            'Authorization':'Bearer ' + AccessToken,
            'Content-Type':'application/json'
        },
        method: 'GET',
        dataType: 'json',
        data: '',
        success: function(data){
            console.log(data);
        }
    });
});
