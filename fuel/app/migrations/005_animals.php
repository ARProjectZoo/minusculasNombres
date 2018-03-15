<?php
namespace Fuel\Migrations;


class Animals
{

    function up()
    {
        \DBUtil::create_table('animals', 
            array(
                'id' => array('type' => 'int', 'constraint' => 100,'auto_increment' => true),
                'name' => array('type' => 'varchar', 'constraint' => 100),
                'description' => array('type' => 'varchar', 'constraint' => 100),
                'photo' => array('type' => 'varchar', 'constraint' => 500),
                'x' => array('type' => 'float'),
                'y' => array('type' => 'float'),
                'id_continent' => array('type'=> 'int', 'constraint' => 100),
                'id_user' => array('type'=> 'int', 'constraint' => 100)

        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci',
    array(
        array(
            'constraint' => 'ForeingKeyAnimalsToContinent',
            'key' => 'id_continent',
            'reference' => array(
                'table' => 'continent',
                'column' => 'id',
            ),
            'on_update' => 'CASCADE',
            'on_delete' => 'RESTRICT'
            ),
        array(
            'constraint' => 'ForeingKeyAnimalsToUser',
            'key' => 'id_user',
            'reference' => array(
                'table' => 'Users',
                'column' => 'id',
            ),
            'on_update' => 'CASCADE',
            'on_delete' => 'RESTRICT'
            )
    
        )
    ); 
        //AMERICA DEL NORTE
        \DB::query("INSERT INTO animals(id, name, description, photo, x, y, id_continent, id_user)VALUES(NULL,'Osos Pardo', 'Animal de America del Norte', 'photo','40.408174','-3.762891','3','1');")->execute();
        //AMERICA DEL SUR
        \DB::query("INSERT INTO animals(id, name, description, photo, x, y, id_continent, id_user)VALUES(NULL,'Gorilas', 'Animal de America del Sur', 'photo','40.409498','-3.765400','4','1');")->execute();
        //AMERICA CENTRAL
        \DB::query("INSERT INTO animals(id, name, description, photo, x, y, id_continent, id_user)VALUES(NULL,'Jirafas', 'America Central y Caribe', 'photo','40.409962','-3.765217','5','1');")->execute();
    }

    function down()
    {
       \DBUtil::drop_table('animals');
    }
}
?>