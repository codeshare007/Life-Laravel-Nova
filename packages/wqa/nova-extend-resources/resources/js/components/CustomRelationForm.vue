<template>
  <div class="w-full">

      <form v-if="fields" @submit.prevent="createRelation" autocomplete="off">

        <div class="flex flex-wrap card">
          <!-- Fields -->
          <div v-for="field in fields" v-if="field.resourceName !== viaResource" class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <component
              :is="'form-' + field.component"
              :resource-name="resourceName"
              :field="field"
            />
          </div>
          <!-- Create / Attach Button -->
          <create-relation-button
            :singular-name="singularName"
            :resource-name="resourceName"
            :via-resource="viaResource"
            :via-resource-id="viaResourceId"
            :via-relationship="viaRelationship"
            :relationship-type="relationshipType"
            :authorized-to-create="authorizedToCreate"
            class="flex-no-shrink ml-auto mt-6 mr-10"
          />
        </div>
      </form>
    </div>

</template>

<script>
import { Errors, Minimum, InteractsWithResourceInformation } from 'laravel-nova'

export default {
    mixins: [InteractsWithResourceInformation],

    props: {
        singularName: {},
        resourceName: {},
        resourceId: {},
        viaResource: {},
        viaResourceId: {},
        viaRelationship: {},
        relationshipType: {},
        indexId: {},
        poly: {},
    },

    data: () => ({
        relationResponse: null,
        loading: true,
        fields: [],
        validationErrors: new Errors(),
    }),

    async created() {
        this.getFields()
    },

    methods: {

        /**
         * Get the available fields for the resource.
         */
        async getFields() {
            this.fields = []

            const { data: fields } = await Nova.request().get(
                `/nova-api/${this.resourceName}/creation-fields`
            )

            this.fields = _.map(fields, (field) => {
                if (field.resourceName === this.viaResource) {
                    field.value = this.viaResourceId
                }
                return field;
            })
            this.loading = false
        },

        /**
         * Create a new resource and reset the form
         */
        async createRelation() {
            try {
                const response = await this.createRequest()

                this.$toasted.show(
                    this.__('The :resource was created!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                // Reset the form by refetching the fields
                this.getFields()

                Nova.$emit('onReloadIndex', {
                    indexId: this.indexId,
                });

                this.validationErrors = new Errors()
            } catch (error) {
                if (error.response.status == 422) {
                    this.validationErrors = new Errors(error.response.data.errors)
                }
            }
        },

        /**
         * Send a create request for this resource
         */
        createRequest() {
            return Nova.request().post(
                `/nova-api/${this.resourceName}`,
                this.createResourceFormData()
            );
        },

        /**
         * Create the form data for creating the resource.
         */
        createResourceFormData() {
            return _.tap(new FormData(), formData => {
                _.each(this.fields, field => {
                    if (field.resourceName === this.viaResource) {
                        formData.append(field.attribute, this.viaResourceId)
                    } else {
                        field.fill(formData)
                    }
                })

                formData.append('viaResource', this.viaResource)
                formData.append('viaResourceId', this.viaResourceId)
                formData.append('viaRelationship', this.viaRelationship)
                if (this.poly) {
                  formData.append('polyTypeKey', this.poly.poly_type)
                  formData.append('polyIdKey', this.poly.poly_id)
                  formData.append('polyId', this.poly.id)
                  formData.append('polyType', this.poly.type)
                }
            })
        },
    }
}
</script>
