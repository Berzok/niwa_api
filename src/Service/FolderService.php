<?php

namespace App\Service;

use App\Entity\Folder;
use App\Entity\Resource;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

class FolderService {

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Returns a Folder from a given path, like /books/example/folder
     * @param string $path
     * @return Folder
     */
    public function resolvePath(string $path): Folder {
        $repository = $this->doctrine->getRepository(Folder::class);
        $folder = $repository->find(1);
        if (empty($path)) {
            return $folder;
        }
        $table = explode('/', $path);

        $depth = 1;
        foreach ($table as $name) {
            $folder = $repository->findOneBy([
                'name' => $name,
                'depth' => $depth
            ]);

            $depth = $folder->getDepth();
        }

        return $folder;
    }

    /**
     * @param Folder $folder
     * @return array
     */
    #[ArrayShape(['text' => "array", 'children' => "array"])]
    public function orgTree(Folder $folder): array {
        $data = [
            'text' => [
                'name' => $folder->getName()
            ],
            'children' => []
        ];

        if ($folder->getContent() == null) {
            return $data;
        }

        /** @var Folder $children */
        foreach ($folder->getChildrenFolder() as $children) {
            $data['children'][] = $this->orgTree($children);
        }

        /** @var Resource $resource */
        foreach ($folder->getContent() as $resource) {
            $data['children'][] = array_merge($data['children'], [
                'text' => [
                    'name' => $resource->getName()
                ]
            ]);
        }

        return $data;
    }

    /**
     * Count the number of resources in a folder and its subfolders
     * @param Folder $folder
     * @return int
     */
    public function childrensCount(Folder $folder): int {
        $count = 0;
        $cResources = $folder->getContent();
        $count += $cResources->count();
        $cFolders = $folder->getChildrenFolder();

        if($cFolders->isEmpty()){
            return $count;
        }
        foreach ($cFolders as $cFolder) {
            $count += $this->childrensCount($cFolder);
        }
        return $count;
    }
}