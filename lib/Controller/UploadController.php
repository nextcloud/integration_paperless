<?php

declare(strict_types=1);

namespace OCA\Paperless\Controller;

use Exception;
use OCA\Paperless\Service\ApiService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Psr\Log\LoggerInterface;

/** @psalm-suppress UnusedClass */
class UploadController extends OCSController {
	public function __construct(
		string $appName,
		IRequest $request,
		private ApiService $apiService,
		private LoggerInterface $logger,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @return DataResponse<Http::STATUS_OK|Http::STATUS_INTERNAL_SERVER_ERROR, null, array{}>
	 */
	#[NoAdminRequired]
	public function upload(int $fileId): DataResponse {
		try {
			$this->apiService->sendFile($fileId);
			return new DataResponse(null);
		} catch (Exception $e) {
			$this->logger->debug($e->getMessage());
			return new DataResponse(null, Http::STATUS_INTERNAL_SERVER_ERROR);
		}
	}
}
