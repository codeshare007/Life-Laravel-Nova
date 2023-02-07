<template>
    <div class="relative ml-auto h-9 flex items-center" style="margin-right: 60px;" v-show="! isLoading">
        <label for="language-switcher" class="text-xs mr-4 text-80">Language:</label>
        <select id="language-switcher" class="min-w-24 h-8 text-s no-appearance bg-40" v-model="language">
            <option v-for="option in languages" v-bind:key="option.value" v-bind:value="option.value">
                {{ option.label }}
            </option>
        </select>
    </div>
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
