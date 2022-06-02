<?php 
namespace App\StaticData;

use ErrorException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class UploadFile extends AbstractController
{
    public static function upload($picture,$controller,SluggerInterface $slugger):string | null
    {
        if ($picture !== null) {
            $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

            try {
                $picture->move(
                    $controller->getParameter('brochures_directory'),
                    $newFilename
                );
            } catch (FileException $message) {
                // ... handle exception if something happens during file upload
                throw new ErrorException($message);
            }
            return $newFilename;
        }
        return null;
    }
}