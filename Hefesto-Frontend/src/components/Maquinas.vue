<template>
        <div class="card filtro-card">
            <div class="row text-center align-items-center">
                <div class="col">
                    <p>Campus</p>
                    <select class="filtro-maquina" name="campus" id="nombre_campus" v-model="selectedCampus">
                        <option v-for="campus in campusList" :key="campus.nombre_campus" :value="campus.nombre_campus">{{ campus.nombre_campus }}</option>
                    </select>
                </div>
                <div class="col">
                    <p>Sección</p>
                    <select class="filtro-maquina" name="seccion" id="nombre_seccion" v-model="selectedSeccion">
                        <option v-for="seccion in seccionList" :key="seccion.nombre_seccion" :value="seccion.nombre_seccion">{{ seccion.nombre_seccion }}</option>
                    </select>
                </div>
                <div class="col">
                    <p>Buscar maquina</p>
                    <input class="filtro-maquina" type="text" v-model="searchName">
                </div>
                <div class="col">
                    <button class="filtro-maquina" @click="searchMachines">Buscar</button>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
            <div v-for="maquina in maquinas" :key="maquina.id_maquina" class="col d-flex justify-content-center">
                <div class="card glassmorphic-card mb-4">
                    <div class="d-flex justify-content-center">
                        <h5>{{ maquina.nombre_maquina }}</h5>
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

const apiUrl = import.meta.env.VITE_API_AUTH_URL;

export default {
    data() {
        return {
            maquinas: [],
            campusList: [],
            seccionList: [],
            selectedCampus: null,
            selectedSeccion: null,
            searchName: '',
            token: null
        };
    },
    mounted() {
        this.token = localStorage.getItem('token');
        this.fetchMaquinas();
        this.fetchCampus();
        this.fetchSeccion();
    },
    methods: {
        async fetchMaquinas() {
            try {
                const response = await axios.post(`${apiUrl}/maquina/all`, {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    }
                });
                this.maquinas = response.data.data;
                console.log(this.maquinas);
            } catch (error) {
                console.error('Error al obtener las máquinas:', error);
            }
        },
        async fetchCampus() {
            try {
                const response = await axios.get(`${apiUrl}/campus/all`, {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    }
                });
                this.campusList = response.data.data;
                console.log(this.campusList);
            } catch (error) {
                console.error('Error al obtener los campus:', error);
            }
        },
        async fetchSeccion() {
            try {
                const selectedCampusObject = this.campusList.find(campus => campus.nombre_campus === this.selectedCampus);
                const id_campus = selectedCampusObject ? selectedCampusObject.id_campus : null;
                console.log('ID Campus:', id_campus+'\nToken:', this.token);
                const response = await axios.post(`${apiUrl}/seccion/all`, {
                    id_campus: id_campus,
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    }
                    
                });
                this.seccionList = response.data.data;
                console.log(this.seccionList);
            } catch (error) {
                console.error('Error al obtener las secciones:', error);
            }
        },
        async searchMachines() {
    const selectedSeccionObject = this.seccionList.find(seccion => seccion.nombre_seccion === this.selectedSeccion);
    const selectedCampusObject = this.campusList.find(campus => campus.nombre_campus === this.selectedCampus);

    const filters = {
        id_seccion: selectedSeccionObject ? selectedSeccionObject.id_seccion : null,
        id_campus: selectedCampusObject ? selectedCampusObject.id_campus : null,
        nombre_maquina: this.searchName
    };
    this.fetchMaquinas(filters);
},
        getStatusClass(habilitado, parada) {
            if (parada) {
                return 'btn-danger';
            } else if (habilitado) {
                return 'btn-success';
            } else {
                return 'btn-warning';
            }
        },
        getStatusText(habilitado, parada) {
            if (parada) {
                return 'Parada';
            } else if (habilitado) {
                return 'Habilitada';
            } else {
                return 'Deshabilitada';
            }
        }
    }
};

</script>

<style scoped>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    .glassmorphic-card {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(8px);
        padding: 20px;
        width: 270px;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
        margin: 10px;
    }

    .filtro-card {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(8px);
        padding: 20px;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
        margin: 10px;
    }

    .filtro-maquina {
        width: 200px;
        height: 30px;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

</style>