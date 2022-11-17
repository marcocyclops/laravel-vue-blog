<script setup>
	// import Header from '../components/header.vue'
	import Header from '@/views/components/header.vue'
	import Footer from '@/views/components/footer.vue'
	import WcmsSideMenu from '@/views/components/wcms-side-menu.vue'

	import '@/css/global.css'
</script>

<template>
	<div class="flex flex-col">
		<Header class="sticky top-0" />
		<div class="flex flex-row">
			<WcmsSideMenu />
			<slot />
		</div>
		<Footer />
	</div>
</template>
