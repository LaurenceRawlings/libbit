<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

onMounted(() => {
    if (props.project) {
        form.name = props.project.name;
    }
});

const props = defineProps({
    project: Object,
});

const form = useForm({
    name: '',
});

const submit = () => {
    if (props.project) {
        form.put(route('projects.update', props.project.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('projects.store'), {
            preserveScroll: true,
        });
    }
};

const action = computed(() => {
  return props.project ? 'Update' : 'Create'
})
</script>

<template>
    <AppLayout :title="`${action} Project`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{action}} Project
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="name" value="Name" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{action}}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
