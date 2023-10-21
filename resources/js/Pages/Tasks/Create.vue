<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import TagInput from '@/Components/TagInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    notes: '',
    status: 'OPEN',
    due_date: '',
    tags:"",
});


const submit = () => {
    form.post(route("tasks.store"), {
        preserveScroll: true,
        preserveState: true
    });
}


</script>

<template>
    <Head title="Create new task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create new Task</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent='submit'>
                        <div class="p-6">
                            <div class="mb-6">
                                <InputLabel for="title">Task title</InputLabel>
                                <TextInput v-model="form.title" type="text" placeholder='Title' id="title"
                                    class="block w-full mt-1" :error='form.errors.title ? true : false' autofocus>
                                </TextInput>
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div class="mb-6">
                                <InputLabel for="notes">Notes</InputLabel>
                                <TextInput v-model="form.notes" placeholder='notes' id="notes"
                                    class="block w-full mt-1" :error='form.errors.notes ? true : false'
                                    :multiline='true' rows='3'></TextInput>
                                <InputError class="mt-2" :message="form.errors.notes" />
                            </div>

                            <div class="mb-6">
                                <InputLabel for="status">Status</InputLabel>
                                <SelectInput v-model="form.status" id="status" class="block w-full mt-1">
                                    <option value="OPEN">Open</option>
                                    <option value="IN_PROGRESS">In progress</option>
                                    <option value="DONE">Done</option>
                                </SelectInput>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>
                            <div class="mb-6">
                                <InputLabel for="due_date">Due date</InputLabel>
                                <TextInput :error='form.errors.due_date ? true : false' v-model="form.due_date" type="date"
                                    id="due_date" class="block w-full mt-1"></TextInput>
                                <InputError class="mt-2" :message="form.errors.due_date" />
                            </div>

                            <div class="mb-6">
                                <InputLabel>Tags</InputLabel>
                                <TagInput v-model='form.tags' class="block w-full mt-1"></TagInput>
                                <InputError class="mt-2" :message="form.errors.tags" />
                            </div>

                           
                            <div class="text-right">
                                <PrimaryButton type="Create">Submit</PrimaryButton>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
