<?php

namespace App\Listener;

use App\Entity\Log;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ApiListener
{
    /** @var LoggerInterface $logger */
    private $logger;

    /** @var EntityManager $em */
    private $em;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @param RequestEvent $event
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return false;
        }

        $request = $event->getRequest();

        try {
            // logger
            $this->logger->info('REQUEST DATA = '.$request->getContent());

            /** @var Log $log */
            $log = new Log();
            $log->setMessage(\json_encode($request->request->all()));
            $log->setUri($request->getRequestUri());
            $log->setType('request');
            $this->em->persist($log);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return true;
    }

    /**
     * @param ResponseEvent $event
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        try {
            $response = $event->getResponse();
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            // logger
            $this->logger->info('RESPONSE DATA = '.$response->getContent());

            /** @var Log $log */
            $log = new Log();
            $log->setMessage($response->getContent());
            $log->setType('response');
            $log->setUri($event->getRequest()->getRequestUri());
            $this->em->persist($log);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return true;
    }
}
