<?php

namespace App\Models;
/**
 * Класс UsersModel отвечает за работу с данными пользователей,
 * хранящимися в формате JSON-файла. Предоставляет функционал для:
 * - получения всех записей,
 * - сохранения данных,
 * - удаления пользователя по идентификатору,
 * - добавления нового пользователя.
 */
class UsersJsonModel implements UsersModelInterface
{
    /**
     * Путь к файлу JSON
     */
    private string $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . "/../../storage/json/users.json";
        $this->ensureFileExists();
    }

    /**
     * Проверяет существует ли JSON фаил, если нет то создает его
     * @return void
     */
    private function ensureFileExists(): void
    {
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    /**
     * Возврощает всех пользователей из JSON
     * @return array
     */
    public function getAll(): array
    {
        return json_decode(file_get_contents($this->filePath), true) ?: [];
    }

    /**
     * Возврощает массив с пользователем по емейлу, если пользователя нет возврощает пустой массив
     * @param string $email
     * @return array
     */
    public function getUserByEmail(string $email): array
    {
        $users = $this->getAll();
        return array_filter($users, fn($user) => $user['email'] === $email);
    }

    /**
     * Сохраняет переданный массив данных в JSON
     * @param array $user
     * @return void
     */
    public function saveUser(array $user): void
    {
        file_put_contents($this->filePath, json_encode($user, JSON_PRETTY_PRINT));
    }

    /**
     * Удаоляет пользователя по Id
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        $users = $this->getAll();
        $newData = array_values(array_filter($users, fn($item) => $item['id'] !== $id));

        if (count($newData) === count($users)) {
            return false;
        }

        $this->saveUser($newData);
        return true;
    }

    /**
     * Вначале идёт проверка: если пользователь с таким адресом электронной почты уже существует, возвращается false.
     * Добавляет нового пользователя в общий массив и сохраняет в JSON-файл.
     * @param array $user
     * @return array
     */
    public function addUser(array $user): array
    {
        if (!empty($this->getUserByEmail($user['email']))) {
            return ['email' => $user['email']];
        }

        $allUsers = $this->getAll();

        //Генерирует Id
        $newId = count($allUsers) > 0 ? max(array_column($allUsers, 'id')) + 1 : 1;
        //Добовлыет новое id в массив
        $user = array_merge(["id" => $newId], $user);
        $allUsers[] = $user;

        $this->saveUser($allUsers);

        return $user;
    }


}