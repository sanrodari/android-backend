* La representación Web.
* Manejar mejor los password, BCrypt.
* Considerar ser más consistente con la forma de definir librerías: `session_manager`
sería mejor que se definiera como `database`.
* Corregir código de respuesta para cuando dice "Faltan parámetros".
* Considerar hacer un 404 para el delete.

* Verificar las peticiones (Códigos de respuesta y encabezados).

Peticiones
----------

* POST      /sessions/
* POST      /users/
* GET       /songs/
* POST      /songs/
* GET       /songs/:id

* Considerar usar el encabezado de petición Content-Type y además el uso de GSON.

Peticiones por hacer
--------------------

* PUT       /users/:id
* GET       /users/:id

* PUT       /songs/:id
* DELETE    /songs/:id

