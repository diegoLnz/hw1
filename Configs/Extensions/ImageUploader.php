<?php

enum ImageValidationResult: string
{
    case INVALID_EXTENSION = "Non è ammessa tale estensione";
    case UPLOAD_ERRORS = "Vi sono stati errori durante l'upload del file";
    case FILE_SIZE_EXCEEDED = "Il file supera la dimensione massima supportata";
    case OK = "OK";
}

class Image {
    public $name;
    public $tmpName;
    public $size;
    public $type;
    public $extension;
    public $error;

    public function __construct($file)
    {
        $this->name = $file['name'];
        $this->tmpName = $file['tmp_name'];
        $this->size = $file['size'];
        $this->type = $file['type'];
        $this->extension = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );
        $this->error = $file['error'];
    }
}

class ImageUploader {
    private $targetDirectory;
    private $allowedExtensions = ['jpg', 'jpeg', 'png'];

    /**
     * 10 MB
     */
    private $maxFileSize = 10000000;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function uploadFile($fileInputName): string
    {
        if (!isset($_FILES[$fileInputName]))
            return ImageValidationResult::UPLOAD_ERRORS->value;

        $image = new Image($_FILES[$fileInputName]);

        $validationResult = $this->validateFile($image);
        if ($validationResult !== ImageValidationResult::OK)
            return $validationResult->value;

        $targetPath = $this->targetDirectory . '/' . basename($image->name);

        return move_uploaded_file($image->tmpName, $targetPath) 
            ? ImageValidationResult::OK->value 
            : ImageValidationResult::UPLOAD_ERRORS->value;
    }

    private function validateFile(Image $file): ImageValidationResult
    {
        if(!in_array($file->extension, $this->allowedExtensions))
            return ImageValidationResult::INVALID_EXTENSION;

        if($file->error == UPLOAD_ERR_OK)
            return ImageValidationResult::UPLOAD_ERRORS;

        if($file->size > $this->maxFileSize)
            return ImageValidationResult::FILE_SIZE_EXCEEDED;

        return ImageValidationResult::OK;
    }
}
