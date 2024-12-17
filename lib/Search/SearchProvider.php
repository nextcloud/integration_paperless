<?php
/**
 * This file contains code derived from Nextcloud - Zulip
 * @copyright Copyright (c) 2024, Edward Ly
 *
 * @author Edward Ly <contact@edward.ly>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

declare(strict_types=1);

namespace OCA\Paperless\Search;

use OCA\Paperless\AppInfo\Application;
use OCA\Paperless\Service\ApiService;
use OCA\Paperless\Service\ConfigService;
use OCP\App\IAppManager;
use OCP\IConfig;
use OCP\IDateTimeFormatter;
use OCP\IDateTimeZone;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\Search\IProvider;
use OCP\Search\ISearchQuery;
use OCP\Search\SearchResult;
use OCP\Search\SearchResultEntry;
use Psr\Log\LoggerInterface;

class SearchProvider implements IProvider {

	public function __construct(
		private LoggerInterface $logger,
		private IAppManager $appManager,
		private IL10N $l10n,
		private IConfig $config,
		private IURLGenerator $urlGenerator,
		private IDateTimeFormatter $dateTimeFormatter,
		private IDateTimeZone $dateTimeZone,
		private ConfigService $configService,
		private ApiService $apiService,
	) {
	}

	public function getId(): string {
		return 'paperless-search-messages';
	}

	public function getName(): string {
		return $this->l10n->t('Paperless Search Result');
	}

	public function getOrder(string $route, array $routeParameters): int {
		return 20; // Adjust priority as needed
	}

	public function search(IUser $user, ISearchQuery $query): SearchResult {
		if (!$this->appManager->isEnabledForUser(Application::APP_ID, $user)) {
			return SearchResult::complete($this->getName(), []);
		}

		$limit = $query->getLimit();
		$term = $query->getTerm();
		$offset = $query->getCursor();
		$offset = $offset ? intval($offset) : 0;

		$url = $this->config->getUserValue($user->getUID(), Application::APP_ID, 'url');
		$apiKey = $this->configService->getConfig($user->getUID(), 'token');

		if ($url === '' || $apiKey === '') {
			return SearchResult::paginated($this->getName(), [], 0);
		}

		// Call Paperless API
		$searchResult = $this->apiService->searchMessages($user->getUID(), $term, $offset, $limit);
		if (isset($searchResult['error'])) {
			return SearchResult::paginated($this->getName(), [], 0);
		}

		$dataEntries = $searchResult['results'] ?? [];
		$formattedResults = array_map(function (array $entry) use ($url): SearchResultEntry {
			$finalThumbnailUrl = $this->getThumbnailUrl($entry);
			$title = $entry['title'] ?? 'Untitled';
			$context = $entry['__search_hit__']['highlights'] ?? '';
			$link = $this->getLinkToPaperless($entry, $url);
			return new SearchResultEntry(
				$finalThumbnailUrl,
				$title,
				strip_tags($context),
				$link,
				$finalThumbnailUrl === '' ? 'icon-paperless-search-fallback' : '',
				true
			);
		}, $dataEntries);

		return SearchResult::paginated(
			$this->getName(),
			$formattedResults,
			$offset + count($dataEntries)
		);
	}

	/**
	 * @param array $entry
	 * @param string $url
	 * @return string
	 */
	protected function getLinkToPaperless(array $entry, string $url): string {
		return rtrim($url, '/') . '/documents/' . ($entry['id'] ?? '#');
	}

	/**
	 * @param array $entry
	 * @return string
	 */
	protected function getThumbnailUrl(array $entry): string {
		return '';
	}
}
