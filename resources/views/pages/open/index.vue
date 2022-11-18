<script setup>

    import { ref, onMounted, onUnmounted, watch } from 'vue'
    import TagsOpen from '@/views/components/tags-open.vue'
	import axios from 'axios'
	import { Inertia } from '@inertiajs/inertia'

    defineProps({
		errors: Object,
        suggestTags: Array,
	})

    const search = ref('')

	const posts = ref([])  // hold all posts
    const list = ref({})  // hold posts for one request, will push into posts above
    const cursorSent = ref('')  // for checking and avoid duplicate getList called
    const tagClicked = ref('')


	const getList = async (cursor=null) => {
        cursorSent.value = list.value.next_cursor  // make sure cursorSent is diffrent from next_cursor
		const response = await axios.get(`/posts/list?cursor=${cursor}&search=${search.value}&tag=${tagClicked.value}`
        )
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
            if (cursor && cursorSent.value != cursor) {
                getList(cursor)
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

    watch(tagClicked, () => {
        posts.value = []
        getList()
    })

</script>

<template layout="open">
    <div class="flex flex-col text-lg md:text-base">
        <div class="flex flex-col-reverse md:flex-row bg-white py-3 sticky top-11">
            <div class="w-full md:w-1/2 my-1 md:mr-2">
                <select v-model="tagClicked" class="px-2 pb-1 pt-2 border-gray-100 shadow-md border w-full md:w-1/2">
                    <option value="">標籤分類</option>
                    <option v-for="tag in suggestTags" :key="tag.text" :value="tag.text" :selected="tagClicked==tag.text">{{ tag.text }}</option>
                </select>
            </div>
            <div class="w-full md:w-1/2 my-1 md:ml-2">
                <input 
                    type="text" 
                    placeholder="搜尋" 
                    v-model="search" 
                    class="px-2 py-1 border-gray-100 shadow-md border w-full" 
                />
            </div>
        </div>

        <div ref="scrollPosts" class="flex flex-col">
            <div 
                v-for="post in posts" 
                :key="post.slug"
                class=" px-4 py-8 mb-6 rounded-lg shadow-inner border border-gray-100"
            >
                <div 
                    @click="toPost(post.slug)"
                    class="text-3xl md:text-2xl font-bold mb-2 cursor-pointer"
                >
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
                    @click="toPost(post.slug)"
                    class="mt-4 cursor-pointer"
                >
                </div>
            </div>
        </div>
    </div>
</template>