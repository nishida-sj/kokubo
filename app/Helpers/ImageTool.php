<?php
// 画像処理ヘルパー

class ImageTool
{
    public static function upload($fileField, $uploadDir = 'works', $generateThumbnail = true)
    {
        if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('ファイルがアップロードされていません。');
        }

        $file = $_FILES[$fileField];

        // ファイル検証
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            throw new Exception('有効な画像ファイルではありません。');
        }

        // ファイルサイズチェック
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            throw new Exception('ファイルサイズが大きすぎます。');
        }

        // 拡張子チェック
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_IMAGE_TYPES)) {
            throw new Exception('許可されていないファイル形式です。');
        }

        // アップロードディレクトリ作成
        $uploadPath = UPLOAD_PATH . '/' . $uploadDir;
        $thumbnailPath = $uploadPath . '/thumbs';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if ($generateThumbnail && !is_dir($thumbnailPath)) {
            mkdir($thumbnailPath, 0755, true);
        }

        // ファイル名生成（重複回避）
        $filename = self::generateUniqueFilename($uploadPath, $extension);
        $filePath = $uploadPath . '/' . $filename;

        // ファイル移動
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('ファイルの保存に失敗しました。');
        }

        $result = [
            'filename' => $filename,
            'path' => '/uploads/' . $uploadDir . '/' . $filename,
            'full_path' => $filePath,
            'size' => $file['size'],
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime_type' => $imageInfo['mime']
        ];

        // サムネイル生成
        if ($generateThumbnail) {
            $thumbnailFilename = $filename;
            $thumbnailFullPath = $thumbnailPath . '/' . $thumbnailFilename;

            if (self::createThumbnail($filePath, $thumbnailFullPath)) {
                $result['thumbnail_path'] = '/uploads/' . $uploadDir . '/thumbs/' . $thumbnailFilename;
                $result['thumbnail_full_path'] = $thumbnailFullPath;
            }
        }

        return $result;
    }

    public static function createThumbnail($sourcePath, $destPath, $width = THUMBNAIL_WIDTH, $height = THUMBNAIL_HEIGHT, $crop = true)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }

        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // ソース画像を読み込み
        $sourceImage = self::createImageFromFile($sourcePath, $mimeType);
        if (!$sourceImage) {
            return false;
        }

        if ($crop) {
            // クロップモード：比率を維持してサムネイルサイズに合わせる
            $sourceRatio = $sourceWidth / $sourceHeight;
            $thumbRatio = $width / $height;

            if ($sourceRatio > $thumbRatio) {
                // 横長画像
                $newWidth = $sourceHeight * $thumbRatio;
                $newHeight = $sourceHeight;
                $srcX = ($sourceWidth - $newWidth) / 2;
                $srcY = 0;
            } else {
                // 縦長画像
                $newWidth = $sourceWidth;
                $newHeight = $sourceWidth / $thumbRatio;
                $srcX = 0;
                $srcY = ($sourceHeight - $newHeight) / 2;
            }

            $thumbnail = imagecreatetruecolor($width, $height);
            self::preserveTransparency($thumbnail, $mimeType);

            imagecopyresampled(
                $thumbnail, $sourceImage,
                0, 0, $srcX, $srcY,
                $width, $height, $newWidth, $newHeight
            );
        } else {
            // リサイズモード：比率を維持してサイズ内に収める
            $ratio = min($width / $sourceWidth, $height / $sourceHeight);
            $newWidth = $sourceWidth * $ratio;
            $newHeight = $sourceHeight * $ratio;

            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
            self::preserveTransparency($thumbnail, $mimeType);

            imagecopyresampled(
                $thumbnail, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight, $sourceWidth, $sourceHeight
            );
        }

        // 保存
        $result = self::saveImageToFile($thumbnail, $destPath, $mimeType);

        imagedestroy($sourceImage);
        imagedestroy($thumbnail);

        return $result;
    }

    private static function createImageFromFile($filepath, $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($filepath);
            case 'image/png':
                return imagecreatefrompng($filepath);
            case 'image/gif':
                return imagecreatefromgif($filepath);
            case 'image/webp':
                return imagecreatefromwebp($filepath);
            default:
                return false;
        }
    }

    private static function saveImageToFile($image, $filepath, $mimeType, $quality = 85)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagejpeg($image, $filepath, $quality);
            case 'image/png':
                return imagepng($image, $filepath);
            case 'image/gif':
                return imagegif($image, $filepath);
            case 'image/webp':
                return imagewebp($image, $filepath, $quality);
            default:
                return false;
        }
    }

    private static function preserveTransparency($image, $mimeType)
    {
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefill($image, 0, 0, $transparent);
        }
    }

    private static function generateUniqueFilename($directory, $extension)
    {
        do {
            $filename = date('Ymd_His_') . uniqid() . '.' . $extension;
            $filepath = $directory . '/' . $filename;
        } while (file_exists($filepath));

        return $filename;
    }

    public static function deleteFile($path)
    {
        if (file_exists($path)) {
            return unlink($path);
        }
        return true;
    }

    public static function deleteFiles($paths)
    {
        $result = true;
        foreach ($paths as $path) {
            if (!self::deleteFile($path)) {
                $result = false;
            }
        }
        return $result;
    }

    public static function getImageDimensions($path)
    {
        if (!file_exists($path)) {
            return false;
        }

        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            return false;
        }

        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime_type' => $imageInfo['mime']
        ];
    }

    public static function resizeImage($sourcePath, $destPath, $maxWidth, $maxHeight)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }

        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // リサイズ不要の場合
        if ($sourceWidth <= $maxWidth && $sourceHeight <= $maxHeight) {
            return copy($sourcePath, $destPath);
        }

        // リサイズ比率計算
        $ratio = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
        $newWidth = $sourceWidth * $ratio;
        $newHeight = $sourceHeight * $ratio;

        // 画像処理
        $sourceImage = self::createImageFromFile($sourcePath, $mimeType);
        if (!$sourceImage) {
            return false;
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        self::preserveTransparency($resizedImage, $mimeType);

        imagecopyresampled(
            $resizedImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight, $sourceWidth, $sourceHeight
        );

        $result = self::saveImageToFile($resizedImage, $destPath, $mimeType);

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return $result;
    }
}