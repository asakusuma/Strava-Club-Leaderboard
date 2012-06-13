<?php

namespace Fuel\Migrations;

class Create_summaries_table
{

    function up()
    {
        \DBUtil::create_table('summaries', array(
            'id' => array('type' => 'int', 'constraint' => 8, 'auto_increment' => true),
			'week' => array('type' => 'int', 'constraint' => 10),
            'member' => array('type' => 'int'),
            'movingTime' => array('type' => 'int'),
			'distance' => array('type' => 'double'),
			'maximumSpeed' => array('type' => 'double'),
			'elevationGain' => array('type' => 'double'),
			'lastUpdated' => array('type' => 'int'),
        ), array('id'));
    }

    function down()
    {
       \DBUtil::drop_table('summaries');
    }
}

?>