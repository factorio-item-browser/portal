<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Database\Entity;

use BluePsyduck\TestHelper\ReflectionTrait;
use DateTime;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * The PHPUnit test of the User class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Database\Entity\User
 */
class UserTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Tests the constructing.
     * @covers ::__construct
     * @covers ::getSidebarEntities
     */
    public function testConstruct(): void
    {
        $sessionId = 'abc';
        $user = new User($sessionId);

        $this->assertNull($user->getId());
        $this->assertSame('', $user->getLocale());
        $this->assertSame(Config::DEFAULT_MODS, $user->getEnabledModNames());
        $this->assertSame(Config::DEFAULT_RECIPE_MODE, $user->getRecipeMode());
        $user->getLastVisit();
        $this->assertTrue($user->getIsFirstVisit());
        $this->assertSame($sessionId, $user->getSessionId());
        $this->assertSame('', $user->getApiAuthorizationToken());
        $this->assertSame([], $user->getSessionData());
        $user->getSidebarEntities();
    }

    /**
     * Tests the setting and getting the id.
     * @covers ::getId
     * @covers ::setId
     */
    public function testSetAndGetId(): void
    {
        $id = 42;
        $user = new User('foo');

        $this->assertSame($user, $user->setId($id));
        $this->assertSame($id, $user->getId());
    }

    /**
     * Tests the setting and getting the locale.
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testSetAndGetLocale(): void
    {
        $locale = 'abc';
        $user = new User('foo');

        $this->assertSame($user, $user->setLocale($locale));
        $this->assertSame($locale, $user->getLocale());
    }

    /**
     * Tests the setting and getting the enabled mod names.
     * @covers ::getEnabledModNames
     * @covers ::setEnabledModNames
     */
    public function testSetAndGetEnabledModNames(): void
    {
        $enabledModNames = ['abc', 'def'];
        $user = new User('foo');

        $this->assertSame($user, $user->setEnabledModNames($enabledModNames));
        $this->assertSame($enabledModNames, $user->getEnabledModNames());
    }

    /**
     * Tests the setting and getting the recipe mode.
     * @covers ::getRecipeMode
     * @covers ::setRecipeMode
     */
    public function testSetAndGetRecipeMode(): void
    {
        $recipeMode = 'abc';
        $user = new User('foo');

        $this->assertSame($user, $user->setRecipeMode($recipeMode));
        $this->assertSame($recipeMode, $user->getRecipeMode());
    }

    /**
     * Tests the setting and getting the last visit.
     * @covers ::getLastVisit
     * @covers ::setLastVisit
     */
    public function testSetAndGetLastVisit(): void
    {
        $lastVisit = new DateTime('2038-01-19 03:14:07');
        $user = new User('foo');

        $this->assertSame($user, $user->setLastVisit($lastVisit));
        $this->assertSame($lastVisit, $user->getLastVisit());
    }

    /**
     * Tests the setting and getting the is first visit.
     * @covers ::getIsFirstVisit
     * @covers ::setIsFirstVisit
     */
    public function testSetAndGetIsFirstVisit(): void
    {
        $user = new User('foo');

        $this->assertSame($user, $user->setIsFirstVisit(false));
        $this->assertFalse($user->getIsFirstVisit());
    }

    /**
     * Tests the setting and getting the session id.
     * @covers ::getSessionId
     * @covers ::setSessionId
     */
    public function testSetAndGetSessionId(): void
    {
        $sessionId = 'abc';
        $user = new User('foo');

        $this->assertSame($user, $user->setSessionId($sessionId));
        $this->assertSame($sessionId, $user->getSessionId());
    }

    /**
     * Tests the setting and getting the api authorization token.
     * @covers ::getApiAuthorizationToken
     * @covers ::setApiAuthorizationToken
     */
    public function testSetAndGetApiAuthorizationToken(): void
    {
        $apiAuthorizationToken = 'abc';
        $user = new User('foo');

        $this->assertSame($user, $user->setApiAuthorizationToken($apiAuthorizationToken));
        $this->assertSame($apiAuthorizationToken, $user->getApiAuthorizationToken());
    }

    /**
     * Tests the setting and getting the session data.
     * @covers ::getSessionData
     * @covers ::setSessionData
     */
    public function testSetAndGetSessionData(): void
    {
        $sessionData = ['abc' => 'def', 'ghi' => 'jkl'];
        $user = new User('foo');

        $this->assertSame($user, $user->setSessionData($sessionData));
        $this->assertSame($sessionData, $user->getSessionData());
    }

    /**
     * Tests the getPinnedSidebarEntities method.
     * @covers ::getPinnedSidebarEntities
     */
    public function testGetPinnedSidebarEntities(): void
    {
        $user = new User('foo');

        $entity1 = new SidebarEntity($user);
        $entity1->setPinnedPosition(42);
        $entity2 = new SidebarEntity($user);
        $entity2->setPinnedPosition(0);
        $entity3 = new SidebarEntity($user);
        $entity3->setPinnedPosition(21);

        $expectedEntities = [$entity3, $entity1];

        $user->getSidebarEntities()->add($entity1);
        $user->getSidebarEntities()->add($entity2);
        $user->getSidebarEntities()->add($entity3);

        $result = $user->getPinnedSidebarEntities();

        $this->assertEquals($expectedEntities, array_values($result->toArray()));
    }

    /**
     * Tests the getUnpinnedSidebarEntities method.
     * @covers ::getUnpinnedSidebarEntities
     */
    public function testGetUnpinnedSidebarEntities(): void
    {
        $user = new User('foo');

        $entity1 = new SidebarEntity($user);
        $entity1->setPinnedPosition(0)
                ->setLastViewTime(new DateTime('2038-01-18'));
        $entity2 = new SidebarEntity($user);
        $entity2->setPinnedPosition(42);
        $entity3 = new SidebarEntity($user);
        $entity3->setPinnedPosition(0)
                ->setLastViewTime(new DateTime('2038-01-19'));

        $expectedEntities = [$entity3, $entity1];

        $user->getSidebarEntities()->add($entity1);
        $user->getSidebarEntities()->add($entity2);
        $user->getSidebarEntities()->add($entity3);

        $result = $user->getUnpinnedSidebarEntities();

        $this->assertEquals($expectedEntities, array_values($result->toArray()));
    }

    /**
     * Tests the getSettingsHash method.
     * @covers ::getSettingsHash
     */
    public function testGetSettingsHash(): void
    {
        $user = new User('foo');
        $user->setApiAuthorizationToken('abc')
             ->setLocale('def')
             ->setRecipeMode('ghi');

        $result = $user->getSettingsHash();
        $this->assertSame('bf7ce9d4', $result);
    }
}
