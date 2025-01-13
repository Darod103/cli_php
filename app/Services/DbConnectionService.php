<?php
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
     *
     * @var string
     */
    private string $port;
    /**
     * Название базы данных.
     *
     * @var string
     */
    private string $database;

    /**
     * Пароль к базе данных.
     *
     * @var string
     */
    private string $password;

    /**
     * Экземпляр PDO для работы с базой данных.
     *
     * @var PDO|null
     */
    private ?PDO $pdo = null;

    /**
     * Сервис для получения переменных окружения.
     *
     * @var EnvService
     */
    private EnvService $envService;

    /**
     * Конструктор класса DbConnectionService.
     * Инициализирует переменные окружения для подключения к MySQL.
     */
    public function __construct()
    {
        $this->envService = new EnvService();
        $this->port = $this->envService->getByKey('MYSQL_PORT');
        $this->password = $this->envService->getByKey('MYSQL_PASSWORD');
        $this->database = $this->envService->getByKey('DATABASE');

    }

    /**
     * Устанавливает соединение с MySQL, используя PDO.
     * В случае ошибки выбрасывает PDOException.
     *
     * @return void
     *
     * @throws PDOException
     */
    public function connection(): void
    {
        $dsn = "mysql:host=127.0.0.1;dbname=$this->database;port=$this->port";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, 'root', $this->password, $options);


        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
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