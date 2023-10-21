<template>
    <div class="bg-white shadow-sm rounded-lg mb-3">
        <div class="p-4 px-6">
            <div class="flex items-start justify-between gap-6 mb-3">
                <div class="text-slate-900 font-semibold text-lg">
                    {{ task.title }}
                </div>
                <Menu as="div" class="relative">
                    <MenuButton class="p-1 hover:bg-gray-100 rounded-full">
                        <EllipsisHorizontalIcon
                            class="h-5"
                        ></EllipsisHorizontalIcon>
                    </MenuButton>
                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                    >
                        <MenuItems
                            class="absolute right-0 mt-2 w-52 origin-top-right bg-white rounded-lg shadow-md"
                        >
                            <div class="p-2 flex flex-col gap-2">
                                <MenuItem>
                                    <Link
                                        :href="route('tasks.show', task.id)"
                                        class="py-1 px-2 rounded text-slate-700 flex items-center gap-2 hover:bg-blue-500 hover:text-white"
                                        ><EyeIcon class='h-5'></EyeIcon> View</Link
                                    >
                                </MenuItem>
                                <MenuItem>
                                    <Link
                                        :href="route('tasks.edit', task.id)"
                                        class="py-1 px-2 rounded text-slate-700 flex items-center gap-2 hover:bg-blue-500 hover:text-white"
                                        > <PencilIcon class='h-4'></PencilIcon> Edit</Link
                                    >
                                </MenuItem>
                                <MenuItem>
                                    <button
                                        @click="$emit('deleteTask',task.id)"
                                        class="py-1 px-2 rounded text-slate-700 flex items-center gap-2 hover:bg-blue-500 hover:text-white"
                                        as='button'
                                        ><TrashIcon class='h-5'></TrashIcon>Delete</button
                                    >
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>

            <div class="text-slate-600 font-light">{{ task.notes }}</div>
            <div class="flex items-center gap-4 mt-4">
                <div
                    class="text-slate-600 text-sm font-light flex items-center gap-2"
                    v-if="task.due_date"
                >
                    <span class="rounded-full bg-gray-100 p-1">
                        <CalendarDaysIcon class="h-5 w-5"></CalendarDaysIcon>
                    </span>
                    {{ task.due_date }}
                </div>
                <div
                    class="text-slate-600 text-sm font-light flex items-center gap-2"
                    v-if="task.todos.length > 0"
                >
                    <span class="rounded-full bg-gray-100 p-1">
                        <ListBulletIcon class="h-5 w-5"></ListBulletIcon>
                    </span>
                    {{ checkedTodoItems.length }} / {{ task.todos.length }}
                </div>
                <div v-if="tags" class="flex flex-wrap">
                    <span
                        v-for="tag in tags"
                        :key="tag"
                        class="bg-slate-800 py-0.5 flex items-center gap-2 px-3 rounded-full text-sm text-white mx-1"
                    >
                    <TagIcon class='h-4'></TagIcon>
                        {{ tag }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    CalendarDaysIcon,
    EllipsisHorizontalIcon,
    PencilIcon,
    TrashIcon,
    EyeIcon,ListBulletIcon
} from "@heroicons/vue/24/outline";
import {TagIcon} from '@heroicons/vue/24/solid'
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    task: Object,
});


const checkedTodoItems = props.task.todos ? props.task.todos.filter((todo) => todo.checked == true) : [];
const tags = props.task.tags != null ? props.task.tags.split(",") : [];
const emits = defineEmits(['deleteTask']);


</script>
