<template>
    <modal v-if="selectedNotification">

        <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 800px;">
            <div class="p-8">

                <heading :level="1" class="mt-6 mb-6">{{ selectedNotification.name }}</heading>

                <!-- START Notifiable Select -->
                <heading :level="2" class="mt-6 mb-6">{{__('Send Notification To')}}</heading>
                <div class="md:flex md:items-center mb-6" v-if="notifiables.length > 1">
                    <div class="md:w-1/3">
                        <label class="block text-grey font-bold md:text-right mb-1 md:mb-0 pr-4"
                               for="notifiable-item">
                            {{__('Notifiable')}}
                        </label>
                    </div>

                    <div class="md:w-2/3">
                        <div class="relative">

                            <select id="notifiable-item" v-model="formObj.notifiable.className"
                                    class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey">
                                <option v-for="notifiable in notifiables" :value="notifiable.className">
                                    {{ notifiable.name }}
                                </option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Notifiable Selects -->

                <!-- START Notifiable Item Select -->
                <div class="md:flex md:items-center mb-6" v-if="formObj.notifiable.className && ! formObj.notifiable.sendToAll">
                    <div class="md:w-1/3">
                        <label class="block text-grey font-bold md:text-right mb-1 md:mb-0 pr-4"
                               for="notifiable">
                            {{ getNotifiableName(formObj.notifiable.className) }}
                        </label>
                    </div>

                    <div class="md:w-2/3">
                        <div class="relative">

                            <select id="notifiable" v-model="formObj.notifiable.value"
                                    class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey">
                                <option v-for="option in getNotifiableItems(formObj.notifiable.className)" :value="option.id">
                                    {{ option.name }} (id:{{ option.id }})
                                </option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Notifiable Item Select -->

                <!-- START Send to all -->
                <div class="md:flex md:items-center mb-6" v-if="formObj.notifiable.className">
                    <div class="md:w-1/3">
                        <label class="block text-grey font-bold md:text-right mb-1 md:mb-0 pr-4"
                               for="notifiable-item">
                            {{__('Send to all')}}
                        </label>
                    </div>

                    <div class="md:w-2/3">
                        <checkbox
                            :checked="formObj.notifiable.sendToAll"
                            @input="formObj.notifiable.sendToAll = ! formObj.notifiable.sendToAll"
                        />
                    </div>
                </div>
                <!-- END Send to all -->

                <p v-if="hasNoSelectedNotifiableError" class="my-2 text-danger">Please select to who or what you'd like to sent the notifiable to.</p>

                <!-- START Notification Parameters -->
                <heading :level="2" class="mt-6 mb-6" v-if="selectedNotification.fields.length">
                    {{__('Define Notification Parameters')}}
                </heading>
                <div v-if="selectedNotification.fields.length" v-for="field in selectedNotification.fields">
                     <component
                        :is="'form-' + field.component"
                        :field="field"
                        :errors="validationErrors"
                    />
                </div>
                <!-- END Notification Parameters -->


            </div>

            <notification-modal-footer :formObj="formObj"/>
        </div>
    </modal>
</template>

<script>
    import NotificationParamForm from "./NotificationParamForm";
    import NotificationModalFooter from "./NotificationModalFooter";

    export default {
        components: {
            NotificationParamForm,
            NotificationModalFooter
        },

        props: {
            selectedNotification: null,
            selectedNotifiable: null,
            notifiables: null,
            value: Object,
            validationErrors: null,
            hasNoSelectedNotifiableError: false,
        },

        data: () => ({
            formObj: {
                notifiable: {
                    className: '',
                    value: '',
                    sendToAll: false,
                },
                notification: {
                    className: null,
                    parameters: [],
                },
            },
        }),

        mounted() {
            this.setDetaultNotifiable();
        },

        methods: {
            getNotifiableItems(className) {
                return _.find(this.notifiables, {className: className}).options;
            },

            getNotifiableName(className) {
                return _.find(this.notifiables, {className: className}).name;
            },

            setDetaultNotifiable() {
                if (this.notifiables.length === 1) {
                    this.formObj.notifiable.className = this.notifiables[0].className;
                }
            }
        }
    }
</script>

<style>
    /* Scoped Styles */
</style>
