<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    multiline: false,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <component is='select' class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md"
        :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" ref="input">
        <slot></slot>
    </component>
</template>
