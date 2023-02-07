<template>
    <div>
        <div>
            Internal deep link: <br>
            <strong>{{ deepLink }}</strong>
        </div>
        <br>
        <div>
            Web sharing link: <br>
            <strong>{{ webLink }}</strong>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'field'],

    computed: {
        deepLink() {
            return `essentiallife://resource/${this.field.resourceType}/${this.field.resourceUuid}`;
        },
        webLink() {
            return `https://link.essentiallife.com/${this.field.resourceType}/${this.field.resourceUuid}`;
        }
    },

    mounted() {
        //
    },
}
</script>
