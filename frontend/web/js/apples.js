/**
 * @param state
 * @returns {string}
 */
const detectAppleState = function (state) {
    'use strict';
    switch (state) {
        case 1:
            return 'fresh';
        case 0:
            return 'rotten';
        case null:
            return 'unripe';
    }

    return '???';
};

/**
 *
 * @param fallenDT {Date}
 * @param freshDuration {number}
 * @returns {number} 1 - fresh, 0 - rotten
 */
const calculateFresh = function (fallenDT, freshDuration) {
    'use strict';
    let freshDurationSeconds = freshDuration * 60 * 60,
        currentDateTime = new Date(),
        dtDiff = currentDateTime.getTime() - fallenDT.getTime();

    return freshDurationSeconds > dtDiff ? 1 : 0;
};

const AppleList = function () {
    'use strict';
    let applesList = $('#apples-list');
    return {
        load: function (apple) {
            applesList.append('<tr id="' + apple.id + '">' +
                '<td>' + apple.id + '</td>' +
                '<td>' + apple.color + '</td>' +
                '<td>' + apple.createdAt + '</td>' +
                '<td><a href="javascript:void(0);" class="fallApple" data-id="' + apple.id + '">' + 'Fall it' + '</a></td>' +
                '<td>' + apple.fallenAt + '</td>' +
                '<td>' + detectAppleState(apple.state) + '</td>' +
                '<td>' + 'apple.eatIt' + '</td>' +
                '<td>' + apple.eatenPercent + '</td>' +
                '<td><a href="javascript:void(0);" class="dropApple" data-id="' + apple.id + '">' + 'Drop it' + '</a></td>' +
                '</tr>');
        }
    }
}();

const AppleColors = function () {
    'use strict';
    let colors = [];
    return {
        load: function (appleColors) {
            colors = [];
            $.each(appleColors, function (index, color) {
                colors[color.id] = color.color;
            });
        },
        /**
         * @param id {number}
         * @returns {string}
         */
        get: function (id) {
            if (colors[id]) {
                return colors[id];
            }
            return '';
        }
    };
}();

$(document).ready(function () {
    'use strict';

    $('a#createApples').on('click', function () {
        $.ajax({
            url: 'http://api-apples.local/apples/create',
            headers: {
                'Authorization': 'Bearer ' + AccessToken,
                'Content-Type': 'application/json'
            },
            method: 'GET',
            dataType: 'json',
            data: '',
            success: function (data) {
            }
        });
    });

    $('#applesTable').on('click', 'a.fallApple', function () {
        console.log('FALL');
        let id = $(this).data('id');
        console.log(id);
    });

    $('#applesTable').on('click', 'a.dropApple', function () {
        console.log('DROP');
        let id = $(this).data('id');
        console.log(id);
    });

    $('#applesTable').on('click', 'a.eatApple', function () {
        console.log('DROP');
        let id = $(this).data('id');
        console.log(id);
    });

    ////////////////// LOAD TABLE /////////////////

    $.ajax({
        url: 'http://api-apples.local/apples/list',
        headers: {
            'Authorization': 'Bearer ' + AccessToken,
            'Content-Type': 'application/json'
        },
        method: 'GET',
        dataType: 'json',
        data: '',
        success: function (data) {
            let apples = data.data.apples,
                appleColors = data.data.appleColors,
                freshDuration = data.data.freshDuration; // hours

            AppleColors.load(appleColors);

            $.each(apples, function (index, apple) {
                apple.color = AppleColors.get(apple.colorId);
                apple.state = null;

                if (apple.fallenAt) {
                    // fallenAt {string} Date&Time ISO 8601
                    apple.state = calculateFresh(new Date(apple.fallenAt), freshDuration);
                }

                // console.log(apple);
                AppleList.load(apple);
            });
        }
    });
});
