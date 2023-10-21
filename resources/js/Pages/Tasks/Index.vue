<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { Head, usePage, Link, router } from "@inertiajs/vue3";
import TaskCard from "@/Components/TaskCard.vue";
import { ref } from "vue";

const confirmDeleteTask = ref(false);
const deleteTaskId = ref(null);

const showDeleteModal = (taskId) => {
    deleteTaskId.value = taskId;
    confirmDeleteTask.value = true;
};

const closeModal = () => {
    deleteTaskId.value = null;
    confirmDeleteTask.value = false;
};

const deleteTask = () => {
    router.delete(route("tasks.destroy", deleteTaskId.value), {
        preserveState: false,
    });
};

const tasks = usePage().props.tasks;
const status = usePage().props.status;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    My Tasks
                </h2>
                <PrimaryButton :href="route('tasks.create')"
                    >Add new task</PrimaryButton
                >
            </div>
        </template>

        <Modal :show="confirmDeleteTask" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your task?
                </h2>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton class="ml-3" @click="deleteTask">
                        Delete Task
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <div class="md:py-12 py-6">
            <div class="max-w-3xl md:mx-auto mx-3 sm:px-6 lg:px-8">
                <div class="flex bg-white overflow-hidden rounded my-4">
                    <Link
                        :href="route('tasks.index')"
                        class="flex-1 text-center py-4 uppercase tracking-wider text-sm"
                        :class="[
                            status === false
                                ? 'text-blue-500 border-b-4 border-blue-500 font-semibold'
                                : 'text-slate-600',
                        ]"
                    >
                        All tasks</Link
                    >
                    <Link
                        :href="route('tasks.index')"
                        :data="{ status: 'OPEN' }"
                        class="flex-1 text-center py-4 uppercase tracking-wider text-sm"
                        :class="[
                            status === 'OPEN'
                                ? 'text-blue-500 border-b-4 border-blue-500 font-semibold'
                                : 'text-slate-600',
                        ]"
                        >Open</Link
                    >
                    <Link
                        :href="route('tasks.index')"
                        :data="{ status: 'IN_PROGRESS' }"
                        class="flex-1 text-center py-4 uppercase tracking-wider text-sm"
                        :class="[
                            status === 'IN_PROGRESS'
                                ? 'text-blue-500 border-b-4 border-blue-500 font-semibold'
                                : 'text-slate-600',
                        ]"
                        >In progress</Link
                    >
                    <Link
                        :href="route('tasks.index')"
                        :data="{ status: 'DONE' }"
                        class="flex-1 text-center py-4 uppercase tracking-wider text-sm"
                        :class="[
                            status === 'DONE'
                                ? 'text-blue-500 border-b-4 border-blue-500 font-semibold'
                                : 'text-slate-600',
                        ]"
                        >Done</Link
                    >
                </div>
                <div>
                    <TaskCard
                        v-for="task in tasks.data"
                        :key="task.id"
                        :task="task"
                        @delete-task="showDeleteModal"
                    ></TaskCard>
                </div>

                <div class="flex">
                    <div class="flex items-center rounded-lg overflow-hidden">
                        <Link
                            v-for="link in tasks.links"
                            class="px-4 py-2"
                            :href="link.url ?? '#'"
                            v-html="link.label"
                            :class="[
                                link.active
                                    ? 'bg-slate-800 text-white'
                                    : 'bg-white text-slate-700',
                            ]"
                        ></Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
