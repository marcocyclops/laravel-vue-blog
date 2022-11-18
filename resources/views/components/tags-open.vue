<script setup>

    import { toRefs } from 'vue'
	import { Inertia } from '@inertiajs/inertia'

	const props = defineProps({
        tags: Array,
        modelValue: String,
	})

    const emit = defineEmits(['update:modelValue'])

    function toTag(tag) {
        emit('update:modelValue', tag)
    }

</script>

<template >
    
    <div class="flex flex-row">
        <div 
            v-for="tag in tags" 
            :key="tag.id" 
            @click="toTag(tag.name.en)"
            class="px-2 border border-black mr-1 cursor-pointer text-base md:text-sm rounded"
        >
            {{ tag.name.en }}
        </div>
    </div>
    
</template>