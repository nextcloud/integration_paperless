<?php

declare(strict_types=1);

namespace OCA\Paperless\Model;

use JsonSerializable;

class Config implements JsonSerializable {
	public function __construct(
		public string $url,
		public string $token,
	) {
	}

	public function isComplete(): bool {
		return !empty($this->url) && !empty($this->token);
	}

	/**
	 * @return array{url: string, token: string}
	 */
	public function jsonSerialize(): array {
		return [
			'url' => $this->url,
			'token' => $this->token,
		];
	}
}
