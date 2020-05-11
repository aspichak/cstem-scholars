<?php

class FileUpload
{
    private $name;
    private $required;
    private $allowedExtensions;
    private $maxSize;

    public function __construct($name, $required = false, $allowedExtensions = null, $maxSize = null)
    {
        $this->name = $name;
        $this->required = $required;
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSize = $maxSize;
    }

    public static function delete($base, $path)
    {
        // TODO: Disallow escaping from base directory
        if (is_file($base . '/' . $path)) {
            return unlink($base . '/' . $path);
        }

        return false;
    }

    public function isValid()
    {
        return $this->error() == null;
    }

    public function isUploaded()
    {
         return ($_FILES[$this->name]['error'] ?? UPLOAD_ERR_NO_FILE) != UPLOAD_ERR_NO_FILE;
    }

    public function error()
    {
        switch ($_FILES[$this->name]['error'] ?? UPLOAD_ERR_NO_FILE) {
            case UPLOAD_ERR_OK:
                if ($this->allowedExtensions && !in_array($this->extension(), $this->allowedExtensions)) {
                    return 'This file type is not allowed. Accepted file types are: ' .
                           implode(', ', $this->allowedExtensions);
                }

                if ($this->maxSize && $_FILES[$this->name]['size'] > $this->maxSize) {
                    return 'Maximum file size is ' . $this->humanReadableSize();
                }

                return null;
            case UPLOAD_ERR_NO_FILE:
                return ($this->required) ? 'File upload is required' : null;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'Exceeded file size limit';
            default:
                return 'Unknown error';
        }
    }

    public function move($base, $path)
    {
        // TODO: Disallow escaping from base directory
        if (!$this->isValid()) {
            throw new UnexpectedValueException('Invalid file upload');
        }

        return move_uploaded_file($_FILES[$this->name]['tmp_name'], $base . '/' . $path);
    }

    public function extension()
    {
        return pathinfo($_FILES[$this->name]['name'] ?? null, PATHINFO_EXTENSION);
    }

    // https://stackoverflow.com/a/23888858
    private function humanReadableSize()
    {
        $bytes = $this->maxSize;

        if ($bytes == 0) {
            return '0 B';
        }

        $s = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $e = floor(log($bytes, 1024));

        return round($bytes / (1024 ** $e), 2) . ' ' . $s[$e];
    }
}
