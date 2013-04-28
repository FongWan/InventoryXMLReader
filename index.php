<?php
// Revisar si existe el archivo 'config.php'
if (file_exists('config.php')) {
  // Existe, incluir el archivo 'config.php'
  include('config.php');
} else {
  // No existe el archivo 'config.php', se va a autogenerar

  // Obtener la ruta absoluta del directorio actual como un constante
  define('ABSPATH', dirname(__FILE__) . '/');

  // Obtener la ubicación web actual
  $requestURI = $_SERVER['REQUEST_URI'];

  // Revisar si la ubicación web actual es la raiz
  if ('/' != $requestURI) {
    // No es la raiz, buscaremos la ubicación URI

    // Separamos la ruta absoluta en partes
    $explodedAbsPath = explode('/', trim(ABSPATH, '/'));

    // Obtenemos la última parte de la ruta absoluta
    $lastPath = $explodedAbsPath[sizeof($explodedAbsPath) - 1];

    // Buscamos si está dentro de la ubicación web actual
    $stringPos = strpos($requestURI, $lastPath);

    // Revisar si encontramos
    if (FALSE != $stringPos) {
      // Encontramos, establecer la ubicación raiz
      $requestURI = substr($requestURI, 0, $stringPos + strlen($lastPath) + 1);
    }
  }

  // Obtener la ubicación web actual como un constante
  define('WEBPATH', $requestURI);

  // Ubicación de datos
  define('DATAPATH', ABSPATH . 'data/');

  // Abrir el archivo de configuración en un puntero
  @$filePointer = fopen('config.php', 'w');
  // Revisar si pudimos abrirlo
  if ($filePointer) {
    // Fijar el contenido que vamos a escribir
    $configContent = '<?php
// Obtener la ruta absoluta del directorio actual como un constante
define(\'ABSPATH\', \'' . ABSPATH . '\');

// Obtener la ubicación web actual como un constante
define(\'WEBPATH\', \'' . WEBPATH . '\');

// Ubicación de datos
define(\'DATAPATH\', \'' . DATAPATH . '\');';

    // Escribir al 'config.php'
    fwrite($filePointer, $configContent);

    // Cerrar puntero
    fclose($filePointer);
  }
}

// Mejora de rendimiento
ob_start('ob_gzhandler');

// Configurar el tipo de contenido que va a ser enviado
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: max-age=60, must-revalidate', true);
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?php echo WEBPATH; ?>resources/styles/global.css"> 
<title>Equipos Quiborax S.A.</title>
<h1>Equipos Quiborax S.A.</h1>
<?php
// Obtener la dirección actual de consulta
$requestURI = parse_url($_SERVER['REQUEST_URI']);

// Revisar si es identico al directorio raiz
if ($requestURI['path'] == WEBPATH) {
  // Identico, mostrar la lista
  include(ABSPATH . 'modules/list.php');
} else {
  // Diferente

  // Obtener el nombre de archivo XML
  $fileName = urldecode(substr($requestURI['path'], strlen(WEBPATH)));

  // Mostrar el detalle
  include(ABSPATH . 'modules/detail.php');
}