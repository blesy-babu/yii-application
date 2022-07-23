<?php
use yii\helpers\html;
use yii\widgets\ActiveForm;
/** @var yii\web\View $this */
$this->title = 'My Yii Application';
?>

<div class="site-index">
    <script>
        function fileOnChange() {
            let e = document.getElementById('doc');
            const fileSize = e.files[0].size / 1024 / 1024; // in MiB
            if (fileSize > 1000) {
                alert('File size exceeds 1 GB');
                e.value='';
            } 
        }
    </script>
    
    <div class="body-content">
        <?php $form = ActiveForm::begin() ?>
        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                <h3>Upload File here : </h3>
            </div>
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
                $hint = 'Info : Supported file types are .png, .jpg, .pdf, .jpeg , Maximum size : 1GB';
                echo $form->field($file, 'name')
                    ->fileInput(['required' => 'true', 'id' => 'doc', 'onchange' => 'fileOnChange()'])->hint($hint)->label(false);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                <div class="row">

                    <div class="col-lg-6 ">
                        <a href=<?php echo yii::$app->homeUrl; ?> class="btn btn-secondary">Go Back</a>
                    </div>
                    <div class="col-lg-6 d-flex flex-row-reverse">
                        <?= Html::submitButton('Create File', ['class' => 'btn btn-success']); ?>
                    </div>
                </div>
            </div>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>