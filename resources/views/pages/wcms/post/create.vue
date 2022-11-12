<script setup>
	import { ref } from 'vue'

	import { useForm } from '@inertiajs/inertia-vue3'
	import tinymce from '@/views/components/tinymce.vue'
	import tags from '@/views/components/tags.vue';

	defineProps({
		errors: Object,
		suggestTags: Array
	})

	const form = useForm({
		'title': null,
		'content': null,
		'tags': [],
		'published': null,
	})

	function submit() {
		form.post('/wcms/posts')
	}

</script>

<template layout="wcms">
	<div>Create Post:</div>

	<div v-if="errors.error">{{ errors.error }}</div>

	<form @submit.prevent="submit">
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