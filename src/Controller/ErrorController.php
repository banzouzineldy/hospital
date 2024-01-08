<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class ErrorListener
{
    private $twig;
    private $kernel;

    public function __construct(Environment $twig, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->kernel = $kernel;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = new Response();

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $tatuMessage =$exception->getMessage();
            if ($statusCode == 404) {
                $response = new Response($this->twig->render('error/404.html.twig '), 404);
            } elseif ($statusCode == 505) {
                $response = new Response($this->twig->render('error/505.html.twig'), 505);
            }elseif ($statusCode == 403) {
                $response = new Response($this->twig->render('error/403.html.twig'), 403);
            }
        }

        $event->setResponse($response);
    }
}

