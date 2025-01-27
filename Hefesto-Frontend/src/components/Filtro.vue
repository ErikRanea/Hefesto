<template>
<GlassmorphicPopup
:visible=""
:title=""
:subtitle=""
:closeButtonText=""
:actionButtonText=""
>

</GlassmorphicPopup>


</template>

<script setup>
import GlassmorphicPopup from './GlassmorphicPopup.vue';
import { ref, onMounted, computed } from 'vue';
import CustomSelect from './CustomSelect.vue';
import axios from 'axios';


</script>

<style scoped>
.glassmorphic-container {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  border: 1px solid rgba(255, 255, 255, 0.18);
  color: #333;
}

.glassmorphic-btn {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(5px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: #333;
  padding: 10px 20px;
  border-radius: 50px;
  font-weight: bold;
  transition: all 0.3s ease;
  cursor: pointer;
}

.glassmorphic-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

.glassmorphic-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.glassmorphic-input, .glassmorphic-select {
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 10px;
  padding: 10px;
  color: #333;
}

.glassmorphic-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
}

.glassmorphic-table th,
.glassmorphic-table td {
  background: rgba(255, 255, 255, 0.2);
  padding: 15px;
  text-align: left;
  border: none;
}

.glassmorphic-table th {
  font-weight: bold;
  text-transform: uppercase;
}

.glassmorphic-table tr {
  transition: all 0.3s ease;
}

.glassmorphic-table tr:hover {
  background: rgba(255, 255, 255, 0.3);
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.add-user-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

.btn-icon {
  width: 20px;
  height: 20px;
}

.danger-btn {
  background: rgba(220, 53, 69, 0.7);
  color: white;
}

.danger-btn:hover {
  background: rgba(220, 53, 69, 0.9);
}

.enable-btn {
  background: rgba(40, 167, 69, 0.7);
  color: white;
}

.enable-btn:hover {
  background: rgba(40, 167, 69, 0.9);
}

.form-title, .list-title {
  font-size: 24px;
  margin-bottom: 20px;
  color: #333;
}

.radio-group {
  display: flex;
  gap: 20px;
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 5px;
}

.glassmorphic-checkbox {
  appearance: none;
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.5);
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.2);
  cursor: pointer;
}

.glassmorphic-checkbox:checked {
  background: rgba(96, 4, 132, 0.7);
}

.table-container {
  overflow-x: auto;
  margin-bottom: 20px;
}
  .switch {
   position: relative;
   display: inline-block;
   width: 120px;
   height: 34px;
  }

  .switch input {
   display: none;
  }

  .slider {
   position: absolute;
   cursor: pointer;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background-color: #3C3C3C;
   -webkit-transition: .4s;
   transition: .4s;
   border-radius: 34px;
  }

  .slider:before {
   position: absolute;
   content: "";
   height: 26px;
   width: 26px;
   left: 4px;
   bottom: 4px;
   background-color: white;
   -webkit-transition: .4s;
   transition: .4s;
   border-radius: 50%;
  }

  input:checked + .slider {
   background-color: rgba(96, 4, 132, 1);
  }

  input:focus + .slider {
   box-shadow: 0 0 1px rgba(96, 4, 132, 0.719);
  }

  input:checked + .slider:before {
   -webkit-transform: translateX(26px);
   -ms-transform: translateX(26px);
   transform: translateX(85px);
  }

  /*------ ADDED CSS ---------*/
  .slider:after {
 content: 'DESABILITADO';
 color: white;
 display: block;
 position: absolute;
 transform: translate(-50%,-50%);
 top: 50%;
 left: 60%;
 font-size: 10px;

}

input:checked + .slider:after {
 content: 'HABILITADO';
 left: 47%;
}

  /*--------- END --------*/
.edit-btn{
    margin-left: 5px;
  background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #333;
    padding: 5px 10px;
    border-radius: 50px;
    font-weight: bold;
    transition: all 0.3s ease;
    cursor: pointer;
}
.edit-btn:hover{
    background: rgba(255, 255, 255, 0.3);
}
.action-cell {
    display: flex;
    align-items: center;
}


</style>