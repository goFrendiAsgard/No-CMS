<?php
echo form_open();
echo form_label('Please provide your email address');
echo br();
echo form_input('email');
echo br(2);
echo form_submit('submit','Submit');
echo form_close();