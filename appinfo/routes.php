<?php

declare(strict_types=1);

return [
	'ocs' => [
		/** @see \OCA\Paperless\Controller\ConfigController::setConfig() */
		['name' => 'Config#setConfig', 'verb' => 'PUT', 'url' => '/config'],
		/** @see \OCA\Paperless\Controller\UploadController::upload() */
		['name' => 'Upload#upload', 'verb' => 'POST', 'url' => '/upload/{fileId}'],
	],
];
