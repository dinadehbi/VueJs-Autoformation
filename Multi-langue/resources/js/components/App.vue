<template>
    <div class="container mt-5 text-center">
        <h1 class="mb-3">{{ $t('title') }}</h1>
        <p class="mb-4">{{ $t('description') }}</p>
        
        <div class="btn-group">
            <button class="btn btn-primary" @click="changeLanguage('fr')">🇫🇷 Français</button>
            <button class="btn btn-secondary" @click="changeLanguage('en')">🇬🇧 English</button>
            <button class="btn btn-success" @click="changeLanguage('ar')">🇸🇦 العربية</button>
            <button class="btn btn-danger" @click="changeLanguage('es')">🇪🇸 Español</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    methods: {
        changeLanguage(lang) {
            localStorage.setItem('locale', lang);
            this.$i18n.locale = lang;

            // Mettre à jour Laravel
            axios.get(`/lang/${lang}`).then(() => {
                console.log("Langue mise à jour !");
            });
        }
    }
};
</script>
