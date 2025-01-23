<?php

namespace App\Models;

use App\Services\DbConnectionService;
use PDO;
/**
 * Class UsersSqlModel
 *
 * Предоставляет методы для работы с таблицей `users` в базе данных:
 * получение списка пользователей, получение пользователя по email,
 * добавление и удаление пользователей.
 *
 * @package App\Models
 */
class UsersSqlModel implements UsersModelInterface
{
    /**
     * Сервис для установки соединеня с базой данныйх.
     */
    private DbConnectionService $dbConnection;
    /**
     * Экземпляр PDO для выполнения запросов к базе данных.
     */
    private PDO $pdo;

    /**
     * Конструктор класса UsersSqlModel.
     * Инициализирует соединение с базой данных через DbConnectionService.
     */
    public function __construct()
    {
        $this->dbConnection = new DbConnectionService();
        $this->pdo = $this->dbConnection->getPdo();
    }

    /**
     * Возвращает всех пользователей из таблицы `users`.
     *
     * @return array Массив с данными о пользователях (каждый элемент — ассоциативный массив).
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Получает пользователя по его email.
     *
     * @param string $email Email пользователя, которого нужно найти.
     * @return array Ассоциативный массив с данными пользователя или пустой массив если пользователь не найден.
     */
    public function getUserByEmail(string $email): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return !$user ? [] : $user;

    }

    /**
     * Удаляет пользователя из таблицы `users` по его ID.
     *
     * @param int $id Идентификатор пользователя, которого нужно удалить.
     * @return bool Возвращает true, если удаление прошло успешно, или false, если пользователь не найден.
     */
    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Добавляет нового пользователя в таблицу `users`.
     * Предварительно проверяет, существует ли уже пользователь с таким email.
     *
     * @param array $user Массив с ключами 'name' и 'email', описывающий нового пользователя.
     * @return array Возвращает массив с данными нового пользователя или false, если такой email уже существует.
     */
    public function addUser(array $user): array
    {
        $checkEmail = $this->getUserByEmail($user['email']);
        if (!empty($checkEmail)) {
            return ['email' => $user['email']];
        }
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->execute([
            "name" => $user['name'],
            "email" => $user['email']
        ]);
        return $user;

    }
}