CREATE DATABASE IF NOT EXISTS ecobol_tracking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecobol_tracking;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'empleado') NOT NULL DEFAULT 'empleado',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuarios_rol (rol)
) ENGINE=InnoDB;

CREATE TABLE sucursales (
    id_sucursal INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    UNIQUE KEY uk_sucursal_nombre_ciudad (nombre, ciudad)
) ENGINE=InnoDB;

CREATE TABLE estados (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    descripcion VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE envios (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    codigo_rastreo VARCHAR(40) NOT NULL UNIQUE,
    remitente VARCHAR(150) NOT NULL,
    destinatario VARCHAR(150) NOT NULL,
    peso DECIMAL(10,2) NOT NULL,
    tipo_servicio VARCHAR(80) NOT NULL,
    id_sucursal_origen INT NOT NULL,
    id_sucursal_destino INT NOT NULL,
    id_estado INT NOT NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_envios_origen FOREIGN KEY (id_sucursal_origen) REFERENCES sucursales(id_sucursal) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_envios_destino FOREIGN KEY (id_sucursal_destino) REFERENCES sucursales(id_sucursal) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_envios_estado FOREIGN KEY (id_estado) REFERENCES estados(id_estado) ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_envios_codigo (codigo_rastreo),
    INDEX idx_envios_fecha (fecha_registro),
    INDEX idx_envios_estado (id_estado),
    INDEX idx_envios_origen (id_sucursal_origen),
    INDEX idx_envios_destino (id_sucursal_destino)
) ENGINE=InnoDB;

CREATE TABLE historial_estados (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_envio INT NOT NULL,
    id_estado INT NOT NULL,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observacion VARCHAR(255) NULL,
    actualizado_por INT NOT NULL,
    CONSTRAINT fk_historial_envio FOREIGN KEY (id_envio) REFERENCES envios(id_envio) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_historial_estado FOREIGN KEY (id_estado) REFERENCES estados(id_estado) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_historial_usuario FOREIGN KEY (actualizado_por) REFERENCES usuarios(id_usuario) ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_historial_envio (id_envio),
    INDEX idx_historial_fecha (fecha_hora)
) ENGINE=InnoDB;
