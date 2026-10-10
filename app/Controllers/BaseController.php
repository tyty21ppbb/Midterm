<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Helpers automatically loaded for every controller.
     *
     * Do not declare an array type here because the parent Controller
     * class defines this property without a type.
     *
     * @var list<string>
     */
    protected $helpers = [
        'form',
        'url',
    ];

    /**
     * Initialize the base controller.
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController(
            $request,
            $response,
            $logger
        );
    }
}
