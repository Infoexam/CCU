<?php

namespace App\Infoexam\Image;

use Carbon\Carbon;
use File;
use Imager;
use Intervention\Image\Constraint;
use Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload
{
    /**
     * @var array
     */
    protected $files;

    /**
     * @var array
     */
    protected $imageable;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * @var string
     */
    protected $dirPath;

    /**
     * @var bool
     */
    public $success = false;

    /**
     * @var int
     */
    public $successCount = 0;

    /**
     * @var int
     */
    public $totalFilesCount = 0;

    /**
     * Upload constructor.
     *
     * @param UploadedFile|array $files
     * @param array $imageable
     */
    public function __construct($files, array $imageable)
    {
        $this->files = is_array($files) ? $files : [$files];
        $this->imageable = $imageable;
        $this->storage = Storage::disk('local');
        $this->dirPath = config('filesystems.disks.local.root') . '/images';
        $this->totalFilesCount = count($this->files);

        $this->store();
    }

    /**
     * 儲存圖片與搬移
     */
    protected function store()
    {
        // 因圖片為 Polymorphic Relations，所以 id 及 type 為必要資訊
        if (! isset($this->imageable['id']) || ! isset($this->imageable['type']) || empty($this->files)) {
            return;
        }

        // 如圖片存放目錄不存在，則創建
        $this->createDirectoryIfNotExists($this->dirPath);

        foreach ($this->files as $file) {
            // 儲存及搬移
            $this->moveToStorage($file, $this->saveToDatabase($file));

            $this->successCount++;
        }

        // 確認是否接上傳成功
        $this->success = $this->successCount === $this->totalFilesCount;
    }

    /**
     * 確認圖片儲存目錄存在
     *
     * @param string $path
     * @return void
     */
    protected function createDirectoryIfNotExists($path)
    {
        if (! File::isDirectory($path)) {
            if (File::isFile($path)) {
                File::delete($path);
            }

            File::makeDirectory($path, 0777, true, true);
        }
    }

    /**
     * 將圖片資料存到資料庫中
     *
     * @param UploadedFile $file
     * @return Image
     */
    protected function saveToDatabase(UploadedFile $file)
    {
        return Image::create([
            'uploaded_at' => Carbon::now(),
            'hash' => random_int(1000000000, 4294967295),
            'extension' => $file->guessExtension(),
            'imageable_id' => $this->imageable['id'],
            'imageable_type' => $this->imageable['type'],
        ]);
    }

    /**
     * @param UploadedFile $file
     * @param Image $image
     * @return void
     */
    protected function moveToStorage(UploadedFile $file, Image $image)
    {
        list($t, $h, $e) = [
            $image->getAttribute('uploaded_at')->timestamp,
            $image->getAttribute('hash'),
            $file->guessExtension(),
        ];

        $this->createDirectoryIfNotExists("{$this->dirPath}/" . substr($t, 0, 3));

        // 儲存圖片
        $this->storage->put(img_path($t, $h, $e, false, false), file_get_contents($file->getRealPath()));

        // 儲存縮圖
        Imager::make($file->getRealPath())->resize(480, 320, function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(img_path($t, $h, $e, true));
    }
}
