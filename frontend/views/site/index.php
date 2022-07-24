<?php

use yii\helpers\html;

/** @var yii\web\View $this */
$this->title = 'My File Manager';
?>
<style>
    .public_pill {
        background-color: #c8e6c9;
        text-align: center;
        border-radius: 5px;
    }

    .private_pill {
        background-color: #ffcdd2;
        text-align: center;
        border-radius: 5px;

    }
</style>
<div class="site-index">
    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">My File Manager!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <?php if ($user_id != null) { ?>
            <span><?= Html::a('Add New File', ['/site/create'], ['class' => 'btn btn-success']) ?></span>
        <?php } ?>

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
                        <th scope="col"></th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    if (count($files) > 0) :
                        foreach ($files as $file) :
                            if ($file->user_id == $user_id) {
                    ?>
                                <tr class="table-">
                                    <td><?php echo ++$i; ?></td>
                                    <td><?php echo $file->title; ?></td>
                                    <td><?php echo $file->description; ?></td>
                                    <td><?php echo $file->name; ?></td>
                                    <td><?php if ($file->status == 'public') { ?>
                                            <div class="public_pill">
                                            <span><?= Html::a('Public', ['public', 'id' => $file->id,'status'=>0]) ?></span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="private_pill">
                                            <span><?= Html::a('Private', ['private', 'id' => $file->id,'status_id'=>1]) ?></span>
                                            <?php
                                        } ?>
                                    </td>
                                    <td><p><a href="<?php echo '../uploads/' . $file->name; ?>" target="_blank">View File &raquo;</a></p></td>
                                    <td>
                                        
                                        <span><?= Html::a('Update', ['update', 'id' => $file->id], ['class' => 'btn btn-primary']) ?></span>
                                        <span><?= Html::a('Delete', ['delete', 'id' => $file->id], ['class' => 'btn btn-danger']) ?></span>

                                        
                                    </td>
                                </tr>
                        <?php }
                        endforeach; ?>
                    <?php
                    else : ?>
                        <tr class="table-active">
                            <td>You Have No Files....!</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                    <tr class="table-success">
                        <td>Public Files :</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    $i = 0;
                    if (count($files) > 0) :
                        foreach ($files as $file) :
                            if ($file->user_id != $user_id) {
                    ?>
                                <tr class="table-">
                                    <td><?php echo ++$i; ?></td>
                                    <td><?php echo $file->title; ?></td>
                                    <td><?php echo $file->description; ?></td>
                                    <td><?php echo $file->name; ?></td>
                                    <td><?php if ($file->status == 'public') { ?>
                                            <div class="public_pill">
                                                Public
                                            </div>
                                        <?php } else { ?>
                                            <div class="private_pill">
                                                Private
                                            <?php
                                        } ?>
                                    </td>
                                    <td>
                                        <p><a href="<?php echo '../uploads/' . $file->name; ?>" target="_blank">View File &raquo;</a></p>
                                    </td>
                                    <td></td>
                                </tr>
                        <?php }
                        endforeach; ?>
                    <?php
                    else : ?>
                        <tr class="table-active">
                            <td>You Have No Files....!</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>