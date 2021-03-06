<?php

namespace ShaoZeMing\Merchant\Form\Field;

class Ip extends Text
{
    protected $rules = 'ip';

    protected static $js = [
        '/vendor/laravel-merchant/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias' => 'ip',
    ];

    public function render()
    {
        $options = json_encode($this->options);

        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').inputmask($options);
EOT;

        $this->prepend('<i class="fa fa-laptop"></i>')
            ->defaultAttribute('style', 'width: 130px');

        return parent::render();
    }
}
