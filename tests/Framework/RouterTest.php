<?php
namespace Tests\Framework;

use Framework\Router;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerResponseInterface;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

  /**
  * @var Router
  */
  private $router;

  public function setUp()
  {
    $this->router = new Router();
  }

  public function testGetMethod()
  {
      $request = new ServerRequest('GET', '/site');
      $this->router->get('/site', function () { return 'hello'; }, 'site');
      $route = $this->router->match($request);
      $this->assertEquals('site', $route->getName());
      $this->assertEquals('hello', call_user_func_array($route->getCallback(), [$request]));
  }

  public function testGetMethodIfURLDoesNotExists()
  {
      $request = new ServerRequest('GET', '/site');
      $this->router->get('/sitejhgj', function () { return 'hello'; }, 'site');
      $route = $this->router->match($request);
      $this->assertEquals(null, $route);
  }

  public function testGetMethodWithParameters()
  {
      $request = new ServerRequest('GET', '/site/slug-8');
      $this->router->get('/site/{slug:[a-z0-9\-]+}-{id:\d+}', function () { return 'ugh'; }, 'post.show');
      $this->router->get('/site', function () { return 'hello'; }, 'posts');
      $route = $this->router->match($request);
      $this->assertEquals('post.show', $route->getName());
      $this->assertEquals('post.show', call_user_func_array($route->getCallback(), [$request]));
      $this->assertEquals(['slug' => 'slug', 'id' => '8'], $route->getParams());
  }
}
