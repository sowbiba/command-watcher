<?php

namespace Sowbiba\CommandWatcherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandWatcherController extends Controller
{
    public function indexAction()
    {
        return $this->render('SowbibaCommandWatcherBundle:CommandWatcher:index.html.twig');
    }
}
