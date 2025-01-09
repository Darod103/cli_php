<?php
//$jsonArr = file_get_contents('./storage/json/users.json');
//$s=json_decode($jsonArr,true);
////foreach ($s['users'] as $user) {
////    var_dump($user['id']);
////}
//
//var_dump(__DIR__);
require_once './app/Models/UsersModel.php';
require_once './app/Controller/UsersController.php';
require_once './app/View/UsersView.php';
require_once './app/Services/RandomValues.php';


use App\Models\UsersModel;
use App\Controller\UsersController;

$test = new UsersController();
$test->add();
$test->add(['name'=>'vasia','email'=>'vasia@vasia.com']);
$test->add(['email'=>'vasia1@vasia.com']);

$test->list();

//echo "Введите ваше имя: ";
//
//// Читаем ввод из консоли
//$input = trim(fgets(STDIN));
//
//// Выводим результат
//echo "Привет, $input!\n";