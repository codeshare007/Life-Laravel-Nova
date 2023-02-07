<template>
    <card class="relative px-6 py-4" v-show="! isLoading">
        <h1 class="flex mb-3 text-base text-80 font-bold">Language Switcher</h1>
        <select v-model="language">
            <option v-for="option in languages" v-bind:key="option.value" v-bind:value="option.value">
                {{ option.label }}
            </option>
        </select>
    </card>
</template>

<script>
export default {
    data() {
        return {
            isLoading: true,
            languages: [],
            language: null,
        }
    },

    props: [
        'card',

        // The following props are only available on resource detail cards...
        // 'resource',
        // 'resourceId',
        // 'resourceName',
    ],

    mounted() {
        this.getLanguages();
    },

    methods: {
        getLanguages() {
            Nova.request().get("/nova-vendor/language-switcher/init").then(response => {
                this.language = response.data.language;
                this.languages = response.data.languages;
                this.isLoading = false;
            });
        },

        setLanguage(language) {
            Nova.request().post("/nova-vendor/language-switcher/switch-language", {
                language: this.language,
            }).then(response => {
                document.location.reload();
            });
        },
    },

    watch: {
        language: function(newVal, oldVal) {
            if (oldVal !== null) {
                this.setLanguage(newVal);
            }
        }
    }
}
</script>
