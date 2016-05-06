<?php

use yii\db\Migration;

class m160120_053954_create_table_files extends Migration
{
    public function up()
    {
        $this->createTable('{{%files}}', [
            'id' => 'pk',
            'owner'=>'varchar(100)',
            'owner_id'=>'int(10)',
            'name'=>'varchar(100)',
            'description'=>'text',
            'alt'=>'varchar(100)',
            'file_name' => 'varchar(100)',
            'original_name'=>'varchar(100)',
            'subdir'=>'varchar(100)',
            'size'=>'varchar(100)',
            'mime_type'=>'varchar(100)',
            'tmp'=>'int(1)',
            'user_id'=>'int(11)',
            'date_create'=>'datetime',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%files}}');
    }
}
