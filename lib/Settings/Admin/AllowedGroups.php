<?php

declare(strict_types=1);

namespace OCA\Printer\Settings\Admin;

use OCA\Printer\Config;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IGroupManager;
use OCP\IInitialStateService;
use OCP\Settings\ISettings;

class AllowedGroups implements ISettings
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var IInitialStateService
     */
    private $initialStateService;

    /**
     * @var IGroupManager
     */
    private $groupManager;

    public function __construct(Config $config, IInitialStateService $initialStateService, IGroupManager $groupManager)
    {
        $this->config = $config;
        $this->initialStateService = $initialStateService;
        $this->groupManager = $groupManager;
    }

    public function getForm(): TemplateResponse
    {
        $this->initialStateService->provideInitialState(
            'printer',
            'allowed_groups',
            $this->config->getAllowedGroupIds()
        );

        $groups = $this->groupManager->search('', 100);
        $allowedGroups = $this->config->getAllowedGroupIds();

        return new TemplateResponse('printer', 'settings/admin/allowed-groups', [
            'groups' => $groups,
            'allowedGroups' => $allowedGroups,
        ], '');
    }

    public function getSection(): string
    {
        return 'printer';
    }

    public function getPriority(): int
    {
        return 10;
    }
}
