<?php

namespace frontend\controllers;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Files;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;



/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->getId() != NULL) {
            $id = Yii::$app->user->getId();
            $query = Files::find()->Where(['user_id' => $id])->orWhere([
                'or',
                ['status' => 'public']
            ]);
            $files = $query->all();
            $name = Yii::$app->user->identity->username;
            return $this->render('index', ['files' => $files, 'user_id' => $id, 'user_name' => $name]);
        } else {
            $query = Files::find()->Where(['status' => 'public']);
            $files = $query->all();
            return $this->render('index', ['files' => $files, 'user_id' => null, 'user_name' => null]);
        }
    }


    /**
     * Create Files.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $file = new Files();
        $formData = yii::$app->request->post();
        $file->user_id = Yii::$app->user->getId();
        $id = Yii::$app->user->getId();
        if ($file->load($formData)) {
            $name = Yii::$app->user->identity->username;
            $file->name = UploadedFile::getInstance($file, 'name');
            $file_path = \Yii::$app->basePath . '/uploads/';
            $document_name = UploadedFile::getInstance($file, 'name');
            if ($file->save()) {

                if (!empty($document_name->name)) {
                    $file->name = $document_name->name;
                    $document_name->saveAs(\Yii::$app->basePath . '/uploads/' . $document_name->name);
                }

                Yii::$app->session->setFlash('success', 'File Uploaded Successfully');
                return $this->goHome();
            } else {

                Yii::$app->session->setFlash('error', 'Failed to Upload File');
                return $this->redirect(['create']);
            }
        }
        return $this->render('create', ['file' => $file]);
    }


    /**
     * Update Files.
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $file = Files::findOne($id);
        $doc_name = $file->name;
        if ($file->load(Yii::$app->request->post())) {
            $file->name = UploadedFile::getInstance($file, 'name');
            $document_name = UploadedFile::getInstance($file, 'name');
            $temp = UploadedFile::getInstance($file, 'name');
            if ($temp == null) {
                $file->name = $doc_name;
                if ($file->save()) {
                    Yii::$app->session->setFlash('success', 'File Uploaded Successfully');
                    return $this->goHome();
                }
            } else {
                if ($file->save()) {
                    if (!empty($document_name->name)) {
                        $file->name = $document_name->name;
                        $document_name->saveAs(\Yii::$app->basePath . '/uploads/' . $document_name->name);
                        Yii::$app->session->setFlash('success', 'File Uploaded Successfully');
                        return $this->goHome();
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to Update File Details');
                    return $this->goHome();
                }
            }
        }
        return $this->render('update', ['file' => $file]);
    }


    /**
     * Delete Files.
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $file = Files::findOne($id)->delete();
        Yii::$app->session->setFlash('success', 'File Deleted Successfully');
        return $this->goHome();
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        //$files = Files::find()->all();

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $id = Yii::$app->user->getId();
            $query = Files::find()->Where(['user_id' => $id])->orWhere([
                'or',
                ['status' => 'public']
            ]);
            $files = $query->all();
            $name = Yii::$app->user->identity->username;
            //echo '<pre>';
            //print_r($files);
            //die();

            return $this->render('index', [
                'model' => $model, 'files' => $files, 'user_id' => $id, 'user_name' => $name
            ]);
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }
    

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please Try Login.');
            return $this->goHome();
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
