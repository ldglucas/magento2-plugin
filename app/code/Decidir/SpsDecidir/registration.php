<?php
/**
 * Carga el modulo de SpsDecidir
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Decidir_SpsDecidir',
    __DIR__
);

/**
 * Carga la libreria del sdk de decidir, buscando por el nombre 'sdk/spsdecidir' que se encuentra en el composer.json
 * dentro del modulo.
 *
 * @note ¡¡Solucion Provisoria!! El sdk de decidir se encuentra dentro de Test/vendor debido a que cuando se encuentra
 *       por fuera de este directorio, magento lo intenta compilar, devolviendo errores y no se puede continuar con el
 *       proceso. Tampoco se puede agregar desde el composer.json general de magento, ya que el sdk tiene errores de
 *       sintaxis en algunos de sus archivos y es necesario repararlos a mano.
 *
 *       Hay que ver algun tipo de solucion agregando un pattern que busque la carpeta vendor o library dentro de los
 *       modulos. Sugerencias http://magento.stackexchange.com/questions/119967/magento-2-setupdicompile-exclude-folders
 *
 *
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::LIBRARY,
    'decidir/php-sdk',
    __DIR__.'/Test/vendor'
);
