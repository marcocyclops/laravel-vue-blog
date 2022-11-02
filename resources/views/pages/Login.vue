<script setup>

	import { ref, onMounted } from 'vue'

	defineProps({
		username: String
	})

	let site = ref('https://marcocyclops.com')

	function change() {
		site.value = 'https://luckyo.com'
	}

	onMounted(() => {
		console.log('mounted')
	})

</script>

<template layout="public">
<div>
    <h1>Please login:</h1>
</div>
</template>