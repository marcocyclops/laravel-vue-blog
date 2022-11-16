<script setup>


</script>


<template>
	<div class="flex flex-row bg-red-100">
		<h1 class="w-1/2">Marco Cyclops</h1>
		<div class="flex flex-row w-1/2 text-end">
			<label for="search">Search:</label>
			<input type="text" id="search" />
		</div>
	</div>
	<slot />
</template>
