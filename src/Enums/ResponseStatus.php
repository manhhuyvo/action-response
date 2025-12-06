<?php

namespace ManhHuyVo\ActionResponse\Enums;

enum ResponseStatus: string
{
    case Success = 'success';

    case Error = 'error';

    case Snooze = 'snooze';
}