<?php

namespace Threading\Task;

/**
 * Clase abstracta de una tarea base la cual es heradada por todas las tareas
 */
abstract class BaseTask
{
    /**
     * Initialize (Ejecutada de primera por el administrador de hilos)
     * 
     * @return mixed
     */
    public function initialize() 
    {
        return true;
    }

    /**
     * Ejecutada por el administrador de hilos si el proceso se completó con exito (Cuando el metodo process() haya retornado true)
     * 
     * @return mixed
     */
    public function onSuccess()
    {
        return true;
    }

    /**
     * Ejecutada por el administrador de hilos si el proceso se completó con fallos (Cuando el metodo process() haya retornado false)
     * 
     * @return mixed
     */
    public function onFailure() 
    {
        return false;
    }

    /**
     * Método principal que contiene la lógica a ser ejecutada por la tarea
     * 
     * @param $params array Array asociativo de parametros
     *
     * @return boolean True para Éxito, false de lo contrario
     */
    abstract public function process(array $params = array());
}
