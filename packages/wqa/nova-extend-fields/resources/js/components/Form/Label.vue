<template>
    <label :for="labelFor" class="inline-block font-bold mb-4">
        <slot />
    </label>
</template>

<script>
export default {
    props: {
        labelFor: {
            type: String,
        },
    },
}
</script>
