<?php

namespace Source\controllers;

use League\Plates\Engine;
use Source\models\User;

/**
 *
 */
class Form
{
    private $view;

    /**
     * @param $router
     */
    public function __construct($router)
    {
        $this->view = Engine::create(dirname(__DIR__, 2) . "/theme", 'php');

        $this->view->addData([
            "router" => $router
        ]);
    }

    /**
     *
     */
    public function home(): void
    {
        $users = (new User())->find()->order('first_name')->fetch(true);
        echo $this->view->render('home',
            [
                'users' => $users
            ]
        );

    }

    /**
     * @param array $data
     */
    public function create(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (in_array("", $data)) {
            $callback['message'] = message('Os campos nome e sobrenome são obrigatórios!', 'info');
            echo json_encode($callback);
            return;
        }
        $user = (new User);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->save();
        if (!$user) {
            $callback = message('Não foi possível cadastrar o usuário!', 'error');
        }
        $callback['user'] = $this->view->render('fragments/users', ['user' => $user]);
        echo json_encode($callback);
    }

    /**
     * @param array $data
     */
    public function delete(array $data): void
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);

        $user = (new User())->findById($id);
        if ($user) {
            $user->destroy();
            $callback['remove'] = true;
        } else {
            $callback['remove'] = false;
        }
        echo json_encode($callback);
    }
}