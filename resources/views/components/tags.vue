<script setup>
    import { computed, ref, toRefs, watch } from 'vue'

	import VueTagsInput from '@sipec/vue3-tags-input'

	const props = defineProps({
        modelValue: Array,
        suggestTags: Array,
	})

    const emit = defineEmits(['update:modelValue'])

    const { modelValue, suggestTags } = toRefs(props)
    const thisTags = ref(modelValue.value)
    const tag = ref()
    
    const autocompleteItems = suggestTags.value
    
    const filteredItems = computed(() => {
        return autocompleteItems.filter(i => {
            return i.text.toLowerCase().indexOf(tag.value ? tag.value.toLowerCase() : "") !== -1
        })
    })

    watch(thisTags, newVal => {
        emit('update:modelValue', newVal)
    })

</script>

<template >
    <vue-tags-input
        v-model="tag"
        :tags="thisTags"
        :autocomplete-items="filteredItems"
        @tags-changed="newTags => thisTags = newTags"
    />

</template>