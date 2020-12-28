<?php

declare(strict_types=1);

namespace OCA\Printer;

use OCP\AppFramework\Utility\ITimeFactory;
use OCP\IConfig;
use OCP\IGroupManager;
use OCP\IUser;
use OCP\Security\ISecureRandom;

class Config
{
    /**
     * @var IConfig
     */
    protected $config;

    /**
     * @var ITimeFactory
     */
    protected $timeFactory;

    /**
     * @var IGroupManager
     */
    private $groupManager;

    /**
     * @var ISecureRandom
     */
    private $secureRandom;

    public function __construct(IConfig $config, IGroupManager $groupManager)
    {
        $this->config = $config;
        $this->groupManager = $groupManager;
    }

    /**
     * @return string[]
     */
    public function getAllowedGroupIds(): array
    {
        $groups = $this->config->getAppValue('printer', 'allowed_groups', '[]');
        $groups = json_decode($groups, true);

        return \is_array($groups) ? $groups : [];
    }

    public function isDisabledForUser(IUser $user): bool
    {
        $allowedGroups = $this->getAllowedGroupIds();

        if (empty($allowedGroups)) {
            return false;
        }

        $userGroups = $this->groupManager->getUserGroupIds($user);

        return empty(array_intersect($allowedGroups, $userGroups));
    }
}
