<template>
    <div v-show="! isLoading">
        <heading class="mb-6">Seed Download</heading>

        <card class="p-6">
            <select v-model="language">
                <option v-for="option in languages" v-bind:key="option.value" v-bind:value="option.value">
                    {{ option.label }}
                </option>
            </select>
            <a :href="'/nova-vendor/SeedDownload/elements?language=' + language" download class="ml-auto btn btn-default btn-primary">
                Download Elements Seed
            </a>

            <a :href="'/nova-vendor/SeedDownload/community-authors?language=' + language" download class="ml-auto btn btn-default btn-primary">
                Download Community Authors Seed
            </a>
        </card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: true,
            languages: [],
            language: 'en',
        }
    },

    mounted() {
        this.getLanguages();
    },

    methods: {
        getLanguages() {
            Nova.request().get("/nova-vendor/SeedDownload/languages").then(response => {
                this.languages = response.data;
                this.isLoading = false;
            });
        },
    }
}
</script>

<style>
/* Scoped Styles */
</style>
