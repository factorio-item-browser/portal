<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use FactorioItemBrowser\Portal\Constant\Config;

/**
 * The entity holding the user data.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class User
{
    /**
     * The id of the user.
     * @var int|null
     */
    protected $id;

    /**
     * The locale the user uses.
     * @var string
     */
    protected $locale = '';

    /**
     * The mods the user wants to have enabled.
     * @var array|string[]
     */
    protected $enabledModNames = Config::DEFAULT_MODS;

    /**
     * The recipe mode the user wants to use.
     * @var string
     */
    protected $recipeMode = Config::DEFAULT_RECIPE_MODE;

    /**
     * The timestamp when the user last visited.
     * @var DateTimeInterface
     */
    protected $lastVisit;

    /**
     * Whether this is the first visit of the user.
     * @var bool
     */
    protected $isFirstVisit = true;

    /**
     * The session ID for the user.
     * @var string
     */
    protected $sessionId = '';

    /**
     * The authorization token of the user.
     * @var string
     */
    protected $apiAuthorizationToken = '';

    /**
     * The data of the user session.
     * @var array
     */
    protected $sessionData = [];

    /**
     * The entities in the sidebar of the user.
     * @var Collection|SidebarEntity[]
     */
    protected $sidebarEntities;

    /**
     * Initializes the entity.
     * @param string $sessionId
     */
    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;

        $this->lastVisit = new DateTime();
        $this->sidebarEntities = new ArrayCollection();
    }

    /**
     * Sets the id of the user.
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the id of the user.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the locale the user uses.
     * @param string $locale
     * @return $this
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Returns the locale the user uses.
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the mods the user wants to have enabled.
     * @param array|string[] $enabledModNames
     * @return $this
     */
    public function setEnabledModNames(array $enabledModNames): self
    {
        $this->enabledModNames = $enabledModNames;
        return $this;
    }

    /**
     * Returns the mods the user wants to have enabled.
     * @return array|string[]
     */
    public function getEnabledModNames(): array
    {
        return $this->enabledModNames;
    }

    /**
     * Sets the recipe mode the user wants to use.
     * @param string $recipeMode
     * @return $this
     */
    public function setRecipeMode(string $recipeMode): self
    {
        $this->recipeMode = $recipeMode;
        return $this;
    }

    /**
     * Returns the recipe mode the user wants to use.
     * @return string
     */
    public function getRecipeMode(): string
    {
        return $this->recipeMode;
    }

    /**
     * Sets the timestamp when the user last visited.
     * @param DateTimeInterface $lastVisit
     * @return $this
     */
    public function setLastVisit(DateTimeInterface $lastVisit): self
    {
        $this->lastVisit = $lastVisit;
        return $this;
    }

    /**
     * Returns the timestamp when the user last visited.
     * @return DateTimeInterface
     */
    public function getLastVisit(): DateTimeInterface
    {
        return $this->lastVisit;
    }

    /**
     * Sets whether this is the first visit of the user.
     * @param bool $isFirstVisit
     * @return $this
     */
    public function setIsFirstVisit(bool $isFirstVisit): self
    {
        $this->isFirstVisit = $isFirstVisit;
        return $this;
    }

    /**
     * Returns whether this is the first visit of the user.
     * @return bool
     */
    public function getIsFirstVisit(): bool
    {
        return $this->isFirstVisit;
    }

    /**
     * Sets the session ID for the user.
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * Returns the session ID for the user.
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * Sets the authorization token of the user.
     * @param string $apiAuthorizationToken
     * @return $this
     */
    public function setApiAuthorizationToken(string $apiAuthorizationToken): self
    {
        $this->apiAuthorizationToken = $apiAuthorizationToken;
        return $this;
    }

    /**
     * Returns the authorization token of the user.
     * @return string
     */
    public function getApiAuthorizationToken(): string
    {
        return $this->apiAuthorizationToken;
    }

    /**
     * Sets the data of the user session.
     * @param array $sessionData
     * @return $this
     */
    public function setSessionData(array $sessionData): self
    {
        $this->sessionData = $sessionData;
        return $this;
    }

    /**
     * Returns the data of the user session.
     * @return array
     */
    public function getSessionData(): array
    {
        return $this->sessionData;
    }

    /**
     * Returns the entities in the sidebar of the user.
     * @return Collection|SidebarEntity[]
     */
    public function getSidebarEntities(): Collection
    {
        return $this->sidebarEntities;
    }

    /**
     * Returns the pinned sidebar entities of the user.
     * @return Collection
     */
    public function getPinnedSidebarEntities(): Collection
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->gt('pinnedPosition', 0))
                 ->orderBy(['pinnedPosition' => Criteria::ASC]);

        return $this->sidebarEntities->matching($criteria);
    }

    /**
     * Returns the unpinned sidebar entities of the user.
     * @return Collection
     */
    public function getUnpinnedSidebarEntities(): Collection
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('pinnedPosition', 0))
                 ->orderBy(['lastViewTime' => Criteria::DESC]);

        return $this->sidebarEntities->matching($criteria);
    }

    /**
     * Returns a hash representing the user's settings.
     * @return string
     */
    public function getSettingsHash(): string
    {
        return hash('crc32b', (string) json_encode([
            $this->apiAuthorizationToken,
            $this->locale,
            $this->recipeMode,
        ]));
    }
}
