<script setup>
	import { Inertia } from '@inertiajs/inertia'

	defineProps({
		posts: Object
	})

	function create() {
		Inertia.get('/wcms/posts/create')
	}
	
</script>

<template layout="wcms">
	<div>Posts List:</div>
	<div>
		<button @click="create">Create</button>
	</div>
	<div>
		<select>
			<option>Select to filter tag</option>
		</select>
	</div>
	<div>
		<button>Publish Selected</button>
		<button>Delete Selected</button>
	</div>
	<table>
		<tr>
			<th>Select</th>
			<th>ID</th>
			<th>Title</th>
			<th>Status</th>
			<th>Published Date</th>
			<th>Created Date</th>
			<th>Action</th>
		</tr>
		<tr v-for="post in posts" :key="post.id">
			<td><input type="checkbox"></td>
			<td>{{ post.id }}</td>
			<td>{{ post.title }}</td>
			<td>{{ post.published ? "Published" : "Draft" }}</td>
			<td>{{ post.published_at ? post.published_at : "-"}}</td>
			<td>{{ post.created_at }}</td>
			<td>
				<button>Publish</button>
				<button>Edit</button>
				<button>Delete</button>
			</td>
		</tr>
	</table>
	
</template>