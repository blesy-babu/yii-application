<?php

    namespace common\models;
    use yii\db\ActiveRecord;
    class Files extends ActiveRecord
   {
        private $title;
        private $description;
        private $name;
        private $status;

        public function rules(){
            return[
               
                [['title', 'description','status'],'required']
            ];
        }

   } 
?>