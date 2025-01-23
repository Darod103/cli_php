<?php

namespace App\Services;
/**
 *Класс FakeUserIdentityService служит для генерации случайных имени или email,
 */
class FakeUserIdentityService
{
    /**
     * Набор имен
     */
    private array $names;
    /**
     * Набор доменов email
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
     */
    public function randomValues(array $user): array
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
     * @param string $name
     * @return string
     */
    public function randomEmail(string $name): string
    {
        $name = strtolower($name);
        return "$name@{$this->domains[array_rand($this->domains)]}";
    }


}