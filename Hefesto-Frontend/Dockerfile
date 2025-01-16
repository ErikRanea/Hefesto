# Usa una imagen base de Node.js para la construcción de la app
FROM node:18-alpine as builder

# Establece el directorio de trabajo
WORKDIR /app

# Copia el archivo package.json y package-lock.json
COPY package*.json ./

# Instala las dependencias
RUN npm install

# Copia todo el resto del código
COPY . .

# Construye la aplicación Vue
RUN npm run build

# Utiliza una imagen nginx para servir los archivos estáticos
FROM nginx:alpine

# Copia los archivos de la construcción del paso anterior al servidor Nginx
COPY --from=builder /app/dist /usr/share/nginx/html

# Expone el puerto 80
EXPOSE 80

# Ejecuta el servidor Nginx
CMD ["nginx", "-g", "daemon off;"]

