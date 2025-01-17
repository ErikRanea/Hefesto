import axios from 'axios';


const comprobarToken = async () => {
const token = localStorage.getItem('token');
if (token) {
  try {
    const response = await axios.get(ME_URL, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return [
        userRole.value = response.data.rol,
        userName.value = response.data.name,
        userLastName.value = response.data.primer_apellido,
        userPicture.value = response.data.foto_perfil,
    ];

  } catch (error) {
    router.push('/');
  }
} else {
  router.push('/');
}
};
