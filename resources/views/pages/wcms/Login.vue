<script setup>

	import { useForm } from '@inertiajs/inertia-vue3'

	defineProps({
		errors: Object
	})

	const form = useForm({
		'email': null,
		'password': null,
		'remember': false,
	})

	async function submit() {
		form.post('/wcms/login')
	}

</script>

<template>
	<div v-if="errors.auth">{{ errors.auth }}</div>
	<div v-if="errors.throttling">{{ errors.throttling }}</div>

	<form @submit.prevent="submit">
		<div>
			<label for="email">Email: </label>
			<input type="text" id="email" v-model="form.email" />
			<div v-if="errors.email">{{ errors.email }}</div>
		</div>

		<div>
			<label for="password">Password: </label>
			<input type="password" id="password" v-model="form.password" />
			<div v-if="errors.password">{{ errors.password }}</div>
		</div>

		<div>
			<input type="checkbox" id="remember" v-model="form.remember" />
			<label for="remember">Remember me</label>
		</div>

		<div>
			<button type="submit" :disabled="form.processing">Login</button>
		</div>
	</form>
</template>