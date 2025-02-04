<?php

namespace Tests\App\Router;

use App\Router\Router;
use Tests\Mocks\TestController;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    /**
     * Подготовка перед каждым тестом
     */
    public function setUp(): void
    {
        $this->router = new Router();
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
     *Проверка роута с параметром.
     */
    public function testRoutWithParam(): void
    {
        $callbackMock = $this->getMockBuilder(TestController::class)
            ->onlyMethods(['index'])
            ->getMock();

        $callbackMock->expects($this->once())
            ->method('index')
            ->with('123');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/user/123';
        $this->router->addRoute('GET', '/user/{id}', [$callbackMock, "index"]);

        ob_start();
        $this->router->run();
        ob_get_clean();
    }

    /**
     * Проверка не существующего uri
     */
    public function testUnknownRout(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/unknown';

        ob_start();
        $this->router->run();
        $result = ob_get_clean();

        $this->assertEquals(404, http_response_code());
        $this->assertJson($result);
        $decodeResult = json_decode($result, true);
        $this->assertArrayHasKey('error', $decodeResult);
        $this->assertEquals('Route not found', $decodeResult['error']);
    }

}
