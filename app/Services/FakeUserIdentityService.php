<?php

namespace App\Services;
/**
 *Класс RandomValues служит для генерации случайных значений,
 */
class RandomValues
{
    /**
     * Набор имен
     * @var string[]
     */
    private array $names;
    /**
     * Набор доменов email
     * @var string[]
     */
    private array $domains;

    /**
     *Инициализирует массивы с именами и доменами.
     */
    public function __construct()
    {
        $this->names = ["James", "Mary", "John", "Patricia", "Robert", "Jennifer", "Michael", "Linda",
            "William", "Elizabeth", "David", "Barbara", "Richard", "Susan", "Joseph", "Jessica",
            "Thomas", "Sarah", "Charles", "Karen", "Christopher", "Nancy", "Daniel", "Lisa",
            "Matthew", "Margaret", "Anthony", "Betty", "Mark", "Sandra", "Paul", "Ashley",
            "Steven", "Dorothy", "Andrew", "Kimberly", "Kenneth", "Donna", "Joshua", "Emily",
            "George", "Michelle", "Kevin", "Carol", "Brian", "Amanda", "Edward", "Melissa"
                         ];
        $this->domains = ["gmail.com", "yahoo.com", "outlook.com", "hotmail.com", "example.com"];
    }

    /**
     *  Проверяет переданный массив пользователя и, если необходимо,
     *  дополняет его случайными значениями для имени и email.
     * @param $user
     * @return array
     */
    public function randomValues($user): array
    {
        if (empty($user['name'])) {
            $user['name'] = $this->randomName();
        }

        if (empty($user['email'])) {
            $user['email'] = $this->randomEmail($user["name"]);
        }
        return $user;
    }

    /**
     * Генерирует случайное имя
     * @return string
     */
    public function randomName(): string
    {
        return $this->names[array_rand($this->names)];
    }

    /**
     * Генерирует случайный домен email.
     * @param $name
     * @return string
     */
    public function randomEmail($name): string
    {
        $name = strtolower($name);
        return "$name@{$this->domains[array_rand($this->domains)]}";
    }


}