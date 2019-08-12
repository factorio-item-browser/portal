<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Middleware;

use BluePsyduck\TestHelper\ReflectionTrait;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Middleware\LocaleMiddleware;
use FactorioItemBrowser\Portal\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;
use Zend\I18n\Translator\Translator;

/**
 * The PHPUnit test of the LocaleMiddleware class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Middleware\LocaleMiddleware
 */
class LocaleMiddlewareTest extends TestCase
{
    use ReflectionTrait;

    /**
     * The mocked translator.
     * @var Translator&MockObject
     */
    protected $translator;

    /**
     * The mocked user service.
     * @var UserService&MockObject
     */
    protected $userService;

    /**
     * Sets up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserService::class);
        $this->translator = $this->createMock(Translator::class);
    }

    /**
     * Tests the constructing.
     * @throws ReflectionException
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $locales = ['abc' => 'def'];

        $middleware = new LocaleMiddleware($this->translator, $this->userService, $locales);

        $this->assertSame($this->translator, $this->extractProperty($middleware, 'translator'));
        $this->assertSame($this->userService, $this->extractProperty($middleware, 'userService'));
        $this->assertSame($locales, $this->extractProperty($middleware, 'locales'));
    }

    /**
     * Tests the process method.
     * @covers ::process
     */
    public function testProcessWithUserLocale(): void
    {
        $locale = 'abc';

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

        /* @var User&MockObject $currentUser */
        $currentUser = $this->createMock(User::class);
        $currentUser->expects($this->once())
                    ->method('getLocale')
                    ->willReturn($locale);
        $currentUser->expects($this->never())
                    ->method('setLocale');


        $this->userService->expects($this->once())
                          ->method('getCurrentUser')
                          ->willReturn($currentUser);

        $this->translator->expects($this->once())
                         ->method('setLocale')
                         ->with($this->identicalTo($locale))
                         ->willReturnSelf();
        $this->translator->expects($this->once())
                         ->method('setFallbackLocale')
                         ->with($this->identicalTo('en'))
                         ->willReturnSelf();

        /* @var LocaleMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(LocaleMiddleware::class)
                           ->setMethods(['detectLocaleFromRequest'])
                           ->setConstructorArgs([$this->translator, $this->userService, []])
                           ->getMock();
        $middleware->expects($this->never())
                   ->method('detectLocaleFromRequest');

        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }

    /**
     * Tests the process method.
     * @throws ReflectionException
     * @covers ::process
     */
    public function testProcessWithoutUserLocale(): void
    {
        $locale = 'abc';

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

        /* @var User&MockObject $currentUser */
        $currentUser = $this->createMock(User::class);
        $currentUser->expects($this->once())
                    ->method('getLocale')
                    ->willReturn('');
        $currentUser->expects($this->once())
                    ->method('setLocale')
                    ->with($this->identicalTo($locale));

        $this->userService->expects($this->once())
                          ->method('getCurrentUser')
                          ->willReturn($currentUser);

        $this->translator->expects($this->once())
                         ->method('setLocale')
                         ->with($this->identicalTo($locale))
                         ->willReturnSelf();
        $this->translator->expects($this->once())
                         ->method('setFallbackLocale')
                         ->with($this->identicalTo('en'))
                         ->willReturnSelf();

        /* @var LocaleMiddleware&MockObject $middleware */
        $middleware = $this->getMockBuilder(LocaleMiddleware::class)
                           ->setMethods(['detectLocaleFromRequest'])
                           ->setConstructorArgs([$this->translator, $this->userService, []])
                           ->getMock();
        $middleware->expects($this->once())
                   ->method('detectLocaleFromRequest')
                   ->with($this->identicalTo($request))
                   ->willReturn($locale);

        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }

    /**
     * Provides the data for the detectLocaleFromRequest test.
     * @return array
     */
    public function provideDetectLocaleFromRequest(): array
    {
        $locales = [
            'de' => 'foo',
            'de-CH' => 'bar',
        ];

        return [
            [$locales, 'de', 'de'],
            [$locales, 'foo', 'en'],
            [$locales, '', 'en'],

            [$locales, 'de-CH, de;q=0.9, en;q=0.8, *;q=0.5', 'de-CH'],
            [$locales, 'de-AT, de;q=0.9, en;q=0.8, *;q=0.5', 'de'],
            [$locales, 'fr-CH, fr;q=0.9, en;q=0.8, *;q=0.5', 'en'],
        ];
    }

    /**
     * Tests the detectLocaleFromRequest method.
     * @param array|string[] $locales
     * @param string $acceptLanguage
     * @param string $expectedResult
     * @throws ReflectionException
     * @covers ::detectLocaleFromRequest
     * @dataProvider provideDetectLocaleFromRequest
     */
    public function testDetectLocaleFromRequest(array $locales, string $acceptLanguage, string $expectedResult): void
    {
        /* @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
                ->method('getHeaderLine')
                ->with($this->identicalTo('Accept-Language'))
                ->willReturn($acceptLanguage);

        $middleware = new LocaleMiddleware($this->translator, $this->userService, $locales);
        $result = $this->invokeMethod($middleware, 'detectLocaleFromRequest', $request);

        $this->assertSame($expectedResult, $result);
    }
}
