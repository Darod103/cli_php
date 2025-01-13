<?php
namespace App\Services;
/**
 * Class EnvService
 *
 * Сервис для работы с переменными окружения, загружаемыми из файла .env.
 */
class EnvService
{
    /**
     * Путь к файлу
     * @var string
     */
    private string $filePath;
    /**
     * Массив загруженных переменных окружения.
     *
     * @var array
     */
    private array $env = [];

    /**
     * Конструктор класса EnvService.
     * Устанавливает путь к файлу .env и загружает переменные окружения.
     */
    public function __construct()
    {
        $this->filePath = __DIR__ . '/../../.env';
        $this->load();
    }

    /**
     * Метод загружает переменый из .env в массив $env.
     * Метод игнорирует пустые строки,пробелы и комментарии.
     * @return void
     */
    public function load(): void
    {
        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            list($key, $value) = explode('=', trim($line));

            if (strpos($value, '#')) {
                $value = substr($value, 0, strpos($value, '#'));
            }
            $this->env[trim($key)] = trim($value);

        }
    }

    /**
     * Получает значение переменной окружения по её ключу.
     *
     * @param string $key Ключ переменной окружения.
     *
     * @return string|bool Возвращает значение переменной или false, если ключ не найден.
     */
    public function getByKey(string $key): string|bool
    {
        return $this->env[$key] ?? false;
    }

    /**
     * Возвращает весь массив загруженных переменных окружения.
     *
     * @return array Ассоциативный массив со всеми переменными окружения.
     */
    public function getAll(): array
    {
        return $this->env;
    }
}