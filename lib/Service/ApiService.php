<?php

declare(strict_types=1);

namespace OCA\Paperless\Service;

use Exception;
use OCA\Paperless\Model\Config;
use OCP\AppFramework\OCS\OCSBadRequestException;
use OCP\Files\File;
use OCP\Files\IRootFolder;
use OCP\Files\NotPermittedException;
use OCP\Http\Client\IClient;
use OCP\Http\Client\IClientService;

class ApiService {
	private IClient $client;
	private Config $config;

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct(
		private string $userId,
		private IRootFolder $root,
		ConfigService $configService,
		IClientService $clientService,
	) {
		$this->client = $clientService->newClient();
		$this->config = $configService->getConfig();
	}


	/**
	 * @return array<string, string>
	 */
	private function getAuthorizationHeaders(): array {
		return [
			'Authorization' => 'Token ' . $this->config->token
		];
	}

	/**
	 * @throws NotPermittedException
	 * @throws OCSBadRequestException
	 * @throws Exception
	 */
	public function sendFile(int $fileId): void {
		$userFolder = $this->root->getUserFolder($this->userId);
		$files = $userFolder->getById($fileId);
		if (empty($files) || !$files[0] instanceof File) {
			throw new OCSBadRequestException('File ID ' . $fileId . ' not found');
		}
		$file = $files[0];

		$this->client->post($this->config->url . '/api/documents/post_document/',
			[
				'headers' => $this->getAuthorizationHeaders(),
				'multipart' => [
					[
						'name' => 'document',
						'contents' => $file->fopen('r'),
						'filename' => $file->getName(),
						'headers' => [
							'Content-Type' => $file->getMimeType(),
						],
					],
					[
						'name' => 'title',
						'contents' => $file->getName(),
					],
				],
				'nextcloud' => [
					'allow_local_address' => true,
				],
			],
		);
	}
}
