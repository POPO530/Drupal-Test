<?php

namespace Drupal\test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class SurveyResultsController extends ControllerBase {
  public function content() {
    $header = [
      ['data' => $this->t('Name'), 'field' => 'name'],
      ['data' => $this->t('Answer 1'), 'field' => 'answer1'],
      ['data' => $this->t('Answer 2'), 'field' => 'answer2'],
      ['data' => $this->t('Answer 3'), 'field' => 'answer3'],
      ['data' => $this->t('Answer 4'), 'field' => 'answer4'],
      ['data' => $this->t('Answer 5'), 'field' => 'answer5'],
    ];

    $query = Database::getConnection()->select('survey_results', 'sr')
      ->fields('sr', ['name', 'answer1', 'answer2', 'answer3', 'answer4', 'answer5'])
      ->execute();

    $rows = [];
    foreach ($query as $row) {
      $rows[] = [
        'data' => (array) $row,
      ];
    }

    $build['survey_results_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No survey results found.'),
    ];

    return $build;
  }
}