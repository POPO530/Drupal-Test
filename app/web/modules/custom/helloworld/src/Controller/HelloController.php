<?php

namespace Drupal\helloworld\Controller;

class HelloController extends HelloWorldBaseController {

    public function content() {
        return $this->renderTemplate('my-template', $this->t('Test Value'));
    }

    public function showSubmissions() {
        $header = [
            'data1' => $this->t('ID'),
            'data2' => $this->t('Name'),
            'data3' => $this->t('Email'),
            'data4' => $this->t('Message'),
        ];

        $rows = [];
        $query = $this->database->select('helloworld_contact', 'hc')
            ->fields('hc', ['id', 'name', 'email', 'message'])
            ->orderBy('id', 'DESC');

        foreach ($query->execute() as $row) {
            $rows[] = [
                'data' => [(string) $row->id, $row->name, $row->email, $row->message],
            ];
        }

        $build = [];
        $build['custom_tab'] = $this->renderTemplate('tab-template', $this->t('bugfix'));
        $build['table'] = [
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => $this->t('No submissions available.'),
        ];

        return $build;
    }
}