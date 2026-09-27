# Challenge PHP DeCampoACampo

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
- 

