

### José Guidi - Seegunda entrega WEB 2  
# API de personajes y casas de Harry Potter  
  



### Introduccion  
Con esta API podras obtener, agregar, modificar y/o eliminar personajes de nuestra base de datos. </br> Los metodos HTTP abarcados son GET/POST/PUT/DELETE.  
  

<br/>  


## GET
  


### Obtener todos o un personaje.  
URL: /characters --> Obtenemos un array de todos los personajes de nuestra base de datos </br>
URL: /characters/:ID --> Obtenemos unicamente, si es que existe, el personaje con el id dado (:ID) </br>
  
  



### Ordenamiento, busqueda y paginado.  
Los siguientes parametros en la url se pueden combinar entre si.
URL: /characters?sortby=<b>campo</b>&order=orden  <br> Obtenemos un array de todos los personajes de nuestra base de datos segun el <b>campo</b> asignado y en el <b> orden </b> dado 
</br>
</br>
URL: /characters?rol=<b>busqueda</b></br> Obtenemos un array de todos los personajes de nuestra base de datos que tengan el rol <b>busqueda</b>
</br>
</br>
URL: /characters?page=<b>nroPagina</b>&limit=<b>nroLimite</b> --> Obtenemos un array de maximo del <b>nroLimite</b> dado. <b>nroPagina</b> indica en que pagina estamos segun el limite dado y la cantidad de personajes en la base de adtos</br>
<br>
<b> Se pueden combinar todos los parametros si es que queremos por ejemplo: </b> <br>
URL: /characters?rol=alumno&sortby=nombre&page=1&limit=5<br> Obtenemos un array ordenado ascendentemente por nombre de todos los personajes de nuestra base de datos que tengan el rol alumno. De todos esos, solo se muestran los 5 segundos. Ya que la primer pagina es 0</br>
</br>

  
  



### Aclaraciones de ordenamiento.  
<b> Los posibles atributos para ordenar los datos obtenidos son: </b>
<ul>
<li>id</li>
<li>nombre</li>
<li>id_casa</li>
<li>rol</li>
<li>nucleo_varita</li>
</ul>
<br>
<b> En caso de querer ordenar descendentemente el queryparam order tiene que ser <u>desc </u>, en caso contrario o de que el valor sea <u>asc</u> se ordenará ascendentemente</b>  
  

<br/>  


## DELETE  


### Remover personaje.  
URL: /characters/:ID  <br> 
Si exite, se eliminará de nuestra base de datos el personaje con id dado. Ademas, en ese caso, nos lo devuelve por si se quiere hacer otra cosa con el personaje ya eliminado.  
  

<br/>  


## POST  


### Agregar personaje.  
URL: /characters <br>
Es necesario enviar con este formato de raw en el body de la peticion HTTP: <br>
{
    "id_casa":12,
    "nombre":"Hermione Granger",
    "rol":"Alumna",
    "nucleo_varita":"fibra de corazon de dragon"
} <br>
<ul>
<li>El campo de id_casa tiene que coincidir obligatoriamente con alguna id de las casas de nuestra base de datos. Los id_casa que se encuentran disponibles son: 11-12-14-17. </li>
<li>En caso de querer ingresar un personaje con otro id no se puede.</li>
<li>Hay que competar si o si todos los campos </li>
</ul>
  
  

<br/>  


## PUT  


### Editar personaje.  
URL: /characters/:ID <br>
En caso de existir en nuestra base de datos el personaje con id dado, se actualizarán los datos de este por la informacion enviada mediante el body de la peticion hecha en formato raw como el siguiente:<br>
Ejemplo url: /characters/28 <br>
{
    "id_casa":12,
    "nombre":"Pedro gomez",
    "rol":"mago",
    "nucleo_varita":"bigote de gato negro"
} <br>
El personaje con id 28 tendra estos nuevos valores en sus campos.<br>
<b> Mismos requerimientos obligatorios que en metodo POST </b>  



