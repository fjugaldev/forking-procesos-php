<?php

namespace Threading;

use Threading\Task\BaseTask as AbstractTask;

/**
 * Multi-thread / task manager
 */
class ThreadManager
{
    /**
     * Array asociativo de pid con hilos activos
     * @var array
     */
    protected $_activeThreads = array();

    /**
     * Número máximo de hilos hijos que pueden ser creados por un hilo padre
     * @var int
     */
    protected $maxThreads = 5;

    /**
     * Class constructor
     *
     * @param int $maxThreads Número máximo de hilos hijos que pueden ser creados por un hilo padre
     */
    public function __construct($maxThreads = 5)
    {
        $this->maxThreads = $maxThreads;
    }

    /**
     * Inicia el aministrador de hilos
     *
     * @param AbstractTask $task Tarea a iniciar
     *
     * @return void
     */
    public function start(AbstractTask $task)
    {
        $pid = pcntl_fork();
        if ($pid == -1) 
        {
            throw new \Exception('[Pid:' . getmypid() . '] No se pudo clonar (fork) el proceso');
        } 
        // Hilo Padre
        elseif ($pid) 
        {
            $this->_activeThreads[$pid] = true;

            // Logrado el numero máximo de hilos permitidos
            if($this->maxThreads == count($this->_activeThreads)) 
            {
                // Proceso Padre : Chequea que todos los hijos hayan terminado (Para evadir los procesos hilos zombies)
                while(!empty($this->_activeThreads)) 
                {
                    $endedPid = pcntl_wait($status);
                    if(-1 == $endedPid) 
                    {
                        $this->_activeThreads = array();
                    }
                    unset($this->_activeThreads[$endedPid]);
                }
            }
        } 
        // Hilo Hijo
        else 
        {
            $task->initialize();

            // On success
            if ($task->process())
            {
                $task->onSuccess();
            } 
            else 
            {
                $task->onFailure();
            }
            
            // Mata el proceso hijo una vez que haya terminado de ejecutarse           
            posix_kill(getmypid(), 9);
        }
        pcntl_wait($status, WNOHANG);
    }
}
