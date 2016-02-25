<?php

namespace MyVendor\Task\Resource\App;

use BEAR\Resource\ResourceObject;
use Koriym\Now\NowInject;
use Koriym\QueryLocator\QueryLocatorInject;
use Ray\AuraSqlModule\AuraSqlInject;

class Task extends ResourceObject
{
    use AuraSqlInject;
    use NowInject;
    use QueryLocatorInject;

    public function onGet($id = null)
    {
        $this->body = $id ?
            $this->pdo->fetchAssoc($this->query['task_item'], ['id' => $id]) :
            $this->pdo->fetchAssoc($this->query['task_list']);

        return $this;
    }

    public function onPost($title)
    {
        $this->pdo->perform($this->query['insert_task'], ['title' => $title, 'created' => $this->now]);
        // last ID
        $id = $this->pdo->lastInsertId('id');
        $this->code = 201;
        $this->headers['Location'] = "/task?id={$id}";

        return $this;
    }

    public function onPatch($id)
    {
        $this->pdo->perform($this->query['update_task'], ['id' => $id]);

        return $this;
    }

    public function onDelete($id)
    {
        $this->pdo->perform($this->query['delete_task'], ['id' => $id]);

        return $this;
    }
}
