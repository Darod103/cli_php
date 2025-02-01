<?php

namespace Tests\App\Router;

use App\Controller\UserControllerApi;
use App\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;
    private UserControllerApi $mockUserController;

    /**
     * Подготовка перед каждым тестом
     */
    public function setUp(): void
    {
        parent::setUp();
        $_SERVER = [];
        $_POST = [];
        $_GET = [];

        $this->router = new Router();

        $this->mockUserController = $this->getMockBuilder(UserControllerApi::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['list', 'delete', 'add'])
            ->getMock();
    }

    /**
     * Очистка после тестов
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $_SERVER = [];
        $_POST = [];
        $_GET = [];
    }

    /**
     * Тест добавления маршрута
     */
    public function testAddRoute(): void
    {
        $this->router->addRoute('GET', '/', ["HomeController", "index"]);
        $routes = $this->router->getRoutes();

        $this->assertNotEmpty($routes, 'Маршруты не должны быть пустыми');
        $route = reset($routes);

        $this->assertEquals('GET', $route['method']);
        $this->assertEquals('/', $route['url']);
        $this->assertEquals(['HomeController', 'index'], $route['callback']);
    }

    /**
     * Тест получения списка пользователей
     */
    public function testListUsers(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/list-users';

        $this->mockUserController->expects($this->once())
            ->method('list')
            ->willReturnCallback(function () {
                echo json_encode([
                    ['id' => 1, 'name' => 'Alice', 'email' => 'alice@example.com'],
                    ['id' => 2, 'name' => 'Bob', 'email' => 'bob@example.com'],
                ]);
            });

        $this->router->addRoute('GET', '/list-users', [$this->mockUserController, 'list']);

        ob_start();
        $this->router->run();
        $output = ob_get_clean();

        $this->assertJson($output);
        $response = json_decode($output, true);
        $this->assertCount(2, $response);
        $this->assertEquals('Alice', $response[0]['name']);
        $this->assertEquals('Bob', $response[1]['name']);
    }

    /**
     * Тест удаления пользователя (успех)
     */
    public function testDeleteUserSuccess(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['REQUEST_URI'] = '/delete-user/1';

        $this->mockUserController->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturnCallback(function () {
                http_response_code(200);
                echo json_encode(['success' => true]);
            });

        $this->router->addRoute('DELETE', '/delete-user/{id}', [$this->mockUserController, 'delete']);

        ob_start();
        $this->router->run();
        $output = ob_get_clean();

        $this->assertJson($output);
        $response = json_decode($output, true);
        $this->assertTrue($response['success']);
    }

    /**
     * Тест удаления пользователя (ошибка)
     */
    public function testDeleteUserFail(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['REQUEST_URI'] = '/delete-user/99';

        $this->mockUserController->expects($this->once())
            ->method('delete')
            ->with(99)
            ->willReturnCallback(function () {
                http_response_code(404);
                echo json_encode(['error' => 'Пользователь не найден']);
            });

        $this->router->addRoute('DELETE', '/delete-user/{id}', [$this->mockUserController, 'delete']);

        ob_start();
        $this->router->run();
        $output = ob_get_clean();

        $this->assertJson($output);
        $response = json_decode($output, true);
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('Пользователь не найден', $response['error']);
    }

    /**
     * Тесты добавления пользователя (успех и ошибка) с dataProvider
     * @dataProvider addUserProvider
     */
    public function testAddUser(array $postData, int $expectedCode, array $expectedResponse): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/add-user';
        $_POST = $postData;

        $this->mockUserController->expects($this->once())
            ->method('add')
            ->willReturnCallback(function () use ($expectedCode, $expectedResponse) {
                http_response_code($expectedCode);
                echo json_encode($expectedResponse);
            });

        $this->router->addRoute('POST', '/add-user', [$this->mockUserController, 'add']);

        ob_start();
        $this->router->run();
        $output = ob_get_clean();

        $this->assertJson($output);
        $response = json_decode($output, true);
        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * DataProvider для testAddUser
     */
    public static function addUserProvider(): array
    {
        return [
            'успех' => [['name' => 'Ivan', 'email' => 'ivan@example.com'], 200, ['success' => true]],
            'ошибка' => [['name' => 'Ivan', 'email' => 'invalid@example.com'], 404, ['error' => 'Email уже занят']],
        ];
    }
}
