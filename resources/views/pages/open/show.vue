<script setup>

    import TagsOpen from '@/views/components/tags-open.vue'

    const props = defineProps({
		post: Object
	})
</script>

<template layout="open">
    <h1>{{post.title}}</h1>
    <div class="flex flex-row">
        <TagsOpen :tags="post.tags" />
        {{ post.published_at }}
    </div>
    <div v-html="post.content"></div>
</template>