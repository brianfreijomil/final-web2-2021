4. API REST
Tenga en cuenta los siguientes casos de uso:
A. Como cliente quiero poder ver mis datos personales
B. Como cliente quiero poder modificar mis datos personales
C. Como cliente quiero poder ver un listado de mis tarjetas
D. Como cliente quiero poder el estado actual de mi cuenta
E. Como cliente quiero poder ver mi historial de actividades dado un intervalo de
dos fechas
F. Como cliente quiero poder dar de baja una tarjeta
a) ¿Qué cambios se deben realizar en el sistema para integrar estos
requerimientos a través de una API REST?
b) Defina la tabla de ruteo para cada requerimiento. (ENDPOINT + METODO
HTTP + CONTROLADOR+MÉTODO). No es necesario implementar.
c) Implemente el controlador de los puntos C, E y F



a)

Se debe agregar un nuevo route especifico para la Api , se puede usar la libreria brindada por catedra,
Ademas modificar .htacces para tomar la nueva url,
nuevos controladores y vistas para api , uso de javascript para trabajar con la api






b) Tablas de ruteo:

(ENDPOINT + METODO HTTP + CONTROLADOR+MÉTODO)

<?php

$router->addRoute('cliente/', 'GET', 'clienteController', 'getClienteById');
$router->addRoute('cliente/:ID', 'PUT', 'clienteController', 'updateCliente');
$router->addRoute('cliente/tarjeta/:ID', 'GET', 'tarjetaController', 'getAllcardsByClient');
$router->addRoute('cliente/actividad/:ID', 'GET', 'actividadController', 'getActividadByClient');
$router->addRoute('cliente/:ID', 'GET', 'actividadController', 'getHistorybtdates');





