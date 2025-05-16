# File Upload Configuration for SMAN1 Girsip

## PHP Configuration for File Uploads

This application has been configured with specific file upload limits to ensure stability and performance:

### Current Configuration:
- **Client-side limit**: 100MB
- **Server-side limit**: 120MB (configured in .htaccess)

### Adjusting File Upload Limits

If you need to increase file upload limits, you can modify the following files:

1. **Root .htaccess file**:
   ```apache
   # PHP Configuration for larger file uploads
   <IfModule mod_php7.c>
       php_value upload_max_filesize 20M
       php_value post_max_size 21M
       php_value max_execution_time 300
       php_value max_input_time 300
       php_value memory_limit 256M
   </IfModule>

   <IfModule mod_php8.c>
       php_value upload_max_filesize 20M
       php_value post_max_size 21M
       php_value max_execution_time 300
       php_value max_input_time 300
       php_value memory_limit 256M
   </IfModule>
   ```

2. **PHP Configuration Override** (`config/php_config.php`):
   ```php
   <?php
   ini_set('upload_max_filesize', '20M');
   ini_set('post_max_size', '21M');
   ini_set('memory_limit', '256M');
   ini_set('max_execution_time', 300);
   ini_set('max_input_time', 300);
   ```

3. **Client-side JavaScript** (Update in these files):
   - `resources/views/siswa/submissions/create.blade.php`
   - `resources/views/siswa/assignments/show.blade.php`
   
   Find and update the `MAX_FILE_SIZE` constant in the JavaScript.

4. **Controller Validation** (`app/Http/Controllers/Siswa/SubmissionController.php`):
   Update the validation rules for file uploads:
   ```php
   $request->validate([
       'file' => 'required|file|max:2048', // 2MB (in kilobytes)
       'notes' => 'nullable|string|max:500',
   ]);
   ```

## Troubleshooting

If you still encounter the "POST data too large" error despite these configurations:

1. **Check your web server configuration**:
   - For Apache: Look for php.ini file or create a custom .user.ini file
   - For Nginx: Update the client_max_body_size directive

2. **Server-level PHP configuration**: 
   Ask your hosting provider to increase the following values in php.ini:
   - upload_max_filesize
   - post_max_size
   - memory_limit

3. **Use alternative upload methods**: 
   For very large files, consider:
   - Breaking files into smaller chunks
   - Using cloud storage links (Google Drive, Dropbox)
   - Implementing a progressive upload mechanism
