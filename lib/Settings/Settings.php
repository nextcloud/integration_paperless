<?php

declare(strict_types=1);

namespace OCA\Paperless\Settings;

use OCA\Paperless\AppInfo\Application;
use OCA\Paperless\Service\ConfigService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\Settings\ISettings;

/** @psalm-suppress UnusedClass */
class Settings implements ISettings {
	public function __construct(
		private IInitialState $initialState,
		private ConfigService $configService,
	) {
	}

	public function getForm(): TemplateResponse {
		$this->initialState->provideInitialState('config', $this->configService->getConfig());
		return new TemplateResponse(Application::APP_ID, 'settings');
	}

	public function getSection(): string {
		return 'connected-accounts';
	}

	public function getPriority(): int {
		return 10;
	}
}
