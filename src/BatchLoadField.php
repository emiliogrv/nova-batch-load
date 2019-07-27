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
     * @return void
     */
    public function __construct()
    {
        parent::__construct(str_random());

        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnUpdate = false;
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
     * Set attribute names that will be ignored and will not appear.
     *
     * @param  string  $ignore
     * @return $this
     */
    public function ignoreAttributes(string $ignore)
    {
        return $this->withMeta(['ignoreAttributes' => $ignore]);
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

    /**
     * Do not show original fields.
     *
     * @return $this
     */
    public function withoutOriginalFields()
    {
        return $this->withMeta(['withoutOriginalFields' => true]);
    }
}
