<?php

/**
 * This file contains code derived from Nextcloud - Zulip.
 * @copyright Julien Veyssier 2022
 * @copyright Anupam Kumar 2023
 * @copyright Edward Ly 2024
 *
 * This code is licensed under the GNU General Public License version 3.
 * For details, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */

declare(strict_types=1);

namespace OCA\Paperless\Controller;

use OCA\Paperless\Service\APIService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\IRequest;

class SearchController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private APIService $apiService,
		private ?string $userId,
	) {
		parent::__construct($appName, $request);
	}

	#[NoCSRFRequired]
	#[NoAdminRequired]
	public function searchDocuments(string $query, int $limit = 10, int $offset = 0): DataResponse {
		$results = $this->apiService->search($this->userId, $query, $limit, $offset);

		if (isset($results['error'])) {
			return new DataResponse(['error' => $results['error']], Http::STATUS_BAD_REQUEST);
		}

		return new DataResponse($results);
	}
}
