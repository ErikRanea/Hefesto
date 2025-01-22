<template>
  <div class="customInputContainer" :class="{ show: isOpen }">
      <div class="customInput" @click="toggleDropdown">
          <div class="selectedData">{{ selectedLabel }}</div>
          <i class="fa-solid fa-angle-right"></i>
      </div>
      <div class="options">
          <div class="searchInput" :class="{ focus: searchInputFocus }">
              <input
                  type="text"
                  ref="searchInput"
                  :value="inputValue"
                  @input="handleSearchInput"
                  placeholder="Buscar"
                  @focus="searchInputFocus = true"
                  @blur="searchInputFocus = false"
              />
          </div>
          <ul>
              <li
                  v-for="option in filteredOptions"
                  :key="option.id"
                  @click="selectOption(option)"
                  :class="{ selected: option.id === modelValue }"
              >
                  {{ option.label }}
              </li>
              <p v-if="!filteredOptions.length" style="margin-top: 1rem; color: #888;">
                  Opps, no se encontraron resultados
                  <p style="margin-top: 0.2rem; font-size: 0.9rem;">
                      Intenta buscar otra cosa.
                  </p>
              </p>
          </ul>
      </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  options: {
      type: Array,
      required: true,
      default: () => [],
  },
  modelValue: {
      type: [String, Number],
      default: null,
  },
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const inputValue = ref('');
const searchInput = ref(null);
const searchInputFocus = ref(false);
const selectedLabel = ref("Seleccionar Opción");

const filteredOptions = computed(() => {
  if (!inputValue.value) {
      return props.options;
  }
  const searchTerm = inputValue.value.toLowerCase();
  return props.options.filter((option) =>
      option.label.toLowerCase().startsWith(searchTerm)
  );
});
const handleSearchInput = (event) => {
  inputValue.value = event.target.value;
};

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
  if(isOpen.value) {
      setTimeout(() => {
          searchInput.value.focus()
      }, 0)
  }
};
const selectOption = (option) => {
  selectedLabel.value = option.label;
  emit('update:modelValue', option.id);
  isOpen.value = false;
  inputValue.value = '';
};


const handleDocumentClick = (event) => {
  if (!event.target.closest('.customInputContainer')) {
      isOpen.value = false;
  }
};


watch(
  () => props.modelValue,
  (newModelValue) => {
      const selectedOption = props.options.find(option => option.id === newModelValue)
      if(selectedOption) {
          selectedLabel.value = selectedOption.label;
      } else {
          selectedLabel.value = "Seleccionar Opción";
      }
  },
  { immediate: true}
);

onMounted(() => {
  if(props.modelValue) {
      const selectedOption = props.options.find(option => option.id === props.modelValue)
      if(selectedOption) {
          selectedLabel.value = selectedOption.label;
      }
  }
  document.addEventListener('click', handleDocumentClick);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleDocumentClick);
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

.customInputContainer {
  width: 100%;
  display: flex;
  max-width: 100%;
  align-items: center;
  flex-direction: column;
  justify-content: center;
  border: 2px solid transparent;
  border-radius: 8px;
}

.customInputContainer .customInput {
  cursor: pointer;
  user-select: none;
  font-size: 1rem;
  justify-content: space-between;
  color: #0d0c22;
  background-color: #f3f3f4;
  padding: 0 1rem;
  height: 40px;
  display: flex;
  align-items: center;
  border-radius: 8px;
  transition: 0.3s ease;
}

.customInputContainer .customInput i {
  transition: transform 0.3s ease-in-out;
  color: #9e9ea7;
}

.customInputContainer.show .customInput i {
  transform: rotate(90deg);
}

.customInputContainer :is(.customInput, .options) {
  width: 100%;
  display: flex;
  align-items: center;
  border-radius: 8px;
}

.customInputContainer .options {
  display: none;
  font-size: 1rem;
  justify-content: start;
  flex-direction: column;
  margin-top: 0.2rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 0 0 4px rgb(150 150 150 / 10%);
  border-top-left-radius: 0;
  border-top-right-radius: 0;
   padding: 0;
}

.customInputContainer.show .options {
  display: block;
}

.customInputContainer .options :is(.searchInput, ul) {
  width: 100%;
  max-height: 15rem;
  overflow-y: auto;
  position: relative;
}

.customInputContainer .options ul::-webkit-scrollbar {
  width: 6px;
  position: relative;
}

.customInputContainer .options ul::-webkit-scrollbar-track {
  width: 2px;
  border-radius: .2rem;
  background: rgb(0 0 0 / 10%);
}

.customInputContainer .options ul::-webkit-scrollbar-thumb {
  border-radius: .2rem;
  background: rgb(0 0 0 / 30%);
}

.customInputContainer .options .searchInput {
  display: flex;
      padding: 0 1rem;
  overflow-y: auto;
  align-items: center;
  border-radius: 8px;
  color: rgb(0 0 0 / 50%);
  background-color: #f3f3f4;
      margin: 0.2rem 0;

}

.customInputContainer .options .searchInput.focus {
  outline: none;
  border-color: rgba(150, 150, 150, 0.4);
  background-color: #fff;
  box-shadow: 0 0 0 4px rgb(150 150 150 / 10%);
}

.customInputContainer .options .searchInput input[type="text"] {
  border: 0;
  width: 100%;
  outline: none;
  height: 40px;
  font-size: 1rem;
      padding: 0 1rem;
  border-radius: 8px;
  color: #333;
  background-color: transparent;

}

.customInputContainer .options .searchInput input[type="text"]::placeholder {
  font-size: 1rem;
  color: #999;
}

.customInputContainer .options ul {
  margin: 0; /* Remove ul default margins */
  padding: 0;  /* Remove ul default padding */
}

.customInputContainer .options ul li {
  cursor: pointer;
  list-style: none;
  padding: 0 1rem;
  border-bottom: 1px solid #eee;
  color: #333;
  height: 40px;
  display: flex;
  align-items: center;

}

.customInputContainer .options ul li.selected {
  background: #e0f7fa;
}

.customInputContainer .options ul li.selected:hover {
  background: #e0f7fa;
}

.customInputContainer .options ul li:last-child {
  border: 0;
}

.customInputContainer .options ul li:hover {
  background: #f0f0f0;
}
</style>