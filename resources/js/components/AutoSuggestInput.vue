<template>
  <div>
    <input
      type="text"
      class="form-control"
      :placeholder="placeholder"
      v-model="searchText"
      @input="fetchSuggestions"
      @blur="hideSuggestions"
      @focus="fetchSuggestions"
    />

    <ul v-if="showDropdown && suggestions.length" class="suggestions">
      <li
        v-for="(word, index) in suggestions"
        :key="index"
        @mousedown.prevent="selectSuggestion(word)"
      >
        {{ word }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'AutoSuggestInput',
  props: {
    value: String,
    placeholder: {
      type: String,
      default: 'Start typing...',
    },
  },
  data() {
    return {
      searchText: this.value || '',
      suggestions: [],
      showDropdown: false,
      typingTimeout: null,
    };
  },
  watch: {
    searchText(newVal) {
      this.$emit('input', newVal); 
      this.fetchSuggestions();
    },
    value(newVal) {
      this.searchText = newVal;
    },
  },
  methods: {
    fetchSuggestions() {
      clearTimeout(this.typingTimeout);
      this.typingTimeout = setTimeout(async () => {
        if (!this.searchText) {
          this.suggestions = [];
          return;
        }

        try {
          const res = await fetch(`https://api.datamuse.com/sug?s=${this.searchText}`);
          const data = await res.json();
          this.suggestions = data.map(item => item.word).slice(0, 5);
          this.showDropdown = true;
        } catch (e) {
          console.error('Suggestion fetch error:', e);
        }
      }, 300);
    },
    selectSuggestion(word) {
      this.searchText = word;
      this.showDropdown = false;
    },
    hideSuggestions() {
      setTimeout(() => {
        this.showDropdown = false;
      }, 100);
    },
  },
};
</script>
<style scoped>
.suggestions {
  border: 1px solid #ccc;
  max-height: 150px;
  overflow-y: auto;
  list-style: none;
  padding: 0;
  margin: 5px 0 0;
}
.suggestions li {
  padding: 6px 10px;
  cursor: pointer;
}
</style>
