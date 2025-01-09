<?php

namespace App\Controller;

use App\Models\UsersModel;
use App\View\UsersView;
use App\Services\RandomValues;

/**
 * Класс UsersController управляет пользователями:
 * - выводит всех пользователей,
 * - добавляет нового пользователя,
 * - удаляет пользователя по его идентификатору.
 */
class UsersController
{
    /**
     * Модель для работы с данными пользователей.
     *
     * @var UsersModel
     */
    private UsersModel $usersModel;

    /**
     * Представление для отображения данных о пользователях.
     *
     * @var UsersView
     */
    private UsersView $usersView;

    /**
     * Сервис для генерации и подстановки случайных значений.
     *
     * @var RandomValues
     */
    private RandomValues $randomValues;

    /**
     * Конструктор, инициализирует необходимые объекты:
     * - модель пользователей,
     * - представление для вывода информации,
     * - сервис для случайных значений.
     */
    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->usersView = new UsersView();
        $this->randomValues = new RandomValues();
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
    public function delete(mixed $id): string
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
        return $this->usersView->displayUserAdd($user);
    }
}