<?php

namespace Sowbiba\CommandsStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SowbibaCommandsStatsBundle:Default:index.html.twig');
    }
}
