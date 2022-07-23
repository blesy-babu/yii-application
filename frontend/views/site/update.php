<?php

use yii\helpers\html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */


$this->title = 'My Yii Application';

?>

<div class="site-index">

<script>  
    function hideAndShow(){
        document.getElementById('invisible_file').style.cssText = 'display:block !important';
        document.getElementById('change_file').style.cssText = 'display:none !important';
    }
</script>

    <h1>Update File Details</h1>

    <div class="body-content">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="row">

            <div class="col-lg-6 offset-lg-6">
                <?= $form->field($file, 'title') ?>
            </div>
            <div class="col-lg-6 offset-lg-6">
                <?= $form->field($file, 'description')->textarea(['rows' => '3']); ?>
            </div>
            <div class="col-lg-6 offset-lg-6">
                <?php $status = ['private' => 'private', 'public' => 'public']; ?>
                <?= $form->field($file, 'status')->dropDownList($status, ['prompt' => 'Select']); ?>
            </div>
            <div class="col-lg-6 offset-lg-6">
                <?php
                $hint = 'Info : Supported file types are .png, .jpg, .pdf, .jpeg , Maximum size : 2MB';
                echo $form->field($file, 'name')->fileInput(['value' =>'../uploads/'.$file->name,'id' => 'invisible_file', 'class' => 'd-none'])->hint($hint)->label(false);
                ?>
                

                <div class="alert alert-warning alert-dismissible fade show " id="change_file"  onclick="hideAndShow()"  role="alert">
                    <strong><?php echo $file['name']?></strong> 
                    <button type="button" class="btn btn-info">Replace</button>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                <div class="row">

                    <div class="col-lg-6 ">
                        <a href=<?php echo yii::$app->homeUrl; ?> class="btn btn-secondary">Go Back</a>
                    </div>
                    <div class="col-lg-6 d-flex flex-row-reverse">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-success']); ?>
                    </div>
                </div>
            </div>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>