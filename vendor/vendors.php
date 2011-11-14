<?php

chdir(dirname(__DIR__));

passthru('git submodule init');
passthru('git submodule sync');
passthru('git submodule update');

