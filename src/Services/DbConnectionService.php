<?php
//TODO настроить что бы была возможность запускать локально и через nginx
//Передаем свойство в конструктор которое равно тру или фел и потом через итерацию выбераем локал или докер
namespace App\Services;

use PDO;
use PDOException;

/**
 * Class DbConnectionService
 *
 * Сервис для установки соединения с базой данных MySQL,
 * используя параметры из .env-файла.
 *
 * @package App\Services
 */
class DbConnectionService
{
    /**
     * Порт для подключения к базе данных.
     */
    private string $port;
    /**
     * Хост для подключение к БД.
     */
    private string $host;
    /**
     * Название базы данных.
     */
    private string $database;

    /**
     * Пароль к базе данных.
     */
    private string $password;

    /**
     * Экземпляр PDO для работы с базой данных.
     */
    private ?PDO $pdo = null;

    /**
     * Сервис для получения переменных окружения.
     */
    private EnvService $envService;

    /**
     * Флаг, указывающий работает ли приложение в Docker.
     */
    private bool $isDocker;
    /**
     * Конструктор класса DbConnectionService.
     * Инициализирует переменные окружения для подключения к MySQL.
     */
    public function __construct()
    {
        $this->envService = new EnvService();
        $this->isDocker =$this->envService->getByKey('APP_ENV') === 'docker';
        $this->port = $this->isDocker? '3306' : $this->envService->getByKey('MYSQL_PORT');
        $this->password = $this->envService->getByKey('MYSQL_PASSWORD');
        $this->database = $this->envService->getByKey('DATABASE');
        $this->host = $this->isDocker ? 'mysql': '127.0.0.1';

    }

    /**
     * Устанавливает соединение с MySQL, используя PDO.
     * @return void
     */
    public function connection(): void
    {
        if ($this->pdo !== null) {
            return;
        }

        $dsn = "mysql:host=$this->host;dbname=$this->database;port=$this->port";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdo = new PDO($dsn, 'root', $this->password, $options);
    }

    /**
     * Возвращает экземпляр PDO для работы с базой данных.
     * Если соединение ещё не было установлено, оно будет установлено.
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        if ($this->pdo === null) {
            $this->connection();
        }
        return $this->pdo;
    }

}