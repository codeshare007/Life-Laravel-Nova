<template>
    <div>
        <heading class="mb-6">{{__('Send Notification')}}</heading>

        <notifications-param-modal
            v-if="selectedNotification"
            :selectedNotification="selectedNotification"
            :notifiables="notifiables"
            :selectedNotifiable="selectedNotifiable"
            :validationErrors="validationErrors"
            :hasNoSelectedNotifiableError="hasNoSelectedNotifiableError"
        ></notifications-param-modal>

        <loading-card :loading="initialLoading" class="flex flex-wrap py-8 mb-8 text-center">

            <table cellpadding="0" cellspacing="0" class="table w-full text-left" v-if="notificationClasses.length">
                <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Parameters')}}</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="notificationClass in notificationClasses">
                    <td class="py-2">
                        <span class="font-bold">{{ notificationClass.name }}</span><br>
                        <span class="text-xs text-80">{{ notificationClass.className }}</span>
                    </td>
                    <td class="py-2">
                        <span v-for="(param, index) in notificationClass.parameters" v-bind:key="index">
                            <span>{{param.displayName}}</span><span v-if="index + 1 < notificationClass.parameters.length">, </span>
                        </span>
                    </td>
                    <td class="text-right py-2">
                        <button class="btn btn-default btn-primary" @click="selectNotification(notificationClass)">
                            {{__('Select')}}
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-else>
                <p class="m-4">{{__("You don't have any notification classes yet.")}}</p>
            </div>
        </loading-card>

    </div>
</template>

<script>
    import NotificationsParamModal from "./NotificationsParamModal";
    import { Errors } from 'laravel-nova';

    export default {
        components: {NotificationsParamModal},
        data: () => ({
            notificationClasses: [],
            initialLoading: true,
            error: false,
            selectedNotification: null,
            selectedNotifiable: {
                name: '',
                id: ''
            },
            notifiables: null,
            formObj: null,
            validationErrors: new Errors(),
            hasNoSelectedNotifiableError: false,
        }),
        created() {
            this.getNotificationClasses();
            this.getNotifiables();
        },
        mounted() {
            this.$root.$on('submitModal', (formObj) => {
                this.formObj = formObj;
                this.formObj.notification.className = this.selectedNotification.className;
                this.formObj.notification.parameters = [];
                this.sendNotification();
            });

            this.$root.$on('cancelModal', () => {
                this.deselectNotification();
            });
        },
        methods: {
            selectNotification(notification) {
                if(!this.notifiables.length) {
                    return this.$toasted.show('No notifiables could be found.', {type: 'error'});
                }
                
                this.selectedNotification = notification;
            },
            deselectNotification() {
                this.resetForm();
                this.selectedNotification = null;
            },
            resetForm() {
                this.validationErrors = new Errors();
                this.formObj = null;
            },
            getNotificationClasses() {
                Nova.request().get('/nova-vendor/nova-notifications/notifications/classes')
                    .then((response) => {
                        this.notificationClasses = response.data;
                        this.initialLoading = false;
                    })
            },
            getNotifiables() {
                Nova.request().get('/nova-vendor/nova-notifications/notifiables')
                    .then((response) => {
                        this.notifiables = response.data;
                    })
            },
            sendNotification() {

                if (!this.selectedNotification.name.length) {
                    return this.$toasted.show(__('Notification has not been chosen.'), {type: 'error'});
                }

                this.hasNoSelectedNotifiableError = false;
                if (this.formObj.notifiable.value === "" && this.formObj.notifiable.sendToAll === false) {
                    this.hasNoSelectedNotifiableError = true;

                    return;
                }

                let fieldData = new FormData();
                this.selectedNotification.fields.forEach(field => {
                    field.fill(fieldData);
                });

                for (var [fieldName, fieldValue] of fieldData.entries()) {
                    this.formObj.notification.parameters.push({[fieldName]: fieldValue});
                }

                Nova.request().post('/nova-vendor/nova-notifications/notifications/send', this.formObj).then((response) => {
                    this.$toasted.show('Notification has been sent!', {type: 'success'});
                    this.resetForm();
                    this.selectedNotification = null;
                }).catch((error) => {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors);
                    } else {
                        console.log(error);
                        this.resetForm();
                        this.$toasted.show('There has been an error!', {type: 'error'});
                    }
                });
            },
        }
    }
</script>

<style>
    /* Scoped Styles */
</style>
