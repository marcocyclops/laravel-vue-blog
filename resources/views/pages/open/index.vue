<script setup>

    import { ref, onMounted, onUnmounted, watch } from 'vue'
    import TagsOpen from '@/views/components/tags-open.vue'
	import axios from 'axios'
	import { Inertia } from '@inertiajs/inertia'

    defineProps({
		errors: Object
	})

    const search = ref('')

	const posts = ref([])  // hold all posts
    const list = ref({})  // hold posts for one request, will push into posts above
    const cursorSent = ref('')  // for checking and avoid duplicate getList called

	const getList = async (cursor=null) => {
        cursorSent.value = list.value.next_cursor  // make sure cursorSent is diffrent from next_cursor
		const response = await axios.get(`/posts/list?cursor=${cursor}&search=${search.value}`)
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
        console.log(Math.floor(element.getBoundingClientRect().bottom), window.innerHeight)
        if (Math.floor(element.getBoundingClientRect().bottom) <= window.innerHeight) {
            let cursor = list.value.next_cursor
            if (cursor && cursorSent.value != cursor) {
                getList(cursor)
                console.log(cursor, cursorSent.value)
            }
        }
    }

    function toPost(slug) {
        Inertia.get(`/posts/show/${slug}`)
    }

    watch(search, () => {
        posts.value = []
        getList()
    })

</script>

<template layout="open">

    <div class="flex flex-col bg-white py-3 sticky top-11 items-end">
        <input 
            type="text" 
            placeholder="search" 
            v-model="search" 
            class="px-2 py-1 w-full md:w-2/5 border-gray-100 shadow-md" 
        />
    </div>

    <div ref="scrollPosts" class="flex flex-col">
        <div 
            v-for="post in posts" 
            :key="post.slug"
            class=" px-4 py-8 mb-6 rounded-lg shadow-inner border border-gray-100"
        >
            <div 
                @click="toPost(post.slug)"
                class="text-2xl font-bold mb-2 cursor-pointer"
            >
                {{ post.title }}
            </div>

            <div class="mb-2 text-sm">
                {{ post.published_at }}
            </div>

            <div>
                <TagsOpen :tags="post.tags" />
            </div>

            <div 
                v-html="post.content" 
                @click="toPost(post.slug)"
                class="mt-4 cursor-pointer"
            >
            </div>
        </div>
    </div>

</template>