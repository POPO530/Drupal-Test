<?php

namespace Drupal\helloworld\Controller;

use Drupal\Core\Controller\ControllerBase;

abstract class HelloWorldBaseController extends ControllerBase {

    protected $database;

    public function __construct() {
        $this->database = \Drupal::database();
        \Drupal::service('page_cache_kill_switch')->trigger();
    }

    protected function renderTemplate($theme, $variables = []) {
        //dd($variables);
        return [
            '#theme' => $theme,
            '#variables' => $variables,
        ];
    }
}