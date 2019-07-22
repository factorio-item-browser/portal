<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Service;

use BluePsyduck\TestHelper\ReflectionTrait;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Repository\UserRepository;
use FactorioItemBrowser\Portal\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * The PHPUnit test of the UserService class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Service\UserService
 */
class UserServiceTest extends TestCase
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
     * @covers ::generateSessionId
     */
    public function testConstruct(): void
    {
        $service = new UserService($this->userRepository);

        $this->assertSame($this->userRepository, $this->extractProperty($service, 'userRepository'));

        $user = $service->getCurrentUser();
        $this->assertNotEmpty($user->getSessionId());
    }

    /**
     * Tests the getUserBySessionId method.
     * @throws ReflectionException
     * @covers ::getUserBySessionId
     */
    public function testGetUserBySessionId(): void
    {
        $sessionId = 'abc';

        /* @var User&MockObject $user */
        $user = $this->createMock(User::class);

        $this->userRepository->expects($this->once())
                             ->method('findBySessionId')
                             ->with($this->identicalTo($sessionId))
                             ->willReturn($user);

        $service = new UserService($this->userRepository);
        $result = $service->getUserBySessionId($sessionId);

        $this->assertSame($user, $result);
    }

    /**
     * Tests the setCurrentUser method.
     * @throws ReflectionException
     * @covers ::setCurrentUser
     */
    public function testSetCurrentUser(): void
    {
        /* @var User&MockObject $user */
        $user = $this->createMock(User::class);

        $service = new UserService($this->userRepository);
        $service->setCurrentUser($user);

        $this->assertSame($user, $this->extractProperty($service, 'currentUser'));
    }

    /**
     * Tests the getCurrentUser method.
     * @throws ReflectionException
     * @covers ::getCurrentUser
     */
    public function testGetCurrentUser(): void
    {
        /* @var User&MockObject $user */
        $user = $this->createMock(User::class);

        $service = new UserService($this->userRepository);
        $this->injectProperty($service, 'currentUser', $user);

        $result = $service->getCurrentUser();

        $this->assertSame($user, $result);
    }

    /**
     * Tests the persistCurrentUser method.
     * @throws ReflectionException
     * @covers ::persistCurrentUser
     */
    public function testPersistCurrentUser(): void
    {
        /* @var User&MockObject $user */
        $user = $this->createMock(User::class);

        $this->userRepository->expects($this->once())
                             ->method('persist')
                             ->with($user);

        $service = new UserService($this->userRepository);
        $this->injectProperty($service, 'currentUser', $user);

        $service->persistCurrentUser();
    }
}
