<?php

use yii\db\Migration;

class m240516_202303_create_currencies_table extends Migration
{
    private const string TABLE = "{{%currencies}}";

    /**
     * @return void
     */
    public function up(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'iso3' => $this->string()->notNull()->unique(),
            'rate' => $this->decimal(13, 5)->defaultValue(1.00000),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->dropTable(self::TABLE);
    }
}
