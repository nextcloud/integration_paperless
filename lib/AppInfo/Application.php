<?php

declare(strict_types=1);

namespace OCA\Paperless\AppInfo;

use OCA\Paperless\Listener\FileActionListener;
use OCA\Paperless\Search\SearchProvider;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Collaboration\Resources\LoadAdditionalScriptsEvent;

class Application extends App implements IBootstrap {
	public const APP_ID = 'integration_paperless';

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(LoadAdditionalScriptsEvent::class, FileActionListener::class);
		$context->registerSearchProvider(SearchProvider::class);
	}

	public function boot(IBootContext $context): void {
	}
}
