<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 8/7/18
 * Time: 3:58 PM
 */

namespace Drupal\unflag_courses\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\flag\Controller\ActionLinkController;
use Drupal\flag\FlagInterface;
use Drupal\flag\FlagServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Entity\EntityInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class CommunityNotifierController.
 */
class UnflagCoursesController extends ControllerBase {

  protected $entityTypeManager;
  
  /**
   * {@inheritdoc}
   */
  public function remove($user_id, $entity_id) {


    $flagId = 'save_list';


    //GET FLAG BY ID save_course
    $flag = \Drupal::service('flag')->getFlagById($flagId);


    //GET USER BY ID $user_id
    $user = \Drupal\user\Entity\User::load($user_id);


    //GET ENTITY BY ID $entity_id
    $entity = Node::load($entity_id);

    //UNFLAG THE COURSE using service
    \Drupal::service('flag')->unflag($flag, $entity, $user);



    //REDIRECT TO COURSES @user_id
    $current_user_id = \Drupal::currentUser()->id();


    $routeName = 'entity.user.canonical';
    $routeParameters = ['user' => $current_user_id];
    $options = [
      'fragment' => 'trainingusers',
    ];

    $url = \Drupal::url($routeName, $routeParameters, $options);

    return new RedirectResponse($url);
  }
}