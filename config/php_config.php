<?php
// Local PHP configuration overrides
ini_set('upload_max_filesize', env('UPLOAD_MAX_FILESIZE', '120M'));
ini_set('post_max_size', env('POST_MAX_SIZE', '120M'));
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 1800); // Increased to 30 minutes
ini_set('max_input_time', 1800);
ini_set('default_socket_timeout', 1800);
