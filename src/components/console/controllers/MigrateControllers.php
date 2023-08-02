<?php

namespace nadzif\base\components\console\controllers;

class MigrateControllers extends \yii\console\controllers\MigrateController
{
    public $generatorTemplateFiles = [
        'create_table' => '@nadzif/base/components/console/views/createTableMigration.php',
        'drop_table' => '@yii/views/dropTableMigration.php',
        'add_column' => '@yii/views/addColumnMigration.php',
        'drop_column' => '@yii/views/dropColumnMigration.php',
        'create_junction' => '@yii/views/createTableMigration.php',
    ];

}