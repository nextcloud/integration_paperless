import { registerFileAction, FileAction } from '@nextcloud/files'
import PaperlessLogo from '../img/app.svg'
import { translate as t } from '@nextcloud/l10n'
import axios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'

async function uploadFile(file) {
	try {
		await axios.post(generateOcsUrl(`/apps/integration_paperless/upload/${file.fileid}`))
		return true
	} catch (e) {
		console.error(e)
		return false
	}
}

registerFileAction(
	new FileAction({
		id: 'integration_paperless-upload-file',
		displayName: () => {
			return t('integration_paperless', 'Upload to Paperless')
		},
		iconSvgInline: () => PaperlessLogo,
		enabled: () => true,
		exec: async (file) => {
			return await uploadFile(file)
		},
		execBatch: async (files) => {
			return Promise.all(files.map((file) => uploadFile(file)))
		},
	}),
)
