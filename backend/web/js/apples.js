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
        load: function(apples, appleColors, freshDuration) {
            AppleColors.load(appleColors);

            applesList.empty();

            $.each(apples, function (index, apple) {
                apple.color = AppleColors.get(apple.colorId);
                apple.state = null;

                if (apple.fallenAt) {
                    // fallenAt {string} Date&Time ISO 8601
                    apple.state = calculateFresh(new Date(apple.fallenAt), freshDuration);
                }

                AppleList.loadApple(apple);
            });
        },
        loadApple: function (apple) {
            applesList.append('<tr id="' + apple.id + '">' +
                '<td>' + apple.id + '</td>' +
                '<td>' + apple.color + '</td>' +
                '<td>' + apple.createdAt + '</td>' +
                '<td><a href="javascript:void(0);" class="fallApple" data-id="' + apple.id + '">' + 'Fall it' + '</a></td>' +
                '<td id="fallenAt' + apple.id + '">' + (apple.fallenAt ? apple.fallenAt : '') + '</td>' +
                '<td>' + detectAppleState(apple.state) + '</td>' +
                '<td><a href="javascript:void(0);" class="eatApple" data-id="' + apple.id + '">' + 'Eat' + '</a> <input id="eatPercent' + apple.id + '" type="number" step="0.1" min="0" max="1" value="0" /></td>' +
                '<td id="eatenPercent' + apple.id + '">' + apple.eatenPercent + '</td>' +
                '<td><a href="javascript:void(0);" class="dropApple" data-id="' + apple.id + '">' + 'Drop it' + '</a></td>' +
                '</tr>');
        },
        getEatPercent: function (id) {
            return $('input#eatPercent' + id, applesList).val();
        },
        setEatenPercent: function (id, percent) {
            $('td#eatenPercent' + id, applesList).text(percent.toFixed(1));
        },
        setFallDT: function (id, dt) {
            $('td#fallenAt' + id, applesList).text(dt);
        }
    }
}();

const Apples = function () {
    'use strict';

    return {
        load: function () {
            $.ajax({
                url: 'http://api-apples.local/apples',
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

                    AppleList.load(apples, appleColors, freshDuration);
                },
                error: function (jqXHR) {
                    alert(jqXHR.responseJSON.message);
                }
            });
        }
    };
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

    //// CREATE APPLE ////
    $('a#createApples').on('click', function () {
        $.ajax({
            url: 'http://api-apples.local/apples',
            headers: {
                'Authorization': 'Bearer ' + AccessToken,
                'Content-Type': 'application/json'
            },
            method: 'POST',
            dataType: 'json',
            data: '',
            success: function (data) {
                Apples.load();
                alert('Added ' + data.data + ' apples');
            },
            error: function (jqXHR) {
                alert(jqXHR.responseJSON.message);
            }
        });
    });

    //// FALL APPLE ////
    $('#applesTable').on('click', 'a.fallApple', function () {
        let id = $(this).data('id');
        $.ajax({
            url: 'http://api-apples.local/apples/' + id,
            headers: {
                'Authorization': 'Bearer ' + AccessToken,
                'Content-Type': 'application/json'
            },
            method: 'PATCH',
            dataType: 'json',
            data: '',
            success: function (data) {
                AppleList.setFallDT(id, data.data);
            },
            error: function (jqXHR) {
                alert(jqXHR.responseJSON.message);
            }
        });
    });

    //// DELETE APPLE ////
    $('#applesTable').on('click', 'a.dropApple', function () {
        let id = $(this).data('id');
        $.ajax({
            url: 'http://api-apples.local/apples/' + id,
            headers: {
                'Authorization': 'Bearer ' + AccessToken,
                'Content-Type': 'application/json'
            },
            method: 'DELETE',
            dataType: 'json',
            data: '',
            success: function () {
                Apples.load();
            },
            error: function (jqXHR) {
                alert(jqXHR.responseJSON.message);
            }
        });
    });

    //// EAT APPLE ////
    $('#applesTable').on('click', 'a.eatApple', function () {
        let id = $(this).data('id'),
            eatPercent = parseFloat(AppleList.getEatPercent(id));

        if(0.0 === eatPercent) {
            alert('You wanna leak apple? Yes, it\'s possible! )');
        }

        $.ajax({
            url: 'http://api-apples.local/apples/' + id + '/' + eatPercent,
            headers: {
                'Authorization': 'Bearer ' + AccessToken,
                'Content-Type': 'application/json'
            },
            method: 'PATCH',
            dataType: 'json',
            data: '',
            success: function (data) {
                AppleList.setEatenPercent(id, data.data);
            },
            error: function (jqXHR) {
                alert(jqXHR.responseJSON.message);
            }
        });
    });

    //// LOAD TABLE ////
    Apples.load();
});
