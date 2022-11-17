<script setup>

    import { ref, onMounted, onUnmounted, toRefs } from 'vue'
    import TagsOpen from '@/views/components/tags-open.vue'
	import axios from 'axios'
	import { Inertia } from '@inertiajs/inertia'

    const props = defineProps({
		errors: Object,
        tagName: String,
	})

    const { tagName } = toRefs(props)

	const posts = ref([])  // hold all posts
    const list = ref({})  // hold posts for one request, will push into posts above

	const getList = async (cursor=null) => {
		const response = await axios.get(`/posts/tag-posts?cursor=${cursor}&tag=${tagName.value}`)
		list.value = response.data
        posts.value.push(...list.value.data)
	}
	
	getList()

    const scrollPosts = ref()

    onMounted(() => {
        window.addEventListener("scroll", handleScroll)
    })

    onUnmounted(() => {
        window.removeEventListener("scroll", handleScroll)
    })

    const handleScroll = (e) => {
        let element = scrollPosts.value
        if (Math.floor(element.getBoundingClientRect().bottom) <= window.innerHeight) {
            let cursor = list.value.next_cursor
            if (cursor) {
                getList(cursor)
            }
        }
    }

    function toPost(slug) {
        Inertia.get(`/posts/show/${slug}`)
    }

</script>

<template layout="open">
    <h1>Home</h1>
    <div ref="scrollPosts">
        <div v-for="post in posts" :key="post.slug">
            <div @click="toPost(post.slug)">{{ post.id }} - {{ post.title }}</div>
            <div class="flex flex-row">
                <TagsOpen :tags="post.tags" />
                {{ post.published_at }}
            </div>
            <div v-html="post.content" @click="toPost(post.slug)"></div>
            <hr />
        </div>
    </div>
</template>