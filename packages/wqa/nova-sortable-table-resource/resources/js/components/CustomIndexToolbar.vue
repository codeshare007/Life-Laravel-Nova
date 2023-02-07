<template>
  <div class="w-full flex items-center justify-end">
    <batch-edit-toggle v-if="showBatchEdit" :indexId="indexId" />
  </div>
</template>

<script>

  export default {

    props: ['indexId'],

    computed: {
      showBatchEdit: function () {
        const batchEditVisibleFor = [
          'ailments',
          'body-systems',
          'categories',
          'recipes',
          'remedies',
          'oils',
          'blends',
          'solutions',
          'supplements',
          'top-uses',
          'tags',
          'tag-constituents',
          'tag-properties',
          'dashboard-cards',
          'questions'
        ];

        return batchEditVisibleFor.includes(this.indexId);
      }
    }
  }
</script>
