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
<title>Detalles - Equipos Quiborax S.A.</title>
<?php
// Revisar si ha recibido el nombre del archivo y que no esté vacío
if (isset($_GET['fileName']) && !empty($_GET['fileName'])) {
  // Ubicación de datos
  $path = ABSPATH . 'data';

  // Nombre del archivo
  $fileName = trim($_GET['fileName']);

  echo '<h1>Detalles de Equipo: ' . $fileName . '</h1>';
  echo '<nav><a href="./">Lista de equipos</a> > <b>Detalles</b></nav>';

  // Reemplazar parte del nombre que tiene más de 2 puntos en uno solo
  $fileName = preg_replace('/[\.]{2,}/', '.', $fileName);

  // Agregarle la extensión 'xml' al nombre de archivo
  $fileName .= '.xml';

  // Revisar si existe el archivo
  if (file_exists($path . '/' . $fileName)) {
    // Sí existe el archivo

    // Leer los datos de XML
    $fileData = simplexml_load_file($path . '/' . $fileName);

    echo '<table>';

    // Si existe datos de BIOS
    if (isset($fileData->BIOS)) {
      // Imprimir datos de BIOS
      echo '<tr><th>BIOS</th><td>' . $fileData->BIOS->Name . ' / ' . $fileData->BIOS->Version . '</td></tr>';
    }

    // Si existe datos de los procesadores
    if (isset($fileData->Processors)) {
      // Imprimir datos de los procesadores
      echo '<tr><th>Procesadores</th><td>';

      // Revisar si hay más de un procesaodr
      if (is_array($fileData->Processors->Processor)) {
        echo '<ol>';

        // Revisar todas las tarjetas gráficas
        for ($i = 0, $totalProcessors = sizeof($fileData->Processors->Processor); $i < $totalProcessors; ++$i) {
          // Imprimir nombre de cada tarjeta
          echo '<li>' . $fileData->Processors->Processor[$i]->Name . '</li>';
        }

        echo '</ol>';
      } else {
        // Imprimir nombre de la tarjeta
        echo $fileData->Processors->Processor->Name;
      }

      echo '</td></tr>';
    }

    // Si existe datos de la placa base
    if (isset($fileData->MotherBoard)) {
      // Imprimir datos de la placa base
      echo '<tr><th>Placa base</th><td>' . $fileData->MotherBoard->Manufacturer . ' / ' . $fileData->MotherBoard->Product . '</td></tr>';
    }

    // Si existe datos de la memoria RAM
    if (isset($fileData->PhysicalMemory)) {
      // Iniciar unidades
      $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

      // Iniciar valor de memoria RAM
      $totalMemory = 0;

      // Iniciar unidad actual
      $currentUnit = 0;

      // Revisar si hay más de una memoria RAM
      if (is_array($fileData->PhysicalMemory->Memory)) {
        // Sumar capacidades de memoria RAM
        for ($i = 0, $totalSlot = sizeof($fileData->PhysicalMemory->Memory); $i < $totalSlot; ++$i) {
          $totalMemory += $fileData->PhysicalMemory->Memory[$i]->Capacity;
        }
      } else {
        // Obtener la capacidad del memoria RAM
        $totalMemory = $fileData->PhysicalMemory->Memory->Capacity;
      }

      // Dividirlo por 1024 hasta que quede menos de 1024
      while (1024 < $totalMemory) {
        $totalMemory /= 1024;

        // Seleccionar la siguiente unidad
        ++$currentUnit;
      }

      // Imprimir datos de la placa base
      echo '<tr><th>Memoria RAM total</th><td>' . ((int) $totalMemory) . $unit[$currentUnit] . '</td></tr>';
    }


    // Si existe datos del monitor
    if (isset($fileData->Monitor)) {
      // Imprimir datos del monitor
      echo '<tr><th>Monitor</th><td>' . $fileData->Monitor->Name . '</td></tr>';
    }

    // Si existe datos de las tarjetas gráficos
    if (isset($fileData->VideoCards)) {
      // Imprimir datos de las tarjetas gráficos
      echo '<tr><th>Tarjetas gráficos</th><td>';

      // Revisar si hay más de una tarjeta gráficos
      if (is_array($fileData->VideoCards->Card)) {
        echo '<ol>';

        // Revisar todas las tarjetas gráficas
        for ($i = 0, $totalGraphicsCards = sizeof($fileData->VideoCards->Card); $i < $totalGraphicsCards; ++$i) {
          // Imprimir nombre de cada tarjeta
          echo '<li>' . $fileData->VideoCards->Card[$i]->Name . '</li>';
        }

        echo '</ol>';
      } else {
        // Imprimir nombre de la tarjeta
        echo $fileData->VideoCards->Card->Name;
      }

      echo '</td></tr>';
    }

    if (isset($fileData->OperatingSystem)) {
      echo '<tr><th>Sistema operativo</th><td>' . $fileData->OperatingSystem->ProductName . '</td></tr>';
      echo '<tr><th>Nombre del equipo</th><td>' . $fileData->OperatingSystem->ComputerName . '</td></tr>';
    }

    echo '</table>';
  }
} else {
  echo '<div class="errorMessage">No hubo ningún solicitud detalles</div>';
}