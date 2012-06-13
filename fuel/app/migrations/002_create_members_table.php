<?php

namespace Fuel\Migrations;

class Create_members_table
{

    function up()
    {
        \DBUtil::create_table('members', array(
            'id' => array('type' => 'int', 'constraint' => 8),
			'name' => array('type' => 'varchar', 'constraint' => 40)
        ), array('id'));
    }

    function down()
    {
       \DBUtil::drop_table('members');
    }
}

?>