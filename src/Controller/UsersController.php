<?php

namespace App\Controller;

use App\Models\UsersModelInterface;
use App\View\UsersView;
use App\Services\FakeUserIdentityService;

/**
 * Класс UsersController управляет пользователями:
 * - выводит всех пользователей,
 * - добавляет нового пользователя,
 * - удаляет пользователя по его идентификатору.
 */
class UsersController
{
    /**
     * Интерфейс для работы с данными пользователей (сохранение,удаление,получиние).
     *
     */
    private  UsersModelInterface $usersModel;

    /**
     * Представление для отображения данных о пользователях.
     */
    private UsersView $usersView;

    /**
     * Сервис для генерации и подстановки случайного имени или пороля.
     */
    private FakeUserIdentityService $randomValues;

    public function __construct(UsersModelInterface $usersModel)
    {
        $this->usersModel = $usersModel;
        $this->usersView = new UsersView();
        $this->randomValues = new FakeUserIdentityService();
    }

    /**
     * Получает список всех пользователей и передаёт их
     * в представление для отображения.
     *
     * @return void
     */
    public function list(): void
    {
        $users = $this->usersModel->getAll();
        $this->usersView->showAllUsers($users);
    }

    /**
     * Удаляет пользователя по его идентификатору.
     *
     * @param mixed $id Идентификатор пользователя для удаления
     * @return string Возвращает сообщение о результате удаления
     */
    public function delete(int $id): string
    {
        $delete = $this->usersModel->deleteById($id);
        if ($delete) {
            return $this->usersView->displayUserDelete($id);
        } else {
            return $this->usersView->displayUserNotFound($id);
        }
    }

    /**
     * Добавляет нового пользователя.
     * При необходимости к переданным данным добавляются случайные значения.
     *
     * @param array $user Массив с данными о пользователе
     * @return string Возвращает сообщение о результате добавления пользователя
     */
    public function add(array $user = []): string
    {
        $user = $this->randomValues->randomValues($user);
        $user = $this->usersModel->addUser($user);
        if (count($user) <= 1) {
            return $this->usersView->displayUserExists($user['email']);
        }
        return $this->usersView->displayUserAdd($user);
    }
}