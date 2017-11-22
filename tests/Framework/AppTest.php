<?php
namespace Tests\Framework;

use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerResponseInterface;
use PHPUnit\Framework\TestCase;


class AppTest extends TestCase {


    public function testRedirectTrailingSlash() {
      $app = new App();
      $request = new ServerRequest('GET', '/testslash/');
      $response = $app->run($request);
      $this->assertContains('/testslash', $response->getHeader('Location'));
      $this->assertEquals( 301, $response->getStatusCode());
    }

    public function testSite() {
      $app = new App();
      $request = new ServerRequest('GET', '/site');
      $response = $app->run($request);
      $this->assertContains('<h1>Bienvenue sur le site</h1>', (string)$response->getBody());
      $this->assertEquals(200, $response->getStatusCode());
    }

    public function testError404() {
      $app = new App();
      $request = new ServerRequest('GET', '/aze');
      $response = $app->run($request);
      $this->assertContains('<h1>Erreur 404</h1>', (string)$response->getBody());
      $this->assertEquals(404, $response->getStatusCode());
    }
}
