<?php
// Obtener la ruta absoluta del directorio actual como un constante
define('ABSPATH', dirname(__FILE__) . '/');

// Obtener la ubicación web actual como un constante
define('WEBPATH', $_SERVER['REQUEST_URI']);

// Mejora de rendimiento
ob_start('ob_gzhandler');

// Configurar el tipo de contenido que va a ser enviado
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?php echo WEBPATH; ?>resources/styles/global.css"> 
<title>Equipos Quiborax S.A.</title>
<h1>Lista de Equipos QB</h1>
<?php
// Ubicación de datos
$path = ABSPATH . 'data';

// Controlar el directorio si es que se puede
if ($handle = opendir($path)) {
  // Se pudo controlar el directorio

  // Variable para almacenar la lista de archivos en forma de enlaces
  $files = array();

  // Leer el directorio
  while ($file = readdir($handle)) {
    // Si el archivo no empieza con '.' (no está oculto) y es un archivo XML
    if ('.' != $file{0} && '.xml' == strtolower(substr($file, -4))) {
      // Eliminar la extensión 'xml' del nombre de archivo
      $fileName = substr($file, 0, -4);

      // Almacenar en forma de enlace los archivos en '$files'
      $files[] = '<a href="' . WEBPATH . urlencode($fileName) . '">' . $fileName . '</a>';
    }
  }

  // Cerrar el controlador
  closedir($handle);

  // Contar la cantidad de archivos
  $filesLength = sizeof($files);

  // Revisar si hay archivos en el directorio
  if ($filesLength) {
    // Hay archivos, imprimir la lista

    echo '<ul id="fileList">';
    for ($i = 0; $i < $filesLength; ++$i) {
      echo '<li>' . $files[$i] . '</li>';
    }
    echo '</ul>';
  } else {
    // No hay archivos en la carpeta, imprimir error
    echo '<div class="errorMessage">No hay lista de equipos</div>';
  }
} else {
  // No se pudo controlar la carpeta, imprimir mensaje de error
  echo '<div class="errorMessage">No se pudo leer la lista de equipos</div>';
}