<script setup>

	import { computed } from 'vue'
	import { Inertia } from '@inertiajs/inertia'
	import { usePage } from '@inertiajs/inertia-vue3'

	const appName = computed(() => usePage().props.value.appName)
	const user = computed(() => usePage().props.value.auth.user)

	function logout() {
		Inertia.get(`/wcms/logout`)
	}
</script>


<template>
	<div class="flex flex-row bg-red-100">
		<h1 class="w-1/2">
			<span  @click="toHome">{{ appName }}</span>
		</h1>

		<h1 class="w-1/2 text-end">
			<span v-if="user">{{ user.name }} | </span>
			<span @click="logout">Logout</span>
		</h1>
	</div>
</template>
