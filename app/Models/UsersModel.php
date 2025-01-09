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
class UsersModel
{
    /**
     * Путь к файлу JSON
     * @var string
     */
    private string $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . "/../../storage/json/users.json";

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
     * Сохраняет переданный массив данных в JSON
     * @param $data
     * @return void
     */
    public function saveData($data): void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Удаоляет пользователя по Id
     * @param $id
     * @return bool
     */
    public function deleteById($id): bool
    {

        $users = $this->getAll();
        $newData = array_filter($users, fn($item) => $item['id'] !== (int)$id);
        if (count($newData) === count($users)) {
            return false;
        }
        $this->saveData($newData);
        return true;


    }

    /**
     *  Добавляет нового пользователя в общий массив и сохраняет в JSON-файл.
     * @param array $value
     * @return array
     */
    public function addUser(array $value): array
    {
        $allUsers = $this->getAll();

        //Генерирует Id
        $newId = count($allUsers) > 0 ? max(array_column($allUsers, 'id')) + 1 : 1;
        //Добовлыет новое id в массив
        $value = array_merge(["id" => $newId], $value);
        $allUsers[] = $value;

        $this->saveData($allUsers);

        return $value;

    }


}