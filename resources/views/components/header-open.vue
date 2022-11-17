<script setup>

	import { computed } from 'vue'
	import { Inertia } from '@inertiajs/inertia'
	import { usePage } from '@inertiajs/inertia-vue3'

	const appName = computed(() => usePage().props.value.appName)
	const email = computed(() => usePage().props.value.contactMe)

	function toHome() {
		Inertia.get(`/`)
	}
</script>


<template>
	<div class="flex flex-row bg-red-100 px-4 py-2">
		<div class="w-1/2">
			<span @click="toHome" class="cursor-pointer text-lg whitespace-nowrap">{{ appName }}</span>
		</div>
		<div class="w-1/2 text-end">
			<a :href="`mailto:${email}`">Contact Me</a>
		</div>
	</div>
</template>
