<template>
	<div id="integration_paperless_settings" class="section">
		<h2>{{ t('integration_paperless', 'Paperless integration') }}</h2>
		<div>
			<label for="integration_paperless_server_url">{{ t('integration_paperless', 'Server URL') }}</label>
			<input
				id="integration_paperless_server_url"
				v-model="config.url"
				type="url"
				:placeholder="t('integration_paperless', 'Server URL')"
				@input="onInputChange">
		</div>
		<div>
			<label for="integration_paperless_token">{{ t('integration_paperless', 'Authorization token') }}</label>
			<input
				id="integration_paperless_token"
				v-model="config.token"
				type="password"
				:placeholder="t('integration_paperless', 'Authorization token')"
				@input="onInputChange">
		</div>
		<br>
		<p class="settings-hint">
			{{ t('integration_paperless', 'You can create an authorization token in your profile settings') }}
		</p>
	</div>
</template>

<script lang="js">
import { translate as t } from '@nextcloud/l10n'
import { loadState } from '@nextcloud/initial-state'
import axios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'
import { showSuccess, showError } from '@nextcloud/dialogs'

export default {
	name: 'Settings',
	data: () => {
		return {
			config: loadState('integration_paperless', 'config'),
		}
	},
	methods: {
		t,
		async onInputChange() {
			// TODO: Check if the server is reachable and only save the settings in case everything is correct
			try {
				await axios.put(generateOcsUrl('/apps/integration_paperless/config'), this.config)
				showSuccess(t('integration_paperless', 'Saved settings'))
			} catch (e) {
				console.error(e)
				showError(t('integration_paperless', 'Failed to save settings'))
			}
		},
	},
}
</script>
