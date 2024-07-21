<?php

namespace atom\form;

use yii\base\Model;

class ModelForm extends Model
{
    public ?string $formName = null;

    private ?Model $_entity = null;

    public function __construct(Model $entity, $config = [])
    {
        $this->_entity = $entity;
        parent::__construct($config);
    }

    /**
     * Custom naming on the forms
     * @return string
     */
    public function formName(): string
    {
        return $this->formName ?: parent::formName();
    }

    /**
     * Make form name for attributes/relations etc.
     * @param string $attributeName
     * @param string|int|null $key
     * @return string
     */
    public function makeFormName(string $attributeName, string|int|null $key = null): string
    {
        $name = $this->formName() . "[{$attributeName}]";
        if ($key !== null) {
            $name .= "[{$key}]";
        }
        return $name;
    }

    /**
     * Returns entity
     * @return Model
     */
    public function entity(): Model
    {
        return $this->_entity;
    }

    /**
     * Entity saving
     * @return bool
     */
    protected function saveEntity(): bool
    {
        return $this->_entity->save(false);
    }

    /**
     * Save entity with form data
     * @param bool $runValidation
     * @return bool
     */
    public function save(bool $runValidation = true): bool
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        return $this->saveEntity();
    }
}
