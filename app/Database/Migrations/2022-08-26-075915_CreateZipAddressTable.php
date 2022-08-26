<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateZipAddressTable extends Migration
{
    private string $table = 'zip_address';

    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'comment' => '住所コード'],
            'pref_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'comment' => '都道府県コード'],
            'city_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'comment' => '市区町村コード'],
            'street_id'      => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'comment' => '町域コード'],
            'zip'            => ['type' => 'VARCHAR', 'constraint' => 8, 'null' => true, 'comment' => '郵便番号'],
            'office_flg'     => ['type' => 'TINYINT', 'unsigned' => true, 'comment' => '事業所フラグ'],
            'delete_flg'     => ['type' => 'TINYINT', 'unsigned' => true, 'comment' => '廃止フラグ'],
            'pref'           => ['type' => 'VARCHAR', 'constraint' => 8, 'null' => true, 'comment' => '都道府県'],
            'pref_kana'      => ['type' => 'VARCHAR', 'constraint' => 8, 'null' => true, 'comment' => '都道府県カナ'],
            'city'           => ['type' => 'VARCHAR', 'constraint' => 24, 'null' => true, 'comment' => '市区町村'],
            'city_kana'      => ['type' => 'VARCHAR', 'constraint' => 24, 'null' => true, 'comment' => '市区町村カナ'],
            'street'         => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true, 'comment' => '町域'],
            'street_kana'    => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true, 'comment' => '町域カナ'],
            'street_memo'    => ['type' => 'VARCHAR', 'constraint' => 16, 'null' => true, 'comment' => '町域補足'],
            'kyoto_name'     => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true, 'comment' => '京都通り名'],
            'block'          => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true, 'comment' => '字丁目'],
            'block_kana'     => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true, 'comment' => '字丁目カナ'],
            'memo'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'comment' => '補足'],
            'office_name'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'comment' => '事業所名'],
            'office_kana'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'comment' => '事業所名カナ'],
            'office_street'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'comment' => '事業所住所'],
            'new_address_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'comment' => '新住所CD'],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('pref_id', false);
        $this->forge->addKey('city_id', false);
        $this->forge->addKey('street_id', false);
        $this->forge->addKey('pref', false);
        $this->forge->addKey('city', false);
        $this->forge->addKey('pref_kana', false);
        $this->forge->addKey('city_kana', false);

        $this->forge->createTable($this->table, true, ['comment' => '住所.jp http://jusyo.jp/']);
    }

    public function down()
    {
        $this->forge->dropTable($this->table);
    }
}
