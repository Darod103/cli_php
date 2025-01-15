<?php

namespace App\Models;

interface UsersModelInterface
{
    public function getAll():array;
    public function getUserByEmail(string $email):array;
    public function deleteById(int $id):bool;
    public function addUser(array $user):array;
}