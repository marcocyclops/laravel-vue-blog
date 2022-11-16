<script setup>
    import { computed, ref, toRefs, watch } from 'vue'

	import VueTagsInput from '@sipec/vue3-tags-input'

	defineProps({
        tags: Array
	})

    

</script>

<template >
    
    <div class="flex flex-row">
        <div v-for="tag in tags" :key="tag.id">
            {{ tag.name.en }}
        </div>
    </div>
    
</template>