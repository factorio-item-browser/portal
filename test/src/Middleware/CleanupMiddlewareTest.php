<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Middleware;

use BluePsyduck\TestHelper\ReflectionTrait;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Repository\UserRepository;
use FactorioItemBrowser\Portal\Middleware\CleanupMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

/**
 * The PHPUnit test of the CleanupMiddleware class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Middleware\CleanupMiddleware
 */
class CleanupMiddlewareTest extends TestCase
{
    use ReflectionTrait;

    /**
     * The mocked user repository.
     * @var UserRepository&MockObject
     */
    protected $userRepository;

    /**
     * Sets up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
    }

    /**
     * Tests the constructing.
     * @throws ReflectionException
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $middleware = new CleanupMiddleware($this->userRepository);

        $this->assertSame($this->userRepository, $this->extractProperty($middleware, 'userRepository'));
    }

    /**
     * Tests the process method.
     * @covers ::process
     */
    public function testProcessWithHit(): void
    {
        $randomNumber = 42;

        /* @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        /* @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);

        /* @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->identicalTo($request))
                ->willReturn($response);

        $this->userRepository->expects($this->once())
                             ->method('cleanup');

        /* @var CleanupMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(CleanupMiddleware::class)
                           ->setMethods(['getRandomNumber'])
                           ->setConstructorArgs([$this->userRepository])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('getRandomNumber')
                   ->willReturn($randomNumber);

        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }

    /**
     * Tests the process method.
     * @covers ::process
     */
    public function testProcessWithoutHit(): void
    {
        $randomNumber = 21;

        /* @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        /* @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);

        /* @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->identicalTo($request))
                ->willReturn($response);

        $this->userRepository->expects($this->never())
                             ->method('cleanup');

        /* @var CleanupMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(CleanupMiddleware::class)
                           ->setMethods(['getRandomNumber'])
                           ->setConstructorArgs([$this->userRepository])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('getRandomNumber')
                   ->willReturn($randomNumber);

        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }

    /**
     * Tests the getRandomNumber method.
     * @throws ReflectionException
     * @covers ::getRandomNumber
     */
    public function testGetRandomNumber(): void
    {
        $middleware = new CleanupMiddleware($this->userRepository);

        $result = $this->invokeMethod($middleware, 'getRandomNumber');

        $this->assertGreaterThanOrEqual(0, $result);
        $this->assertLessThanOrEqual(Config::CLEANUP_FACTOR, $result);
    }
}
