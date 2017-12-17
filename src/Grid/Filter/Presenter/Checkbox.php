<?php

namespace ShaoZeMing\Merchant\Grid\Filter\Presenter;

use ShaoZeMing\Merchant\Facades\Merchant;

class Checkbox extends Radio
{
    protected function prepare()
    {
        $script = "$('.{$this->filter->getId()}').iCheck({checkboxClass:'icheckbox_minimal-blue'});";

        Merchant::script($script);
    }
}
