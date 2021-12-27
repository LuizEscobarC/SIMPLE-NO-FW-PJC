<?php $v->layout("_theme", ["title" => "Usuários"]); ?>

    <div class="create">
        <div class="form_ajax" style="display: none"></div>
        <form class="form" name="gallery" action="<?= $router->route('form.create') ?>" method="post"
              enctype="multipart/form-data">
            <label>
                <input type="text" name="first_name" placeholder="Nome:"/>
            </label>
            <label>
                <input type="text" name="last_name" placeholder="Sobrenome:"/>
            </label>
            <button>Cadastrar Usuário</button>
        </form>
    </div>

    <section class="users">
        <?php
        if (!empty($users)):
            foreach ($users as $user):
                ?>
                <?= $v->insert('fragments/users', ['user' => $user]) ?>
            <?php
            endforeach;
        endif;
        ?>
    </section>
<?php $v->start("js"); ?>
    <script>
        $(function () {
            function load(action) {
                var div_load = $('.ajax_load');
                if (action === "open") {
                    div_load.fadeIn().css("display", "flex");
                } else {
                    div_load.fadeOut();
                }
            }

            $('.create form').submit(function (e) {
                e.preventDefault();
                var users = $('.users');
                var div_message = $('.form_ajax');
                var form = $(this);

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

            $('body').on('click', '[data-action]', function (e) {
                e.preventDefault();
                var data = $(this).data();
                var div = $(this).parent();

                $.post(data.action, data, function () {
                    div.fadeOut();
                }, 'json').fail(function () {
                    alert('Teste . Deu erro');
                });
            })
        });
    </script>
<?php $v->end(); ?>