-- Incidencia 1: Fallo Mecánico en Máquina 1
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 1, 1, 'Fallo en el motor', 'Ruido extraño al arrancar', 'El motor hace un ruido inusual y parece que vibra más de lo normal. Revisar correas y rodamientos.', 1, 0);

-- Incidencia 2: Mantenimiento Preventivo en Máquina 2
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 2, 3, 'Mantenimiento Semanal', 'Revisión general de componentes', 'Realizar la rutina de mantenimiento preventivo semanal. Lubricar, ajustar y limpiar.', 1, 0);

-- Incidencia 3: Reparación Urgente en Máquina 1
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 1, 5, 'Parada de Emergencia', 'La máquina dejó de funcionar', 'Se detuvo inesperadamente y no responde a los controles. Revisar sistema eléctrico y de control inmediatamente.', 1, 0);

-- Incidencia 4: Problema de Calidad en Máquina 2
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 2, 13, 'Piezas con defectos', 'Revisar calidad de la producción', 'Las piezas producidas presentan rebabas y falta de precisión. Ajustar parámetros o revisar herramientas.', 1, 0);

-- Incidencia 5: Ajuste de Máquina en Máquina 1
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 1, 6, 'Ajuste de parámetros', 'La máquina está funcionando fuera de rango', 'Ajustar los parámetros de velocidad y temperatura para un funcionamiento óptimo.', 1, 0);

-- Incidencia 6: Fallo de Sensor en Máquina 2
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 2, 14, 'Sensor de proximidad no funciona', 'La máquina no detecta la pieza', 'Revisar el sensor de proximidad, cableado y configuración.', 1, 0);

-- Incidencia 7: Reemplazo de Piezas en Máquina 1
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 1, 8, 'Cambiar correa', 'La correa está desgastada', 'Reemplazar la correa de transmisión principal. Pedir la pieza necesaria.', 1, 0);

-- Incidencia 8: Lubricación en Máquina 2
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 2, 7, 'Lubricación general', 'Engrasar los puntos de fricción', 'Realizar lubricación en todos los puntos recomendados en el manual del fabricante.', 1, 0);

-- Incidencia 9: Actualización de Software en Máquina 1
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 1, 10, 'Actualizar software de control', 'Nueva versión disponible', 'Instalar la nueva versión del software de control de la máquina.', 1, 0);

-- Incidencia 10: Calibración en Máquina 2
INSERT INTO incidencias (fecha_apertura, id_maquina, id_tipo_incidencia, titulo, subtitulo, descripcion, id_creador, estado)
VALUES (NOW(), 2, 11, 'Calibrar medidor de presión', 'Mediciones incorrectas', 'Calibrar el medidor de presión para garantizar mediciones precisas.', 1, 0);