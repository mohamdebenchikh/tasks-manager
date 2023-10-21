<template>
    <div class="flex flex-wrap border border-gray-300 rounded  px-1">
        <span v-for="(tag, index) in tags" :key='index'
            class="bg-slate-800 flex items-center text-sm gap-2 text-white px-3 py-0.5 rounded-full m-1 ">
            {{ tag }} <button type='button' @click.prevent="removeTag(index)">
                <XCircleIcon class='h-5'></XCircleIcon>
            </button> </span>
        <input type='tag' class="border-none flex-1 outline-none px-2 py-1 m-1" v-model="newTag" placeholder="Add a tag"
            @keydown.enter.prevent="addTag">
    </div>
</template>
<script setup>
import { ref } from "vue";
import { XCircleIcon } from '@heroicons/vue/24/solid'
import { watchEffect,onBeforeMount } from "vue";


const props = defineProps({
    modelValue: String,
});

const emits = defineEmits(['update:modelValue']);

const tags = ref([]);
const newTag = ref('');

const addTag = () => {
    if (newTag.value && !tags.value.includes(newTag.value)) {
        tags.value.push(newTag.value);
        newTag.value = '';
    }
}

const removeTag = (index) => {
    tags.value.splice(index, 1);
};

// Watch for changes in tags and emit updates
watchEffect(() => {
    emits("update:modelValue", tags.value.join(","));
});

onBeforeMount(()=> {
    if (props.modelValue) {
        tags.value = props.modelValue.split(",");
    }
})

</script>
