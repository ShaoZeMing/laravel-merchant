<?php

namespace ShaoZeMing\Merchant\Grid\Tools;

class BatchDelete extends BatchAction
{
    /**
     * Script of batch delete action.
     */
    public function script()
    {
        $deleteConfirm = trans('merchant.delete_confirm');
        $confirm = trans('merchant.confirm');
        $cancel = trans('merchant.cancel');

        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    var id = selectedRows().join();

    swal({
      title: "$deleteConfirm",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "$confirm",
      closeOnConfirm: false,
      cancelButtonText: "$cancel"
    },
    function(){
        $.ajax({
            method: 'post',
            url: '{$this->resource}/' + id,
            data: {
                _method:'delete',
                _token:'{$this->getToken()}'
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

EOT;
    }
}
