<?php

namespace iPresso\Model;

/**
 * Class Tag
 * @package iPresso\Model
 */
class Tag
{
    const VAR_NAME = 'name';
    const VAR_PARENT_ID = 'parentId';

    /**
     * @var array
     */
    public $tag = [];

    /**
     * Parameter `TAG_NAME` should be replaced with the name of the tag being added.
     * @var string
     */
    private $name = '';

    /**
     * Parameter `PARENT_TAG_ID` should be replaced with ID of parent tag.
     * Not required
     * @var int
     */
    private $parent_id = 0;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param int $parent_id
     * @return Tag
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTag()
    {
        if (empty($this->name))
            throw new \Exception('Set category name first.');

        $this->tag[self::VAR_NAME] = $this->name;

        if (!empty($this->parent_id))
            $this->tag[self::VAR_PARENT_ID] = $this->parent_id;

        return $this->tag;
    }
}
