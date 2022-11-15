<script setup>
	import { ref, reactive } from 'vue'
	import { Inertia } from '@inertiajs/inertia'
	import { useForm } from '@inertiajs/inertia-vue3'
	import { TailwindPagination } from 'laravel-vue-pagination'
	import axios from 'axios'

	defineProps({
		errors: Object
	})

	const posts = ref({})

	const form = useForm({
		'filter_title': null,
		'filter_published': null,
	})
	
	const getList = async (page = 1) => {
		const response = await axios.get(`/wcms/posts/list?page=${page}&filter_title=${form.filter_title}&filter_published=${form.filter_published}`)
		posts.value = response.data

		posts.value.data = posts.value.data.map(post => post = {...post, selected:false})
	}
	
	getList()

	function createPost() {
		Inertia.get('/wcms/posts/create')
	}

	function editPost(slug) {
		Inertia.get(`/wcms/posts/${slug}/edit`)
	}

	const deletePost = async (id) => {
		const response = await axios.delete(`/wcms/posts/${id}`)
		if (response.data.deleted) {
			getList(posts.value.current_page)
		}
	}

	async function deleteSelected() {
		let ids = []
		posts.value.data.forEach(((post) => {
			if (post.selected) {
				ids.push(post.id)
			}
		}))
		
		const response = await axios.delete(`/wcms/posts`, {
			params: {
				ids: ids
			}
		})
		
		if (response.data.deleted) {
			getList(posts.value.current_page)
		}

	}
	
</script>

<template layout="wcms">
	<div>Posts List:</div>

	<div v-if="errors.error">{{ errors.error }}</div>
	<div>
		<button @click="createPost">Create</button>
	</div>
	<div>
		<form @submit.prevent="getList(posts.current_page)">
			<div>
				<label for="filter_title">Filter Title: </label>
				<input type="text" id="filter_title" v-model="form.filter_title" />
				<div v-if="errors.filter_title">{{ errors.filter_title }}</div>
			</div>
			<div>
				<label for="filter_published">Filter Status: </label>
				<select id="filter_published" v-model="form.filter_published">
					<option value="null"></option>
					<option value="true">Published</option>
					<option value="false">Draft</option>
				</select>
				<div v-if="errors.filter_published">{{ errors.filter_published }}</div>
			</div>
			<div>
				<button type="submit" :disabled="form.processing">Search</button>
			</div>
		</form>
	</div>
	<div>
		<button>Publish Selected</button>
		<button @click="deleteSelected">Delete Selected</button>
	</div>
	
	<TailwindPagination 
        :data="posts"
        @pagination-change-page="getList"
	/>
	<table>
		<thead>
			<tr>
				<th>Select</th>
				<th>ID</th>
				<th>Title</th>
				<th>Status</th>
				<th>Published Date</th>
				<th>Created Date</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody v-if="posts">
			<tr v-for="post in posts.data" :key="post.id">
				<td><input type="checkbox" v-model="post.selected" /></td>
				<td>{{ post.id }}</td>
				<td>{{ post.title }}</td>
				<td>{{ post.published ? "Published" : "Draft" }}</td>
				<td>{{ post.published_at ? post.published_at : "-"}}</td>
				<td>{{ post.created_at }}</td>
				<td>
					<button>Publish</button>
					<button @click="editPost(post.slug)">Edit</button>
					<button @click="deletePost(post.id)">Delete</button>
				</td>
			</tr>
		</tbody>
		<tbody v-else>
			<tr>
				<td>No record.</td>
			</tr>
		</tbody>
	</table>
</template>