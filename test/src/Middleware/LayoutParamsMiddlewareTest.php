<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Middleware;

use BluePsyduck\TestHelper\ReflectionTrait;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Middleware\LayoutParamsMiddleware;
use FactorioItemBrowser\Portal\Service\UserService;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

/**
 * The PHPUnit test of the LayoutParamsMiddleware class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Middleware\LayoutParamsMiddleware
 */
class LayoutParamsMiddlewareTest extends TestCase
{
    use ReflectionTrait;

    /**
     * The mocked layout params helper.
     * @var LayoutParamsHelper&MockObject
     */
    protected $layoutParamsHelper;

    /**
     * The mocked meta session container.
     * @var MetaSessionContainer&MockObject
     */
    protected $metaSessionContainer;

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

        $this->layoutParamsHelper = $this->createMock(LayoutParamsHelper::class);
        $this->metaSessionContainer = $this->createMock(MetaSessionContainer::class);
        $this->userService = $this->createMock(UserService::class);
    }

    /**
     * Tests the constructing.
     * @throws ReflectionException
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $middleware = new LayoutParamsMiddleware(
            $this->layoutParamsHelper,
            $this->metaSessionContainer,
            $this->userService
        );

        $this->assertSame($this->layoutParamsHelper, $this->extractProperty($middleware, 'layoutParamsHelper'));
        $this->assertSame($this->metaSessionContainer, $this->extractProperty($middleware, 'metaSessionContainer'));
        $this->assertSame($this->userService, $this->extractProperty($middleware, 'userService'));
    }

    /**
     * Tests the process method.
     * @covers ::process
     */
    public function testProcess(): void
    {
        $settingsHash = 'abc';
        $numberOfAvailableMods = 42;
        $numberOfEnabledMods = 21;

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
                    ->method('getSettingsHash')
                    ->willReturn($settingsHash);

        $this->layoutParamsHelper->expects($this->once())
                                 ->method('setSettingsHash')
                                 ->with($this->identicalTo($settingsHash))
                                 ->willReturnSelf();
        $this->layoutParamsHelper->expects($this->once())
                                 ->method('setNumberOfAvailableMods')
                                 ->with($this->identicalTo($numberOfAvailableMods))
                                 ->willReturnSelf();
        $this->layoutParamsHelper->expects($this->once())
                                 ->method('setNumberOfEnabledMods')
                                 ->with($this->identicalTo($numberOfEnabledMods))
                                 ->willReturnSelf();

        $this->metaSessionContainer->expects($this->once())
                                   ->method('getNumberOfAvailableMods')
                                   ->willReturn($numberOfAvailableMods);
        $this->metaSessionContainer->expects($this->once())
                                   ->method('getNumberOfEnabledMods')
                                   ->willReturn($numberOfEnabledMods);

        $this->userService->expects($this->once())
                          ->method('getCurrentUser')
                          ->willReturn($currentUser);

        $middleware = new LayoutParamsMiddleware(
            $this->layoutParamsHelper,
            $this->metaSessionContainer,
            $this->userService
        );
        $result = $middleware->process($request, $handler);

        $this->assertSame($response, $result);
    }
}
