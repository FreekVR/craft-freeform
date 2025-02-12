<?php

namespace Solspace\Freeform\Bundles\Form\Types\Regular;

use craft\helpers\UrlHelper;
use Solspace\Commons\Helpers\PermissionHelper;
use Solspace\Freeform\Bundles\Transformers\Builder\Form\FormTransformer;
use Solspace\Freeform\Events\Forms\GenerateLinksEvent;
use Solspace\Freeform\Freeform;
use Solspace\Freeform\Library\Bundles\FeatureBundle;
use yii\base\Event;

class AttachFormLinks extends FeatureBundle
{
    public function __construct()
    {
        Event::on(
            FormTransformer::class,
            FormTransformer::EVENT_ATTACH_LINKS,
            function (GenerateLinksEvent $event) {
                $form = $event->getForm();
                $data = $event->getFormData();

                $canManageForm = PermissionHelper::checkPermission(Freeform::PERMISSION_FORMS_MANAGE);
                if (!$canManageForm) {
                    $canManageForm = PermissionHelper::checkPermission(
                        PermissionHelper::prepareNestedPermission(
                            Freeform::PERMISSION_FORMS_MANAGE,
                            $form->getId()
                        )
                    );
                }

                $canReadSubmissions = PermissionHelper::checkPermission(Freeform::PERMISSION_SUBMISSIONS_READ);
                if (!$canReadSubmissions) {
                    $canReadSubmissions = PermissionHelper::checkPermission(
                        PermissionHelper::prepareNestedPermission(
                            Freeform::PERMISSION_SUBMISSIONS_READ,
                            $form->getId()
                        )
                    );
                }

                $canManageSubmissions = PermissionHelper::checkPermission(Freeform::PERMISSION_SUBMISSIONS_MANAGE);
                if (!$canManageSubmissions) {
                    $canManageSubmissions = PermissionHelper::checkPermission(
                        PermissionHelper::prepareNestedPermission(
                            Freeform::PERMISSION_SUBMISSIONS_MANAGE,
                            $form->getId()
                        )
                    );
                }

                $submissions = Freeform::t('{count} Submissions', ['count' => $data->counters['submissions']]);
                $spam = Freeform::t('{count} Spam', ['count' => $data->counters['spam']]);

                if ($canManageForm) {
                    $event->add($form, UrlHelper::cpUrl('freeform/forms/'.$form->getId()), 'title');
                }

                if ($canReadSubmissions || $canManageSubmissions) {
                    $event->add($submissions, UrlHelper::cpUrl('freeform/submissions?source=form:'.$form->getId()), 'linkList');
                    $event->add($spam, UrlHelper::cpUrl('freeform/spam?source=form:'.$form->getId()), 'linkList');
                }
            }
        );
    }
}
