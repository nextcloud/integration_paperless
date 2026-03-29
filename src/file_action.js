import { registerFileAction } from '@nextcloud/files'
import PaperlessLogo from '../img/app.svg'
import { translate as t } from '@nextcloud/l10n'
import axios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'

async function uploadFile(node) {
	try {
		await axios.post(generateOcsUrl(`/apps/integration_paperless/upload/${node.fileid}`))
		return true
	} catch (e) {
		console.error(e)
		return false
	}
}

registerFileAction({
	id: 'integration_paperless-upload-file',
	displayName: () => {
		return t('integration_paperless', 'Upload to Paperless')
	},
	iconSvgInline: () => PaperlessLogo,
	enabled: () => true,
	exec: async (context) => {
		return await uploadFile(context.nodes[0])
	},
	execBatch: async (context) => {
		return Promise.all(context.nodes.map(uploadFile))
	},
})
