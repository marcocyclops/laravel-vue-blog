<script setup>

	import { Inertia } from '@inertiajs/inertia'

	function toDashboard() {
		Inertia.get(`/wcms/posts`)
	}

	function toCreate() {
		Inertia.get(`/wcms/posts/create`)
	}
</script>


<template>
	<div class="flex flex-col bg-red-200">
		<div @click="toDashboard">Dashboard</div>
		<div @click="toCreate" class="whitespace-nowrap">Create Post</div>
	</div>
	<slot />
</template>
