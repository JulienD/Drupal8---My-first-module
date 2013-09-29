<?php

/**
 * @file
 * Contains \Drupal\animal\Controller\AnimalController.
 */

namespace Drupal\animal\Controller;

use Drupal\Component\Utility\Xss;
//use Drupal\animal\AnimalInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for user routes.
 */
class AnimalController extends ContainerAware {

  /**
   * Returns the user page.
   *
   * Displays user profile if user is logged in, or login form for anonymous
   * users.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
   *   Returns either a redirect to the user page or the render
   *   array of the login form.
   */
  public function animalPage(Request $request) {
    global $animal;
    if ($animal->id()) {
      $response = new RedirectResponse(url('zoo/animal/' . $animal->id(), array('absolute' => TRUE)));
    }
    else {
      $response = drupal_get_form(UserLoginForm::create($this->container), $request);
    }
    return $response;
  }

  /**
   * Route title callback.
   *
   * @param \Drupal\animal\UserInterface $animal
   *   The user account.
   *
   * @return string
   *   The user account name.
   */
  public function animalTitle(UserInterface $animal = NULL) {
    return $animal ? Xss::filter($animal->getUsername()) : '';
  }
}
