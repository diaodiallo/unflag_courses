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

  //ActionLinkController

  /**
   * {@inheritdoc}
   */
  public function remove($user_id, $entity_id) {

    \Drupal::logger('Unflag_coureses')->info('In the unflag controller');
    \Drupal::logger('Unflag_coureses')->info('User id : ' .$user_id. ' Entity id : '. $entity_id);

    $flagId = 'save_list';


    //GET FLAG BY ID save_course
    $flag = \Drupal::service('flag')->getFlagById($flagId);


    //GET USER BY ID $user_id
    $user = \Drupal\user\Entity\User::load($user_id);

    \Drupal::logger('Unflag_coureses')->info('The course subscriber : ' .$user->getAccountName());


    //GET ENTITY BY ID $entity_id
    //$entity = \Drupal\Core\Entity\Entity::load($entity_id);
    //$entity = EntityInterface::load($entity_id);
    $entity = Node::load($entity_id);

    \Drupal::logger('Unflag_coureses')->info('The entity received : ' .$entity->id());

    //UNFLAG THE COURSE using service
    \Drupal::service('flag')->unflag($flag, $entity, $user);



    //REDIRECT TO COURSES @user_id
    $current_user_id = \Drupal::currentUser()->id();
    ///node/add/training_list?destination=/user/{{ raw_arguments.uid }}%23traininglists

    //$refresh = 'https://fpntcd8.lndo.site';

    //$destination = Url::fromUserInput(\Drupal::destination()->get());
    //$destination = Url::fromUserInput($refresh);
    //$params = array('id' => $current_user_id);

   // $url = new Url('https://fpntcd8.lndo.site',[$current_user_id])​;
    //return $this->redirect($url);

/*
    if ($destination->isRouted()) {
      // Valid internal path.
      \Drupal::logger('Unflag_coureses')->info('Route name : ' .$destination->getRouteName());
      return $this->redirect($destination->getRouteName());
    }else{
      \Drupal::logger('Unflag_coureses')->info('Not a valid route ');
    }*/

    $routeName = 'entity.user.canonical';
    $routeParameters = ['user' => $current_user_id]; // id of your user
    $options = [
      'fragment' => 'trainingusers',
    ];
    $url = \Drupal::url($routeName, $routeParameters, $options);
    return new RedirectResponse($url);


  }


}