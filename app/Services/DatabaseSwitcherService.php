<?php
namespace App\Services;

use App\Models\UsersJsonModel;
use App\Models\UsersSqlModel;
use InvalidArgumentException;

/**
 * Класс DatabaseSwitcherService
 *
 * Предоставляет функционал выбора и возврата нужной реализации работы с пользователями
 * (JSON или MySQL) на основе переменной окружения DB_SOURCE.
 */
class DatabaseSwitcherService
{
    /**
     * Сервис для работы с переменными окружения.
     *
     * @var EnvService
     */
    private EnvService $envService;

    /**
     * Конструктор класса DatabaseSwitcherService.
     * Создаёт экземпляр EnvService для получения настроек окружения.
     */
    function __construct()
    {
        $this->envService = new EnvService();
    }

    /**
     * Возвращает экземпляр модели для работы с пользователями на основе DB_SOURCE:
     * - UsersJsonModel, если DB_SOURCE = 'json'
     * - UsersSqlModel, если DB_SOURCE = 'mysql'
     *
     * @return UsersSqlModel|UsersJsonModel
     * @throws InvalidArgumentException Если значение DB_SOURCE некорректно.
     */
    public function getDb(): UsersSqlModel|UsersJsonModel
    {
        $dbSource = $this->envService->getByKey('DB_SOURCE');

        return match (strtolower($dbSource)) {
            'json' => new UsersJsonModel(),
            'mysql' => new UsersSqlModel(),
            default => throw new InvalidArgumentException('Не верно указана база данных: ' . $dbSource),
        };
    }
}