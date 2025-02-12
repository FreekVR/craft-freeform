<?php

namespace Solspace\Freeform\Services\Pro;

use craft\base\Component;
use Solspace\Freeform\Events\Assets\RegisterEvent;
use Solspace\Freeform\Events\Forms\PageJumpEvent;
use Solspace\Freeform\Resources\Bundles\SubmissionEditRulesBundle;
use yii\base\InvalidConfigException;

class RulesService extends Component
{
    public function handleFormPageJump(PageJumpEvent $event)
    {
        // TODO: implement me
        return;
        $form = $event->getForm();
        $ruleProperties = $form->getRuleProperties();

        if (null !== $ruleProperties && $ruleProperties->hasActiveGotoRules($form->getCurrentPage()->getIndex())) {
            $event->setJumpToIndex($ruleProperties->getPageJumpIndex($form));
        }
    }

    /**
     * @throws InvalidConfigException
     */
    public function registerRulesJsAsAssets(RegisterEvent $event)
    {
        $event->getView()->registerAssetBundle(SubmissionEditRulesBundle::class);
    }
}
