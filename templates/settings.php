<?php
declare(strict_types=1);

use OCA\Paperless\AppInfo\Application;
use OCP\Util;

Util::addScript(Application::APP_ID, Application::APP_ID . '-settings');
?>

<div id="integration_paperless_settings"></div>
