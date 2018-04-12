<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity holding the user data.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 *
 * @ORM\Entity(repositoryClass="FactorioItemBrowser\Portal\Database\Repository\UserRepository")
 * @ORM\Table(name="User")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     *
     * The id of the user.
     * @var int|null
     */
    protected $id;

    /**
     * @ORM\Column(name="locale")
     *
     * The locale the user uses.
     * @var string
     */
    protected $locale = '';

    /**
     * @ORM\Column(name="enabledModNames", type="json")
     *
     * The mods the user wants to have enabled.
     * @var array|string[]
     */
    protected $enabledModNames = [];

    /**
     * @ORM\Column(name="lastVisit", type="datetime")
     *
     * The timestamp when the user last visited.
     * @var DateTime
     */
    protected $lastVisit;

    /**
     * @ORM\Column(name="sessionId")
     *
     * The session ID for the user.
     * @var string
     */
    protected $sessionId = '';

    /**
     * @ORM\Column(name="apiAuthorizationToken")
     *
     * The authorization token of the user.
     * @var string
     */
    protected $apiAuthorizationToken = '';

    /**
     * @ORM\Column(name="sessionData", type="json")
     *
     * The data of the user session.
     * @var array
     */
    protected $sessionData = [];

    /**
     * @ORM\OneToMany(targetEntity="SidebarEntity", mappedBy="user", fetch="EAGER")
     *
     * The entities in the sidebar of the user.
     * @var Collection|SidebarEntity[]
     */
    protected $sidebarEntities;

    /**
     * Initializes the entity.
     */
    public function __construct()
    {
        $this->lastVisit = new DateTime();
        $this->sidebarEntities = new ArrayCollection();
    }

    /**
     * Sets the id of the user.
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id)
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
    public function setLocale(string $locale)
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
    public function setEnabledModNames(array $enabledModNames)
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
     * Sets the timestamp when the user last visited.
     * @param DateTime $lastVisit
     * @return $this
     */
    public function setLastVisit(DateTime $lastVisit)
    {
        $this->lastVisit = $lastVisit;
        return $this;
    }

    /**
     * Returns the timestamp when the user last visited.
     * @return DateTime
     */
    public function getLastVisit(): DateTime
    {
        return $this->lastVisit;
    }

    /**
     * Sets the session ID for the user.
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId(string $sessionId)
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
    public function setApiAuthorizationToken(string $apiAuthorizationToken)
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
    public function setSessionData(array $sessionData)
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
}