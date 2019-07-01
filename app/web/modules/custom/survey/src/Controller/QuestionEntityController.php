<?php

namespace Drupal\survey\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\survey\Entity\QuestionEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class QuestionEntityController.
 *
 *  Returns responses for Question entity routes.
 */
class QuestionEntityController extends ControllerBase implements ContainerInjectionInterface {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new QuestionEntityController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Question entity revision.
   *
   * @param int $question_entity_revision
   *   The Question entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($question_entity_revision) {
    $question_entity = $this->entityTypeManager()->getStorage('question_entity')
      ->loadRevision($question_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('question_entity');

    return $view_builder->view($question_entity);
  }

  /**
   * Page title callback for a Question entity revision.
   *
   * @param int $question_entity_revision
   *   The Question entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($question_entity_revision) {
    $question_entity = $this->entityTypeManager()->getStorage('question_entity')
      ->loadRevision($question_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $question_entity->label(),
      '%date' => $this->dateFormatter->format($question_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Question entity.
   *
   * @param \Drupal\survey\Entity\QuestionEntityInterface $question_entity
   *   A Question entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(QuestionEntityInterface $question_entity) {
    $account = $this->currentUser();
    $question_entity_storage = $this->entityTypeManager()->getStorage('question_entity');

    $langcode = $question_entity->language()->getId();
    $langname = $question_entity->language()->getName();
    $languages = $question_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $question_entity->label()]) : $this->t('Revisions for %title', ['%title' => $question_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all question entity revisions") || $account->hasPermission('administer question entity entities')));
    $delete_permission = (($account->hasPermission("delete all question entity revisions") || $account->hasPermission('administer question entity entities')));

    $rows = [];

    $vids = $question_entity_storage->revisionIds($question_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\survey\QuestionEntityInterface $revision */
      $revision = $question_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $question_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.question_entity.revision', [
            'question_entity' => $question_entity->id(),
            'question_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $question_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.question_entity.translation_revert', [
                'question_entity' => $question_entity->id(),
                'question_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.question_entity.revision_revert', [
                'question_entity' => $question_entity->id(),
                'question_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.question_entity.revision_delete', [
                'question_entity' => $question_entity->id(),
                'question_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['question_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
