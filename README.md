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

