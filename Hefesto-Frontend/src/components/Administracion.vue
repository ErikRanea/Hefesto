<template>
<div class="container-fluid h-100">
    <div class="row">
        <div class="col-md-6 h-100">
            <button class="glassmorphic-card mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-body text-center">
                        <h2>Usuarios</h2>
                    </div>
                    <div class="card-body text-center">
                        <img src="../assets/images/icons/usuarios.svg">
                    </div>
                </div>
            </button>
            <button class="glassmorphic-card mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-body text-center">
                        <h2>Campuses</h2>
                    </div>
                    <div class="card-body text-center">
                        <img src="../assets/images/icons/campuses.svg">
                    </div>
                </div>
            </button>
        </div>
        <div class="col-md-6">
            <button class="glassmorphic-card mb-4">
                <div class="justify-content-center align-items-center text-center">
                    <div class="card-body justify-content-center align-items-center mb-3">
                        <h2>Secciones</h2>
                    </div>
                    <div class="card-body-2">
                        <img src="../assets/images/icons/secciones.svg">
                    </div>
                </div>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <button class="glassmorphic-card mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-body text-center">
                        <h2>Maquinas</h2>
                    </div>
                    <div class="card-body text-center">
                        <img width="200" src="../assets/images/icons/maquinas.svg">
                    </div>
                </div>
            </button>
        </div>
    </div>

</div>

</template>

<script>
export default {
    name: 'Administracion',
}
</script>

<style scoped>

    .glassmorphic-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
        border-radius: 16px;
        width: 100%;
    }

    .card-body {
        padding: 32px;
    }

    .card-body-2 {
        padding: 73px;
    }

</style>