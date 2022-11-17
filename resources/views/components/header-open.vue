<script setup>
	import { Inertia } from '@inertiajs/inertia'

	function toHome() {
		Inertia.get(`/`)
	}
</script>


<template>
	<div class="flex flex-row bg-red-100">
		<h1 @click="toHome" class="w-1/2">Marco Cyclops</h1>
	</div>
</template>
