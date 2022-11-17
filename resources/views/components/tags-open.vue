<script setup>

	import { Inertia } from '@inertiajs/inertia'

	defineProps({
        tags: Array
	})

    function toTag(tag) {
        Inertia.get(`/posts/tag/${tag}`)
    }

</script>

<template >
    
    <div class="flex flex-row">
        <div v-for="tag in tags" :key="tag.id" @click="toTag(tag.name.en)">
            {{ tag.name.en }}
        </div>
    </div>
    
</template>