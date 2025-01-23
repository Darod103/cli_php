<?php

namespace App\Controller;

use App\Models\UsersModelInterface;
use App\Services\FakeUserIdentityService;

/**
 * Класс UsersController управляет пользователями:
 * - выводит всех пользователей,
 * - добавляет нового пользователя,
 * - удаляет пользователя по его идентификатору.
 */
class UserControllerApi
{
    /**
     * Интерфейс для работы с данными пользователей (сохранение,удаление,получиние).
     *
     */
    private UsersModelInterface $usersModel;
    /**
     * Сервис для генерации и подстановки случайного имени или пороля.
     */
    private FakeUserIdentityService $randomValues;

    public function __construct(UsersModelInterface $usersModel)
    {
        $this->usersModel = $usersModel;
        $this->randomValues = new FakeUserIdentityService();
    }

    /**
     * Получает список всех пользователей и передаёт их
     * в формате JSON.
     *
     * @return void
     */
    public function list(): void
    {
        $users = $this->usersModel->getAll();
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    /**
     * Удаляет пользователя по его идентификатору.
     *
     * @param mixed $id Идентификатор пользователя для удаления
     */
    public function delete(int $id): void
    {
        $result = $this->usersModel->deleteById($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => true]);
            return;
        }
        http_response_code(404);
        echo json_encode(['error' => 'Пользователь не найден']);
    }
    /**
     * Добавляет нового пользователя.
     * При необходимости к переданным данным добавляются случайные значения.
     */
    public function add(): void
    {
        $inputData = json_decode(file_get_contents('php://input'), true);
        $inputData = is_null($inputData) ? [] : $inputData;
        //Фильтруем массив оставляя только ключ и значение name,email
        $userData = array_intersect_key($inputData, array_flip(['name', 'email']));

        $user = $this->randomValues->randomValues($userData);
        $user = $this->usersModel->addUser($user);
        if (count($user) <= 1) {
            http_response_code(409);
            echo json_encode(['error' => 'Email уже занят']);
            return;
        }
        http_response_code(200);
        echo json_encode(['success' => true]);

    }
}