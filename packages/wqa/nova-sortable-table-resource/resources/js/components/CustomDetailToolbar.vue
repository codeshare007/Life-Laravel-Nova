<template>
  <div class="w-full flex items-center justify-end mx-3">
    <batch-edit-toggle v-if="showBatchEdit" :indexId="indexId" />
  </div>
</template>

<script>

export default {

    props: ['indexId'],

    computed: {
      showBatchEdit: function () {
        const batchEditVisibleFor = [
          // 'top-uses'
        ];

        return batchEditVisibleFor.includes(this.indexId);
      }
    }
}
</script>
