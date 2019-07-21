<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Entity;

use DateTime;
use DateTimeInterface;

/**
 * The entity holding the entities in the sidebar of the users.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarEntity
{
    /**
     * The ID of the sidebar entity.
     * @var int|null
     */
    protected $id;

    /**
     * The user owning the sidebar entity.
     * @var User
     */
    protected $user;

    /**
     * The type of the entity.
     * @var string
     */
    protected $type = '';

    /**
     * The name of the entity.
     * @var string
     */
    protected $name = '';

    /**
     * The translated label of the entity.
     * @var string
     */
    protected $label = '';

    /**
     * The translated description of the entity.
     * @var string
     */
    protected $description = '';

    /**
     * The pinned position of the entity in the sidebar. 0 if not pinned.
     * @var int
     */
    protected $pinnedPosition = 0;

    /**
     * The time when the entity was last viewed.
     * @var DateTimeInterface
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
    public function setId(?int $id): self
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
    public function setUser(User $user): self
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
    public function setType(string $type): self
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
    public function setName(string $name): self
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
    public function setLabel(string $label): self
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
    public function setDescription(string $description): self
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
    public function setPinnedPosition(int $pinnedPosition): self
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
     * @param DateTimeInterface $lastViewTime
     * @return $this
     */
    public function setLastViewTime(DateTimeInterface $lastViewTime): self
    {
        $this->lastViewTime = $lastViewTime;
        return $this;
    }

    /**
     * Returns the time when the entity was last viewed.
     * @return DateTimeInterface
     */
    public function getLastViewTime(): DateTimeInterface
    {
        return $this->lastViewTime;
    }
}
