<?php

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait HasUploadFile
{
    /**
     * Do operation uploaded file to specific directory.
     *
     * @param UploadedFile $file
     * @param string       $uploadRootDir
     *
     * @return string $newFileName
     */
    public function doUpload(UploadedFile $file, $uploadRootDir)
    {
        // do whatever you want to generate a unique name
        $newFileName = sha1(uniqid(mt_rand(), true));
        $newFileName = $newFileName.'.'.$file->guessExtension();

        $uploadRootDir = $uploadRootDir.date('Y').'/'.date('m');
        $this->createDir($uploadRootDir);

        $file->move($uploadRootDir, $newFileName);

        return date('Y').'/'.date('m').'/'.$newFileName;
    }

    /**
     * Do operation delete a specific uploaded file from  a specific directory.
     *
     * @param string $oldFileName
     * @param string $uploadRootDir
     */
    public function deleteFileUpload($oldFileName, $uploadRootDir)
    {
        if (!empty($oldFileName) && file_exists($uploadRootDir.'/'.$oldFileName)) {
            unlink($uploadRootDir.'/'.$oldFileName);
        }
    }

    /**
     * Do operation uploaded multiple files to specific directory.
     *
     * @param File   $file
     * @param string $uploadRootDir
     * @param string $currentYearAndMonth
     *
     * @return string $newFileName
     */
    public function doMultiUpload(
        File $file,
        $uploadRootDir,
        $currentYearAndMonth
    ) {
        // generate directory for upload
        $uploadRootDir = $uploadRootDir.$currentYearAndMonth;
        $this->createDir($uploadRootDir);

        // do whatever you want to generate a unique name
        $newFileName = sha1(uniqid(mt_rand(), true));
        $newFileName = $newFileName.'.'.$file->guessExtension();

        $file->move($uploadRootDir, $newFileName);

        return $newFileName;
    }

    /**
     * Generate Upload Directory.
     *
     * @param string $uploadRootDir
     */
    public function createDir($uploadRootDir)
    {
        if (!is_dir($uploadRootDir)) {
            mkdir($uploadRootDir, 0777, true);
        }
    }

    /**
     * to upload new file and remove old one, return the new file name.
     *
     * @param UploadedFile $newFile
     * @param string       $oldFileName
     *
     * @return string
     */
    public function saveAndReplaceImage(UploadedFile $newFile, $oldFileName)
    {
        $uploadDir = $this->container->getParameter('upload_thumbs_directory');

        $newFileName = $this->doUpload(
            $newFile,
            $uploadDir
        );

        if (!is_null($oldFileName)) {
            $this->deleteFileUpload($oldFileName, $uploadDir);
        }

        return $newFileName;
    }

    /**
     * To check that file is existing or not.
     *
     * @param String $fileName
     * @param String $dir
     *
     * @return bool
     */
    public function doesFileExist($dir, $fileName)
    {
        return file_exists($dir.'/'.$fileName);
    }
}
