<template>
    <div class="card-preview-tool">
        <p class="mb-2">Below you will find a representation of what the card will look like in the app.</p>
        <p class="mb-8">In order to see the latest preview, make sure you save the card and click on "Refresh preview".</p>
        <button class="mb-8 btn btn-default btn-primary" v-on:click="updatePreview()" v-text="isLoading ? 'Updating preview...' : 'Refresh preview'"></button>
        <div class="flex">
            <div class="mr-6">
                <h4 class="mb-2">iPhone 5s, SE</h4>
                <card-preview :card="card" :width="270" />
            </div>
            <div class="mr-6">
                <h4 class="mb-2">iPhone 6, 7, 8, X</h4>
                <card-preview :card="card" :width="325" />
            </div>
            <div>
                <h4 class="mb-2">iPhone XR, XS Max</h4>
                <card-preview :card="card" :width="364" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            isLoading: true,
            card: {},
        }
    },

    mounted() {
        this.updatePreview();
    },

    methods: {
        updatePreview() {
            this.isLoading = true;

            Nova.request().get(`/nova-vendor/card-preview-tool/${this.resourceId}`).then(response => {
                this.card = response.data.data;
                this.isLoading = false;
            });
        },
    },
}
</script>
