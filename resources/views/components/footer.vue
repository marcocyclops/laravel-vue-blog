<script setup>

	const year = new Date().getFullYear()

</script>

<template>
	<div class="text-sm mx-auto p-4 text-center">
		<span class="mx-auto">Designed, developed and maintained by CHEUNG Marco Cyclops, 2022{{ year > 2022 ? `-${year}` : '' }}</span>
	</div>
</template>
