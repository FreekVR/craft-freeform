<?php

namespace Solspace\Freeform\controllers;

use Solspace\Commons\Helpers\PermissionHelper;
use Solspace\Freeform\Freeform;
use Solspace\Freeform\Resources\Bundles\LogBundle;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class LogsController extends BaseController
{
    /**
     * @throws Exception
     */
    public function actionIndex(): Response
    {
        $logReader = $this->getLoggerService()->getLogReader();

        $this->getLoggerService()->registerJsTranslations($this->view);

        return $this->renderTemplate(
            'freeform/logs/index',
            [
                'logReader' => $logReader,
            ]
        );
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionError(): Response
    {
        $logReader = $this->getLoggerService()->getLogReader();

        $this->getLoggerService()->registerJsTranslations($this->view);

        \Craft::$app->view->registerAssetBundle(LogBundle::class);

        return $this->renderTemplate(
            'freeform/logs/error',
            [
                'logReader' => $logReader,
            ]
        );
    }

    /**
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionClear(): Response
    {
        $this->requirePostRequest();

        PermissionHelper::requirePermission(Freeform::PERMISSION_SETTINGS_ACCESS);

        $this->getLoggerService()->clearLogs();

        if (\Craft::$app->request->getIsAjax()) {
            return $this->asJson(['success' => true]);
        }

        return $this->redirect('/');
    }
}
