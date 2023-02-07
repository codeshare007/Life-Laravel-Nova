<template>
    <div class="user-debug">
        <div class="mb-6">
            <p class="mb-2">
                Last logged in: {{ user.last_logged_in_at || 'Never' }} 
                <span v-if="user.last_logged_in_at">
                    ({{ moment(user.last_logged_in_at).fromNow() }})
                </span>
            </p>
            <p class="mb-2">
                Last used app: {{ user.last_used_app_at || 'Never' }}
                <span v-if="user.last_used_app_at">
                    ({{ moment(user.last_used_app_at).fromNow() }})
                </span>
            </p>
        </div>

        <div class="mb-6">
            <p class="mb-2">Subscription override: {{ user.bypass_subscription_receipt_validation ? 'Yes - subscription check is bypassed until ' + ( user.subscription_expires_at || '-' ) + '. Once this date has passed, the subscription will be checked using the appropriate receipt token for the user.' : 'No' }}</p>
            <div v-if="! user.bypass_subscription_receipt_validation">
                <p class="mb-2">Subscription type: {{ user.subscription_type  || '-' }}</p>
                <p class="mb-2" v-if="user.subscription_type !== 1">
                    Subscription expires: {{ user.subscription_expires_at  || '-' }}
                    <span v-if="user.subscription_expires_at">
                        ({{ moment(user.subscription_expires_at).fromNow() }})
                    </span>
                </p>
            </div>
        </div>

        <div class="mb-6">
            <p class="mb-2">Platform: {{ user.platform  || '-' }}</p>
            <p class="mb-2">App version: {{ user.app_version  || '-' }}</p>
            <p class="mb-2">App build number: {{ user.app_build_number || '-' }}</p>
            <p class="mb-2">Device name: {{ user.device_name || '-' }}</p>
            <p class="mb-2">Device system version: {{ user.system_version || '-' }}</p>
        </div>

        <div>
            <p class="mb-2">FCM token: {{ user.fcm_token || '-' }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            isLoading: true,
            user: {},
            moment: moment,
        }
    },

    mounted() {
        this.updatePreview();
    },

    methods: {
        updatePreview() {
            this.isLoading = true;

            Nova.request().get(`/nova-vendor/user-debug/${this.resourceId}`).then(response => {
                this.user = response.data.data;
                this.isLoading = false;
            });
        },
    },
}
</script>
