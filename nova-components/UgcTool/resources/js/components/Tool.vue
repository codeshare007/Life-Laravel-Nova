<template>
    <div>
        <table cellpadding="0" cellspacing="0" class="table w-full text-left">
            <thead>
            <tr>
                <th class="">
                    {{__('Quantity')}}
                </th>
                <th class="">
                    {{__('Measure')}}
                </th>
                <th class="">
                    {{__('Ingredient')}}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="ingredient in getIngredients" v-bind:key="ingredient.id">
                <td>{{ ingredient.quantity }}</td>
                <td>{{ ingredient.measure }}</td>
                <td>{{ ingredient.name }}</td>
            </tr>
            </tbody>
        </table>
        <h4 style="padding:30px 0;">Custom Ingredients</h4>
        <table cellpadding="0" cellspacing="0" class="table w-full text-left">
            <thead>
            <tr>
                <th class="">
                    {{__('Quantity')}}
                </th>
                <th class="">
                    {{__('Measure')}}
                </th>
                <th class="">
                    {{__('Ingredient')}}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="ingredient in getCustomIngredients" v-bind:key="ingredient.id">
                <td>{{ ingredient.quantity }}</td>
                <td>{{ ingredient.measure }}</td>
                <td>{{ ingredient.name }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>


<script>

export default {
    props: ['ingredients', 'resourceName', 'resourceId', 'field'],

    computed: {
        getIngredients() {
            return this.field.ingredients.filter(item => item.resource_type !== 'CustomIngredient')
        },
        getCustomIngredients() {
            return this.field.ingredients.filter(item => item.resource_type === 'CustomIngredient')
        },
    },

    mounted() {
        //
    },
}
</script>
