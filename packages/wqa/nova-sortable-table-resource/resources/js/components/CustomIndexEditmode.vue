<template>
  <div class="flex justify-end items-center mb-6">
    <batch-edit-toggle v-if="showBatchEdit" :indexId="indexId" />
  </div>
</template>

<script>

export default {

    props: ['indexId'],

    computed: {
      showBatchEdit: function () {
        const batchEditVisibleFor = [
          // 'supplement-ailments',
          // 'supplement-ingredients',
          'recipes-recipeIngredients-recipe-ingredients',
          'remedies-remedyIngredients-remedy-ingredients',
          'body-systems-oils-solutions',
          'body-systems-blends-solutions',
          'body-systems-supplements-solutions',
          'ailments-solutions-solution-groups',
          'blends-usages-top-uses',
          'oil-usages-top-uses',
          'ailments-solutions-ailment-solutions',
          'categories-topTips-category-top-tips',
          'categories-panels-category-panels'
        ];

        return batchEditVisibleFor.includes(this.indexId);
      }
    }
}
</script>
