<?php

use ssm229\LaravelAdminAliyunoss\Http\Controllers\OssFormController;

Route::get('alioss_param', OssFormController::class . '@getOssParam');

