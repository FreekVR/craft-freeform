<?php

namespace Solspace\Freeform\Integrations\Elements\User\EventListeners;

use Solspace\Freeform\Events\Integrations\ElementIntegrations\ProcessValueEvent;
use Solspace\Freeform\Fields\Interfaces\FileUploadInterface;
use Solspace\Freeform\Integrations\Elements\User\User;
use Solspace\Freeform\Library\Bundles\FeatureBundle;
use Solspace\Freeform\Library\Integrations\Types\Elements\ElementIntegrationInterface;
use yii\base\Event;

class PhotoTransform extends FeatureBundle
{
    public function __construct()
    {
        Event::on(
            ElementIntegrationInterface::class,
            ElementIntegrationInterface::EVENT_PROCESS_VALUE,
            [$this, 'processPhoto']
        );
    }

    public function processPhoto(ProcessValueEvent $event): void
    {
        if (!$event->getIntegration() instanceof User) {
            return;
        }

        if ('photo' !== $event->getHandle()) {
            return;
        }

        $value = $event->getValue();
        $field = $event->getFreeformField();

        if (empty($value)) {
            $event->setValue(null);

            return;
        }

        if ($field instanceof FileUploadInterface) {
            $asset = $field->getAssets()->one();
            if ($asset) {
                $event->setValue($asset);
            }

            return;
        }

        $assetId = reset($value);
        if ($assetId && is_numeric($assetId)) {
            $asset = \Craft::$app->getAssets()->getAssetById((int) $assetId);
            if ($asset) {
                $event->setValue($asset);
            }
        }
    }
}
