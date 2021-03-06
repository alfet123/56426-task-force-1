<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

abstract class UnsecuredController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function($rule, $action) {
                            return $this->redirect('/tasks');
                        }
                    ]
                ]
            ]
        ];
    }
}
