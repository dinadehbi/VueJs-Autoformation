import 'bootstrap/dist/css/bootstrap.min.css';
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import App from './components/App.vue';


// Charger les fichiers de langue
import fr from '../lang/fr.json';
import en from '../lang/en.json';
import ar from '../lang/ar.json';
import es from '../lang/es.json';

// Détecter la langue depuis Laravel (Session)
const locale = localStorage.getItem('locale') || 'fr';

// Configurer i18n
const i18n = createI18n({
    legacy: false,
    locale,
    fallbackLocale: 'fr',
    messages: { en, fr, ar, es }
});

const app = createApp(App);
app.use(i18n);
app.mount('#app');
