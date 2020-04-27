<?php

namespace App\Service;

class ImageTexteGenerateur
{
    public function texte2Image($texte)
    {
        $font = 5; // CODE COMPLIQUE ININTERESSANT
        $largeur = imagefontwidth($font) * strlen($texte);
        $hauteur = imagefontheight($font);
        $image = imagecreatetruecolor($largeur, $hauteur);
        $gris = imagecolorallocate($image, 200, 200, 200);
        $noir = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $gris);
        imagestring($image, $font, 0, 0, $texte, $noir);
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();
        $dataURI = 'data:image/image/png;base64,' . base64_encode($imageData);
        imagedestroy($image);
        return $dataURI;
    }

}