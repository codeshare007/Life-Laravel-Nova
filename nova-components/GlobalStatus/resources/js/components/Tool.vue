<template>
    <div>
        <heading class="mb-6">Global Status - iOS</heading>
        <div class="card overflow-hidden">
            <form @submit.prevent="save('ios')">
                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Status</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <select v-model="iosStatus.type" class="form-control form-select mb-3 w-full" :class="{ 'border-danger': fieldHasErrors('type') }" :disabled="isLoading">
                                <option v-for="(key, value) in statusTypes" v-bind:key="key" :value="value">{{ key }}</option>
                            </select>
                            <p class="my-2 text-danger" v-if="fieldHasErrors('type')">{{ errors.type[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="iosStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Title</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <input v-model="iosStatus.title" type="text" class="w-full form-control form-input form-input-bordered" :class="{ 'border-danger': fieldHasErrors('title') }" :disabled="isLoading">
                            <p class="my-2 text-danger" v-if="fieldHasErrors('title')">{{ errors.title[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="iosStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Message</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <textarea v-model="iosStatus.message" type="text" class="w-full form-control form-input form-input-bordered py-3 min-h-textarea" :class="{ 'border-danger': fieldHasErrors('message') }" :disabled="isLoading"></textarea>
                            <p class="my-2 text-danger" v-if="fieldHasErrors('message')">{{ errors.message[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="iosStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Button Text</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <input v-model="iosStatus.button_text" type="text" class="w-full form-control form-input form-input-bordered" :class="{ 'border-danger': fieldHasErrors('button_text') }" :disabled="isLoading">
                            <p class="my-2 text-danger" v-if="fieldHasErrors('button_text')">{{ errors.button_text[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="iosStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Button URL</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <input v-model="iosStatus.button_url" type="text" class="w-full form-control form-input form-input-bordered" :class="{ 'border-danger': fieldHasErrors('button_url') }" :disabled="isLoading">
                            <p class="my-2 text-danger" v-if="fieldHasErrors('button_url')">{{ errors.button_url[0] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-30 flex px-8 py-4 items-center">
                    <p class="text-80 text-sm">Last updated: {{ iosStatus.updated_at }}</p>
                    <button type="submit" class="ml-auto btn btn-default btn-primary" :disabled="isLoading">
                        Update iOS Global Status
                    </button>
                </div>
            </form>
        </div>

        <heading class="mt-8 mb-6">Global Status - Android</heading>
        <div class="card overflow-hidden">
            <form @submit.prevent="save('android')">
                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Status</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <select v-model="androidStatus.type" class="form-control form-select mb-3 w-full" :class="{ 'border-danger': fieldHasErrors('type') }" :disabled="isLoading">
                                <option v-for="(key, value) in statusTypes" v-bind:key="key" :value="value">{{ key }}</option>
                            </select>
                            <p class="my-2 text-danger" v-if="fieldHasErrors('type')">{{ errors.type[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="androidStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Title</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <input v-model="androidStatus.title" type="text" class="w-full form-control form-input form-input-bordered" :class="{ 'border-danger': fieldHasErrors('title') }" :disabled="isLoading">
                            <p class="my-2 text-danger" v-if="fieldHasErrors('title')">{{ errors.title[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="androidStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Message</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <textarea v-model="androidStatus.message" type="text" class="w-full form-control form-input form-input-bordered py-3 min-h-textarea" :class="{ 'border-danger': fieldHasErrors('message') }" :disabled="isLoading"></textarea>
                            <p class="my-2 text-danger" v-if="fieldHasErrors('message')">{{ errors.message[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="androidStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Button Text</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <input v-model="androidStatus.button_text" type="text" class="w-full form-control form-input form-input-bordered" :class="{ 'border-danger': fieldHasErrors('button_text') }" :disabled="isLoading">
                            <p class="my-2 text-danger" v-if="fieldHasErrors('button_text')">{{ errors.button_text[0] }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="androidStatus.type !== 'ok'">
                        <div class="w-1/4 px-8 py-6">
                            <label class="inline-block text-80 h-9 pt-2">Button URL</label>
                        </div>
                        <div class="w-3/4 px-8 py-6">
                            <input v-model="androidStatus.button_url" type="text" class="w-full form-control form-input form-input-bordered" :class="{ 'border-danger': fieldHasErrors('button_url') }" :disabled="isLoading">
                            <p class="my-2 text-danger" v-if="fieldHasErrors('button_url')">{{ errors.button_url[0] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-30 flex px-8 py-4 items-center">
                    <p class="text-80 text-sm">Last updated: {{ androidStatus.updated_at }}</p>
                    <button type="submit" class="ml-auto btn btn-default btn-primary" :disabled="isLoading">
                        Update Android Global Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: true,
            iosStatus: {},
            androidStatus: {},
            statusTypes: {},
            errors: {},
        }
    },

    mounted() {
        this.getStatus();
    },

    methods: {
        getStatus() {
            Nova.request().get("/nova-vendor/global-status/status").then(response => {
                this.iosStatus = response.data.ios_status;
                this.androidStatus = response.data.android_status;
                this.statusTypes = response.data.status_types;
                this.isLoading = false;
            });
        },

        save(platform) {
            this.errors = {};
            this.isLoading = true;

            let status = platform === 'ios' ? this.iosStatus : this.androidStatus;
            let payload = {
                ...status,
                platform: platform,
            };

            Nova.request().put("/nova-vendor/global-status/status", payload).then(response => {
                this.getStatus();

                this.$toasted.show(`Global status for ${platform} has been updated successfully`, { 
                    type: 'success'
                });
            }).catch(error => {
                this.isLoading = false;
                this.errors = error.response.data.errors;

                this.$toasted.show('There was a problem saving the global status. Please check the errors and try again.', { 
                    type: 'error'
                });
            });
        },

        fieldHasErrors(field) {
            return this.errors && this.errors.hasOwnProperty(field);
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
