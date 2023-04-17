import { createApp } from 'vue'
import PrimeVue from 'primevue/config'
import App from '@/App.vue'
import '/node_modules/primeflex/primeflex.min.css'
import 'primevue/resources/themes/lara-light-indigo/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'
import '@/assets/main.css'

createApp(App).use(PrimeVue).mount('#app')
