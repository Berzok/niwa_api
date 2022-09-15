<?php

namespace App\Service;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService {

    private SluggerInterface $slugger;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }

    /**
     * @throws Exception
     */
    private function disconnect(): void {
        try {
            unset($this->client);
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * @param UploadedFile $file
     * @param string $directory
     * @return array
     */
    #[ArrayShape([
        'new' => "string",
        'safe' => "\Symfony\Component\String\AbstractUnicodeString"
    ])]
    public function upload(UploadedFile $file, string $directory): array {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $directory,
                $newFilename
            );
        } catch (FileException $e) {
            var_dump($e);
            exit();
            // ... handle exception if something happens during file upload
        }

        return [
            'new' => $newFilename,
            'safe' => $safeFilename
        ];
    }
}