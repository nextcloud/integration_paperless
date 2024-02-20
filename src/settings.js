import Vue from 'vue'
import Settings from './components/Settings.vue'

const VueSettings = Vue.extend(Settings)
new VueSettings().$mount('#integration_paperless_settings')
