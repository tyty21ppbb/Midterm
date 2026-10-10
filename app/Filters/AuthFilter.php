<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Check authentication before opening a protected page.
     */
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        if (! session()->get('is_logged_in')) {
            return redirect()
                ->to(site_url('login'))
                ->with(
                    'error',
                    'Please log in to access that page.'
                );
        }

        return null;
    }

    /**
     * No processing is required after the response.
     */
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        return null;
    }
}
