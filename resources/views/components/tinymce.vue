<script setup>
    import { ref, toRefs, watch } from 'vue'

    import tinymce from 'tinymce/tinymce.js'
    import 'tinymce/models/dom'; //(TinyMCE 6)
    import 'tinymce/skins/ui/oxide/skin.css'
    import 'tinymce/themes/silver'
    import 'tinymce/icons/default'
    import Editor from '@tinymce/tinymce-vue'

	const props = defineProps({
        modelValue: String,
	})

    const emit = defineEmits(['update:modelValue'])

    const { modelValue } = toRefs(props)
    const editValue = ref(modelValue.value)

    watch(editValue, newVal => {
        emit('update:modelValue', newVal)
    })

</script>

<template >

    <Editor
        v-model="editValue"
        :init="{
            content_css: false, // path of the css, control the layout of all your page
            skin : false
        }"
    >
    </Editor>

</template>