# Challenge PHP DeCampoACampo
-----
## Iniciar
### Crear .env
- Es necesario crear el .env, utilizando la copia del example
```shell
cp .env.example .env
```

### Iniciar
- Al iniciar, automaticamente ejecuta el script de creacion de la tabla en la base de datos
```shell
docker compose up -d
```

### Autoload
- Tambien es necesario correr el composer para generar el autoload
```shell
docker compose exec app composer install
```
-----
## Como probar
- Una vez iniciado el docker siguiendo los pasos anteriores
- Ir a http://localhost para utilizar el frontend que llama al api
- Por defecto el api esta configurada en el puerto :8080 y agregado en el env.js del frontend

### Pruebas curl
```shell
# List
curl http://localhost:8080/productos

# New
curl -X POST http://localhost:8080/productos \
--data '{
    "nombre": "Mi nuevo producto",
    "descripcion": "Un producto nuevo",
    "precio": 250.5
}'

# Product
curl http://localhost:8080/productos/1

# Update
curl -X PUT http://localhost:8080/productos/1 \
--data '{
    "nombre": "Mi nuevo producto actualizado",
    "descripcion": "Un producto nuevo nueva descripcion",
    "precio": 350.5
}'

# Delete
curl -X DELETE http://localhost:8080/productos/1
```

### Formatos de respuesta basados en OpenAPI
Ok
```json
{
    "data": {
        "id": 5,
        "nombre": "Mi nuevo producto",
        "descripcion": "Un producto nuevo",
        "precio": "250.50",
        "created_at": "2026-09-27 17:56:03",
        "updated_at": "2026-09-27 17:56:03"
    }
}
```
Error
```json
{
    "error": {
        "code": 500,
        "message": "SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column 'nombre' at row 1"
    }
}
```


-----
## Notas
- Para manejar un standard todas las variables y codigo estan en ingles (aunque la base la dejo en español pero deberia estar en ingles)
- Dockerizado
- Utilizando el patron singleton en la conexion a la DB
- Creando routings que soporten multiples parametros en la base
- Agregando Inyeccion de dependencia en el framework MVC usando el Service Container
- Implemente un factory para monedas en services y seguir el requerimiento de implementar el calculo de precio en la capa logica
- Seria ideal para resultados optimizados procesar el valor del precio en el query directamente
- Middleware de error handler junto con paginas de error basicas implementadas
- Los productos deberian tener un SKU para poder hacer insert or update y evitar repetirlos
- Agregue metodo automatico para obtener nombre de tabla basado en el modelo pero no se usa porque la tabla esta en espanol asi que se puede definir como variable
- Separacion de errores para ajax como json o vista para requests http
- Utilice binding en las consultas para proteger las consultas contra inyecciones
- Deberia agregar repositorios para manejar los datos aisladamente del modelo pero no queria sobrearquitecturar mas
- Permitiendo cors desde todos para probar el frontend
- El frontend esta minimamente optimizado para mobile
- Falta paginacion desde el lado del frontend pero el backend esta minimamente preparado para esto
- La actualizacion dinamica de los precios en dolares viene calculada desde el backend siendo el backend el unico responsable de esto
- Para valores en dolares menores a 0.001 muestra 4 decimales para que en el frontend se vea un valor logico
- Deje el codigo preparado para hacer transactions en la base pero ya que son transacciones de una sola operacion no me parece necesario aplicarlo
- Al no tener auth no estoy enviando ningun tipo de authorization bearer de front a backend aunque por seguridad implementaria uno, no considero que sea necesario para el challenge 

