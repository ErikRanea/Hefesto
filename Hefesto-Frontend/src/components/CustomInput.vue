<template>
    <div class="group">
      <input
        :placeholder="placeholder"
        type="text"
        class="input"
        v-model="inputValue"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const props = defineProps({
    placeholder: {
      type: String,
      default: 'Buscar...',
    },
    modelValue: {
      type: String,
      default: '',
    },
  });
  
  const emit = defineEmits(['update:modelValue']);
  
  const inputValue = ref(props.modelValue);
  </script>
  
  <style scoped>
  .group {
    display: flex;
    line-height: 28px;
    align-items: center;
    position: relative;
    max-width: 100%;
  }
  
  .input {
    width: 100%;
    height: 40px;
    line-height: 28px;
    padding: 0 1rem;
    border: 2px solid transparent;
    border-radius: 8px;
    outline: none;
    background-color: #f3f3f4;
    color: #0d0c22;
    transition: 0.3s ease;
  }
  
  .input::placeholder {
    color: #9e9ea7;
  }
  
  .input:focus,
  input:hover {
    outline: none;
    border-color: rgba(150, 150, 150, 0.4);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgb(150 150 150 / 10%);
  }
  </style>