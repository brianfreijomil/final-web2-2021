FINAL - 16 Dic 2021
La cadena de estaciones de servicio PFY busca desarrollar una aplicación de
beneficios y premios para fidelizar a sus clientes. En esta aplicación los usuarios
podrán realizar sus pagos y obtener kilómetros por cada compra con el fin de
acceder a descuentos.
La empresa nos provee la siguiente base de datos para sus clientes:
CLIENTE(id: int; nombre: string, dni: string, telefono: string, direccion: string,
ejecutivo: boolean)
TARJETA(id: int; fecha_alta: datetime; nro_tarjeta: string, fecha_vencimiento:
int, tipo_tarjeta: string, id_cliente: int)
Además. nos brinda acceso a la tabla interna donde se registran todas las
actividades por tarjeta para llevar la totalidad de kms obtenidos:
ACTIVIDAD(id: int; kms: int, fecha: datetime, tipo_operación: int, id_cliente:
int)
Donde el tipo_operación es 1=canje y 2=suma
Un cliente puede tener varias tarjetas asociadas, pero cada una tiene un número
único.
Esta plataforma debe contar con una sección privada donde los empleados de la
estación administren las cuentas, y una sección solo para clientes donde puedan ver
el estado de sus cuentas.





1. ALTA CLIENTE
Implemente el siguiente requerimiento siguiendo el patrón MVC. No es necesario
realizar las vistas, solo controlador(es), modelo(s) y las invocaciones a la vista.
- Dar de alta un cliente nuevo al sistema.
Se debe poder crear un nuevo cliente en el sistema indicando todos los
datos necesarios y cumpliendo los siguientes requerimientos. Informar los
errores correspondientes en caso de no cumplirlos.
■ Controlar posibles errores de carga de datos.
■ Verificar que un usuario admin esté logueado.
■ Verificar que no exista un cliente con el mismo dni.
■ Cuando se agrega un cliente se le deben depositar
automáticamente 200 kms en su cuenta.
■ Si el cliente es EJECUTIVO, se le debe asociar
automáticamente una tarjeta del tipo ejecutiva empresarial.
(Suponga que los datos de la tarjeta se obtienen de una función
CardHelper->getBussinesCard())
ACLARACIÓN: No es necesario implementar el router del sistema.