<script setup>
    import { ref, toRefs, watch } from 'vue'

	import VueTagsInput from '@sipec/vue3-tags-input'

	const props = defineProps({
        modelValue: Array,
	})

    const emit = defineEmits(['update:modelValue'])

    const { modelValue } = toRefs(props)
    const thisTags = ref(modelValue.value)
    const tag = ref()

    watch(thisTags, newVal => {
        emit('update:modelValue', newVal)
    })

</script>

<template >
    <vue-tags-input
        v-model="tag"
        :tags="thisTags"
        @tags-changed="newTags => thisTags = newTags"
    />

</template>