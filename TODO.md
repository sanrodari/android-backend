* Revisar la rutas y pensar en una refactorización grande.
* Manejar mejor los password, BCrypt.
* Considerar ser más consistente con la forma de definir librerías: `session_manager`
sería mejor que se definió la de `database`.
* Corregir código de respuesta para cuando dice "Faltan parámetros".
* Considerar hacer un 404 para el DELETE.

* Verificar las peticiones (Códigos de respuesta y encabezados).
* Considerar usar el encabezado de petición Content-Type y además el uso de GSON.

Peticiones
----------

* POST      /sessions/
* POST      /users/
* GET       /songs/
* POST      /songs/
* GET       /songs/:id

* PUT       /users/:id
* GET       /users/:id

* PUT       /songs/:id
* DELETE    /songs/:id

Completados
-----------

* La representación Web.