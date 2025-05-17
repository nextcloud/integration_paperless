<?php

declare(strict_types=1);

namespace OCA\Paperless\Settings;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

/** @psalm-suppress UnusedClass */
class SettingsSection implements IIconSection {
	public function __construct(
		private IURLGenerator $urlGenerator,
		private IL10N $l,
	) {
	}

	public function getID(): string {
		return 'connected-accounts';
	}

	public function getName(): string {
		return $this->l->t('Connected accounts');
	}

	public function getPriority(): int {
		return 80;
	}

	public function getIcon(): ?string {
		return $this->urlGenerator->imagePath('core', 'categories/integration.svg');
	}
}
