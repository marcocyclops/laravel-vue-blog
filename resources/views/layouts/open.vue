<script setup>
	import HeaderOpen from '@/views/components/header-open.vue'
	import Footer from '@/views/components/footer.vue'

	import '@/css/global.css'
</script>

<template>
	<div class="flex flex-col">
		<HeaderOpen class="sticky top-0" />
		<slot />
		<Footer />
	</div>
</template>
