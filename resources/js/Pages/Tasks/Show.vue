<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm, router } from "@inertiajs/vue3";
import {
    CalendarDaysIcon,
    TrashIcon,
    CheckCircleIcon,
    PencilIcon,
    LightBulbIcon,
    RocketLaunchIcon,
    ClockIcon,
} from "@heroicons/vue/24/outline";
import { TagIcon } from "@heroicons/vue/24/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { DateTime } from "luxon";
import { computed, ref } from "vue";

const task = ref(usePage().props.task);
const tags = task.value.tags != null ? task.value.tags.split(",") : [];

const createdAt = computed(() => {
    return DateTime.fromISO(task.value.created_at).toFormat(
        "yyyy-MM-dd HH:mm:ss"
    );
});

const newTodoForm = useForm({
    todo: "",
    task_id: task.value.id,
});

const addNewTodo = () => {
    newTodoForm.post(route("todos.store"), {
        preserveScroll: true,
        onSuccess({ props }) {
            task.value = props.task;
            newTodoForm.todo = "";
        },
    });
};

const deleteTodo = (id) => {
    router.delete(route("todos.destroy", id), {
        preserveScroll: true,
        onSuccess({ props }) {
            task.value = props.task;
        },
    });
};

const updateTodoState = (id) => {
    router.put(
        route("todos.update", id),
        {},
        {
            preserveScroll: true,
            onSuccess({ props }) {
                task.value = props.task;
            },
        }
    );
};

const confirmDeleteTask = ref(false);

const showDeleteModal = () => {
    confirmDeleteTask.value = true;
};

const closeModal = () => {
    confirmDeleteTask.value = false;
};

const deleteTask = () => {
    router.delete(route("tasks.destroy", task.value.id));
};

const completeTask = () => {
    task.value.status = "DONE";
    router.put(route("tasks.update", task.value.id), {
        preserveScroll: false,
        preserveState: false,
    });
};
</script>

<template>
    <Head title="Task Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                Task Details
            </h2>
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
                        Yes, delete it
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <div class="py-12">
            <div class="max-w-3xl md:mx-auto mx-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-4">
                    <div></div>
                    <div class="flex gap-2">
                        <SecondaryButton
                            :href="route('tasks.edit', task.id)"
                            class="flex gap-2"
                        >
                            <PencilIcon class="h-4"></PencilIcon>
                            Edit
                        </SecondaryButton>
                        <DangerButton
                            class="flex gap-2"
                            @click="showDeleteModal"
                            ><TrashIcon class="h-4"></TrashIcon>
                            Delete</DangerButton
                        >
                        <PrimaryButton
                            @click="completeTask"
                            v-if="task.status != 'DONE'"
                            class="flex gap-2"
                            ><CheckCircleIcon class="h-6"></CheckCircleIcon>
                            Complete</PrimaryButton
                        >
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div
                        v-if="task.status == 'OPEN'"
                        class="bg-sky-500 p-4 text-white"
                    >
                        <h4
                            class="text-lg flex gap-2 items-center font-semibold"
                        >
                            <LightBulbIcon class="h-5"></LightBulbIcon>
                            <span class="uppercase">Open</span>
                        </h4>
                    </div>
                    <div
                        v-if="task.status == 'IN_PROGRESS'"
                        class="bg-blue-500 p-4 text-white"
                    >
                        <h4
                            class="text-lg flex gap-2 items-center font-semibold"
                        >
                            <RocketLaunchIcon class="h-5"></RocketLaunchIcon>
                            <span class="uppercase">In Progress</span>
                        </h4>
                    </div>
                    <div
                        v-if="task.status == 'DONE'"
                        class="bg-green-500 p-4 text-white"
                    >
                        <h4
                            class="text-lg flex gap-2 items-center font-semibold"
                        >
                            <CheckCircleIcon class="h-6"></CheckCircleIcon>
                            <span class="uppercase">Done</span>
                        </h4>
                    </div>
                    <div class="p-6 border-b bottom-1 border-green-100">
                        <div class="mb-4">
                            <span
                                class="text-slate-500 uppercase text-xs font-bold"
                                >Title</span
                            >
                            <div
                                class="text-gray-900 text-xl font-semibold pl-1"
                            >
                                {{ task.title }}
                            </div>
                        </div>

                        <div class="mb-4" v-if="task.notes">
                            <span
                                class="text-slate-500 uppercase text-xs font-bold"
                                >Notes</span
                            >
                            <p class="text-slate-900 pl-1">
                                {{ task.notes }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <span
                                class="text-slate-500 uppercase text-xs font-bold"
                                >Created at</span
                            >
                            <div
                                class="text-slate-900 text-sm pl-1 flex items-center gap-2"
                            >
                                <ClockIcon class="h-4"></ClockIcon>
                                {{ createdAt }}
                            </div>
                        </div>

                        <div v-if="task.due_date" class="mb-4">
                            <span
                                class="text-slate-500 uppercase text-xs font-bold"
                                >Due date</span
                            >
                            <div
                                class="text-slate-900 text-sm pl-1 font-light flex items-center gap-2"
                            >
                                <CalendarDaysIcon
                                    class="h-4 w-4"
                                ></CalendarDaysIcon>
                                {{ task.due_date }}
                            </div>
                        </div>

                        <div v-if="tags.length > 0">
                            <span
                                class="text-slate-500 uppercase text-xs font-bold"
                                >Tags</span
                            >
                            <div v-if="tags" class="flex flex-wrap mt-2">
                                <span
                                    v-for="tag in tags"
                                    :key="tag"
                                    class="bg-slate-900 py-0.5 flex items-center gap-2 px-3 rounded-full text-white mx-1"
                                >
                                    <TagIcon class="h-4"></TagIcon>
                                    {{ tag }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pt-4">
                        <span class="uppercase text-sm text-slate-600 font-bold"
                            >Todo list</span
                        >
                    </div>
                    <div
                        v-for="todo in task.todos"
                        class="flex items-center justify-between py-2 px-7 border-b border-green-100"
                    >
                        <div class="flex-1">
                            <label class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    :checked="todo.checked"
                                    @change="updateTodoState(todo.id)"
                                    class="rounded"
                                />
                                <span
                                    class="text-slate-800"
                                    :class="[
                                        todo.checked ? 'line-through' : '',
                                    ]"
                                    >{{ todo.todo }}</span
                                >
                            </label>
                        </div>
                        <DangerButton @click="deleteTodo(todo.id)">
                            <TrashIcon class="h-4 mr-2"></TrashIcon>
                            delete
                        </DangerButton>
                    </div>
                    <form @submit.prevent="addNewTodo">
                        <div class="px-4 py-3 flex items-stretch gap-1">
                            <TextInput
                                type="text"
                                class="flex-1"
                                placeholder="Enter new todo"
                                v-model="newTodoForm.todo"
                            ></TextInput>
                            <PrimaryButton type="submit">Add</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
