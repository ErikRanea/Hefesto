/* Trigger para que automáticamente, cada vez que se inserte o actualice un registro en la tabla tarea_usuario,
 se cree o actualice un registro en la tabla registros_trabajo con la información correspondiente.*/

 DELIMITER //

CREATE TRIGGER after_tarea_usuario_insert
AFTER INSERT ON tarea_usuario
FOR EACH ROW
BEGIN
    -- Insertar un registro en la tabla registros_trabajo
    INSERT INTO registros_trabajo (id_usuario, id_tarea, fecha_trabajo, horas_trabajo,habilitado)
    VALUES (
        NEW.id_usuario,
        NEW.id_tarea,
        IFNULL(NEW.fecha_inicio, CURDATE()), -- Usar fecha inicio o fecha actual si fecha inicio es null
      IF(NEW.fecha_fin IS NULL, 0,TIMESTAMPDIFF(HOUR, NEW.fecha_inicio, IFNULL(NEW.fecha_fin, NOW()))),
        NEW.habilitado
    );
END;
//

CREATE TRIGGER after_tarea_usuario_update
AFTER UPDATE ON tarea_usuario
FOR EACH ROW
BEGIN
    -- Verificar si fecha inicio o fecha fin ha cambiado
    IF OLD.fecha_inicio <> NEW.fecha_inicio OR OLD.fecha_fin <> NEW.fecha_fin OR OLD.habilitado <> NEW.habilitado THEN
        -- Verificar si existe un registro en registros_trabajo para la tarea y el usuario en esa fecha
        IF EXISTS (SELECT 1 FROM registros_trabajo WHERE id_usuario = NEW.id_usuario AND id_tarea = NEW.id_tarea AND fecha_trabajo = IFNULL(NEW.fecha_inicio, CURDATE())) THEN
            -- Actualizar el registro existente
            UPDATE registros_trabajo
            SET horas_trabajo = IF(NEW.fecha_fin IS NULL, 0,TIMESTAMPDIFF(HOUR, NEW.fecha_inicio, IFNULL(NEW.fecha_fin, NOW()))),
            habilitado=NEW.habilitado
            WHERE id_usuario = NEW.id_usuario AND id_tarea = NEW.id_tarea AND fecha_trabajo = IFNULL(NEW.fecha_inicio, CURDATE());
        ELSE
            -- Insertar un nuevo registro
          INSERT INTO registros_trabajo (id_usuario, id_tarea, fecha_trabajo, horas_trabajo,habilitado)
          VALUES (
              NEW.id_usuario,
              NEW.id_tarea,
               IFNULL(NEW.fecha_inicio, CURDATE()),
              IF(NEW.fecha_fin IS NULL, 0,TIMESTAMPDIFF(HOUR, NEW.fecha_inicio, IFNULL(NEW.fecha_fin, NOW()))),
                NEW.habilitado
          );
        END IF;
    END IF;
END;
//

DELIMITER ;