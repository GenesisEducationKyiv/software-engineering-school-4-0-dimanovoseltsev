<?php

use yii\db\Migration;

class m240706_143002_create_subscriptions_table extends Migration
{
    private const string TABLE = "{{%subscriptions}}";

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
            'email' => $this->text()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-email', self::TABLE, 'email(767)', true);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->dropTable(self::TABLE);
    }
}
