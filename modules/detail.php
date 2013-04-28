<?php
// Revisar si ha recibido el nombre del archivo y que no esté vacío
if (!empty($fileName)) {
  echo '<nav id="breadcrumb"><a href="./">Lista de equipos</a> > <b>Detalles de Equipo: ' . $fileName . '</b></nav>';

  // Reemplazar parte del nombre que tiene más de 2 puntos en uno solo
  $fileName = preg_replace('/[\.]{2,}/', '.', $fileName);

  // Agregarle la extensión 'xml' al nombre de archivo
  $fileName .= '.xml';

  // Revisar si existe el archivo
  if (file_exists(DATAPATH . $fileName)) {
    // Sí existe el archivo

    // Leer los datos de XML
    $fileData = simplexml_load_file(DATAPATH . '/' . $fileName);

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

      // Obtener el número total de procesadores
      $totalProcessors = sizeof($fileData->Processors->Processor);

      // Revisar si hay más de un procesador
      if (1 < $totalProcessors) {
        echo '<ol>';

        // Revisar todas los procesadores
        for ($i = 0; $i < $totalProcessors; ++$i) {
          // Imprimir nombre de cada procesador
          echo '<li>' . $fileData->Processors->Processor[$i]->Name . '</li>';
        }

        echo '</ol>';
      } else {
        // Imprimir nombre del procesador
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

      // Obtener el número total de slot
      $totalSlot = sizeof($fileData->PhysicalMemory->Memory);

      // Revisar si hay más de una memoria RAM
      if (1 < $totalSlot) {
        // Sumar capacidades de memoria RAM
        for ($i = 0; $i < $totalSlot; ++$i) {
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

      // Imprimir datos de la memoria RAM
      echo '<tr><th>Memoria RAM total</th><td>' . ((int) $totalMemory) . $unit[$currentUnit] . ' (' . $totalSlot . ' slot)</td></tr>';
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

      // Obtener el número total de tarjetas gráficos
      $totalGraphicsCards = sizeof($fileData->VideoCards->Card);

      // Revisar si hay más de una tarjeta gráficos
      if (1 < $totalGraphicsCards) {
        echo '<ol>';

        // Revisar todas las tarjetas gráficas
        for ($i = 0; $i < $totalGraphicsCards; ++$i) {
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

    // Si existe datos de los adaptadores de red
    if (isset($fileData->NetworkAdapters)) {
      // Imprimir datos de los adaptadores de red
      echo '<tr><th>Adaptadores de red</th><td>';

      // Obtener el número total de los adaptadores
      $totalNetworkAdapters = sizeof($fileData->NetworkAdapters->Adapter);

      // Revisar si hay más de un adaptador de red
      if (1 < $totalNetworkAdapters) {
        echo '<ol>';

        // Revisar todos los adaptadores de red
        for ($i = 0; $i < $totalNetworkAdapters; ++$i) {
          // Revisar si son tarjetas reales buscando la MAC
          if (!empty($fileData->NetworkAdapters->Adapter[$i]->MACAddress)) {
            if (!empty($fileData->NetworkAdapters->Adapter[$i]->IPAddress)) {
              $ipAddress = $fileData->NetworkAdapters->Adapter[$i]->IPAddress;
            } else {
              $ipAddress = '0.0.0.0';
            }

            // Imprimir nombre de cada tarjeta
            echo '<li>' . $fileData->NetworkAdapters->Adapter[$i]->Description . ' (IP: ' . $ipAddress . ')</li>';
          }
        }

        echo '</ol>';
      } else {
        // Imprimir nombre de la tarjeta
        echo $fileData->NetworkAdapters->Adapter->Description;
      }

      echo '</td></tr>';
    }

    // Si existe datos de las tarjetas de sonidos
    if (isset($fileData->SoundDevice)) {
      // Imprimir datos de las tarjetas de sonidos
      echo '<tr><th>Tarjetas de sonidos</th><td>';

      // Obtener el número total de tarjetas de sonidos
      $totalSoundDevice = sizeof($fileData->SoundDevice->Device);

      // Revisar si hay más de una tarjeta de sonidos
      if (1 < $totalSoundDevice) {
        echo '<ol>';

        // Revisar todas las tarjetas de sonidos
        for ($i = 0; $i < $totalSoundDevice; ++$i) {
          // Imprimir nombre de cada tarjeta
          echo '<li>' . $fileData->SoundDevice->Device[$i]->Name . '</li>';
        }

        echo '</ol>';
      } else {
        // Imprimir nombre de la tarjeta
        echo $fileData->SoundDevice->Device->Name;
      }

      echo '</td></tr>';
    }

    // Si existe datos de los lectores
    if (isset($fileData->CDROMDrives)) {
      // Imprimir datos de los lectores
      echo '<tr><th>Lector de CD/DVD</th><td>';

      // Obtener el número total de lectores
      $totalCDROMDrives = sizeof($fileData->CDROMDrives->Drive);

      // Revisar si hay más de un lector
      if (1 < $totalCDROMDrives) {
        echo '<ol>';

        // Revisar todos los lectores
        for ($i = 0; $i < $totalCDROMDrives; ++$i) {
          // Imprimir nombre de cada lector
          echo '<li>' . $fileData->CDROMDrives->Drive[$i]->Name . '</li>';
        }

        echo '</ol>';
      } else {
        // Imprimir nombre del lector
        echo $fileData->CDROMDrives->Drive->Name;
      }

      echo '</td></tr>';
    }

    // Si existe datos de las impresoras
    if (isset($fileData->Printers)) {
      // Imprimir datos de las impresoras
      echo '<tr><th>Impresoras</th><td>';

      // Obtener el número total de impresoras
      $totalPrinters = sizeof($fileData->Printers->Printer);

      // Revisar si hay más de una impresora
      if (1 < $totalPrinters) {
        echo '<ol>';

        // Revisar todas las impresoras
        for ($i = 0; $i < $totalPrinters; ++$i) {
          // Imprimir nombre de cada impresora
          echo '<li>' . $fileData->Printers->Printer[$i]->Name . '</li>';
        }

        echo '</ol>';
      } else {
        // Imprimir nombre de la impresora
        echo $fileData->Printers->Printer->Name;
      }

      echo '</td></tr>';
    }

    // Si existe datos del sistema operativo
    if (isset($fileData->OperatingSystem)) {
      // Imprimir el tipo de sistema operativo
      echo '<tr><th>Sistema operativo</th><td>' . $fileData->OperatingSystem->ProductName . '</td></tr>';
      echo '<tr><th>Nombre del equipo</th><td>' . $fileData->OperatingSystem->ComputerName . '</td></tr>';
    }

    // Si existe datos de los programas instalados
    if (isset($fileData->Software)) {
      // Imprimir datos de los porgramas instalados
      echo '<tr><th>Softwares</th><td>';

      // Obtener el número total de programas instalados
      $totalSoftwares = sizeof($fileData->Software->Product);

      // Revisar si hay más de un programas instalado
      if (1 < $totalSoftwares) {
        echo '<ol>';

        // Revisar todos los programas instalados
        for ($i = 0; $i < $totalSoftwares; ++$i) {
          // Imprimir nombre de cada programa
          echo '<li>' . $fileData->Software->Product[$i]->Name . '</li>';
        }

        echo '</ol>';
      } else {
        // Imprimir nombre del programa
        echo $fileData->Software->Product->Name;
      }

      echo '</td></tr>';
    }

    echo '</table>';
  } else {
    // No se pudo controlar la carpeta, imprimir mensaje de error
    echo '<div class="errorMessage">No se pudo leer el detalle de este equipo</div>';
  }
} else {
  echo '<div class="errorMessage">No hubo ningún solicitud detalles</div>';
}