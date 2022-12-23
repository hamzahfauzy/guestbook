<?php

return "<a href='".routeTo('forms/index',['event_id'=>$d->id])."' class='btn btn-sm btn-success'>Form</a>
        <a href='".routeTo('submissions/index',['event_id'=>$d->id])."' class='btn btn-sm btn-info'>Submission</a>";