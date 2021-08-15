<?php

namespace App\Services\FileUpload;

use App\Http\Resources\Files\FileResource;
use App\Jobs\Files\CreateThumbnail;
use App\Models\Files\File;
use App\Models\User;
use App\Services\FileUpload\Exceptions\FilePrivacyException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUpload
{
    /**
     * The user uploading the file
     *
     * @var User $user
     */
    private User $user;
    /**
     * Privacy of the file, "public" or "private"
     * will be set from user settings, or from request
     *
     * @var string $privacy
     */
    private string $privacy;
    /**
     * Do we expect a json or string response?
     *
     * @var bool $isJson
     */
    private bool $isJson = false;
    /**
     * The laravel file object representing the file we're uploading
     *
     * @var UploadedFile|null $file
     */
    private UploadedFile|null $file = null;
    /**
     * The laravel file object representing the thumbnail that we're uploading
     * This will only be available if we're uploading from the desktop app
     *
     * @var UploadedFile|null $thumbFile
     */
    private UploadedFile|null $thumbFile = null;
    /**
     * A simple string of the file type we're uploading, "image" "video" etc
     *
     * @var string|null $fileType
     */
    private ?string $fileType = null;
    /**
     * If we're uploading a code file, it will be the language of the file
     *
     * @var string|null $codeFileType
     */
    private ?string $codeFileType = null;
    /**
     * Any metadata we wish to store with the file upload
     *
     * @var array $meta
     */
    private array $meta = [];
    /**
     * The directory we're storing this file in on digitalocean spaces
     *
     * @var string $directory
     */
    private string $directory;
    /**
     * The random file name we assigned to this file
     *
     * @var string $fileName
     */
    private string $fileName;
    /**
     * The model that was stored in the database
     *
     * @var File|null $fileModel
     */
    private ?File $fileModel = null;


    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user      = $user;
        $this->file      = request()->file('file');
        $this->thumbFile = request()->file('thumb');

        if (request()->isJson() || request()->acceptsJson()) {
            $this->isJson = true;
        }
    }

    public static function forUser(User $user): static
    {
        return new static($user);
    }

    /**
     * Run our validation, upload the file and submit a job for extracting a thumbnail
     *
     * @return array|string|JsonResource|JsonResponse
     */
    public function handle(): array|string|JsonResource|JsonResponse
    {
        $validationResponse = $this->validate();

        if ($validationResponse !== null) {
            return $validationResponse;
        }

        $path      = $this->storeFile($this->file);
        $thumbPath = null;

        if ($this->thumbFile) {
            $thumbPath = $this->storeFile($this->thumbFile, true);
        }

        $this->fileModel = File::create([
            'user_id'       => $this->user->id,
            'name'          => $this->file->getClientOriginalName(),
            'type'          => $this->fileType,
            'path'          => $path,
            'thumb'         => $thumbPath,
            'mime_type'     => $this->file->getMimeType(),
            'extension'     => $this->file->getClientOriginalExtension(),
            'size_in_bytes' => $this->file->getSize(),
            'private'       => $this->isPrivate(),
            'status'        => 'complete',
            'meta'          => $this->meta,
        ]);

        if ($this->canExtractThumbnail()) {
            $this->extractThumbnail();
        }

        return $this->response(
            new FileResource($this->fileModel),
            route('files.view', $this->fileModel)
        );
    }

    /**
     * Validate we have some things:
     * - A file to upload
     * - A correct privacy setting
     * - It's a file type we accept
     *
     * @return Response|JsonResponse|null
     */
    private function validate(): Response|JsonResponse|null
    {
        if (!$this->file) {
            return $this->validationResponse('file', 'No file has been uploaded', true);
        }

        try {
            $this->setUploadPrivacy();
        } catch (FilePrivacyException $exception) {
            return $this->validationResponse('privacy', $exception->getMessage(), true);
        }

        $this->fileType = FileValidation::fileType($this->file->getMimeType());

        if ($this->fileType === null) {
            return $this->validationResponse('file', 'File can only be image, video, audio, text, code or compressed.', true);
        }

        if (request()->has('meta')) {
            $meta = request('meta', []);
            foreach ($meta as $key => $value) {
                $this->meta[$key] = $value;
            }
        }

        $this->codeFileType = FileValidation::isCodeFile($this->file->getClientOriginalExtension());

        if ($this->codeFileType !== null) {
            $this->fileType = 'code';
            $this->meta     = $this->codeFileType;
        }

        $this->directory = Str::random();
        $this->fileName  = Str::random() . '.' . $this->file->getClientOriginalExtension();

        return null;
    }

    /**
     * Format our field -> message data for an error
     *
     * @param       $field      | The field from the request
     * @param       $message    | The error message for this field
     * @param false $asResponse | Should we return a laravel response?
     *
     * @return array|string|JsonResource|JsonResponse
     */
    public function validationResponse($field, $message, bool $asResponse = false): array|string|JsonResource|JsonResponse
    {
        $json     = ['error' => [$field => $message]];
        $string   = "$field: $message";
        $response = $this->isJson
            ? $json
            : $string;

        if ($asResponse) {
            return $this->response($json, $string);
        }

        return $response;
    }

    /**
     * If the request accepts json, we'll return a json response, otherwise string
     *
     * @param $json
     * @param $string
     *
     * @return string|JsonResource|JsonResponse
     */
    public function response(JsonResource|array $json, $string): string|JsonResource|JsonResponse
    {
        return $this->isJson
            ? (($json instanceof JsonResource) ? $json : response()->json($json))
            : $string;
    }

    /**
     * Get the privacy type from the request or user settings
     *
     * @throws FilePrivacyException
     */
    private function setUploadPrivacy()
    {
        $this->privacy = $this->user->private_uploads ? 'private' : 'public';

        if (request()->has('privacy')) {
            if (!in_array(request('privacy'), ['public', 'private'])) {
                throw new FilePrivacyException();
            }

            $this->privacy = request('privacy');
        }
    }

    /**
     * @param UploadedFile|null $file
     * @param bool              $isThumbnail
     *
     * @return false|string
     */
    private function storeFile(?UploadedFile $file, bool $isThumbnail = false): string|false
    {
        $fileName = $this->fileName;

        if ($isThumbnail) {
            $fileName = Str::before($fileName, '.') . '_thumb.jpeg';
        }

        $path = $file->storeAs($this->directory, $fileName, 'spaces');

        Storage::setVisibility($path, $this->privacy);

        return $path;
    }

    /**
     * Do we have privacy set to "private"?
     *
     * @return bool
     */
    private function isPrivate(): bool
    {
        return $this->privacy === 'private';
    }

    /**
     * If our file is an image or video, we can extract a thumbnail
     *
     * @return bool
     */
    private function canExtractThumbnail(): bool
    {
        if ($this->thumbFile) {
            return false;
        }

        return $this->fileType === 'image' || $this->fileType === 'video';
    }

    /**
     * Store our file in a temp directory and fire a job to extract a frame
     */
    private function extractThumbnail()
    {
        $folderName = md5($this->file->getClientOriginalName());

        $this->file->storeAs('/temporary/' . $folderName, $this->fileName, 'local');

        CreateThumbnail::dispatch($folderName, $this->fileModel);
    }
}
