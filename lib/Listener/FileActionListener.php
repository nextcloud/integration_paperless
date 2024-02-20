<?php

declare(strict_types=1);

namespace OCA\Paperless\Listener;

use OCA\Paperless\AppInfo\Application;
use OCA\Paperless\Service\ConfigService;
use OCP\Collaboration\Resources\LoadAdditionalScriptsEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

/**
 * @implements IEventListener<LoadAdditionalScriptsEvent>
 */
class FileActionListener implements IEventListener {
	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct(
		private ConfigService $configService,
	) {
	}

	public function handle(Event $event): void {
		$config = $this->configService->getConfig();
		if ($config->isComplete()) {
			Util::addInitScript(Application::APP_ID, Application::APP_ID . '-file_action');
		}
	}
}
