<template>
        <div class="container-fluid">
            <div class="card">
                <span>Sección:</span>
                <select name="" id=""></select>
                <span>Campus:</span>
                <select name="" id=""></select>
                <span>Buscar maquina:</span>
                <input type="text">
            </div>
            
        </div>
        

        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
            <div v-for="maquina in maquinas.slice(0,8)" :key="maquina.id_maquina" class="col d-flex justify-content-center">
                <div class="card glassmorphic-card mb-4">
                    <div class="d-flex justify-content-between">
                        <h5>MAQUINA {{ maquina.nombre_maquina }}</h5>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <span>ID:</span>
                            <span>{{ maquina.id_maquina }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Número interno:</span>
                            <span>{{ maquina.numero_interno }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tipo:</span>
                            <span>{{ maquina.tipo_maquina }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Prioridad:</span>
                            <span>{{ maquina.prioridad }}</span>
                        </div>
                        <div class="machine-status-button">
                            <button :class="getStatusClass(maquina.habilitado, maquina.parada)" class="btn w-100" disabled>
                                {{ getStatusText(maquina.habilitado, maquina.parada) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      maquinas: [],
      loading: false,
      error: null,
    };
  },
  mounted() {
      this.fetchMaquinas();
  },
  methods: {
    async fetchMaquinas(){
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/maquinas');
        this.maquinas = response.data;
      } catch(error) {
          console.error('Error fetching maquinas', error);
          this.error = 'Error al cargar las máquinas. Intenta nuevamente.'
      } finally{
          this.loading = false;
      }
    },
    getStatusClass(habilitado, parada) {
          if (parada) {
              return "btn-warning"
          }
        return habilitado ? "btn-success" : "btn-danger";
      },
      getStatusText(habilitado, parada) {
           if (parada) {
             return "Parada"
         }
        return habilitado ? "Disponible" : "No disponible";
      },
  },
};
</script>

<style scoped>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    /*
    .machine-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 20px;
        width: 270px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        transition: .4s ease-in-out;
        margin: 10px;
    }
    */

    .glassmorphic-card {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(8px);
        padding: 20px;
        width: 270px;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
        margin: 10px;
    }

</style>