# videocatalog

## Puesta en marcha

### Requisitos:
 - php 8.1 o superior
 - composer 
 - symfony, servidor http o docker

### Clonar el repositorio:
```bash
git clone
```
En la raíz del proyecto ejecutar:
```sh
composer install
```

### Ejecución

Si se tiene symfony instalado, en la raíz del proyecto:
```sh
symfony server:start
```
Alternativamente, la carpeta docker contiene un entorno suficiente para poner en marcha el proyecto:
```sh
cd ./docker
docker compose up
```
El entrypoint es algo burdo, pero levanta el servidor de pruebas de symfony.
Por defecto el servidor será accesible a través de http://localhost:8000 y en adelante asumimo que esta es la URL.

## Interfaz HTTP

Consta de un endpoint: http:/localhost:8000/films
Recibe un payload JSON con los filtros deseados por POST y devuelve el listado de películas que cumplan los criterios.
Si no se especifica ningún payload devuelve el listado completo.

### Formato JSON

```json
{
    "year" : #int#,
    "title": [#criterio#, #string#],
    "rating": [#criterio#, #int#]
}
```
Los campos *year*, *title* y *rating* son todos opcionales. Cuándo se especifican deben ir acompañados de argumentos 
válidos.
De acuerdo al enunciado, no todos los criterios de filtrado son válidos en todos los casos:

| Criterio      | Clave | Campos |
|---------------|-------|--------|
| Mayor o igual | ge    | rating |
| Menor o igual | le    | rating |
| Empieza por   | sw    | title  |
| Contiene      | ct    | title  |
| Termina en    | ew    | title  |

Así, para filtrar las entradas con un rating mayor o igual que 5 se envía este payload:
```json
{
  "rating": [ "ge", 5 ]
}
```

## Interfaz de línea de comandos

Se implementa cómo un comando de symfony. Y se puede ejecutar de dos manera:
```sh
bin/console app:films
```
O bien mediante un script:
```sh
cd bin
./films
```
Este script solo llama al anterior pasándole todos los argumentos que reciba.

### Argumentos

Funcionan de forma análog al JSON de la versión HTTP.
Un ejemplo puede ser suficiente:
```sh
./films --year 1994 --rating "ge:5"
```
Muestra todos las entradas de 1994 con una valoración superior o igual a 5.
En este caso, la salida se maqueta en texto plano.

# Comentarios
La lista de películas empleada ha sido generada con un petitión a ChatGPT. Las valoraciones son aleatorias.

Empleo Symfony porque me proporciona cierta estructura inicial y el enrutamiento. Aunque la aplicación solo tenga un endpoint hay que pensar que podría llegar a tener una estructura más compleja.
Symfony además facilita mucho la creación de la app de consola.

Trato de ajustarme al modelo hexagonal, manteniendo desacoplado el *core* de la aplicación del framework (*Symfony*) y 
de la fuente de datos.

La entrada recibida por la aplicación es distinta según el origen (http/cli), así que hago uso de unos *mappers* que validan
y convierten la entrada del usuario en los datos del dominio.

Además, procuro que los *controllers* cli y HTTP se encarguen solo lo que les corresponde: pasar la entrada del usuario a un mapper especídico y llamar a un service para obtener los datos.

Defino una serie de *value objects* para aportar a la vez validación y seguridad de tipos.
Uno de ellos es un FilmInfoList, que aporta validación al añadir, pero devuelve un array crudo (método get). 

El interfaz IFilmRepository describe el método *search* para obtener y filtrar el conjunto de datos.

Uso symfony para establecer un manejador es excepciones que si se produce una excepción muestra un mensaje y un código de 
error en JSON, lo que resulta útil para mostrar los mensajes de error en caso de fallos de validación. A este respecto,

### Mejoras
Los *value objets* *FilmYear*, *FilmRating* y *FilmTitle* deberían transportar solo el valor y no el filtrado. 
Habría que crear tres clases más, porque el filtrado require validación ya que no todos los criterios son aplicables a todos los campos. 
Debería definirse un interfaz para los filtros.

Los *mappers* de la entrada implementan el mismo interfaz, pero hubiera preferido que compartieran más código.

El código de filtrado esta implementado en el propio *repository*, lo que es discutible.

Aunque los test implmementados ofrecen una covertura razonable, habría implementado algunos más de haber dispuesto de más tiempo.







