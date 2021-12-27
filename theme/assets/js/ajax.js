$(function () {
    var body = $('body');

    function load(action) {
        let div_load = $('.ajax_load');
        if (action === "open") {
            div_load.fadeIn().css("display", "flex");
        } else {
            div_load.fadeOut();
        }
    }

    $('.create form').submit(function (e) {
        e.preventDefault();
        let users = $('.users');
        let div_message = $('.form_ajax');
        let form = $(this);

        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            type: 'POST',
            dataType: "json",
            beforeSend: function () {
                load('open');
            },
            success: function (callback) {
                if (callback.message) {
                    div_message.html(callback.message).fadeIn();
                } else {
                    div_message.fadeOut(function () {
                        div_message.html("")
                    });
                }
                if (callback.user) {
                    users.prepend(callback.user);
                }
            },
            complete: function () {
                load("close");
            }
        });
    });

    body.on('click', '[data-action]', function (e) {
        e.preventDefault();
        let data = $(this).data();
        let div = $(this).parent();

        $.post(data.action, data, function () {
            div.fadeOut(function () {
                div.remove();
            });
        }, 'json').fail(function () {
            alert('Teste . Deu erro');
        });
    })
});