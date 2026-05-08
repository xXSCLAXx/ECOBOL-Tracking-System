USE ecobol_tracking;

INSERT INTO estados (nombre, descripcion) VALUES
('Registrado', 'Envio creado y pendiente de procesamiento'),
('En tránsito', 'Envio en movimiento entre sucursales'),
('En sucursal destino', 'Envio disponible en la sucursal de destino'),
('Entregado', 'Envio entregado al destinatario');

INSERT INTO sucursales (nombre, ciudad, direccion, telefono) VALUES
('Casa Central La Paz', 'La Paz', 'Av. Mariscal Santa Cruz, Edificio Central', '2200001'),
('Sucursal Santa Cruz Centro', 'Santa Cruz', 'Calle Libertad, Zona Centro', '3300002'),
('Sucursal Cochabamba', 'Cochabamba', 'Av. Oquendo Esq. 16 de Julio', '4400003');

INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES
('Administrador ECOBOL', 'admin@ecobol.bo', '$2b$12$auhihsYdHs5sXROv3GWSBOu.0rX993KN.BPHXPnCQMVzJLQWR0doa', 'administrador'),
('Empleado ECOBOL', 'empleado@ecobol.bo', '$2b$12$auhihsYdHs5sXROv3GWSBOu.0rX993KN.BPHXPnCQMVzJLQWR0doa', 'empleado');
