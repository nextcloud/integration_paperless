const webpackConfig = require('@nextcloud/webpack-vue-config')
const ESLintPlugin = require('eslint-webpack-plugin')
const StyleLintPlugin = require('stylelint-webpack-plugin')
const path = require('path')

webpackConfig.entry = {
	file_action: { import: path.join(__dirname, 'src', 'file_action.js'), filename: 'integration_paperless-file_action.js' },
	settings: { import: path.join(__dirname, 'src', 'settings.js'), filename: 'integration_paperless-settings.js' },
}

webpackConfig.plugins.push(
	new ESLintPlugin({
		extensions: ['js', 'vue'],
		files: 'src',
	}),
)
webpackConfig.plugins.push(
	new StyleLintPlugin({
		files: 'src/**/*.{css,scss,vue}',
	}),
)

webpackConfig.module.rules.push({
	test: /\.svg$/i,
	type: 'asset/source',
})

module.exports = webpackConfig
