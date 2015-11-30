<?php

namespace Clicker\Model;

use Zend\Db\TableGateway\TableGateway;

class QuestionTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getQuestion($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveQuestion(Question $question)
    {
        $data = array(
            'name'    => $question->name,
            'content' => $question->content,
            'updated' => date('Y-m-d H:i:s'),
        );

        $id = (int) $question->id;
        if ($id == 0)
        {
            $data['created'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
        }
        else
        {
            if ($this->getQuestion($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else
            {
                throw new \Exception('Question id does not exist');
            }
        }
    }

    public function deleteQuestion($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
