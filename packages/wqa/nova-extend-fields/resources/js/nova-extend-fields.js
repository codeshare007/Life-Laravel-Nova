import vSelect from 'vue-select'
import Multiselect from 'vue-multiselect'
import VueTextareaAutosize from 'vue-textarea-autosize'

Nova.booting((Vue, router) => {
    //Vue.config.devtools = true;

    Vue.use(VueTextareaAutosize)

    Vue.component('default-field', require('./components/Form/DefaultField'))
    Vue.component('field-wrapper', require('./components/Form/FieldWrapper'))
    Vue.component('form-label', require('./components/Form/Label'))
    Vue.component('index-boolean-field', require('./components/Index/BooleanField'))
    Vue.component('index-text-field', require('./components/Index/TextField'))
    Vue.component('form-text-field', require('./components/Form/TextField.vue'))
    Vue.component('index-textarea-field', require('./components/Index/TextareaField'))
    Vue.component('form-textarea-field', require('./components/Form/TextareaField'))

    Vue.component('index-file-field', require('./components/Index/FileField'))
    Vue.component('form-file-field', require('./components/Form/FileField'))

    Vue.component('form-select-field', require('./components/Form/SelectField'))
    Vue.component('index-select-field', require('./components/Index/SelectField'))

    Vue.component('form-boolean-switch-field', require('./components/Form/BooleanSwitchField'))
    Vue.component('index-boolean-switch-field', require('./components/Index/BooleanSwitchField'))

    Vue.component('v-select', vSelect)
    Vue.component('multiselect', Multiselect)
    Vue.component('form-multi-select-field', require('./components/Form/MultiSelectField.vue'))
    Vue.component('form-select-toggle-text-field', require('./components/Form/SelectToggleTextField.vue'));
    // Vue.component('custom-index-toolbar', require('./components/CustomIndexToolbar'));
    // Vue.component('resource-table', require('./components/ResourceTable'));
    // Vue.component('resource-table-row', require('./components/ResourceTableRow'));

    Vue.component('index-morph-to-field', require('./components/Index/MorphToField.vue'))
    Vue.component('form-morph-to-field', require('./components/Form/MorphToField.vue'))
})
