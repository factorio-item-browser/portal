<?php

declare(strict_types=1);

namespace FactorioItemBrowserTest\Portal\Database\Entity;

use DateTime;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * The PHPUnit test of the SidebarEntity class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \FactorioItemBrowser\Portal\Database\Entity\SidebarEntity
 */
class SidebarEntityTest extends TestCase
{
    /**
     * The mocked user.
     * @var User&MockObject
     */
    protected $user;

    /**
     * Sets up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createMock(User::class);
    }

    /**
     * Tests the constructing.
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $entity = new SidebarEntity($this->user);

        $this->assertNull($entity->getId());
        $this->assertSame($this->user, $entity->getUser());
        $this->assertSame('', $entity->getType());
        $this->assertSame('', $entity->getName());
        $this->assertSame('', $entity->getLabel());
        $this->assertSame('', $entity->getDescription());
        $this->assertSame(0, $entity->getPinnedPosition());
        $entity->getLabel();
    }

    /**
     * Tests the setting and getting the id.
     * @covers ::getId
     * @covers ::setId
     */
    public function testSetAndGetId(): void
    {
        $id = 42;
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setId($id));
        $this->assertSame($id, $entity->getId());
    }

    /**
     * Tests the setting and getting the user.
     * @covers ::getUser
     * @covers ::setUser
     */
    public function testSetAndGetUser(): void
    {
        /* @var User&MockObject $user */
        $user = $this->createMock(User::class);
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setUser($user));
        $this->assertSame($user, $entity->getUser());
    }

    /**
     * Tests the setting and getting the type.
     * @covers ::getType
     * @covers ::setType
     */
    public function testSetAndGetType(): void
    {
        $type = 'abc';
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setType($type));
        $this->assertSame($type, $entity->getType());
    }

    /**
     * Tests the setting and getting the name.
     * @covers ::getName
     * @covers ::setName
     */
    public function testSetAndGetName(): void
    {
        $name = 'abc';
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setName($name));
        $this->assertSame($name, $entity->getName());
    }

    /**
     * Tests the setting and getting the label.
     * @covers ::getLabel
     * @covers ::setLabel
     */
    public function testSetAndGetLabel(): void
    {
        $label = 'abc';
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setLabel($label));
        $this->assertSame($label, $entity->getLabel());
    }

    /**
     * Tests the setting and getting the description.
     * @covers ::getDescription
     * @covers ::setDescription
     */
    public function testSetAndGetDescription(): void
    {
        $description = 'abc';
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setDescription($description));
        $this->assertSame($description, $entity->getDescription());
    }

    /**
     * Tests the setting and getting the pinned position.
     * @covers ::getPinnedPosition
     * @covers ::setPinnedPosition
     */
    public function testSetAndGetPinnedPosition(): void
    {
        $pinnedPosition = 42;
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setPinnedPosition($pinnedPosition));
        $this->assertSame($pinnedPosition, $entity->getPinnedPosition());
    }

    /**
     * Tests the setting and getting the last view time.
     * @covers ::getLastViewTime
     * @covers ::setLastViewTime
     */
    public function testSetAndGetLastViewTime(): void
    {
        $lastViewTime = new DateTime('2038-01-19 03:14:17');
        $entity = new SidebarEntity($this->user);

        $this->assertSame($entity, $entity->setLastViewTime($lastViewTime));
        $this->assertSame($lastViewTime, $entity->getLastViewTime());
    }
}
