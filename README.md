# Backend para gestion de usuarios y posts

## Pasos para correr la api:

1. Clonar el repositorio: ```git clone -b main https://github.com/DevJuanDuarte/usuarios_backend.git usuarios_pt```
2. Acceder a la carpeta del proyecto: ```cd usuarios_pt```
3. Ejecutar ```composer install```
4. Crear una base de datos llamada usuariosdb en mysql y correr el script ```usuariosdb.sql``` que se encuentra dentro de la carpeta sql


Endpoint de ejemplo para listar posts: ```http://localhost/usuarios_pt/public/api/posts```

### Tests:

- Ejecutar en la terminal : ```vendor\bin\phpunit --testdox```