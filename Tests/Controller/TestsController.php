<?php

/*
 * This file is part of the Behat MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Behat\MinkBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * MinkBundle test actions controller.
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class TestsController extends Controller
{
    public function pageAction($page)
    {
        return $this->render('MinkBundle::page.html.php', array(
            'page' => preg_replace('/page(\d+)/', 'Page N\\1', $page),
            'environment' => $this->get('kernel')->getEnvironment(),
            'genUrl' => $this->get('router')->generate('_behat_tests_page', array('page' => 'page0'), true),
        ));
    }

    public function redirectAction()
    {
        return new RedirectResponse($this->generateUrl('_behat_tests_page', array('page' => 'page1')));
    }

    public function formAction()
    {
        return $this->render('MinkBundle::form.html.php');
    }

    public function submitAction()
    {
        $data = $this->get('request')->request->all();

        return $this->render('MinkBundle::submit.html.php', array(
            'method'     => $this->get('request')->getMethod(),
            'name'       => $data['name'],
            'age'        => $data['age'],
            'speciality' => $data['speciality'],
        ));
    }

    public function headersAction()
    {
        $headers = $this->getRequest()->headers->all();

        return $this->render('MinkBundle::headers.html.php', array(
            'headers' => var_export($headers, true),
        ));
    }
}
