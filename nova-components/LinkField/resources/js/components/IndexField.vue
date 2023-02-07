<template>
    <a class="text-black hover:text-primary font-bold no-underline" :href="field.href">{{ field.value }}</a>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>
