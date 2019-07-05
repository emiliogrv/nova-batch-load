<?php

namespace Emiliogrv\NovaBatchLoad;

use Laravel\Nova\Fields\Field;

class BatchLoadField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'batch-load-field';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name = null, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct(str_random());

        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnUpdate = false;

        $this->defaultTabActive(0);
        $this->keepOriginalFields('belongs|morph');
    }

    /**
     * Set the accepted file formats by extensions.
     *
     * @param  string  $accept
     * @return $this
     */
    public function accept(string $accept)
    {
        return $this->withMeta(['accept' => $accept]);
    }

    /**
     * Set the tab default when load field.
     *
     * @param  int  $keep
     * @return $this
     */
    public function defaultTabActive(int $tab)
    {
        return $this->withMeta(['defaultTabActive' => $tab]);
    }

    /**
     * Set which fields keep as original Nova format and options.
     *
     * @param  string  $keep
     * @return $this
     */
    public function keepOriginalFields(string $keep)
    {
        return $this->withMeta(['keepOriginalFields' => $keep]);
    }
}
