<script setup>

    import { ref, watch } from 'vue'
	import { Inertia } from '@inertiajs/inertia'
    import TagsOpen from '@/views/components/tags-open.vue'

    const props = defineProps({
		post: Object
	})

    const tagClicked = ref('')

    watch(tagClicked, () => {
        Inertia.get(`/posts`, {'tag': tagClicked.value,})
    })

</script>

<template layout="open">

    <div class="flex flex-col px-4 py-8 mt-4 mb-6 rounded-lg shadow-inner border border-gray-100 text-lg md:text-base">
        <div class="text-3xl md:text-2xl font-bold mb-2">
            {{ post.title }}
        </div>

        <div class="mb-2 text-base md:text-sm">
            {{ post.published_at }}
        </div>

        <div>
            <TagsOpen :tags="post.tags" v-model="tagClicked" />
        </div>

        <div 
            v-html="post.content" 
            class="mt-6"
        >
        </div>
    </div>
</template>