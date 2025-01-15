<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\UsersController;
use App\Services\DatabaseSwitcherService;

$dbSource = new DatabaseSwitcherService();

$cli = new UsersController($dbSource->getDb());
//Удаляю названия файла
array_shift($argv);

$command = empty($argv[0]) ? null : $argv[0];
switch ($command) {
    case 'list':
        if (count($argv) > 1) {
            echo "В данную комнаду нельзя добовлять аргументы";
            break;
        }
        $cli->list();
        break;
    case 'delete':

        $id = !empty($argv[1])? $argv[1] : null;
        if ($id === null) {
            echo "Не указан ID пользователя для удаления. Пример: php script_name.php delete 1\n";
            exit(1);
        }
        $result = $cli->delete($id);
        echo $result;
        break;
    case 'add':
        $name = $argv[1] ?? null;
        $email = $argv[2] ?? null;
        echo $cli->add(['name' => $name, 'email' => $email]);
        break;
    case 'help':
        echo "Использование:\n";
        echo "  php script_name.php list - отоброжает всех пользователей\n";
        echo "  php script_name.php удаляет пользователя по id\n";
        echo "  php script_name.php add добовляет пользователя можно указать имя и email \n";
        break;
    default:
        echo "Нет такой команды \n";
}