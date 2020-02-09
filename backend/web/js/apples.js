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

const showMessage = function (jqXHR) {
    if(jqXHR.responseJSON && jqXHR.responseJSON.message) {
        alert(jqXHR.responseJSON.message);
        return;
    }
    alert('Server error detected');
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
        load: function (apples, appleColors, freshDuration, precision) {
            AppleColors.load(appleColors);

            applesList.empty();

            $.each(apples, function (index, apple) {
                apple.color = AppleColors.get(apple.colorId);
                apple.state = null;

                if (apple.fallenAt) {
                    // fallenAt {string} Date&Time ISO 8601
                    apple.state = calculateFresh(new Date(apple.fallenAt), freshDuration);
                }

                AppleList.loadApple(apple, precision);
            });
        },
        loadApple: function (apple, precision) {
            let step = '0.' + '0'.repeat(precision - 1) + '1';
            applesList.append('<tr id="' + apple.id + '">' +
                '<td>' + apple.id + '</td>' +
                '<td>' + apple.color + '</td>' +
                '<td>' + apple.createdAt + '</td>' +
                '<td><a href="javascript:void(0);" class="fallApple" data-id="' + apple.id + '">' + 'Fall it' + '</a></td>' +
                '<td id="fallenAt' + apple.id + '">' + (apple.fallenAt ? apple.fallenAt : '') + '</td>' +
                '<td id="status' + apple.id + '">' + detectAppleState(apple.state) + '</td>' +
                '<td><a href="javascript:void(0);" class="eatApple" data-id="' + apple.id + '">' + 'Eat' + '</a> <input id="eatPercent' + apple.id + '" type="number" step="' + step + '" min="0" max="1" value="0" /></td>' +
                '<td id="eatenPercent' + apple.id + '">' + apple.eatenPercent.toFixed(precision) + '</td>' +
                '<td><a href="javascript:void(0);" class="dropApple" data-id="' + apple.id + '">' + 'Drop it' + '</a></td>' +
                '</tr>');
        },
        getEatPercent: function (id) {
            return $('input#eatPercent' + id, applesList).val();
        },
        setEatenPercent: function (id, percent, precision) {
            $('td#eatenPercent' + id, applesList).text(percent.toFixed(precision));
        },
        setFallDT: function (id, dt) {
            $('td#fallenAt' + id, applesList).text(dt);
            $('td#status' + id, applesList).text('fresh');
        }
    }
}();

const Apples = function () {
    'use strict';

    let precision = 0;

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
                        /** {int} hours */
                        freshDuration = data.data.freshDuration;

                    /** {int} Apple eat percent precision */
                    precision = data.data.eatPercentPrecision;

                    AppleList.load(apples, appleColors, freshDuration, precision);
                },
                error: function (jqXHR) {
                    showMessage(jqXHR);
                }
            });
        },
        getEatPercentPrecision: function () {
            return precision;
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
                showMessage(jqXHR);
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
            method: 'PUT',
            dataType: 'json',
            data: '',
            success: function (data) {
                AppleList.setFallDT(id, data.data);
            },
            error: function (jqXHR) {
                showMessage(jqXHR);
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
                showMessage(jqXHR);
            }
        });
    });

    //// EAT APPLE ////
    $('#applesTable').on('click', 'a.eatApple', function () {
        let id = $(this).data('id'),
            eatPercent = parseFloat(AppleList.getEatPercent(id)),
            eatPercentPrecision = Apples.getEatPercentPrecision();

        $.ajax({
            url: 'http://api-apples.local/apples/' + id,
            headers: {
                'Authorization': 'Bearer ' + AccessToken,
                'Content-Type': 'application/json'
            },
            method: 'PATCH',
            dataType: 'json',
            data: JSON.stringify({
                eatenPercent: eatPercent,
                eatPercentPrecision: eatPercentPrecision
            }),
            success: function (data) {

                if (0.0 === eatPercent) {
                    alert('You wanna leak apple? Yes, it\'s possible! )');
                }

                let percent = data.data.eatenPercent,
                    precision = data.data.eatPercentPrecision;
                AppleList.setEatenPercent(id, percent, precision);
            },
            error: function (jqXHR) {
                showMessage(jqXHR);
            }
        });
    });

    //// INIT PAGE - LOAD TABLE ////
    Apples.load();
});
