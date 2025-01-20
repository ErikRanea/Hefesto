<template>
<div class="row g-4">
    <div class="col-md-7">
        <div class="card glassmorphic-card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4 align-items-center">
                    <h1 class="mb-1">Foto de perfil</h1>
                    <img src="../assets/images/icons/perfilDefault.svg">
                </div>
            </div>
        </div>
        <div class="card glassmorphic-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4 align-items-center">
                    <h1>Contraseña</h1>
                    <img src="../assets/images/icons/password.svg">
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Tickets List -->
    <div class="col-md-5">
      <div class="card glassmorphic-card h-100">
          <div class="card-body-2 d-flex justify-content-center align-items-center mb-3">
            <h1 class="mb-0">Fondo de pantalla</h1>
          </div>
          <div class="card-body-2 d-flex justify-content-center align-items-center mb-3">
            <img src="../assets/images/icons/fondoPantalla.svg">
          </div>
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
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
    }

    .card-body {
        padding: 2rem;
    }

    .card-body-2 {
        padding-top: 7rem;
    }

</style>