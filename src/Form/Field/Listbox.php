<?php

namespace ShaoZeMing\Merchant\Form\Field;

/**
 * Class ListBox.
 *
 * @see https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox
 */
class Listbox extends MultipleSelect
{
    protected $settings = [];

    protected static $css = [
        '/vendor/laravel-merchant/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-merchant/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js',
    ];

    public function settings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function render()
    {
        $settings = array_merge($this->settings, [
            'infoText'          => trans('merchant.listbox.text_total'),
            'infoTextEmpty'     => trans('merchant.listbox.text_empty'),
            'infoTextFiltered'  => trans('merchant.listbox.filtered'),
            'filterTextClear'   => trans('merchant.listbox.filter_clear'),
            'filterPlaceHolder' => trans('merchant.listbox.filter_placeholder'),
        ]);

        $settings = json_encode($settings);

        $this->script = <<<SCRIPT

$("{$this->getElementClassSelector()}").bootstrapDualListbox($settings);

SCRIPT;

        return parent::render();
    }
}
