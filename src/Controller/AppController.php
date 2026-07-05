<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');

        /*
         * Enable later only if needed.
         */
        // $this->loadComponent('FormProtection');
    }
}