<?php

use yii\db\Schema;
use yii\db\Migration;

class m150428_083252_first extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'role', Schema::TYPE_STRING);

        $this->insert('{{%user}}', [
            'username'      => 'admin',
            'email'         => 'admin@mail.ru',
            'role'          => 'admin',
            'password_hash' => '$2y$12$YPyXCnKQfcw/LtSf6mrqJ.KedxewDCwFUxgErEuH2ozl6wXSrwEGy',
            'auth_key'      => '8O8ilPeVYo3YGyiDv3mthMuBowLo8Ay1'
        ]);

        $this->createTable('sites', [
            'id'            => Schema::TYPE_PK,
            'domain'        => Schema::TYPE_STRING  . 'UNIQUE NOT NULL',
            'contacts'      => Schema::TYPE_TEXT,
            'comments'      => Schema::TYPE_TEXT,
            'status'        => Schema::TYPE_INTEGER,
            'created_at'    => Schema::TYPE_INTEGER,
            'updated_at'    => Schema::TYPE_INTEGER,
            'author_id'     => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->addForeignKey('fk_sites_author_id', 'sites', 'author_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('site_callback', [
            'id'            => Schema::TYPE_PK,
            'site_id'       => Schema::TYPE_INTEGER  . ' NOT NULL',
            'type'          => Schema::TYPE_INTEGER  . ' NOT NULL',
            'value'         => Schema::TYPE_TEXT
        ]);
        $this->addForeignKey('fk_site_callback_site_id', 'site_callback', 'site_id', 'sites', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'role');
        $this->delete('{{%user}}', ['username'=>'admin']);

        $this->dropForeignKey('fk_sites_author_id', 'sites');
        $this->dropForeignKey('fk_site_callback_site_id', 'site_callback');

        $this->dropTable('sites');
        $this->dropTable('site_callback');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
