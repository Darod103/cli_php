<?php

namespace App\View;

/**
 *Класс для отоброжения информации о пользователе.
 */
class UsersView
{
    /**
     * Отоброжает список всех пользователей.
     * @param array $users массив всех пользователей.
     * @return bool если массив пустой то возрощает false.
     */
    public function showAllUsers(array $users): bool
    {
        if (empty($users)) {
            echo "Список пустой! \n";
            return false;

        }
        echo 'Список пользывателей:' . PHP_EOL;
        foreach ($users as $user) {
            echo "Id:{$user['id']} Имя:{$user['name']} Email:{$user['email']}" . PHP_EOL;
        }
        return true;
    }

    /**
     * Возврощает строку с id удаленного пользователя.
     * @param $id
     * @return string Id пользователя
     */
    public function displayUserDelete($id): string
    {
        return "Пользователь с Id:$id удален" . PHP_EOL;
    }

    /**
     * Возрощает строку если пользователь не найден по id
     * @param $id
     * @return string
     */
    public function displayUserNotFound($id): string
    {
        return "Пользователь с Id:$id не найден" . PHP_EOL;
    }

    /**
     * Возврощает строку о том что польщзователь с id,name,email был добавлен в бд.
     * @param array $user принемает массив данных о пользователе
     * @return string
     */
    public function displayUserAdd(array $user): string
    {
        return "Пользователь с именем:{$user['name']} и Email:{$user['email']} добавлен" . PHP_EOL;
    }
}