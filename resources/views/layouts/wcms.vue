<script setup>
	// import Header from '../components/header.vue'
	import Header from '@/views/components/header.vue'
	import WcmsSideMenu from '@/views/components/wcms-side-menu.vue'

	import '@/css/global.css'
</script>

<template>
	<div class="flex flex-col">
		<div>
			<Header/>
		</div>
		<div class="flex flex-row">
			<div><WcmsSideMenu /></div>
			<div><slot /></div>
		</div>
	</div>
</template>
