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
use Psr\Log\LoggerInterface;

class ApiService {
	private IClient $client;
	private Config $config;

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct(
		private string $userId,
		private IRootFolder $root,
		ConfigService $configService,
		IClientService $clientService,
		private LoggerInterface $logger,
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

		$arguments = [
			'document' => $file->fopen('r'),
			'title' => $file->getName(),
		];

		$this->client->post($this->config->url . '/api/documents/post_document/',
			[
				'headers' => $this->getAuthorizationHeaders(),
				'multipart' => array_map(
					static fn (string $key, mixed $value) => ['name' => $key, 'contents' => $value],
					array_keys($arguments),
					array_values($arguments),
				),
			],
		);
	}

	public function searchMessages(string $userId, string $term, int $offset = 0, int $limit = 5): array {
		$arguments = [
			'format' => 'json',
			'query' => '*' . $term . '*' ,
		];

		$paperlessURL = rtrim($this->config->url, '/') . '/api/documents/?' . http_build_query($arguments);
		$result = $this->client->get($paperlessURL,
			[
				'headers' => array_merge(
					$this->getAuthorizationHeaders(),
					[
						'Accept' => 'application/json'
					]
				)
			]
		);

		$body = $result->getBody();
		$json_body = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

		return array_slice($json_body, $offset, $limit);
	}
}
