<?php
abstract class EbayChannelTheme {


    abstract public function  getProductExtraData($productId, $languageId);
    abstract public function getName();
    abstract public function getVersion();
    abstract public function getAuthor();
    abstract public function getCoverName();

    protected $db;

    public function __construct() {

    }

    public function resize($filename, $width, $height, $type = "") {
        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $info = pathinfo($filename);

        $extension = $info['extension'];

        $old_image = $filename;
        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type .'.' . $extension;


        $fn = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
        $fn = str_replace(basename($fn), rawurlencode(basename($fn)), $fn);
        $encodelImage = 'cache/' . $fn . '-' . $width . 'x' . $height . $type .'.' . $extension;

        if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!file_exists(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $old_image);
                $image->resize($width, $height, $type);
                $image->save(DIR_IMAGE . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }
        }

        return HTTP_CATALOG . 'image/' . $encodelImage;
    }


}


