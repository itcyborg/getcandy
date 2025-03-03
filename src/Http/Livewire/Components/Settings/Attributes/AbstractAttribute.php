<?php

namespace GetCandy\Hub\Http\Livewire\Components\Settings\Attributes;

use GetCandy\FieldTypes\Number;
use GetCandy\FieldTypes\Text;
use GetCandy\FieldTypes\TranslatedText;
use GetCandy\Models\AttributeGroup;
use GetCandy\Models\Collection;
use GetCandy\Models\ProductType;
use Illuminate\Support\Str;
use Livewire\Component;

abstract class AbstractAttribute extends Component
{
    /**
     * Define whether the handle should be treated as manually input.
     *
     * @var bool
     */
    public bool $manualHandle = false;

    /**
     * Generate the handle based on a value.
     *
     * @param  string  $value
     * @return void
     */
    public function generateHandle($value)
    {
        if ($value && ! $this->manualHandle) {
            $this->attribute->handle = Str::slug($value);
        }
    }

    /**
     * Return the current attribute groups in the system.
     *
     * @return void
     */
    public function getAttributeGroupsProperty()
    {
        return AttributeGroup::whereAttributableType($this->attribute->attribute_type)->get();
    }

    /**
     * Watches attribute type and resets group id if it doesn't exist.
     *
     * @param  string  $value
     * @return void
     */
    public function updatedAttributeAttributeType($value)
    {
        $groupStillExists = $this->attributeGroups->first(function ($group) {
            return $group->id == $this->attribute->attribute_group_id;
        });
        if ($value && ! $groupStillExists) {
            $this->attribute->attribute_group_id = null;
        }
    }

    /**
     * Return attribute types mapping for select dropdown.
     *
     * @return array
     */
    public function getAttributeTypesProperty()
    {
        return [
            ProductType::class => class_basename(ProductType::class),
            'GetCandy\Models\Order' => 'Order',
            Collection::class => class_basename(Collection::class),
        ];
    }

    /**
     * Return field types mapping for select dropdown.
     *
     * @return array
     */
    public function getTypesProperty()
    {
        return [
            Text::class => class_basename(Text::class),
            TranslatedText::class => class_basename(TranslatedText::class),
        ];
    }

    /**
     * Return which type of config we should be showing.
     *
     * @return string
     */
    public function getConfigTypeProperty()
    {
        if ($this->attribute->type == Number::class) {
            return 'number';
        }

        return 'text';
    }

    /**
     * Returns whether editing should be considered locked.
     *
     * @return bool
     */
    public function getIsLockedProperty()
    {
        return $this->attribute->id ? (bool) $this->attribute->system : false;
    }
}
