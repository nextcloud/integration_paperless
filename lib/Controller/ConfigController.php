<?php

declare(strict_types=1);

namespace OCA\Paperless\Controller;

use OCA\Paperless\Model\Config;
use OCA\Paperless\Service\ConfigService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use OCP\PreConditionNotMetException;

/** @psalm-suppress UnusedClass */
class ConfigController extends OCSController {
	public function __construct(
		string $appName,
		IRequest $request,
		private ConfigService $configService,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @return DataResponse<Http::STATUS_OK, null, array{}>
	 * @throws PreConditionNotMetException
	 */
	#[NoAdminRequired]
	public function setConfig(string $url, string $token): DataResponse {
		$this->configService->setConfig(
			new Config(
				$url,
				$token,
			),
		);
		return new DataResponse(null);
	}
}
