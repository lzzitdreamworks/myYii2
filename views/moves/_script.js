$('i.glyphicon-refresh-animate').hide();

function updateRoutes(r) {
    _opts.moves.available = r.available;
    _opts.moves.assigned = r.assigned;
    search('available');
    search('assigned');
}

$('#btn-new').click(function () {
    var $this = $(this);
    var moves = $('#inp-moves').val().trim();
    if (moves != '') {
        $this.children('i.glyphicon-refresh-animate').show();
        $.post($this.attr('href'), {moves: moves}, function (r) {
            $('#inp-moves').val('').focus();
            updateRoutes(r);
        }).always(function () {
            $this.children('i.glyphicon-refresh-animate').hide();
        });
    }
    return false;
});

$('.btn-assign').click(function () {
    var $this = $(this);
    var target = $this.data('target');
    var moves = $('select.list[data-target="' + target + '"]').val();

    if (moves && moves.length) {
        $this.children('i.glyphicon-refresh-animate').show();
        $.post($this.attr('href'), {moves: moves}, function (r) {
            updateRoutes(r);
        }).always(function () {
            $this.children('i.glyphicon-refresh-animate').hide();
        });
    }
    return false;
});

$('#btn-refresh').click(function () {
    var $icon = $(this).children('span.glyphicon');
    $icon.addClass('glyphicon-refresh-animate');
    $.post($(this).attr('href'), function (r) {
        updateRoutes(r);
    }).always(function () {
        $icon.removeClass('glyphicon-refresh-animate');
    });
    return false;
});

$('.search[data-target]').keyup(function () {
    search($(this).data('target'));
});

function search(target) {
    var $list = $('select.list[data-target="' + target + '"]');
    $list.html('');
    var q = $('.search[data-target="' + target + '"]').val();
    $.each(_opts.moves[target], function () {
        var r = this;
        if (r.indexOf(q) >= 0) {
            $('<option>').text(r).val(r).appendTo($list);
        }
    });
}

// initial
search('available');
search('assigned');
