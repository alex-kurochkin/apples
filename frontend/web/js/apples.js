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

    $.get('http://api-apples.local/apples/list', function( data ) {
        // $( ".result" ).html( data );
        console.log(data);
    });
});
