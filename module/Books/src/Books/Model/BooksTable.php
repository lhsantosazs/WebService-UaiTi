<?php

namespace Books\Model;

use Zend\Db\TableGateway\TableGateway;

class BooksTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getBooks($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBooks(Books $books) {
        $data = array(
            'title' => $books->title,
            'author' => $books->author,
            'publisher' => $books->publisher,
            'isbn' => $books->isbn,           
        );

        $id = (int) $books->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBooks($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Books id does not exist');
            }
        }
    }

    public function deleteBooks($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}

?>