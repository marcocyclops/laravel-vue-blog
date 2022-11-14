<script setup>
	import { toRefs } from 'vue'

	import { useForm } from '@inertiajs/inertia-vue3'
	import tinymce from '@/views/components/tinymce.vue'
	import tags from '@/views/components/tags.vue';

	const props = defineProps({
		errors: Object,
		post: Object,
		suggestTags: Array,
	})

	const { post, postTags } = toRefs(props)

	const form = useForm({
		'title': post.value.title,
		'content': post.value.content,
		'tags': post.value.tags.map((value) => {return {text:value.name.en}}),
		'published': post.value.published,
	})

	function submit(id) {
		form.put(`/wcms/posts/${id}`)
	}

</script>

<template layout="wcms">
	<div>Create Post:</div>
	<div v-if="errors.error">{{ errors.error }}</div>

	<form @submit.prevent="submit(post.id)">
		<div>
			<label for="title">Title: </label>
			<input type="text" id="title" v-model="form.title" />
			<div v-if="errors.title">{{ errors.title }}</div>
		</div>

		<div>
			<label for="content">Content: </label>
			<tinymce id="content" v-model="form.content" />
			<div v-if="errors.content">{{ errors.content }}</div>
		</div>

		<div>
			<label for="tags">Tags: </label>
			<tags id="tags" 
				:suggestTags="suggestTags"
				v-model="form.tags" 
			/>
			<div v-if="errors.tags">{{ errors.tags }}</div>
		</div>

		<div>
			<input type="checkbox" id="published" v-model="form.published" />
			<label for="published">Published</label>
			<div v-if="errors.published">{{ errors.published }}</div>
		</div>

		<div>
			<button type="submit" :disabled="form.processing">Save</button>
		</div>
	</form>

	
</template>