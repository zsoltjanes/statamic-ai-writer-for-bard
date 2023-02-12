<template>
    <div class="addon-container">
        <button
            class="bard-toolbar-button"
            v-html="button.html"
            v-tooltip="button.text"
            @click="toggleDropdown"
        ></button>
        <div class="dropdown-container" v-if="showDropdown" v-click-outside="toggleDropdown">
            <button v-for="(type, key) in defaultOptions" :key="key" class="button" @click="send(key)"
            >
                {{ type.name }}
            </button>
        </div>
    </div>
</template>

<script>
import vClickOutside from 'v-click-outside'
import axios from "axios";

export default {
    name: "Menu",
    directives: {
        clickOutside: vClickOutside.directive
    },
    mixins: [BardToolbarButton],
    data() {
        return {
            showDropdown: false,
            defaultOptions: {
                'grammar': {
                    name: 'Grammar correction',
                },
                'continue': {
                    name: 'Continue',
                },
                'summarize': {
                    name: 'Summarize',
                },
                'article': {
                    name: 'Generate an article',
                },
                'advertisement': {
                    name: 'Generate an advertisement',
                },
            }
        };
    },
    methods: {
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        async send(type) {

            // Close dropdown
            this.toggleDropdown();

            // Don't touch the editor til we are waiting for the api response.
            this.editor.setEditable(false)

            // Get selected content
            const selection = this.editor.view.state.selection;

            // Get the first selected character position
            const selectionFrom = selection.from;

            // Get the last selected character position
            const selectionTo = selection.to;

            // Get the selected elements as JSON format
            const selectedElements = this.editor.view.state.toJSON().doc;

            // Prepare data to the api request
            const data = {
                type,
                prompt: selectedElements
            }

            const that = this;

            await axios.post('/!/statamic-bard-openai/', data).then(function (response) {
                if (response?.data) {

                    if (type === 'grammar' || type === 'article' || type === 'advertisement' || type === 'continue') {
                        if (response?.data?.text) {
                            that.editor.commands.deleteSelection();
                            that.editor.commands.insertContentAt(selectionFrom, response.data.text);
                        }
                    }

                    if (type === 'summarize') {
                        if (response?.data?.text) {
                            that.editor.commands.insertContentAt(selectionTo, `Summary: ${response.data.text}`);
                        }
                    }

                    Statamic.$toast.success(__('OpenAI response successfully!'));
                }
            }).catch(function (error) {
                Statamic.$toast.error(error?.response?.data || error.message);
            }).finally(function () {
                that.editor.commands.focus();
                that.editor.setEditable(true);
            });
        }
    }
};
</script>

<style lang="postcss">
.addon-container {
    @apply inline-block relative;
}

.dropdown-container {
    @apply absolute bg-white border border-gray-300 rounded-sm z-10 divide-y divide-gray-100 shadow-lg;
}

.button {
    @apply text-left px-3 py-2 w-full hover:bg-gray-100 flex items-center whitespace-nowrap;
}

.button.active {
    @apply bg-gray-200;
}

.ProseMirror[contenteditable="false"]::after {
    @apply absolute -mt-8 -ml-8 w-12 h-12 border-8 border-gray-400 border-t-gray-900 rounded-full animate-spin inset-1/2 content-[''];
}

.ProseMirror[contenteditable="false"] {
    @apply bg-gray-200;
}
</style>
