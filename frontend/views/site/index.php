<?php
use yii\helpers\html;
/** @var yii\web\View $this */
//print_r(Yii::$app->user->identity->id);
//die();

//echo '<pre>';
//print_r($files);
//die();

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">My File Manager!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        
        
        <span><?= Html::a('Add New File',['/site/create'],['class'=> 'btn btn-success'])?></span>

    </div>

<div class="body-content">
    <div class="row">
        <table class="table table-hover">
            <thead>
                <tr class="table-primary">
                    <th scope="col">Sl No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">File name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $i=0;
                    if(count($files)>0):
                        foreach($files as $file):
                ?>
                <tr class="table-">
                    <td><?php echo ++$i; ?></td>
                    <td><?php echo $file->title; ?></td>
                    <td><?php echo $file->description; ?></td>
                    <td><?php echo $file->name; ?></td>
                    <td><?php echo $file->status; ?></td>
                    <td>
                        <span><?= Html::a('View',['view','id'=>$file->id],['class'=>'btn btn-success']) ?></span>
                        <span><?= Html::a('Update',['update','id'=>$file->id],['class'=>'btn btn-primary']) ?></span>
                        <span><?= Html::a('Delete',['delete','id'=>$file->id],['class'=>'btn btn-danger']) ?></span>
                    </td>
                </tr>
                <?php 
                    endforeach;?> 
                    <?php 
                    else: ?>
                    <tr class="table-active">
                    <td>You Have No Files....!</td>
                </tr>    
                <?php endif; ?>    
            </tbody>
        </table>
    </div>    
</div>

</div>
