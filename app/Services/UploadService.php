<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService{
    //Je veux une première fonction qui me permet d'enregistrer l'image en locale dans storage/app/public/images

    public function saveImageLocally(UploadedFile $image){
        try{
            $path = $image->store('public/images');
            return Storage::url($path);
        }catch(Exception $e){
            throw new Exception('Erreur lors de l\'enregistrement de l\'image : ' . $e->getMessage());
        }
    }

    //Je veux une deuxième fonction qui me permet de transformer l'image en base64
    //C'est ce que je vais stoker dans ma BD dans la colonne photo
    public function convertToBase64(UploadedFile $image){
        try{
            $fileContent = file_get_contents($image->getRealPath());
            return base64_encode($fileContent);
        }catch(Exception $e){
            throw new Exception('Erreur lors de la conversion de l\'image en base64 : ' . $e->getMessage());
        }
    }

    //Je veux une troisième fonction qui me permet de faire l'upload de l'image. C'est cette fonction que je vais appler dans le service ClientService.php
    //L'image doit-être de type png, jpg, jpeg svg et la taille maximale est de 40KO. Si ça dépasse les 40KO, on affiche un message d'erreur indiquant que l'image est trop lourde et ne doit pas dépasser 40KO
    public function uploadImage(UploadedFile $image){
        $allowedMimeTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'];
        if(!in_array($image->getClientMimeType(), $allowedMimeTypes)){
            throw new Exception('Le format de l\'image doit être png, jpg, jpeg ou svg');
        }

        $maxFileSize = 40 * 1024;
        if($image->getSize() > $maxFileSize){
            throw new Exception('L\'image est trop lourde. La taille maximale est de 40KO');
        }

        $this->saveImageLocally($image);
        return $this->convertToBase64($image);
    }
}