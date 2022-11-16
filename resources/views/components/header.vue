<script setup>

	import { Inertia } from '@inertiajs/inertia'

	function logout() {
		Inertia.get(`/wcms/logout`)
	}
</script>


<template>
	<div class="flex flex-row bg-red-100">
		<h1 class="w-1/2">Marco Cyclops</h1>
		<h1 class="w-1/2 text-end border"><span @click="logout">Logout</span></h1>
	</div>
	<slot />
</template>
