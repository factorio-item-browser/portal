<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity holding the entities in the sidebar of the users.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 *
 * @ORM\Entity(repositoryClass="FactorioItemBrowser\Portal\Database\Repository\SidebarEntityRepository")
 * @ORM\Table(name="SidebarEntity")
 */
class SidebarEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     *
     * The ID of the sidebar entity.
     * @var int|null
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sidebarEntities")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     *
     * The user owning the sidebar entity.
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(name="type")
     *
     * The type of the entity.
     * @var string
     */
    protected $type = '';

    /**
     * @ORM\Column(name="name")
     *
     * The name of the entity.
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(name="label")
     *
     * The translated label of the entity.
     * @var string
     */
    protected $label = '';

    /**
     * @ORM\Column(name="description")
     *
     * The translated description of the entity.
     * @var string
     */
    protected $description = '';

    /**
     * @ORM\Column(name="pinnedPosition", type="integer")
     *
     * The pinned position of the entity in the sidebar. 0 if not pinned.
     * @var int
     */
    protected $pinnedPosition = 0;

    /**
     * @ORM\Column(name="lastViewTime", type="datetime")
     *
     * The time when the entity was last viewed.
     * @var DateTime
     */
    protected $lastViewTime;

    /**
     * Initializes the entity.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->lastViewTime = new DateTime();
    }

    /**
     * Sets the ID of the sidebar entity.
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the ID of the sidebar entity.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the user owning the sidebar entity.
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Returns the user owning the sidebar entity.
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Sets the type of the entity.
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the type of the entity.
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the name of the entity.
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the name of the entity.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the translated label of the entity.
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Returns the translated label of the entity.
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Sets the translated description of the entity.
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Returns the translated description of the entity.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the pinned position of the entity in the sidebar. 0 if not pinned.
     * @param int $pinnedPosition
     * @return $this
     */
    public function setPinnedPosition(int $pinnedPosition)
    {
        $this->pinnedPosition = $pinnedPosition;
        return $this;
    }

    /**
     * Returns the pinned position of the entity in the sidebar. 0 if not pinned.
     * @return int
     */
    public function getPinnedPosition(): int
    {
        return $this->pinnedPosition;
    }

    /**
     * Sets the time when the entity was last viewed.
     * @param DateTime $lastViewTime
     * @return $this
     */
    public function setLastViewTime(DateTime $lastViewTime)
    {
        $this->lastViewTime = $lastViewTime;
        return $this;
    }

    /**
     * Returns the time when the entity was last viewed.
     * @return DateTime
     */
    public function getLastViewTime(): DateTime
    {
        return $this->lastViewTime;
    }
}
